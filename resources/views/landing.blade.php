<x-layouts.app title="UrbanPulse — Asisten Keputusan Kota Berkelanjutan">
    
    <!-- HERO SECTION: INTERACTIVE & PROFESSIONAL DESIGN -->
    <section class="relative bg-gradient-to-b from-slate-900 via-slate-900 to-slate-950 text-white overflow-hidden py-12 sm:py-16 lg:py-24 border-b border-slate-800">
        <!-- Subtle Glow Elements -->
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 right-10 w-96 h-96 bg-emerald-600/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10" x-data="{ 
            heroTab: 'finder', 
            activity: 'exercise', 
            time: 'afternoon', 
            priority: 'air_quality',
            setPreset(act, t, p) {
                this.heroTab = 'finder';
                this.activity = act;
                this.time = t;
                this.priority = p;
            }
        }">
            
            <div class="grid lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                
                <!-- Left Column: Content & Interactive Quick Presets -->
                <div class="lg:col-span-7 space-y-6 sm:space-y-8 text-center lg:text-left">
                    
                    <!-- Live City Indicator Badge -->
                    <div class="inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full bg-slate-800/80 border border-slate-700/80 text-xs text-slate-300 shadow-xs">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span>Lokasi Aktif: <strong class="text-emerald-400 font-bold">{{ $activeCity->name ?? 'Kota Bogor' }}</strong></span>
                        <span class="text-slate-600">|</span>
                        <span class="text-slate-400 font-mono">{{ $weather['temperature'] ?? 27.5 }}°C &bull; {{ $airQuality['air_quality_status'] ?? 'Udara Baik' }}</span>
                    </div>

                    <!-- Main Headline -->
                    <h1 class="text-3xl sm:text-5xl lg:text-6xl font-bold tracking-tight text-white leading-tight">
                        Cari Tempat Terbaik.<br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 via-teal-300 to-emerald-500">
                            Keputusan Kota Berkelanjutan.
                        </span>
                    </h1>

                    <p class="text-slate-300 text-base sm:text-lg leading-relaxed font-normal max-w-2xl mx-auto lg:mx-0">
                        UrbanPulse merekomendasikan taman, fasilitas publik, dan waktu aktivitas optimal berdasarkan data cuaca BMKG, kualitas udara, dan tingkat keramaian real-time.
                    </p>

                    <!-- Interactive Preset Quick Chips -->
                    <div class="pt-2 space-y-2">
                        <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">⚡ Rencana Cepat Populer:</div>
                        <div class="flex flex-wrap items-center justify-center lg:justify-start gap-2">
                            <button type="button" @click="setPreset('exercise', 'morning', 'air_quality')" class="px-3 py-1.5 rounded-xl bg-slate-800/90 hover:bg-emerald-950/60 hover:text-emerald-300 border border-slate-700/80 text-xs text-slate-300 transition-all inline-flex items-center gap-1.5">
                                <span>🏃</span> Jogging Pagi Udara Bersih
                            </button>
                            <button type="button" @click="setPreset('relax', 'afternoon', 'crowd')" class="px-3 py-1.5 rounded-xl bg-slate-800/90 hover:bg-emerald-950/60 hover:text-emerald-300 border border-slate-700/80 text-xs text-slate-300 transition-all inline-flex items-center gap-1.5">
                                <span>🧘</span> Santai Sore Tenang
                            </button>
                            <button type="button" @click="setPreset('cycling', 'morning', 'access')" class="px-3 py-1.5 rounded-xl bg-slate-800/90 hover:bg-emerald-950/60 hover:text-emerald-300 border border-slate-700/80 text-xs text-slate-300 transition-all inline-flex items-center gap-1.5">
                                <span>🚲</span> Rute Sepeda Pagi
                            </button>
                        </div>
                    </div>

                    <!-- Direct Action Buttons -->
                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-3 sm:gap-4 pt-2">
                        <a href="#interactive-finder" @click="heroTab = 'finder'" class="w-full sm:w-auto px-6 py-3.5 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-slate-950 font-bold text-sm sm:text-base transition-all shadow-lg shadow-emerald-500/20 flex items-center justify-center gap-2 min-h-[48px]">
                            <span>Cari Tempat Sekarang</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                        <a href="{{ route('city.dashboard') }}" class="w-full sm:w-auto px-6 py-3.5 rounded-xl bg-slate-800/80 hover:bg-slate-800 text-slate-200 border border-slate-700/80 font-semibold text-sm sm:text-base transition-all flex items-center justify-center gap-2 min-h-[48px]">
                            <span>Pantau Kondisi Kota</span>
                        </a>
                    </div>

                    <!-- Impact Counter Pill -->
                    <div class="pt-4 border-t border-slate-800/80 grid grid-cols-3 gap-4 text-center lg:text-left max-w-lg mx-auto lg:mx-0 text-xs">
                        <div>
                            <div class="font-mono font-bold text-emerald-400 text-lg">{{ $locationCount }}</div>
                            <div class="text-slate-400 text-[11px]">Lokasi Terdaftar</div>
                        </div>
                        <div>
                            <div class="font-mono font-bold text-emerald-400 text-lg">{{ number_format($totalCo2Avoided, 1) }} kg</div>
                            <div class="text-slate-400 text-[11px]">CO2 Hemat</div>
                        </div>
                        <div>
                            <div class="font-mono font-bold text-emerald-400 text-lg">BMKG & AQI</div>
                            <div class="text-slate-400 text-[11px]">Data Terverifikasi</div>
                        </div>
                    </div>

                </div>

                <!-- Right Column: Interactive Hub Card with Dual Tabs -->
                <div class="lg:col-span-5" id="interactive-finder">
                    <div class="bg-slate-900/90 border border-slate-800 rounded-3xl p-5 sm:p-7 shadow-2xl backdrop-blur-md space-y-5">
                        
                        <!-- Tab Navigation Switcher -->
                        <div class="flex items-center bg-slate-950 p-1 rounded-xl border border-slate-800 text-xs font-semibold">
                            <button type="button" @click="heroTab = 'finder'" :class="heroTab === 'finder' ? 'bg-emerald-600 text-white font-bold shadow-xs' : 'text-slate-400 hover:text-slate-200'" class="flex-1 py-2 rounded-lg transition-all text-center">
                                🔍 Rekomendasi Tempat
                            </button>
                            <button type="button" @click="heroTab = 'status'" :class="heroTab === 'status' ? 'bg-emerald-600 text-white font-bold shadow-xs' : 'text-slate-400 hover:text-slate-200'" class="flex-1 py-2 rounded-lg transition-all text-center">
                                📊 Sensor Live Kota
                            </button>
                        </div>

                        <!-- TAB 1: QUESTIONNAIRE FINDER -->
                        <div x-show="heroTab === 'finder'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                            <form method="POST" action="{{ route('recommend.process') }}" class="space-y-4">
                                @csrf
                                <input type="hidden" name="preferred_crowd" :value="priority === 'crowd' ? 'low' : 'any'">
                                <input type="hidden" name="transport_mode" :value="activity === 'cycling' ? 'bicycle' : (activity === 'commute' ? 'public_transport' : 'walking')">

                                <!-- Q1: Aktivitas -->
                                <div>
                                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">1. Pilih Rencana Aktivitas:</label>
                                    <div class="grid grid-cols-3 gap-2 text-xs">
                                        <label class="cursor-pointer">
                                            <input type="radio" name="activity_type" value="exercise" x-model="activity" class="sr-only">
                                            <div class="p-2.5 rounded-xl border text-center transition-all min-h-[50px] flex flex-col items-center justify-center" :class="activity === 'exercise' ? 'border-emerald-500 bg-emerald-500/10 text-emerald-300 font-bold' : 'border-slate-800 bg-slate-950/60 text-slate-400 hover:bg-slate-800/50'">
                                                <span class="text-base mb-0.5">🏃</span>
                                                <span class="text-[11px]">Olahraga</span>
                                            </div>
                                        </label>
                                        <label class="cursor-pointer">
                                            <input type="radio" name="activity_type" value="relax" x-model="activity" class="sr-only">
                                            <div class="p-2.5 rounded-xl border text-center transition-all min-h-[50px] flex flex-col items-center justify-center" :class="activity === 'relax' ? 'border-emerald-500 bg-emerald-500/10 text-emerald-300 font-bold' : 'border-slate-800 bg-slate-950/60 text-slate-400 hover:bg-slate-800/50'">
                                                <span class="text-base mb-0.5">🧘</span>
                                                <span class="text-[11px]">Bersantai</span>
                                            </div>
                                        </label>
                                        <label class="cursor-pointer">
                                            <input type="radio" name="activity_type" value="cycling" x-model="activity" class="sr-only">
                                            <div class="p-2.5 rounded-xl border text-center transition-all min-h-[50px] flex flex-col items-center justify-center" :class="activity === 'cycling' ? 'border-emerald-500 bg-emerald-500/10 text-emerald-300 font-bold' : 'border-slate-800 bg-slate-950/60 text-slate-400 hover:bg-slate-800/50'">
                                                <span class="text-base mb-0.5">🚲</span>
                                                <span class="text-[11px]">Bersepeda</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <!-- Q2: Waktu Aktivitas -->
                                <div>
                                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">2. Waktu Kunjungan:</label>
                                    <div class="grid grid-cols-2 gap-2 text-xs">
                                        <label class="cursor-pointer">
                                            <input type="radio" name="preferred_time" value="morning" x-model="time" class="sr-only">
                                            <div class="p-2 rounded-xl border text-center transition-all min-h-[40px] flex items-center justify-center gap-1.5" :class="time === 'morning' ? 'border-emerald-500 bg-emerald-500/10 text-emerald-300 font-bold' : 'border-slate-800 bg-slate-950/60 text-slate-400 hover:bg-slate-800/50'">
                                                <span>🌅 Pagi (06-09)</span>
                                            </div>
                                        </label>
                                        <label class="cursor-pointer">
                                            <input type="radio" name="preferred_time" value="afternoon" x-model="time" class="sr-only">
                                            <div class="p-2 rounded-xl border text-center transition-all min-h-[40px] flex items-center justify-center gap-1.5" :class="time === 'afternoon' ? 'border-emerald-500 bg-emerald-500/10 text-emerald-300 font-bold' : 'border-slate-800 bg-slate-950/60 text-slate-400 hover:bg-slate-800/50'">
                                                <span>🌤️ Sore (16-18)</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <!-- Q3: Prioritas Utama -->
                                <div>
                                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">3. Prioritas Utama:</label>
                                    <div class="grid grid-cols-2 gap-2 text-xs">
                                        <label class="cursor-pointer">
                                            <input type="radio" name="user_priority" value="air_quality" x-model="priority" class="sr-only">
                                            <div class="p-2 rounded-xl border text-center transition-all min-h-[40px] flex items-center justify-center gap-1.5" :class="priority === 'air_quality' ? 'border-emerald-500 bg-emerald-500/10 text-emerald-300 font-bold' : 'border-slate-800 bg-slate-950/60 text-slate-400 hover:bg-slate-800/50'">
                                                <span>🍃 Udara Bersih</span>
                                            </div>
                                        </label>
                                        <label class="cursor-pointer">
                                            <input type="radio" name="user_priority" value="crowd" x-model="priority" class="sr-only">
                                            <div class="p-2 rounded-xl border text-center transition-all min-h-[40px] flex items-center justify-center gap-1.5" :class="priority === 'crowd' ? 'border-emerald-500 bg-emerald-500/10 text-emerald-300 font-bold' : 'border-slate-800 bg-slate-950/60 text-slate-400 hover:bg-slate-800/50'">
                                                <span>🧘 Suasana Tenang</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <button type="submit" class="w-full py-3.5 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-slate-950 font-bold text-sm transition-all shadow-md shadow-emerald-500/20 flex items-center justify-center gap-2 mt-2">
                                    <span>Tampilkan Rekomendasi Tempat</span>
                                    <svg class="w-4 h-4 text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </button>
                            </form>
                        </div>

                        <!-- TAB 2: LIVE SENSOR STATUS CARD -->
                        <div x-show="heroTab === 'status'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="space-y-3 text-xs">
                            <div class="p-3.5 rounded-xl bg-slate-950 border border-slate-800 space-y-1">
                                <div class="flex justify-between items-center text-slate-400">
                                    <span>Kualitas Udara Real-Time</span>
                                    <span class="px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-400 font-mono text-[10px]">BMKG / OPEN API</span>
                                </div>
                                <div class="text-xl font-bold font-mono text-emerald-400">{{ $airQuality['air_quality_status'] ?? 'Baik' }}</div>
                                <p class="text-slate-400 text-[11px]">Konsentrasi partikel PM2.5 tergolong aman untuk aktivitas olahraga luar ruang.</p>
                            </div>

                            <div class="p-3.5 rounded-xl bg-slate-950 border border-slate-800 space-y-1">
                                <div class="flex justify-between items-center text-slate-400">
                                    <span>Cuaca & Suhu Kota</span>
                                    <span class="px-2 py-0.5 rounded bg-amber-500/20 text-amber-400 font-mono text-[10px]">STASIUN BMKG</span>
                                </div>
                                <div class="text-xl font-bold text-white">{{ $weather['weather_description'] ?? 'Cerah' }}, {{ $weather['temperature'] ?? 27.5 }}°C</div>
                                <p class="text-slate-400 text-[11px]">Peluang hujan {{ $weather['rain_probability'] ?? 20 }}%. Kondisi fisik taman sangat mendukung.</p>
                            </div>

                            <a href="{{ route('city.dashboard') }}" class="block w-full py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-center font-semibold text-slate-200 transition-colors text-xs">
                                Buka Dashboard Sensor Kota Lengkap &rarr;
                            </a>
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </section>

    <!-- LIVE ENVIRONMENT STATUS SUMMARY -->
    <section class="py-8 sm:py-12 bg-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-6 mb-6 sm:mb-8">
                <div>
                    <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider block mb-1">RINGKASAN REAL-TIME</span>
                    <h2 class="text-xl sm:text-2xl font-bold text-slate-900">Kondisi Lingkungan {{ $activeCity->name ?? 'Kota Anda' }} Saat Ini</h2>
                </div>
                <a href="{{ route('city.dashboard') }}" class="inline-flex items-center gap-1.5 text-sm sm:text-base font-bold text-emerald-700 hover:underline">
                    <span>Lihat Selengkapnya</span> &rarr;
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                <!-- Kualitas Udara -->
                <div class="p-5 sm:p-6 rounded-3xl bg-slate-50 border border-slate-200">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-xl flex-shrink-0">🍃</div>
                        <div>
                            <div class="text-xs font-bold text-slate-500 uppercase">KUALITAS UDARA</div>
                            <div class="text-lg sm:text-xl font-bold text-slate-900">{{ $airQuality['air_quality_status'] ?? 'Baik' }}</div>
                        </div>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        Sangat baik dan aman untuk aktivitas fisik luar ruangan hari ini.
                    </p>
                    <div class="mt-4 pt-3 border-t border-slate-200 text-xs text-slate-500">
                        Sumber Data: Air Quality Open Data
                    </div>
                </div>

                <!-- Cuaca BMKG -->
                <div class="p-5 sm:p-6 rounded-3xl bg-slate-50 border border-slate-200">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center font-bold text-xl flex-shrink-0">🌤️</div>
                        <div>
                            <div class="text-xs font-bold text-slate-500 uppercase">CUACA {{ strtoupper($activeCity->name ?? 'KOTA') }}</div>
                            <div class="text-lg sm:text-xl font-bold text-slate-900">{{ $weather['weather_description'] ?? 'Cerah' }}, {{ $weather['temperature'] ?? 27.5 }}°C</div>
                        </div>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        Peluang hujan relatif rendah ({{ $weather['rain_probability'] ?? 20 }}%), mendukung olahraga outdoor.
                    </p>
                    <div class="mt-4 pt-3 border-t border-slate-200 text-xs text-slate-500">
                        Sumber Data: Stasiun BMKG / Open Data
                    </div>
                </div>

                <!-- Tingkat Keramaian -->
                <div class="p-5 sm:p-6 rounded-3xl bg-slate-50 border border-slate-200 sm:col-span-2 lg:col-span-1">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-xl flex-shrink-0">👥</div>
                        <div>
                            <div class="text-xs font-bold text-slate-500 uppercase">TINGKAT KERAMAIAN</div>
                            <div class="text-lg sm:text-xl font-bold text-slate-900">Sedang (Nyaman)</div>
                        </div>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        Kepadatan pengunjung di tempat publik tergolong normal dan nyaman.
                    </p>
                    <div class="mt-4 pt-3 border-t border-slate-200 text-xs text-slate-500">
                        Estimasi Model UrbanPulse
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- POPULAR LOCATIONS PREVIEW -->
    <section class="py-10 sm:py-16 bg-slate-50 border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4 mb-6 sm:mb-10">
                <div>
                    <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider block mb-1">LOKASI PILIHAN</span>
                    <h2 class="text-2xl sm:text-3xl font-bold text-slate-900">Taman & Ruang Terbuka Hijau {{ $activeCity->name ?? 'Kota' }}</h2>
                </div>
                <a href="{{ route('map') }}" class="px-4 sm:px-5 py-2.5 sm:py-3 rounded-xl bg-white border border-slate-300 hover:border-emerald-600 text-slate-800 hover:text-emerald-700 font-bold text-sm sm:text-base transition-all shadow-xs flex items-center gap-2 self-start sm:self-auto min-h-[44px]">
                    <span>Lihat di Peta Interaktif</span> &rarr;
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
                @forelse($featuredLocations as $loc)
                    <div class="bg-white rounded-3xl border border-slate-200 p-5 sm:p-6 shadow-sm hover:shadow-md transition-shadow space-y-4 flex flex-col justify-between">
                        <div class="space-y-2">
                            <div class="flex justify-between items-start gap-2">
                                <h3 class="text-lg sm:text-xl font-bold text-slate-900 leading-tight">{{ $loc->name }}</h3>
                                <span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-800 text-[11px] font-bold uppercase flex-shrink-0">{{ $loc->category }}</span>
                            </div>
                            <p class="text-slate-600 text-xs sm:text-sm leading-relaxed line-clamp-2">{{ $loc->description }}</p>
                        </div>

                        <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs sm:text-sm">
                            <div class="font-medium text-slate-700">
                                <span class="text-emerald-700 font-bold">★ {{ $loc->green_score }}%</span> Ruang Hijau
                            </div>
                            <a href="{{ route('locations.show', $loc->slug) }}" class="font-bold text-emerald-700 hover:underline min-h-[38px] flex items-center">
                                Detail Tempat &rarr;
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-8 text-slate-500">
                        Belum ada lokasi fisik terdaftar untuk {{ $activeCity->name ?? 'kota ini' }}.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- SDG & IMPACT EXPLANATION -->
    <section class="py-10 sm:py-16 bg-white border-b border-slate-200">
        <div class="max-w-5xl mx-auto px-4 text-center space-y-6 sm:space-y-8">
            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-100 text-emerald-800 text-xs sm:text-sm font-semibold">
                Dampak Berkelanjutan
            </span>
            <h2 class="text-2xl sm:text-4xl font-bold text-slate-900 leading-tight">
                Bersama Mendorong Kota yang Lebih Sehat & Hijau
            </h2>
            <p class="text-slate-600 text-base sm:text-lg leading-relaxed max-w-3xl mx-auto">
                Setiap langkah jalan kaki, kayuhan sepeda, atau penggunaan transportasi umum yang Anda catat di UrbanPulse langsung menghitung emisi CO2 yang berhasil Anda hindari.
            </p>

            <div class="grid sm:grid-cols-2 gap-4 sm:gap-6 text-left max-w-3xl mx-auto">
                <div class="p-5 sm:p-6 rounded-3xl bg-slate-50 border border-slate-200 space-y-2">
                    <div class="text-2xl mb-1">🏢</div>
                    <h3 class="font-bold text-slate-900 text-base sm:text-lg">SDG 11: Kota Berkelanjutan</h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">Mendukung akses publik ke ruang hijau yang sehat dan infrastruktur pejalan kaki di wilayah perkotaan.</p>
                </div>

                <div class="p-5 sm:p-6 rounded-3xl bg-slate-50 border border-slate-200 space-y-2">
                    <div class="text-2xl mb-1">🌍</div>
                    <h3 class="font-bold text-slate-900 text-base sm:text-lg">SDG 13: Penanganan Perubahan Iklim</h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">Melacak perkiraan CO2 yang dihindari secara nyata dari pilihan transportasi sehari-hari.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FINAL CALL TO ACTION -->
    <section class="py-10 sm:py-16 bg-emerald-700 text-white">
        <div class="max-w-4xl mx-auto px-4 text-center space-y-4 sm:space-y-6">
            <h2 class="text-2xl sm:text-4xl font-bold">Mulai Buat Keputusan Berkelanjutan Hari Ini</h2>
            <p class="text-emerald-100 text-base sm:text-lg max-w-2xl mx-auto">
                Eksplorasi tempat terbaik di {{ $activeCity->name ?? 'kota Anda' }} dan catat kontribusi hijau Anda.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-4 pt-2 sm:pt-4">
                <a href="{{ route('recommend.index') }}" class="w-full sm:w-auto px-6 sm:px-8 py-3.5 sm:py-4 rounded-2xl bg-white text-emerald-800 hover:bg-emerald-50 font-bold text-base sm:text-lg shadow-lg transition-all min-h-[48px] sm:min-h-[52px] flex items-center justify-center">
                    Cari Rekomendasi Tempat
                </a>
                <a href="{{ route('city.dashboard') }}" class="w-full sm:w-auto px-6 sm:px-8 py-3.5 sm:py-4 rounded-2xl bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-base sm:text-lg transition-all min-h-[48px] sm:min-h-[52px] flex items-center justify-center border border-emerald-600">
                    Jelajahi {{ $activeCity->name ?? 'Kota' }}
                </a>
            </div>
        </div>
    </section>

</x-layouts.app>
