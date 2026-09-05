<?php

namespace App\Http\Controllers;

use App\Models\AiConversation;
use App\Services\UrbanPulseAiService;
use Illuminate\Http\Request;

class AssistantController extends Controller
{
    protected UrbanPulseAiService $aiService;

    public function __construct(UrbanPulseAiService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function index(Request $request)
    {
        $initialQuery = $request->query('q', '');
        return view('assistant.index', compact('initialQuery'));
    }

    public function query(Request $request)
    {
        $request->validate([
            'prompt' => ['required', 'string', 'max:1000'],
        ]);

        $activeCity = view()->shared('activeCity');
        $prompt = $request->input('prompt');
        $result = $this->aiService->ask($prompt, null, $activeCity);

        AiConversation::create([
            'user_id' => auth()->id(),
            'prompt' => $prompt,
            'response' => $result['response'],
            'context_data' => $result['context'],
        ]);

        return response()->json([
            'prompt' => $prompt,
            'response' => $result['response'],
            'source' => $result['source'],
            'timestamp' => now()->format('H:i:s WIB'),
        ]);
    }
}
