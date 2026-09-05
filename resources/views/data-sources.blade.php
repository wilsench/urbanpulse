<x-layouts.app title="Transparansi Data & Sumber — UrbanPulse">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10 space-y-6 sm:space-y-10">

        <!-- Header -->
        <div class="max-w-3xl space-y-2 sm:space-y-3">
            <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider block">PRINSIP INTEGRITAS DATA</span>
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Transparansi Sumber Data & Metodologi</h1>
            <p class="text-slate-600 text-xs sm:text-base leading-relaxed">
                UrbanPulse memegang prinsip ketat: <strong>tidak pernah memalsukan data lingkungan real-time</strong>. Semua indikator cuaca, kualitas udara, dan lokasi berasal dari API resmi terverifikasi.
            </p>
        </div>

        <!-- SOURCES GRID CARDS -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
            @foreach($sources as $src)
                <div class="p-5 sm:p-6 rounded-3xl bg-white border border-slate-200 shadow-sm space-y-3 sm:space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="px-2.5 py-0.5 rounded-md bg-emerald-100 text-emerald-800 text-[11px] sm:text-xs font-bold">
                            {{ $src['status'] }}
                        </span>
                        <span class="text-[11px] sm:text-xs text-slate-500 font-medium">{{ $src['category'] }}</span>
                    </div>

                    <div>
                        <h3 class="text-lg sm:text-xl font-bold text-slate-900 mb-1">{{ $src['name'] }}</h3>
                        <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">{{ $src['purpose'] }}</p>
                    </div>

                    <div class="p-3.5 sm:p-4 rounded-2xl bg-slate-50 border border-slate-200 text-xs text-slate-600 space-y-1">
                        <div class="font-bold text-slate-800">Penyedia Data:</div>
                        <div class="truncate font-mono text-[11px] sm:text-xs">{{ $src['endpoint'] }}</div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</x-layouts.app>
