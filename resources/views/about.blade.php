<x-layouts.app title="Tentang UrbanPulse — Infinitera 2.0">

    {{-- Samakan font display dengan beranda. Kalau sudah ada di layout utama, blok ini bisa dihapus. --}}
    <style>
        @import url('https://api.fontshare.com/v2/css?f[]=general-sans@600,500,700&display=swap');
        .font-display { font-family: 'General Sans', ui-sans-serif, system-ui, sans-serif; }
    </style>

    <!-- Container selaras dengan halaman Jelajahi Kota (max-w-7xl) -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 space-y-10 sm:space-y-14 overflow-hidden">

    <!-- Page Header (TIDAK DIUBAH) -->
        <div class="w-full pb-6 border-b border-slate-200 reveal-on-scroll opacity-0 translate-y-8 transition-all duration-1000 ease-out">
            <h1 class="font-display text-3xl sm:text-4xl font-semibold text-slate-900 tracking-tight">
                Tentang UrbanPulse
            </h1>
            <p class="text-slate-500 text-sm sm:text-base mt-2.5 leading-relaxed">
                Menjembatani inovasi teknologi web dan prinsip keberlanjutan untuk menciptakan dampak terukur bagi generasi masa depan.
            </p>
        </div>

        <!-- FOKUS & KESELARASAN TEMA -->
        <div class="reveal-on-scroll opacity-0 translate-x-8 transition-all duration-1000 ease-out">
            <h2 class="font-display text-2xl sm:text-3xl font-semibold text-slate-900 tracking-tight mb-6 sm:mb-8">
                Fokus & keselarasan tema
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-5">

                <!-- Inovasi & Keberlanjutan (Span 4, brand pillar) -->
                <div class="md:col-span-4 p-8 rounded-2xl bg-emerald-950 text-white flex flex-col justify-between space-y-10 min-h-[280px]">
                    <svg class="w-8 h-8 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                    <div class="space-y-3">
                        <h3 class="font-display font-semibold text-white text-lg">Inovasi & keberlanjutan</h3>
                        <p class="text-sm text-emerald-100/70 leading-relaxed">
                            Mengubah data mentah lingkungan menjadi rekomendasi praktis untuk keputusan harian warga kota.
                        </p>
                    </div>
                </div>

                <!-- SDG 11 (Span 8, warna resmi SDG 11: oranye) -->
                <div class="md:col-span-8 rounded-2xl border border-slate-200 relative overflow-hidden min-h-[280px] flex flex-col justify-between p-8">
                    <!-- Numeral besar sebagai elemen visual, bukan dekorasi -->
                    <span class="absolute -right-4 -bottom-10 font-display font-semibold text-[220px] leading-none text-[#FD9D24]/10 select-none pointer-events-none">
                        11
                    </span>

                    <div class="relative z-10 flex items-center gap-3">
                        <span class="w-9 h-9 rounded-md bg-[#FD9D24] flex items-center justify-center text-white font-display font-semibold text-sm">
                            11
                        </span>
                        <span class="text-sm text-slate-500">Tujuan Pembangunan Berkelanjutan</span>
                    </div>

                    <div class="relative z-10 space-y-2 max-w-md">
                        <h3 class="font-display font-semibold text-slate-900 text-2xl">Kota dan permukiman yang berkelanjutan</h3>
                        <p class="text-sm text-slate-500 leading-relaxed">
                            Mendorong ruang terbuka hijau, kenyamanan pejalan kaki, dan mobilitas berkelanjutan di wilayah perkotaan — ini pilar utama yang dijawab UrbanPulse.
                        </p>
                    </div>
                </div>

                <!-- SDG 13 (Span 12, warna resmi SDG 13: hijau tua) -->
                <div class="md:col-span-12 rounded-2xl relative overflow-hidden p-8 flex flex-col sm:flex-row sm:items-center gap-6 justify-between" style="background-color:#3F7E44;">
                    <div class="flex items-center gap-5 relative z-10">
                        <span class="w-9 h-9 shrink-0 rounded-md bg-white/15 flex items-center justify-center text-white font-display font-semibold text-sm">
                            13
                        </span>
                        <div class="space-y-1.5">
                            <h3 class="font-display font-semibold text-white text-xl">Penanganan perubahan iklim</h3>
                            <p class="text-sm text-white/75 leading-relaxed max-w-2xl">
                                Aksi mitigasi perubahan iklim terukur dengan pelacakan emisi CO2 avoided dari aktivitas individu — pilar pendukung UrbanPulse.
                            </p>
                        </div>
                    </div>
                    <span class="font-display font-semibold text-[110px] leading-none text-white/10 select-none pointer-events-none shrink-0 relative sm:static -mt-6 sm:mt-0">
                        13
                    </span>
                </div>

            </div>
        </div>

        <!-- PRINSIP DESAIN & AKSESIBILITAS -->
        <div class="reveal-on-scroll opacity-0 translate-y-10 transition-all duration-1000 ease-out">
            <h2 class="font-display text-2xl sm:text-3xl font-semibold text-slate-900 tracking-tight mb-6 sm:mb-8">
                Prinsip desain & aksesibilitas
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <!-- Card 1 -->
                <div class="p-8 rounded-2xl bg-white border border-slate-200 space-y-4">
                    <div class="flex items-baseline gap-3">
                        <span class="font-display text-3xl font-semibold text-emerald-700">16px+</span>
                        <h4 class="font-display font-semibold text-slate-900 text-lg">Mudah digunakan semua usia</h4>
                    </div>
                    <p class="text-sm text-slate-500 leading-relaxed">
                        Teks minimal 16px, kontras tinggi, tombol besar (minimal 44px area sentuh), serta navigasi yang ramah perangkat seluler.
                    </p>
                </div>

                <!-- Card 2 -->
                <div class="p-8 rounded-2xl bg-white border border-slate-200 space-y-4">
                    <div class="flex items-baseline gap-3">
                        <span class="font-display text-3xl font-semibold text-emerald-700">Bahasa ID</span>
                        <h4 class="font-display font-semibold text-slate-900 text-lg">Bahasa Indonesia yang ramah</h4>
                    </div>
                    <p class="text-sm text-slate-500 leading-relaxed">
                        Menghindari istilah teknis rumit di antarmuka utama agar siapapun dapat membuat keputusan hijau tanpa kebingungan.
                    </p>
                </div>

            </div>
        </div>

    </div>

    <!-- Scroll Reveal Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const observerOptions = {
                root: null,
                rootMargin: '0px',
                threshold: 0.15
            };

            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.remove('opacity-0', 'translate-y-8', 'translate-y-10', 'translate-x-8', 'scale-95');
                        entry.target.classList.add('opacity-100', 'translate-y-0', 'translate-x-0', 'scale-100');
                        observer.unobserve(entry.target); 
                    }
                });
            }, observerOptions);

            const revealElements = document.querySelectorAll('.reveal-on-scroll');
            revealElements.forEach(el => observer.observe(el));
        });
    </script>
</x-layouts.app>