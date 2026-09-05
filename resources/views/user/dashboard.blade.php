<x-layouts.app title="Dashboard Saya — UrbanPulse">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10 space-y-6 sm:space-y-8">

        <!-- User Welcome Banner -->
        <div class="p-5 sm:p-8 rounded-3xl bg-gradient-to-r from-emerald-600 to-teal-700 text-white shadow-lg shadow-emerald-600/20 flex flex-col md:flex-row md:items-center justify-between gap-4 sm:gap-6">
            <div>
                <span class="text-[10px] sm:text-xs font-bold text-emerald-200 uppercase tracking-wider block mb-1">DASHBOARD PENGGUNA</span>
                <h1 class="text-2xl sm:text-3xl font-bold">Selamat Datang, {{ $user->name }}!</h1>
                <p class="text-emerald-100 text-xs sm:text-base mt-1">Terima kasih telah berkontribusi menjaga keberlanjutan Kota Bogor.</p>
            </div>

            <div class="flex items-center justify-between sm:justify-start gap-4 bg-white/10 backdrop-blur p-3.5 sm:p-4 rounded-2xl border border-white/20">
                <div>
                    <div class="text-[10px] sm:text-xs font-bold text-emerald-100 uppercase">POIN HIJAU SAYA</div>
                    <div class="text-2xl sm:text-3xl font-bold">+{{ $user->eco_points }}</div>
                </div>
                <a href="{{ route('actions.index') }}" class="px-4 sm:px-5 py-2.5 sm:py-3 rounded-xl bg-white text-emerald-800 hover:bg-emerald-50 font-bold text-xs sm:text-sm transition-all shadow-sm min-h-[44px] flex items-center justify-center">
                    + Catat Aksi Hijau
                </a>
            </div>
        </div>

        <!-- STATS OVERVIEW CARDS -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6">
            <div class="p-5 sm:p-6 rounded-3xl bg-white border border-slate-200 shadow-sm space-y-1.5 sm:space-y-2">
                <div class="text-[10px] sm:text-xs font-bold text-slate-500 uppercase">PERKIRAAN CO2 YANG DIHINDARI</div>
                <div class="text-3xl sm:text-4xl font-bold text-emerald-700">{{ number_format($totalCo2, 2) }} <span class="text-sm sm:text-base text-slate-500 font-normal">kg</span></div>
                <div class="text-xs text-slate-600">Total Pengurangan Emisi Karbon Anda</div>
            </div>

            <div class="p-5 sm:p-6 rounded-3xl bg-white border border-slate-200 shadow-sm space-y-1.5 sm:space-y-2">
                <div class="text-[10px] sm:text-xs font-bold text-slate-500 uppercase">TOTAL AKSI HIJAU</div>
                <div class="text-3xl sm:text-4xl font-bold text-slate-900">{{ $totalActionsCount }}</div>
                <div class="text-xs text-slate-600">Aktivitas Bersepeda, Jalan Kaki, Bus</div>
            </div>

            <div class="p-5 sm:p-6 rounded-3xl bg-white border border-slate-200 shadow-sm space-y-1.5 sm:space-y-2">
                <div class="text-[10px] sm:text-xs font-bold text-slate-500 uppercase">CUACA HARI INI</div>
                <div class="text-2xl sm:text-3xl font-bold text-slate-900">{{ $weather['temperature'] }}°C</div>
                <div class="text-xs text-slate-600">{{ $weather['weather_description'] }} &bull; Udara {{ $airQuality['air_quality_status'] }}</div>
            </div>
        </div>

        <!-- RECENT ACTIONS LIST -->
        <div class="bg-white border border-slate-200 rounded-3xl p-5 sm:p-8 shadow-sm space-y-4 sm:space-y-6">
            <div class="flex justify-between items-center pb-4 border-b border-slate-100">
                <h2 class="text-lg sm:text-xl font-bold text-slate-900">Aksi Hijau Terbaru Anda</h2>
                <a href="{{ route('actions.index') }}" class="text-xs sm:text-sm font-bold text-emerald-700 hover:underline min-h-[38px] flex items-center">
                    Lihat Semua &rarr;
                </a>
            </div>

            @if($recentActions->isEmpty())
                <div class="text-center py-8 text-slate-500 text-xs sm:text-base">
                    Belum ada aksi hijau yang dicatat. Mulai catat aktivitas bersepeda atau jalan kaki Anda!
                </div>
            @else
                <div class="space-y-3">
                    @foreach($recentActions as $act)
                        <div class="p-3.5 sm:p-4 rounded-2xl bg-slate-50 border border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-2 sm:gap-3 text-xs sm:text-sm">
                            <div>
                                <div class="font-bold text-slate-900 uppercase text-sm sm:text-base">
                                    {{ $act->action_type }} &bull; {{ $act->distance_km }} km
                                </div>
                                <div class="text-slate-600 text-xs sm:text-sm">
                                    {{ $act->notes ?? ($act->location->name ?? 'Kota Bogor') }}
                                </div>
                            </div>
                            <div class="flex items-center justify-between sm:justify-end gap-3 sm:gap-4 border-t sm:border-t-0 pt-2 sm:pt-0 border-slate-200/60">
                                <div class="text-left sm:text-right">
                                    <div class="text-emerald-700 font-bold text-sm sm:text-base">+{{ $act->co2_avoided_kg }} kg CO2</div>
                                    <div class="text-slate-500 text-[11px] sm:text-xs">+{{ $act->eco_points_earned }} pts</div>
                                </div>
                                <span class="text-slate-500 text-[11px] sm:text-xs font-mono">{{ $act->performed_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</x-layouts.app>
