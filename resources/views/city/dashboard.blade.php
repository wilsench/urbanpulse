<x-layouts.app title="Kondisi Lingkungan — {{ $activeCity->name ?? 'Kota Anda' }} — UrbanPulse">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 space-y-10 sm:space-y-14 reveal-on-scroll opacity-0 translate-y-8 transition-all duration-1000 ease-out">

        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-6 pb-6 border-b border-slate-200">
            <div>
                <h1 class="text-3xl sm:text-4xl font-semibold text-slate-900 tracking-tight">Kondisi Lingkungan</h1>
                <p class="text-slate-500 text-base mt-2 flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span>{{ $activeCity->name ?? 'Kota Anda' }}</span>
                </p>
            </div>
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full sm:w-auto">
                <a href="{{ route('map') }}" class="px-5 py-2.5 rounded-lg bg-white border border-slate-300 hover:border-emerald-500 hover:text-emerald-700 text-slate-700 font-medium text-sm transition-colors flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                    <span>Lihat di Peta</span>
                </a>
                <a href="{{ route('recommend.index') }}" class="px-5 py-2.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-medium text-sm transition-colors shadow-sm flex items-center justify-center gap-2">
                    <span>Cari Rekomendasi</span>
                </a>
            </div>
        </div>

        <!-- MAIN SUSTAINABILITY CARD (Hero-like treatment) -->
        <div class="bg-slate-950 border border-slate-800 rounded-2xl p-8 sm:p-10 shadow-xl relative overflow-hidden">
            <!-- Subtle Grid Background -->
            <div class="absolute inset-0 opacity-10 bg-[linear-gradient(to_right,#ffffff12_1px,transparent_1px),linear-gradient(to_bottom,#ffffff12_1px,transparent_1px)] bg-[size:24px_24px]"></div>
            
            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-8 lg:gap-12">
                <div class="space-y-4 flex-grow">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-medium">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Indeks Keberlanjutan Kota
                    </div>
                    <div class="flex items-baseline gap-2">
                        <div class="text-5xl sm:text-6xl font-semibold tracking-tighter text-white">
                            {{ $sustainabilityScore }}
                        </div>
                        <div class="text-xl text-slate-500 font-medium">/ 100</div>
                    </div>
                    <div class="text-lg font-medium text-emerald-400">Kondisi Sangat Baik & Berkelanjutan</div>
                    <p class="text-slate-400 text-sm sm:text-base max-w-2xl leading-relaxed">
                        Skor ini dihitung dari kombinasi Kualitas Udara bersih, ketersediaan Ruang Terbuka Hijau, serta kenyamanan akses pejalan kaki di {{ $activeCity->name ?? 'kota ini' }}.
                    </p>
                </div>

                <!-- Meaning & Action Box -->
                <div class="bg-slate-900/80 border border-slate-800 backdrop-blur-sm rounded-xl p-6 w-full lg:w-96 shrink-0 space-y-4">
                    <div class="font-medium text-white text-sm border-b border-slate-800 pb-3 flex items-center gap-2">
                        <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        Apa Artinya Bagi Anda?
                    </div>
                    <p class="text-sm text-slate-400 leading-relaxed">
                        Hari ini adalah waktu yang sangat ideal untuk berolahraga, bersepeda, atau bersantai di taman luar ruangan {{ $activeCity->name ?? 'kota Anda' }}.
                    </p>
                    <a href="{{ route('recommend.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-emerald-400 hover:text-emerald-300 transition-colors pt-2">
                        <span>Lihat rekomendasi taman terdekat</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- CORE INDICATORS GRID -->
        <div class="space-y-6">
            <h2 class="text-2xl font-semibold tracking-tight text-slate-900">Indikator Kunci</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                <!-- 1. Kualitas Udara -->
                <div class="p-6 rounded-xl bg-white border border-slate-200 hover:border-emerald-200 transition-colors shadow-sm flex flex-col justify-between space-y-6">
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-2.5 bg-emerald-50 text-emerald-600 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path></svg>
                            </div>
                            <div class="text-sm font-medium text-slate-500">Kualitas Udara</div>
                        </div>
                        <div class="text-2xl font-semibold text-slate-900 mb-2">
                            {{ $airQuality['air_quality_status'] ?? 'Baik' }}
                        </div>
                        <p class="text-sm text-slate-500 leading-relaxed">
                            Udara bersih dan aman untuk anak-anak hingga lansia.
                        </p>
                    </div>
                    <div class="pt-4 border-t border-slate-100 text-xs text-slate-400">
                        Data: Air Quality Open API
                    </div>
                </div>

                <!-- 2. Cuaca -->
                <div class="p-6 rounded-xl bg-white border border-slate-200 hover:border-amber-200 transition-colors shadow-sm flex flex-col justify-between space-y-6">
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-2.5 bg-amber-50 text-amber-500 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            </div>
                            <div class="text-sm font-medium text-slate-500">Suhu & Cuaca</div>
                        </div>
                        <div class="text-2xl font-semibold text-slate-900 mb-2">
                            {{ $weather['temperature'] }}°C
                        </div>
                        <p class="text-sm text-slate-500 leading-relaxed">
                            {{ $weather['weather_description'] }}.
                        </p>
                    </div>
                    <div class="pt-4 border-t border-slate-100 text-xs text-slate-400">
                        Data: BMKG Open Data
                    </div>
                </div>

                <!-- 3. Peluang Hujan -->
                <div class="p-6 rounded-xl bg-white border border-slate-200 hover:border-blue-200 transition-colors shadow-sm flex flex-col justify-between space-y-6">
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-2.5 bg-blue-50 text-blue-500 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path></svg>
                            </div>
                            <div class="text-sm font-medium text-slate-500">Peluang Hujan</div>
                        </div>
                        <div class="text-2xl font-semibold text-slate-900 mb-2">
                            {{ $weather['rain_probability'] }}%
                        </div>
                        <p class="text-sm text-slate-500 leading-relaxed">
                            Aktivitas outdoor sore ini relatif aman dari hujan.
                        </p>
                    </div>
                    <div class="pt-4 border-t border-slate-100 text-xs text-slate-400">
                        Data: Prakiraan Meteorologi
                    </div>
                </div>

                <!-- 4. Tingkat Keramaian -->
                <div class="p-6 rounded-xl bg-white border border-slate-200 hover:border-indigo-200 transition-colors shadow-sm flex flex-col justify-between space-y-6">
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-2.5 bg-indigo-50 text-indigo-500 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </div>
                            <div class="text-sm font-medium text-slate-500">Tingkat Keramaian</div>
                        </div>
                        <div class="text-2xl font-semibold text-slate-900 mb-2">
                            Sedang
                        </div>
                        <p class="text-sm text-slate-500 leading-relaxed">
                            Kepadatan pengunjung di tempat publik tergolong normal dan nyaman.
                        </p>
                    </div>
                    <div class="pt-4 border-t border-slate-100 text-xs text-slate-400">
                        Estimasi UrbanPulse
                    </div>
                </div>

            </div>
        </div>

    </div>

    <!-- Script Intersection Observer for consistency across pages -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const observerOptions = {
                root: null,
                rootMargin: '0px',
                threshold: 0.10
            };

            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.remove('opacity-0', 'translate-y-8');
                        entry.target.classList.add('opacity-100', 'translate-y-0');
                        observer.unobserve(entry.target); 
                    }
                });
            }, observerOptions);

            const revealElements = document.querySelectorAll('.reveal-on-scroll');
            revealElements.forEach(el => observer.observe(el));
        });
    </script>
</x-layouts.app>