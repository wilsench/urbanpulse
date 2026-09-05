<x-layouts.admin title="Dashboard Admin — UrbanPulse">
    <div class="space-y-6 sm:space-y-8">
        
        <!-- Top Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-200">
            <div>
                <span class="text-xs font-bold text-amber-700 uppercase tracking-wider block mb-1">CONTROL CENTER</span>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Dashboard Administrator</h1>
                <p class="text-slate-600 text-sm sm:text-base mt-1">Monitoring status integrasi API, agregasi dampak emisi, dan manajemen lokasi Kota Bogor.</p>
            </div>

            <form method="POST" action="{{ route('admin.environment.sync') }}">
                @csrf
                <button type="submit" class="w-full sm:w-auto px-4 sm:px-5 py-3 rounded-2xl bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs sm:text-sm flex items-center justify-center gap-2 transition-all shadow-md shadow-amber-600/20 min-h-[44px]">
                    <span>⚡ Picu Sinkronisasi Data Manual</span>
                </button>
            </form>
        </div>

        <!-- KPI METRICS GRID -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            <div class="p-5 sm:p-6 rounded-3xl bg-white border border-slate-200 shadow-sm space-y-1">
                <div class="text-[10px] sm:text-xs font-bold text-slate-500 uppercase">TOTAL PENGGUNA</div>
                <div class="text-3xl sm:text-4xl font-bold text-slate-900">{{ $totalUsers }}</div>
                <div class="text-xs text-slate-500">Pengguna Terdaftar</div>
            </div>

            <div class="p-5 sm:p-6 rounded-3xl bg-white border border-slate-200 shadow-sm space-y-1">
                <div class="text-[10px] sm:text-xs font-bold text-slate-500 uppercase">TOTAL AKSI HIJAU</div>
                <div class="text-3xl sm:text-4xl font-bold text-slate-900">{{ $totalEcoActions }}</div>
                <div class="text-xs text-slate-500">Aktivitas Terhitung</div>
            </div>

            <div class="p-5 sm:p-6 rounded-3xl bg-white border border-slate-200 shadow-sm space-y-1">
                <div class="text-[10px] sm:text-xs font-bold text-slate-500 uppercase">PERKIRAAN CO2 DIHINDARI</div>
                <div class="text-3xl sm:text-4xl font-bold text-emerald-700">{{ number_format($totalCo2Avoided, 2) }} <span class="text-sm text-slate-500 font-normal">kg</span></div>
                <div class="text-xs text-slate-500">Pengurangan Emisi Karbon</div>
            </div>

            <div class="p-5 sm:p-6 rounded-3xl bg-white border border-slate-200 shadow-sm space-y-1">
                <div class="text-[10px] sm:text-xs font-bold text-slate-500 uppercase">LOKASI TERDAFTAR</div>
                <div class="text-3xl sm:text-4xl font-bold text-amber-700">{{ $activeLocations }}</div>
                <div class="text-xs text-slate-500">Kota Bogor</div>
            </div>
        </div>

        <!-- API STATUS MONITORS -->
        <div class="bg-white border border-slate-200 rounded-3xl p-5 sm:p-8 shadow-sm space-y-4 sm:space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-4 border-b border-slate-100 font-bold text-xs sm:text-sm">
                <span class="text-slate-900">Status Integrasi Sumber Data External</span>
                <span class="text-emerald-700">Pemeriksaan Real-Time</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 text-xs sm:text-sm">
                <!-- BMKG API -->
                <div class="p-4 sm:p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-2">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-900 font-bold">BMKG Weather API</span>
                        <span class="px-2.5 py-1 rounded bg-emerald-100 text-emerald-800 font-bold text-xs">TERHUBUNG</span>
                    </div>
                    <div class="text-slate-600 text-xs">Data Cuaca & Hujan Bogor</div>
                </div>

                <!-- Air Quality API -->
                <div class="p-4 sm:p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-2">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-900 font-bold">Air Quality API</span>
                        <span class="px-2.5 py-1 rounded bg-emerald-100 text-emerald-800 font-bold text-xs">TERHUBUNG</span>
                    </div>
                    <div class="text-slate-600 text-xs">Kualitas Udara & PM2.5</div>
                </div>

                <!-- OpenStreetMap -->
                <div class="p-4 sm:p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-2">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-900 font-bold">OpenStreetMap</span>
                        <span class="px-2.5 py-1 rounded bg-emerald-100 text-emerald-800 font-bold text-xs">TERSEDIA</span>
                    </div>
                    <div class="text-slate-600 text-xs">Data Spasial & Fasilitas</div>
                </div>
            </div>
        </div>

    </div>
</x-layouts.admin>
