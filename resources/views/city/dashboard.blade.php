<x-layouts.app title="Kondisi Lingkungan — {{ $activeCity->name ?? 'Kota Anda' }} — UrbanPulse">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10 space-y-6 sm:space-y-10">

        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-200">
            <div>
                <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider block mb-1">PEMANTAUAN KOTA REAL-TIME</span>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Kondisi Lingkungan {{ $activeCity->name ?? 'Kota Anda' }}</h1>
                <p class="text-slate-600 text-sm sm:text-base mt-1">Informasi cuaca, kualitas udara, dan ruang hijau terverifikasi untuk membantu aktivitas harian Anda.</p>
            </div>
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 sm:gap-3 w-full sm:w-auto">
                <a href="{{ route('map') }}" class="px-4 sm:px-5 py-2.5 sm:py-3 rounded-2xl bg-white border border-slate-300 hover:border-emerald-600 text-slate-800 font-bold text-sm sm:text-base transition-all shadow-xs flex items-center justify-center gap-2 min-h-[44px]">
                    <span>🗺️ Lihat di Peta</span>
                </a>
                <a href="{{ route('recommend.index') }}" class="px-4 sm:px-5 py-2.5 sm:py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm sm:text-base transition-all shadow-md shadow-emerald-600/20 min-h-[44px] flex items-center justify-center gap-2">
                    <span>Cari Rekomendasi</span> &rarr;
                </a>
            </div>
        </div>

        <!-- MAIN SUSTAINABILITY CARD -->
        <div class="bg-white border border-slate-200 rounded-3xl p-5 sm:p-8 shadow-sm space-y-6">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 sm:gap-8">
                <div class="space-y-3">
                    <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 font-bold text-xs">
                        SKOR KEBERLANJUTAN {{ strtoupper($activeCity->name ?? 'KOTA') }}
                    </span>
                    <div class="text-3xl sm:text-4xl lg:text-5xl font-bold text-slate-900">
                        {{ $sustainabilityScore }} <span class="text-base sm:text-xl text-slate-500 font-normal">/ 100</span>
                    </div>
                    <div class="text-base sm:text-lg font-bold text-emerald-700">Kondisi Sangat Baik & Berkelanjutan</div>
                    <p class="text-sm sm:text-base text-slate-600 max-w-2xl leading-relaxed">
                        Skor ini dihitung dari kombinasi Kualitas Udara bersih, ketersediaan Ruang Terbuka Hijau, serta kenyamanan akses pejalan kaki di {{ $activeCity->name ?? 'kota ini' }}.
                    </p>
                </div>

                <!-- Meaning & Action Box -->
                <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-4 sm:p-6 w-full lg:w-96 space-y-3">
                    <div class="font-bold text-slate-900 text-sm sm:text-base border-b border-emerald-200 pb-2">
                        💡 Apa Artinya Bagi Anda?
                    </div>
                    <p class="text-xs sm:text-sm text-slate-700 leading-relaxed">
                        Hari ini adalah waktu yang sangat ideal untuk berolahraga, bersepeda, atau bersantai di taman luar ruangan {{ $activeCity->name ?? 'kota Anda' }}.
                    </p>
                    <a href="{{ route('recommend.index') }}" class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-bold text-emerald-800 hover:underline pt-1">
                        <span>Lihat rekomendasi taman terdekat</span> &rarr;
                    </a>
                </div>
            </div>
        </div>

        <!-- CORE INDICATORS GRID -->
        <div class="space-y-4 sm:space-y-6">
            <h2 class="text-xl sm:text-2xl font-bold text-slate-900">Indikator Kunci Lingkungan</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">

                <!-- 1. Kualitas Udara -->
                <div class="p-5 sm:p-6 rounded-3xl bg-white border border-slate-200 shadow-xs space-y-3">
                    <div class="text-xs font-bold text-slate-500 uppercase">KUALITAS UDARA</div>
                    <div class="text-xl sm:text-2xl font-bold text-emerald-700">
                        {{ $airQuality['air_quality_status'] ?? 'Baik' }}
                    </div>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        Udara bersih dan aman untuk anak-anak hingga lansia.
                    </p>
                    <div class="pt-3 border-t border-slate-100 text-xs text-slate-500">
                        Sumber: Air Quality Open Data
                    </div>
                </div>

                <!-- 2. Cuaca -->
                <div class="p-5 sm:p-6 rounded-3xl bg-white border border-slate-200 shadow-xs space-y-3">
                    <div class="text-xs font-bold text-slate-500 uppercase">SUHU & CUACA</div>
                    <div class="text-xl sm:text-2xl font-bold text-slate-900">
                        {{ $weather['temperature'] }}°C
                    </div>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        {{ $weather['weather_description'] }}.
                    </p>
                    <div class="pt-3 border-t border-slate-100 text-xs text-slate-500">
                        Sumber: BMKG Open Data
                    </div>
                </div>

                <!-- 3. Peluang Hujan -->
                <div class="p-5 sm:p-6 rounded-3xl bg-white border border-slate-200 shadow-xs space-y-3">
                    <div class="text-xs font-bold text-slate-500 uppercase">PELUANG HUJAN</div>
                    <div class="text-xl sm:text-2xl font-bold text-emerald-700">
                        {{ $weather['rain_probability'] }}% (Rendah)
                    </div>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        Aktivitas outdoor sore ini relatif aman dari hujan.
                    </p>
                    <div class="pt-3 border-t border-slate-100 text-xs text-slate-500">
                        Prakiraan Meteorologi
                    </div>
                </div>

                <!-- 4. Tingkat Keramaian -->
                <div class="p-5 sm:p-6 rounded-3xl bg-white border border-slate-200 shadow-xs space-y-3">
                    <div class="text-xs font-bold text-slate-500 uppercase">TINGKAT KERAMAIAN</div>
                    <div class="text-xl sm:text-2xl font-bold text-slate-900">
                        Sedang (Nyaman)
                    </div>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        Kepadatan pengunjung di tempat publik tergolong normal.
                    </p>
                    <div class="pt-3 border-t border-slate-100 text-xs text-slate-500">
                        Estimasi UrbanPulse
                    </div>
                </div>

            </div>
        </div>

    </div>
</x-layouts.app>
