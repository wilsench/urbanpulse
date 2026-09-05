<x-layouts.app title="{{ $location->name }} — UrbanPulse">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10 space-y-6 sm:space-y-8">

        <!-- Breadcrumb navigation -->
        <nav class="flex flex-wrap text-xs sm:text-sm text-slate-500 gap-1.5 sm:gap-2">
            <a href="{{ route('landing') }}" class="hover:text-emerald-700">Beranda</a>
            <span>/</span>
            <a href="{{ route('map') }}" class="hover:text-emerald-700">Peta {{ $activeCity->name ?? 'Kota' }}</a>
            <span>/</span>
            <span class="text-slate-900 font-bold truncate max-w-[150px] sm:max-w-none">{{ $location->name }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
            <!-- Main Information Column -->
            <div class="lg:col-span-2 space-y-6 sm:space-y-8">
                <!-- Title Card -->
                <div class="p-5 sm:p-8 rounded-3xl bg-white border border-slate-200 shadow-sm space-y-4">
                    <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                        <span class="px-3 py-1 rounded-md bg-emerald-50 text-emerald-800 font-bold text-xs uppercase">
                            {{ $location->category }}
                        </span>
                        <span class="text-xs font-semibold text-slate-500">
                            Sumber Data: {{ strtoupper($location->source) }}
                        </span>
                    </div>

                    <h1 class="text-2xl sm:text-4xl font-bold text-slate-900 leading-tight">{{ $location->name }}</h1>
                    <p class="text-slate-600 text-sm sm:text-base leading-relaxed">{{ $location->description }}</p>

                    <div class="flex items-start gap-2.5 text-xs sm:text-sm text-slate-700 bg-slate-50 p-3.5 sm:p-4 rounded-2xl border border-slate-200">
                        <svg class="w-5 h-5 text-emerald-700 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                        <span>{{ $location->address }}</span>
                    </div>
                </div>

                <!-- Scores Breakdown Grid -->
                <div class="bg-white border border-slate-200 rounded-3xl p-5 sm:p-8 shadow-sm space-y-4 sm:space-y-6">
                    <h2 class="text-xl sm:text-2xl font-bold text-slate-900">Indikator Kualitas Lokasi</h2>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
                        <div class="p-4 sm:p-5 rounded-2xl bg-slate-50 border border-slate-200 text-center space-y-1">
                            <div class="text-[10px] sm:text-xs font-bold text-slate-500 uppercase">RUANG HIJAU</div>
                            <div class="text-2xl sm:text-3xl font-bold text-emerald-700">{{ $location->green_score }}%</div>
                            <div class="text-[11px] sm:text-xs text-slate-500">Cakupan Pohon</div>
                        </div>

                        <div class="p-4 sm:p-5 rounded-2xl bg-slate-50 border border-slate-200 text-center space-y-1">
                            <div class="text-[10px] sm:text-xs font-bold text-slate-500 uppercase">AKSESIBILITAS</div>
                            <div class="text-2xl sm:text-3xl font-bold text-slate-900">{{ $location->accessibility_score }}%</div>
                            <div class="text-[11px] sm:text-xs text-slate-500">Kemudahan Akses</div>
                        </div>

                        <div class="p-4 sm:p-5 rounded-2xl bg-slate-50 border border-slate-200 text-center space-y-1">
                            <div class="text-[10px] sm:text-xs font-bold text-slate-500 uppercase">JALAN KAKI</div>
                            <div class="text-2xl sm:text-3xl font-bold text-slate-900">{{ $location->walking_score }}%</div>
                            <div class="text-[11px] sm:text-xs text-slate-500">Jalur Pedestrian</div>
                        </div>

                        <div class="p-4 sm:p-5 rounded-2xl bg-slate-50 border border-slate-200 text-center space-y-1">
                            <div class="text-[10px] sm:text-xs font-bold text-slate-500 uppercase">SEPEDA</div>
                            <div class="text-base sm:text-lg font-bold text-emerald-700 mt-1 sm:mt-2">{{ $location->bike_friendly ? 'RAMAH' : 'TERBATAS' }}</div>
                            <div class="text-[11px] sm:text-xs text-slate-500">Jalur Sepeda</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Side Environmental Context & Actions -->
            <div class="space-y-6">
                <!-- Live Environmental Box -->
                <div class="p-5 sm:p-6 rounded-3xl bg-white border border-slate-200 shadow-sm space-y-3 sm:space-y-4">
                    <h3 class="font-bold text-slate-900 text-base sm:text-lg border-b border-slate-100 pb-3">Kondisi Saat Ini</h3>

                    <div class="flex justify-between items-center text-xs sm:text-sm py-1">
                        <span class="text-slate-600">Kualitas Udara:</span>
                        <span class="font-bold text-emerald-700">{{ $airQuality['air_quality_status'] }}</span>
                    </div>

                    <div class="flex justify-between items-center text-xs sm:text-sm py-1">
                        <span class="text-slate-600">Suhu Udara:</span>
                        <span class="font-bold text-slate-900">{{ $weather['temperature'] }}°C</span>
                    </div>

                    <div class="flex justify-between items-center text-xs sm:text-sm py-1">
                        <span class="text-slate-600">Prakiraan Cuaca:</span>
                        <span class="font-bold text-slate-900">{{ $weather['weather_description'] }}</span>
                    </div>

                    <div class="flex justify-between items-center text-xs sm:text-sm py-1">
                        <span class="text-slate-600">Peluang Hujan:</span>
                        <span class="font-bold text-emerald-700">{{ $weather['rain_probability'] }}%</span>
                    </div>

                    <div class="flex justify-between items-center text-xs sm:text-sm py-1">
                        <span class="text-slate-600">Estimasi Keramaian:</span>
                        <span class="font-bold text-slate-900">{{ $crowd['label'] }}</span>
                    </div>
                </div>

                <!-- Action CTAs (Large Touch Targets min 44px) -->
                <div class="p-5 sm:p-6 rounded-3xl bg-white border border-slate-200 shadow-sm space-y-3">
                    <a href="{{ route('assistant.index', ['q' => 'Mengapa lokasi ' . $location->name . ' direkomendasikan?']) }}" class="w-full py-3 sm:py-3.5 px-4 sm:px-5 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs sm:text-base flex items-center justify-center gap-2 transition-all shadow-md shadow-emerald-600/20 min-h-[48px]">
                        <span>💬 Tanya UrbanPulse Tentang Tempat Ini</span>
                    </a>

                    @auth
                        <a href="{{ route('actions.index') }}" class="w-full py-3 sm:py-3.5 px-4 sm:px-5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs sm:text-base flex items-center justify-center gap-2 transition-all min-h-[48px]">
                            <span>🌱 Catat Aksi Hijau di Tempat Ini</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="w-full py-3 sm:py-3.5 px-4 sm:px-5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs sm:text-base text-center block transition-all min-h-[48px]">
                            Masuk Akun untuk Catat Aksi
                        </a>
                    @endauth
                </div>
            </div>
        </div>

    </div>
</x-layouts.app>
