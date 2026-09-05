<x-layouts.app title="UrbanPulse — Asisten Keputusan Kota Berkelanjutan">
    
    <!-- HERO SECTION -->
    <section class="bg-gradient-to-b from-emerald-50/60 via-white to-slate-50 py-8 sm:py-12 lg:py-20 border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto text-center space-y-4 sm:space-y-6">
                
                <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-100 border border-emerald-200 text-emerald-800 text-xs sm:text-sm font-semibold">
                    <span>🌱</span> Asisten Kota Berkelanjutan: <strong>{{ $activeCity->name ?? 'Kota Bogor' }}</strong>
                </span>

                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-bold tracking-tight text-slate-900 leading-tight">
                    Cari Tempat yang Tepat.<br>
                    <span class="text-emerald-600">Buat Keputusan Berkelanjutan.</span>
                </h1>

                <p class="text-base sm:text-xl text-slate-600 leading-relaxed font-normal max-w-2xl mx-auto">
                    UrbanPulse membantu Anda menemukan lokasi dan waktu yang sesuai berdasarkan kondisi lingkungan real-time dan data tata kota.
                </p>
            </div>

            <!-- PRIMARY USER QUESTIONNAIRE CARD (IMMEDIATE HOMEPAGE INTERACTION) -->
            <div class="mt-8 sm:mt-12 max-w-4xl mx-auto bg-white rounded-3xl p-4 sm:p-8 lg:p-10 border border-slate-200 shadow-xl shadow-slate-200/50 space-y-6 sm:space-y-8" x-data="{ activity: 'exercise', time: 'afternoon', priority: 'air_quality' }">
                
                <div class="border-b border-slate-100 pb-4 text-center sm:text-left">
                    <h2 class="text-xl sm:text-2xl font-bold text-slate-900">Temukan Rekomendasi Tempat di {{ $activeCity->name ?? 'Kota Anda' }}</h2>
                    <p class="text-slate-600 text-sm sm:text-base mt-1">Pilih rencana aktivitas Anda di bawah ini untuk mendapatkan rekomendasi lokasi terverifikasi.</p>
                </div>

                <form method="POST" action="{{ route('recommend.process') }}" class="space-y-6 sm:space-y-8">
                    @csrf
                    <input type="hidden" name="preferred_crowd" :value="priority === 'crowd' ? 'low' : 'any'">
                    <input type="hidden" name="transport_mode" :value="activity === 'cycling' ? 'bicycle' : (activity === 'commute' ? 'public_transport' : 'walking')">

                    <!-- QUESTION 1: Apa yang ingin Anda lakukan? -->
                    <div>
                        <label class="block text-sm sm:text-base font-bold text-slate-900 mb-2.5 sm:mb-3">
                            1. Apa yang ingin Anda lakukan?
                        </label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-2.5 sm:gap-3">
                            <label class="cursor-pointer">
                                <input type="radio" name="activity_type" value="exercise" x-model="activity" class="sr-only">
                                <div class="p-3 sm:p-4 rounded-2xl border text-center transition-all min-h-[64px] sm:min-h-[70px] flex flex-col justify-center items-center" :class="activity === 'exercise' ? 'border-emerald-600 bg-emerald-50 text-emerald-800 font-bold ring-2 ring-emerald-600' : 'border-slate-200 bg-slate-50 text-slate-700 hover:bg-slate-100'">
                                    <span class="text-xl sm:text-2xl mb-0.5">🏃</span>
                                    <span class="text-xs sm:text-sm">Olahraga</span>
                                </div>
                            </label>

                            <label class="cursor-pointer">
                                <input type="radio" name="activity_type" value="relax" x-model="activity" class="sr-only">
                                <div class="p-3 sm:p-4 rounded-2xl border text-center transition-all min-h-[64px] sm:min-h-[70px] flex flex-col justify-center items-center" :class="activity === 'relax' ? 'border-emerald-600 bg-emerald-50 text-emerald-800 font-bold ring-2 ring-emerald-600' : 'border-slate-200 bg-slate-50 text-slate-700 hover:bg-slate-100'">
                                    <span class="text-xl sm:text-2xl mb-0.5">🧘</span>
                                    <span class="text-xs sm:text-sm">Bersantai</span>
                                </div>
                            </label>

                            <label class="cursor-pointer">
                                <input type="radio" name="activity_type" value="study" x-model="activity" class="sr-only">
                                <div class="p-3 sm:p-4 rounded-2xl border text-center transition-all min-h-[64px] sm:min-h-[70px] flex flex-col justify-center items-center" :class="activity === 'study' ? 'border-emerald-600 bg-emerald-50 text-emerald-800 font-bold ring-2 ring-emerald-600' : 'border-slate-200 bg-slate-50 text-slate-700 hover:bg-slate-100'">
                                    <span class="text-xl sm:text-2xl mb-0.5">📚</span>
                                    <span class="text-xs sm:text-sm">Belajar</span>
                                </div>
                            </label>

                            <label class="cursor-pointer">
                                <input type="radio" name="activity_type" value="outdoor" x-model="activity" class="sr-only">
                                <div class="p-3 sm:p-4 rounded-2xl border text-center transition-all min-h-[64px] sm:min-h-[70px] flex flex-col justify-center items-center" :class="activity === 'outdoor' ? 'border-emerald-600 bg-emerald-50 text-emerald-800 font-bold ring-2 ring-emerald-600' : 'border-slate-200 bg-slate-50 text-slate-700 hover:bg-slate-100'">
                                    <span class="text-xl sm:text-2xl mb-0.5">🌳</span>
                                    <span class="text-xs sm:text-sm">Jalan-jalan</span>
                                </div>
                            </label>

                            <label class="cursor-pointer col-span-2 sm:col-span-1">
                                <input type="radio" name="activity_type" value="cycling" x-model="activity" class="sr-only">
                                <div class="p-3 sm:p-4 rounded-2xl border text-center transition-all min-h-[64px] sm:min-h-[70px] flex flex-col justify-center items-center" :class="activity === 'cycling' ? 'border-emerald-600 bg-emerald-50 text-emerald-800 font-bold ring-2 ring-emerald-600' : 'border-slate-200 bg-slate-50 text-slate-700 hover:bg-slate-100'">
                                    <span class="text-xl sm:text-2xl mb-0.5">🚲</span>
                                    <span class="text-xs sm:text-sm">Bersepeda</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- QUESTION 2: Kapan Anda ingin beraktivitas? -->
                    <div>
                        <label class="block text-sm sm:text-base font-bold text-slate-900 mb-2.5 sm:mb-3">
                            2. Kapan Anda ingin beraktivitas?
                        </label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 sm:gap-3">
                            <label class="cursor-pointer">
                                <input type="radio" name="preferred_time" value="morning" x-model="time" class="sr-only">
                                <div class="p-3 sm:p-3.5 rounded-2xl border text-center transition-all min-h-[50px] sm:min-h-[56px] flex flex-col justify-center items-center" :class="time === 'morning' ? 'border-emerald-600 bg-emerald-50 text-emerald-800 font-bold ring-2 ring-emerald-600' : 'border-slate-200 bg-slate-50 text-slate-700 hover:bg-slate-100'">
                                    <span class="text-xs sm:text-sm">🌅 Pagi (06.00-09.00)</span>
                                </div>
                            </label>

                            <label class="cursor-pointer">
                                <input type="radio" name="preferred_time" value="afternoon" x-model="time" class="sr-only">
                                <div class="p-3 sm:p-3.5 rounded-2xl border text-center transition-all min-h-[50px] sm:min-h-[56px] flex flex-col justify-center items-center" :class="time === 'afternoon' ? 'border-emerald-600 bg-emerald-50 text-emerald-800 font-bold ring-2 ring-emerald-600' : 'border-slate-200 bg-slate-50 text-slate-700 hover:bg-slate-100'">
                                    <span class="text-xs sm:text-sm">🌤️ Sore (16.00-18.00)</span>
                                </div>
                            </label>

                            <label class="cursor-pointer">
                                <input type="radio" name="preferred_time" value="evening" x-model="time" class="sr-only">
                                <div class="p-3 sm:p-3.5 rounded-2xl border text-center transition-all min-h-[50px] sm:min-h-[56px] flex flex-col justify-center items-center" :class="time === 'evening' ? 'border-emerald-600 bg-emerald-50 text-emerald-800 font-bold ring-2 ring-emerald-600' : 'border-slate-200 bg-slate-50 text-slate-700 hover:bg-slate-100'">
                                    <span class="text-xs sm:text-sm">🌙 Malam (18.30-20.30)</span>
                                </div>
                            </label>

                            <label class="cursor-pointer">
                                <input type="radio" name="preferred_time" value="any" x-model="time" class="sr-only">
                                <div class="p-3 sm:p-3.5 rounded-2xl border text-center transition-all min-h-[50px] sm:min-h-[56px] flex flex-col justify-center items-center" :class="time === 'any' ? 'border-emerald-600 bg-emerald-50 text-emerald-800 font-bold ring-2 ring-emerald-600' : 'border-slate-200 bg-slate-50 text-slate-700 hover:bg-slate-100'">
                                    <span class="text-xs sm:text-sm">⏰ Bebas (Kapan Saja)</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- QUESTION 3: Apa yang paling penting bagi Anda? -->
                    <div>
                        <label class="block text-sm sm:text-base font-bold text-slate-900 mb-2.5 sm:mb-3">
                            3. Apa yang paling penting bagi Anda?
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 sm:gap-3">
                            <label class="cursor-pointer">
                                <input type="radio" name="user_priority" value="air_quality" x-model="priority" class="sr-only">
                                <div class="p-3.5 sm:p-4 rounded-2xl border text-center transition-all min-h-[52px] sm:min-h-[60px] flex items-center justify-center gap-2" :class="priority === 'air_quality' ? 'border-emerald-600 bg-emerald-50 text-emerald-800 font-bold ring-2 ring-emerald-600' : 'border-slate-200 bg-slate-50 text-slate-700 hover:bg-slate-100'">
                                    <span class="text-lg sm:text-xl">🍃</span>
                                    <span class="text-xs sm:text-sm">Udara Segar & Bersih</span>
                                </div>
                            </label>

                            <label class="cursor-pointer">
                                <input type="radio" name="user_priority" value="crowd" x-model="priority" class="sr-only">
                                <div class="p-3.5 sm:p-4 rounded-2xl border text-center transition-all min-h-[52px] sm:min-h-[60px] flex items-center justify-center gap-2" :class="priority === 'crowd' ? 'border-emerald-600 bg-emerald-50 text-emerald-800 font-bold ring-2 ring-emerald-600' : 'border-slate-200 bg-slate-50 text-slate-700 hover:bg-slate-100'">
                                    <span class="text-lg sm:text-xl">🧘</span>
                                    <span class="text-xs sm:text-sm">Suasana Tenang (Tidak Ramai)</span>
                                </div>
                            </label>

                            <label class="cursor-pointer">
                                <input type="radio" name="user_priority" value="access" x-model="priority" class="sr-only">
                                <div class="p-3.5 sm:p-4 rounded-2xl border text-center transition-all min-h-[52px] sm:min-h-[60px] flex items-center justify-center gap-2" :class="priority === 'access' ? 'border-emerald-600 bg-emerald-50 text-emerald-800 font-bold ring-2 ring-emerald-600' : 'border-slate-200 bg-slate-50 text-slate-700 hover:bg-slate-100'">
                                    <span class="text-lg sm:text-xl">🚴</span>
                                    <span class="text-xs sm:text-sm">Akses Mudah & Sepeda</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- SUBMIT BUTTON -->
                    <button type="submit" class="w-full py-3.5 sm:py-4 px-6 sm:px-8 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-base sm:text-lg transition-all shadow-lg shadow-emerald-600/25 flex items-center justify-center gap-2 sm:gap-3 min-h-[50px] sm:min-h-[52px]">
                        <span>Temukan Rekomendasi Tempat</span>
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </form>
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
