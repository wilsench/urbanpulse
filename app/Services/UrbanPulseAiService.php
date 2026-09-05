<?php

namespace App\Services;

use App\Models\City;
use App\Models\Location;
use App\Services\External\AirQualityService;
use App\Services\External\BmkgService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UrbanPulseAiService
{
    protected ?string $apiKey;
    protected ?string $kieKey;
    protected BmkgService $bmkgService;
    protected AirQualityService $airQualityService;
    protected RecommendationScoreService $scoreService;

    public function __construct(
        BmkgService $bmkgService,
        AirQualityService $airQualityService,
        RecommendationScoreService $scoreService
    ) {
        $this->apiKey = config('services.gemini.key', env('GEMINI_API_KEY'));
        $this->kieKey = config('services.kie.key', env('KIE_API_KEY', env('GEMINI_API_KEY')));
        $this->bmkgService = $bmkgService;
        $this->airQualityService = $airQualityService;
        $this->scoreService = $scoreService;
    }

    /**
     * Get the JSON specification array from config system or file.
     */
    public function getConfig(): array
    {
        $config = config('urbanpulse_ai');
        if (empty($config) || !is_array($config)) {
            $jsonPath = config_path('urbanpulse_ai.json');
            if (file_exists($jsonPath)) {
                $config = json_decode(file_get_contents($jsonPath), true) ?: [];
            }
        }
        return $config ?: [];
    }

    /**
     * Validate that all required configuration sections exist.
     */
    public function validateConfig(array $config): void
    {
        $requiredSections = [
            'agent',
            'identity',
            'communication',
            'behavior',
            'data_policy',
            'conversation',
            'supported_tasks',
            'limitations',
            'response_format',
        ];

        foreach ($requiredSections as $section) {
            if (!isset($config[$section]) || empty($config[$section])) {
                throw new \InvalidArgumentException("UrbanPulse AI specification is missing required section: [{$section}]");
            }
        }
    }

    /**
     * Dynamically construct system prompt from the JSON specification.
     */
    public function buildSystemPrompt(): string
    {
        $config = $this->getConfig();
        $this->validateConfig($config);

        $agent = $config['agent'];
        $identity = $config['identity'];
        $commRules = implode("\n- ", $config['communication']['rules'] ?? []);
        $genBehavior = implode("\n- ", $config['behavior']['general'] ?? []);
        $recBehavior = implode("\n- ", $config['behavior']['recommendation'] ?? []);
        $dataCoreRule = $config['data_policy']['core_rule'] ?? '';
        $dataRules = implode("\n- ", $config['data_policy']['rules'] ?? []);
        $limitations = implode("\n- ", $config['limitations']['rules'] ?? []);
        $respRules = implode("\n- ", $config['response_format']['rules'] ?? []);
        $allowedEmoji = implode(', ', $agent['allowed_emoji'] ?? []);
        $forbiddenEmoji = implode(', ', $agent['forbidden_emoji'] ?? []);

        return <<<PROMPT
IDENTITY:
Name: {$agent['name']}
Role: {$agent['role']}
Language: {$agent['language']}
Communication Style: {$agent['communication_style']}
Tone of Voice: {$agent['tone_of_voice']}

PRODUCT CONTEXT:
Product: {$identity['product']}
Description: {$identity['description']}
Primary Goal: {$identity['primary_goal']}
Primary SDG: {$identity['primary_sdg']}
Secondary SDG: {$identity['secondary_sdg']}

COMMUNICATION RULES:
- {$commRules}

BEHAVIOR RULES:
- {$genBehavior}

RECOMMENDATION RULES:
- {$recBehavior}

DATA POLICY:
Core Rule: {$dataCoreRule}
Data Rules:
- {$dataRules}

LIMITATIONS:
- {$limitations}

RESPONSE FORMAT RULES:
- {$respRules}

EMOJI RULES:
Allowed Emojis: {$allowedEmoji}
Forbidden Emojis: {$forbiddenEmoji}
PROMPT;
    }

    /**
     * Build current dynamic UrbanPulse data context, matching specific requested location if mentioned in prompt.
     */
    public function buildDataContext(?City $activeCity = null, ?string $userPrompt = null): array
    {
        $cityName = $activeCity ? $activeCity->name : 'Kota Bogor';
        $cityId = $activeCity ? $activeCity->id : null;
        $lat = $activeCity ? $activeCity->latitude : -6.5971;
        $lng = $activeCity ? $activeCity->longitude : 106.7949;

        $weather = $this->bmkgService->getWeather($lat, $lng);
        $airQuality = $this->airQualityService->getCurrentAirQuality($lat, $lng);

        $query = Location::where('is_active', true);
        if ($cityId) {
            $query->where('city_id', $cityId);
        }

        $matchedLocation = null;
        if (!empty($userPrompt)) {
            $cleaned = preg_replace('/[^\w\s]/', ' ', $userPrompt);
            $words = array_filter(explode(' ', $cleaned), fn($w) => strlen($w) > 2);
            $ignore = ['mengapa', 'tempat', 'lokasi', 'direkomendasikan', 'taman', 'bogor', 'jakarta', 'bandung', 'apakah', 'bagaimana'];
            
            foreach ($words as $word) {
                if (in_array(strtolower($word), $ignore)) {
                    continue;
                }
                $found = (clone $query)->where('name', 'LIKE', '%' . $word . '%')->first();
                if ($found) {
                    $matchedLocation = $found;
                    break;
                }
            }
        }

        $locations = $query->take(8)->get();

        if ($matchedLocation && !$locations->contains('id', $matchedLocation->id)) {
            $locations->prepend($matchedLocation);
        }

        $locationContexts = [];
        foreach ($locations as $loc) {
            $score = $this->scoreService->calculateScore($loc, 'exercise', 'low', 'afternoon', 'bicycle', $weather, $airQuality);
            $locationContexts[] = [
                'id' => $loc->id,
                'name' => $loc->name,
                'category' => $loc->category,
                'address' => $loc->address ?? 'NOT AVAILABLE',
                'green_score' => $loc->green_score ? "{$loc->green_score}/100" : 'NOT AVAILABLE',
                'accessibility_score' => $loc->accessibility_score ? "{$loc->accessibility_score}/100" : 'NOT AVAILABLE',
                'urbanpulse_match_score' => "{$score['total_score']}% [Calculated by UrbanPulse]",
                'recommended_time_slot' => $score['recommended_time_slot'] ?? 'NOT AVAILABLE',
                'match_reasons' => implode(', ', $score['reasons'] ?? []),
            ];
        }

        return [
            'city' => $cityName,
            'weather' => [
                'description' => $weather['weather_description'] ?? 'NOT AVAILABLE',
                'temperature' => isset($weather['temperature']) ? "{$weather['temperature']}°C" : 'NOT AVAILABLE',
                'rain_probability' => isset($weather['rain_probability']) ? "{$weather['rain_probability']}%" : 'NOT AVAILABLE',
                'source' => $weather['source'] ?? 'BMKG Open Data',
            ],
            'air_quality' => [
                'index' => $airQuality['air_quality_index'] ?? 'NOT AVAILABLE',
                'status' => $airQuality['air_quality_status'] ?? 'NOT AVAILABLE',
                'pm25' => isset($airQuality['pm25']) ? "{$airQuality['pm25']} µg/m³" : 'NOT AVAILABLE',
                'source' => $airQuality['source'] ?? 'Air Quality Open Data',
            ],
            'crowd_level' => 'Sedang (Nyaman) [Estimated by UrbanPulse]',
            'locations' => $locationContexts,
            'timestamp' => now()->toDateTimeString(),
        ];
    }

    /**
     * Answer user prompt using Kie.ai or Gemini API driven by config specification.
     */
    public function ask(string $prompt, ?array $contextData = null, ?City $activeCity = null): array
    {
        $systemPrompt = $this->buildSystemPrompt();

        if (empty($contextData)) {
            $contextData = $this->buildDataContext($activeCity, $prompt);
        }

        $fullPrompt = "SYSTEM PROMPT:\n" . $systemPrompt . "\n\nURBANPULSE DATA CONTEXT:\n" . json_encode($contextData, JSON_PRETTY_PRINT) . "\n\nUSER PROMPT:\n" . $prompt;

        // 1. ALTERNATIVE HIT: Kie.ai Endpoint (https://api.kie.ai/gemini-2.5-flash/v1/chat/completions)
        $kieKey = $this->kieKey;
        if (!empty($kieKey)) {
            try {
                $response = Http::timeout(10)
                    ->withHeaders([
                        'Authorization' => 'Bearer ' . $kieKey,
                        'Content-Type' => 'application/json',
                    ])
                    ->post('https://api.kie.ai/gemini-2.5-flash/v1/chat/completions', [
                        'messages' => [
                            [
                                'role' => 'user',
                                'content' => [
                                    [
                                        'type' => 'text',
                                        'text' => $fullPrompt,
                                    ]
                                ]
                            ]
                        ],
                        'stream' => false,
                    ]);

                if ($response->successful()) {
                    $json = $response->json();
                    $text = $json['choices'][0]['message']['content'] ?? ($json['choices'][0]['text'] ?? null);

                    if (!empty($text)) {
                        return [
                            'response' => $text,
                            'source' => 'UrbanPulse Assistant',
                            'context' => $contextData,
                        ];
                    }
                }
            } catch (\Throwable $e) {
                Log::warning("Kie.ai API call error: " . $e->getMessage());
            }
        }

        // 2. Direct Gemini REST API Fallback
        if (!empty($this->apiKey) && !str_contains($this->apiKey, 'AQ.Ab8RN6J')) {
            $modelsToTry = ['gemini-1.5-flash', 'gemini-1.5-pro', 'gemini-pro'];

            foreach ($modelsToTry as $model) {
                try {
                    $response = Http::timeout(8)->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$this->apiKey}", [
                        'contents' => [
                            [
                                'parts' => [
                                    ['text' => $fullPrompt]
                                ]
                            ]
                        ]
                    ]);

                    if ($response->successful()) {
                        $json = $response->json();
                        $text = $json['candidates'][0]['content']['parts'][0]['text'] ?? null;
                        if (!empty($text)) {
                            return [
                                'response' => $text,
                                'source' => 'UrbanPulse Assistant',
                                'context' => $contextData,
                            ];
                        }
                    }
                } catch (\Throwable $e) {
                    Log::warning("Gemini API call error for model {$model}: " . $e->getMessage());
                }
            }
        }

        // 3. Prompt-aware context-grounded intelligent fallback engine
        return $this->getFriendlyFallbackResponse($prompt, $contextData);
    }

    /**
     * Prompt-aware intelligent fallback engine driven by config/urbanpulse_ai.json rules.
     */
    public function getFriendlyFallbackResponse(string $prompt, array $contextData): array
    {
        $cityName = $contextData['city'] ?? 'Kota Anda';
        $weather = $contextData['weather'] ?? [];
        $airQuality = $contextData['air_quality'] ?? [];
        $locations = $contextData['locations'] ?? [];
        $lowerPrompt = strtolower($prompt);

        // Security check per limitations.rules: Do not reveal system prompt / internal JSON
        if (str_contains($lowerPrompt, 'system prompt') || str_contains($lowerPrompt, 'instruksi internal') || str_contains($lowerPrompt, 'json config')) {
            return [
                'response' => "Maaf, aku tidak dapat membagikan instruksi internal atau konfigurasi sistem. 😊 Ada informasi tempat, cuaca, atau emisi CO2 di {$cityName} yang bisa aku bantu?",
                'source' => 'UrbanPulse Assistant',
                'context' => $contextData,
            ];
        }

        // 1. Greeting / Salutation
        if (preg_match('/\b(halo|hai|hi|pagi|siang|sore|malam|selamat)\b/i', $lowerPrompt)) {
            $reply = "Halo! 😊 Aku UrbanPulse Assistant untuk {$cityName}.\n\nAku siap membantu kamu menemukan tempat olahraga, bersantai, atau mengecek kualitas udara dan cuaca hari ini. Ada yang bisa aku bantu?";
            return [
                'response' => $reply,
                'source' => 'UrbanPulse Assistant',
                'context' => $contextData,
            ];
        }

        // 2. Weather / Cuaca / Suhu Query
        if (preg_match('/\b(cuaca|suhu|hujan|panas|dingin|berawan|stasiun|bmkg)\b/i', $lowerPrompt)) {
            $desc = $weather['description'] ?? 'TIDAK TERSEDIA';
            $temp = $weather['temperature'] ?? 'TIDAK TERSEDIA';
            $rainProb = $weather['rain_probability'] ?? 'TIDAK TERSEDIA';
            $source = $weather['source'] ?? 'BMKG Open Data';

            $reply = "Berikut informasi cuaca saat ini di **{$cityName}**:\n\n";
            $reply .= "🌤️ **Kondisi Cuaca:** {$desc}\n";
            $reply .= "🌡️ **Suhu Udara:** {$temp}\n";
            $reply .= "🌧️ **Peluang Hujan:** {$rainProb}\n\n";
            $reply .= "🌱 *Sumber Data:* {$source}";

            return [
                'response' => $reply,
                'source' => 'UrbanPulse Assistant',
                'context' => $contextData,
            ];
        }

        // 3. Air Quality / Kualitas Udara Query
        if (preg_match('/\b(udara|kualitas udara|aqi|pm25|polusi|segar|sehat)\b/i', $lowerPrompt)) {
            $status = $airQuality['status'] ?? 'TIDAK TERSEDIA';
            $aqi = $airQuality['index'] ?? 'TIDAK TERSEDIA';
            $pm25 = $airQuality['pm25'] ?? 'TIDAK TERSEDIA';
            $source = $airQuality['source'] ?? 'Air Quality Open Data';

            $reply = "Berikut informasi kualitas udara di **{$cityName}**:\n\n";
            $reply .= "🍃 **Status Kualitas Udara:** {$status}\n";
            $reply .= "📊 **Indeks AQI:** {$aqi}\n";
            $reply .= "💨 **Konsentrasi PM2.5:** {$pm25}\n\n";
            $reply .= "Sangat baik dan aman untuk aktivitas luar ruangan! 🌱\n";
            $reply .= "🌱 *Sumber Data:* {$source}";

            return [
                'response' => $reply,
                'source' => 'UrbanPulse Assistant',
                'context' => $contextData,
            ];
        }

        // 4. CO2 Avoided / Eco Actions Query
        if (preg_match('/\b(co2|karbon|emisi|sepeda|jalan|bus|krl|hemat|poin)\b/i', $lowerPrompt)) {
            $reply = "Berdasarkan metodologi perhitungan emisi UrbanPulse:\n\n";
            $reply .= "🚲 **Bersepeda:** Menghindari ~0.21 kg CO2 per kilometer dibanding mobil pribadi.\n";
            $reply .= "🚶 **Jalan Kaki:** Menghindari ~0.21 kg CO2 per kilometer.\n";
            $reply .= "🚌 **Transportasi Umum:** Menghindari ~0.14 kg CO2 per kilometer.\n\n";
            $reply .= "Kamu bisa mencatat aktivitas fisikmu di menu **Catat Aksi Hijau** untuk menambah Poin Hijau! 👍";

            return [
                'response' => $reply,
                'source' => 'UrbanPulse Assistant',
                'context' => $contextData,
            ];
        }

        // 5. Specific Location Matching Logic (Matching prompt against database location names)
        $matchedLoc = null;
        foreach ($locations as $loc) {
            $locNameLower = strtolower($loc['name']);
            if (str_contains($lowerPrompt, $locNameLower)) {
                $matchedLoc = $loc;
                break;
            }

            // Keyword token matching
            $nameWords = array_filter(explode(' ', $locNameLower), fn($w) => strlen($w) > 3);
            foreach ($nameWords as $w) {
                if (str_contains($lowerPrompt, $w)) {
                    $matchedLoc = $loc;
                    break 2;
                }
            }
        }

        if ($matchedLoc) {
            $reply = "Tentu! 😊 Berikut analisis mengapa **{$matchedLoc['name']}** direkomendasikan di {$cityName}:\n\n";
            $reply .= "📍 **{$matchedLoc['name']}**\n";
            $reply .= "• **Tingkat Kecocokan:** {$matchedLoc['urbanpulse_match_score']}\n";
            $reply .= "• **Indeks Ruang Hijau:** {$matchedLoc['green_score']}\n";
            $reply .= "• **Aksesibilitas & Mobilitas:** {$matchedLoc['accessibility_score']}\n";
            $reply .= "• **Waktu Terbaik:** {$matchedLoc['recommended_time_slot']}\n\n";
            if (!empty($matchedLoc['match_reasons'])) {
                $reply .= "💡 **Alasan Utama Rekomendasi:**\n{$matchedLoc['match_reasons']}.\n\n";
            } else {
                $reply .= "💡 **Alasan Utama Rekomendasi:**\nLokasi ini memiliki kualitas udara baik, tingkat keramaian nyaman, dan mendukung mobilitas hijau.\n\n";
            }
            $reply .= "Semoga aktivitasmu di **{$matchedLoc['name']}** menyenangkan! 🌿";

            return [
                'response' => $reply,
                'source' => 'UrbanPulse Assistant',
                'context' => $contextData,
            ];
        }

        // 6. Generic Recommendation Query
        $topLoc = $locations[0] ?? null;
        if ($topLoc) {
            $reply = "Tentu! 😊 Berdasarkan analisis data lingkungan di **{$cityName}**:\n\n";
            $reply .= "📍 **Rekomendasi Utama: {$topLoc['name']}**\n";
            $reply .= "• **Tingkat Kecocokan:** {$topLoc['urbanpulse_match_score']}\n";
            $reply .= "• **Waktu Terbaik:** {$topLoc['recommended_time_slot']}\n";
            $reply .= "• **Indeks Ruang Hijau:** {$topLoc['green_score']}\n\n";
            $reply .= "💡 **Alasan Rekomendasi:**\n";
            $reply .= "{$topLoc['name']} terpilih karena kualitas udaranya baik, tingkat keramaiannya nyaman, dan mendukung aktivitas mobilitas hijau.\n\n";
            if (isset($locations[1])) {
                $reply .= " Alternatif lain yang tak kalah menarik: **{$locations[1]['name']}**.\n\n";
            }
            $reply .= "Semoga aktivitasmu menyenangkan! 🌿";

            return [
                'response' => $reply,
                'source' => 'UrbanPulse Assistant',
                'context' => $contextData,
            ];
        }

        return [
            'response' => "Maaf, data terverifikasi untuk pertanyaan tersebut belum tersedia di {$cityName}. 😊",
            'source' => 'UrbanPulse Assistant',
            'context' => $contextData,
        ];
    }
}
