<x-layouts.admin title="Dashboard Admin — UrbanPulse">
    <div class="space-y-6 sm:space-y-8">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-slate-200/80">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Control Center</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Dashboard Administrator</h1>
                <p class="text-slate-500 text-xs sm:text-sm mt-0.5">Monitoring status integrasi API, agregasi emisi karbon, dan manajemen lokasi Kota Bogor.</p>
            </div>

            <form method="POST" action="{{ route('admin.environment.sync') }}">
                @csrf
                <button type="submit" class="w-full sm:w-auto px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs flex items-center justify-center gap-2 transition-all shadow-xs min-h-[40px]">
                    <svg class="w-4 h-4 text-white shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    <span>Sinkronisasi Data Manual</span>
                </button>
            </form>
        </div>

        <!-- KPI Metrics Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-xs space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Total Pengguna</span>
                    <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                </div>
                <div class="text-2xl sm:text-3xl font-bold font-mono text-slate-900">{{ $totalUsers }}</div>
                <div class="text-xs text-slate-400">Pengguna Terdaftar</div>
            </div>

            <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-xs space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Total Aksi Hijau</span>
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                </div>
                <div class="text-2xl sm:text-3xl font-bold font-mono text-slate-900">{{ $totalEcoActions }}</div>
                <div class="text-xs text-slate-400">Aktivitas Terhitung</div>
            </div>

            <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-xs space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">CO2 Dihindari</span>
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 002 2h1.5a2.5 2.5 0 002.5-2.5V11a2 2 0 012-2h1.065"></path></svg>
                    </div>
                </div>
                <div class="text-2xl sm:text-3xl font-bold font-mono text-emerald-700">
                    {{ number_format($totalCo2Avoided, 2) }} <span class="text-xs text-slate-500 font-normal">kg</span>
                </div>
                <div class="text-xs text-slate-400">Pengurangan Emisi</div>
            </div>

            <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-xs space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Lokasi Terdaftar</span>
                    <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                    </div>
                </div>
                <div class="text-2xl sm:text-3xl font-bold font-mono text-slate-900">{{ $activeLocations }}</div>
                <div class="text-xs text-slate-400">Kota Bogor</div>
            </div>
        </div>

        <!-- API Status Monitors -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 sm:p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 text-xs font-semibold">
                <span class="text-slate-900 font-bold">Status Integrasi Sumber Data Eksternal</span>
                <span class="text-emerald-700 inline-flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    Real-time Check
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                <!-- BMKG API -->
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 space-y-2">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-900 font-semibold">BMKG Weather API</span>
                        <span class="px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-800 font-mono font-bold text-[10px]">ACTIVE</span>
                    </div>
                    <p class="text-slate-500 text-[11px]">Data Cuaca & Hujan Kota Bogor</p>
                </div>

                <!-- Air Quality API -->
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 space-y-2">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-900 font-semibold">Air Quality Open API</span>
                        <span class="px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-800 font-mono font-bold text-[10px]">ACTIVE</span>
                    </div>
                    <p class="text-slate-500 text-[11px]">Kualitas Udara & Konsentrasi PM2.5</p>
                </div>

                <!-- OpenStreetMap -->
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 space-y-2">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-900 font-semibold">OpenStreetMap (Overpass)</span>
                        <span class="px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-800 font-mono font-bold text-[10px]">ACTIVE</span>
                    </div>
                    <p class="text-slate-500 text-[11px]">Data Spasial & Pemetaan Fasilitas</p>
                </div>
            </div>
        </div>

    </div>
</x-layouts.admin>
