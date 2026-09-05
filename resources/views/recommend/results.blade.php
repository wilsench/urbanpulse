<x-layouts.app title="Hasil Rekomendasi Tempat — UrbanPulse">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10 space-y-6 sm:space-y-8">

        <!-- Results Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-200">
            <div>
                <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider block mb-1">REKOMENDASI TERBAIK UNTUK ANDA</span>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Tempat yang Paling Cocok di {{ $activeCity->name ?? 'Kota Anda' }}</h1>
                <p class="text-slate-600 text-sm sm:text-base mt-1">Dianalisis dari kondisi real-time udara, cuaca, dan tingkat keramaian.</p>
            </div>
            <a href="{{ route('recommend.index') }}" class="px-4 py-2.5 rounded-xl bg-white border border-slate-300 hover:bg-slate-100 text-slate-700 text-xs sm:text-sm font-semibold self-start sm:self-auto min-h-[44px] flex items-center justify-center">
                &larr; Ubah Pilihan Saya
            </a>
        </div>

        <!-- TOP 3 RECOMMENDATIONS CARDS -->
        <div class="space-y-6 sm:space-y-8">
            @forelse($results as $index => $res)
                @php
                    $loc = $res['location'];
                    $rank = $index + 1;
                    $score = $res['total_score'];
                @endphp
                <div class="bg-white border {{ $rank === 1 ? 'border-emerald-600 shadow-lg ring-2 ring-emerald-600/20' : 'border-slate-200 shadow-sm' }} rounded-3xl p-4 sm:p-6 lg:p-8 space-y-4 sm:space-y-6 relative overflow-hidden" x-data="{ showDetails: false }">
                    
                    @if($rank === 1)
                        <div class="inline-block bg-emerald-600 text-white font-bold text-xs px-3.5 py-1 rounded-full uppercase tracking-wider mb-1">
                            ★ Pilihan Utama #1
                        </div>
                    @endif

                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <h2 class="text-xl sm:text-2xl font-bold text-slate-900 flex items-center gap-2">
                                <span>🌳 {{ $loc->name }}</span>
                            </h2>
                            <p class="text-xs sm:text-base text-slate-600 mt-1">Sangat cocok untuk aktivitas {{ strtolower($criteria['activity_type']) }}.</p>
                        </div>

                        <!-- Match Score Badge -->
                        <div class="bg-emerald-50 border border-emerald-200 px-4 sm:px-5 py-2.5 sm:py-3 rounded-2xl text-center flex-shrink-0 self-start sm:self-auto">
                            <div class="text-[10px] sm:text-xs font-bold text-emerald-800 uppercase">Tingkat Kecocokan</div>
                            <div class="text-2xl sm:text-3xl font-bold text-emerald-700">{{ $score }}% <span class="text-xs sm:text-sm font-normal text-slate-600">Cocok</span></div>
                        </div>
                    </div>

                    <!-- Human Friendly Summary List -->
                    <div class="bg-slate-50 p-4 sm:p-5 rounded-2xl border border-slate-200 space-y-3">
                        <div class="text-xs font-bold text-slate-900 uppercase tracking-wider">// KONDISI LOKASI SAAT INI</div>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs sm:text-sm">
                            <div>
                                <span class="text-slate-500 block text-xs">Kualitas Udara:</span>
                                <span class="font-bold text-emerald-700">{{ $res['breakdown']['air_quality']['value'] }}</span>
                            </div>
                            <div>
                                <span class="text-slate-500 block text-xs">Tingkat Keramaian:</span>
                                <span class="font-bold text-slate-900">{{ $res['breakdown']['crowd_level']['value'] }}</span>
                            </div>
                            <div>
                                <span class="text-slate-500 block text-xs">Prakiraan Cuaca:</span>
                                <span class="font-bold text-slate-900">{{ $res['breakdown']['weather']['value'] }}</span>
                            </div>
                            <div>
                                <span class="text-slate-500 block text-xs">Waktu Disarankan:</span>
                                <span class="font-bold text-emerald-700">{{ $res['recommended_time_slot'] }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Reasons Bullet Points -->
                    <div class="space-y-2">
                        <div class="text-xs sm:text-sm font-bold text-slate-900">Mengapa tempat ini direkomendasikan?</div>
                        <ul class="space-y-1.5 text-xs sm:text-sm text-slate-700">
                            @foreach($res['reasons'] as $reason)
                                <li class="flex items-start gap-2">
                                    <span class="text-emerald-600 font-bold flex-shrink-0">✓</span>
                                    <span>{{ $reason }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Secondary Collapsible Detail for System Math Formulas -->
                    <div class="pt-3 border-t border-slate-100">
                        <button @click="showDetails = !showDetails" class="text-xs sm:text-sm font-semibold text-slate-600 hover:text-emerald-700 flex items-center gap-1.5 focus:outline-none">
                            <span x-text="showDetails ? 'Sembunyikan Rincian Teknis Skor' : 'Lihat Rincian Teknis Skor'"></span>
                            <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': showDetails }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>

                        <div x-show="showDetails" x-transition class="mt-3 p-3.5 sm:p-4 rounded-2xl bg-slate-100 border border-slate-200 text-xs font-mono space-y-2">
                            <div class="font-bold text-slate-800 mb-2">RINCIAN PERHITUNGAN SCORE MULTI-BOBOT:</div>
                            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-2 text-center">
                                <div class="bg-white p-2 rounded border border-slate-200">
                                    <div class="text-slate-500">Udara (30%)</div>
                                    <div class="font-bold text-emerald-700">{{ $res['breakdown']['air_quality']['earned'] }}/30</div>
                                </div>
                                <div class="bg-white p-2 rounded border border-slate-200">
                                    <div class="text-slate-500">Keramaian (25%)</div>
                                    <div class="font-bold text-slate-900">{{ $res['breakdown']['crowd_level']['earned'] }}/25</div>
                                </div>
                                <div class="bg-white p-2 rounded border border-slate-200">
                                    <div class="text-slate-500">Ruang Hijau (20%)</div>
                                    <div class="font-bold text-emerald-700">{{ $res['breakdown']['green_space']['earned'] }}/20</div>
                                </div>
                                <div class="bg-white p-2 rounded border border-slate-200">
                                    <div class="text-slate-500">Aksesibilitas (15%)</div>
                                    <div class="font-bold text-slate-900">{{ $res['breakdown']['accessibility']['earned'] }}/15</div>
                                </div>
                                <div class="bg-white p-2 rounded border border-slate-200 col-span-2 sm:col-span-1">
                                    <div class="text-slate-500">Cuaca (10%)</div>
                                    <div class="font-bold text-emerald-700">{{ $res['breakdown']['weather']['earned'] }}/10</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 pt-2">
                        <a href="{{ route('assistant.index', ['q' => 'Mengapa tempat ' . $loc->name . ' direkomendasikan?']) }}" class="px-4 sm:px-5 py-2.5 sm:py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs sm:text-sm flex items-center justify-center gap-2 transition-all shadow-md shadow-emerald-600/20 min-h-[44px]">
                            <span>💬 Tanya UrbanPulse tentang {{ $loc->name }}</span>
                        </a>

                        <a href="{{ route('locations.show', $loc->slug) }}" class="text-xs sm:text-sm font-bold text-emerald-700 hover:underline min-h-[44px] flex items-center justify-center">
                            Lihat Detail Tempat &rarr;
                        </a>
                    </div>

                </div>
            @empty
                <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 text-center text-slate-500 text-sm sm:text-base">
                    Belum ada rekomendasi tempat yang tersedia untuk kriteria dan kota terpilih saat ini.
                </div>
            @endforelse
        </div>

    </div>
</x-layouts.app>
