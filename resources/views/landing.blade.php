<x-layouts.app title="UrbanPulse — Asisten Keputusan Kota Berkelanjutan">

    {{-- 
        Font display untuk headline. Kalau project sudah punya font loader sendiri 
        di layout utama, style block ini bisa dipindah ke sana / dihapus dan cukup 
        tambahkan class font-display ke tailwind.config.js.
    --}}
    <style>
        @import url('https://api.fontshare.com/v2/css?f[]=general-sans@600,500,700&display=swap');
        .font-display { font-family: 'General Sans', ui-sans-serif, system-ui, sans-serif; }
    </style>

    <!-- HERO SECTION -->
    <section class="relative bg-slate-950 text-white overflow-hidden py-12 sm:py-16 lg:py-24 border-b border-slate-800">
        <!-- City Grid Background Pattern -->
        <div class="absolute inset-0 opacity-10 bg-[linear-gradient(to_right,#80808012_1px,transparent_1px),linear-gradient(to_bottom,#80808012_1px,transparent_1px)] bg-[size:24px_24px]"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/80 to-transparent pointer-events-none"></div>
        <!-- Single deliberate accent glow behind the headline only -->
        <div class="absolute -top-24 left-1/3 w-[420px] h-[420px] bg-emerald-400/10 rounded-full blur-[100px] pointer-events-none"></div>

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
            
            <div class="grid lg:grid-cols-12 gap-10 lg:gap-16 items-center">
                
                <!-- Left Column -->
                <div class="lg:col-span-7 space-y-7 sm:space-y-9 text-center lg:text-left">
                    
                    <!-- Live City Indicator -->
                    <div class="inline-flex items-center gap-2.5 px-3 py-1.5 rounded-lg bg-slate-900 border border-slate-800 text-xs text-slate-300">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="font-medium">Lagi mantau: <span class="text-emerald-400 font-semibold">{{ $activeCity->name ?? 'Kota Bogor' }}</span></span>
                        <span class="text-slate-700">|</span>
                        <span class="text-slate-400 font-mono">{{ $weather['temperature'] ?? 27.5 }}°C &bull; {{ $airQuality['air_quality_status'] ?? 'Udara Baik' }}</span>
                    </div>

                    <!-- Main Headline -->
                    <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl font-medium tracking-tight text-white leading-[1.05]">
                        Kotamu, versi
                        <span class="relative inline-block">
                            <span class="relative z-10">paling adem.</span>
                            <span class="absolute left-0 right-0 bottom-1 h-3 bg-emerald-500/30 -z-0"></span>
                        </span>
                    </h1>

                    <p class="text-slate-400 text-base sm:text-lg leading-relaxed max-w-2xl mx-auto lg:mx-0">
                        Males olahraga di tempat yang polusinya parah, atau nongkrong di taman yang penuh sesak? UrbanPulse kasih tau tempat yang pas — udara, cuaca, sama keramaiannya udah dicek dulu buat kamu.
                    </p>

                    <!-- Interactive Preset Quick Chips -->
                    <div class="pt-1 space-y-3">
                        <div class="text-sm font-medium text-slate-500">Pilih vibe hari ini:</div>
                        <div class="flex flex-wrap items-center justify-center lg:justify-start gap-2.5">
                            <button type="button" @click="setPreset('exercise', 'morning', 'air_quality')" class="group px-3.5 py-2 rounded-lg bg-slate-900 hover:bg-slate-800 border border-slate-800 hover:border-emerald-500/50 text-xs text-slate-300 transition-all inline-flex items-center gap-2 hover:-translate-y-0.5">
                                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                Jogging Udara Bersih
                            </button>
                            <button type="button" @click="setPreset('relax', 'afternoon', 'crowd')" class="group px-3.5 py-2 rounded-lg bg-slate-900 hover:bg-slate-800 border border-slate-800 hover:border-emerald-500/50 text-xs text-slate-300 transition-all inline-flex items-center gap-2 hover:-translate-y-0.5">
                                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                                Me Time Sore
                            </button>
                            <button type="button" @click="setPreset('cycling', 'morning', 'access')" class="group px-3.5 py-2 rounded-lg bg-slate-900 hover:bg-slate-800 border border-slate-800 hover:border-emerald-500/50 text-xs text-slate-300 transition-all inline-flex items-center gap-2 hover:-translate-y-0.5">
                                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                Gowes Pagi
                            </button>
                        </div>
                    </div>

                    <!-- Direct Action Buttons -->
                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-3 sm:gap-4 pt-4">
                        <a href="#interactive-finder" @click="heroTab = 'finder'" class="w-full sm:w-auto px-6 py-3 rounded-lg bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-semibold text-sm transition-colors flex items-center justify-center gap-2">
                            Cariin Tempat
                        </a>
                        <a href="{{ route('city.dashboard') }}" class="w-full sm:w-auto px-6 py-3 rounded-lg bg-transparent hover:bg-slate-800 text-slate-300 border border-slate-700 font-medium text-sm transition-colors flex items-center justify-center">
                            Cek Kondisi Kota
                        </a>
                    </div>

                    <!-- Streak-style Impact Card (Gen Z gamification, Duolingo-esque) -->
                    <div class="pt-2">
                        <div class="inline-flex flex-wrap items-center gap-4 sm:gap-6 px-5 py-4 rounded-2xl bg-gradient-to-r from-slate-900 to-slate-900/60 border border-slate-800 mx-auto lg:mx-0">
                            <div class="flex items-center gap-2">
                                <svg class="w-6 h-6 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path></svg>
                                <div class="text-left">
                                    <div class="font-display font-semibold text-white text-lg leading-none">{{ number_format($totalCo2Avoided, 1) }} kg</div>
                                    <div class="text-slate-500 text-[11px] mt-0.5">CO2 dihemat bareng-bareng</div>
                                </div>
                            </div>
                            <div class="w-px h-8 bg-slate-800 hidden sm:block"></div>
                            <div class="text-left">
                                <div class="font-display font-semibold text-white text-lg leading-none">{{ $locationCount }}</div>
                                <div class="text-slate-500 text-[11px] mt-0.5">spot terdaftar</div>
                            </div>
                            <div class="w-px h-8 bg-slate-800 hidden sm:block"></div>
                            <div class="text-left">
                                <div class="font-display font-semibold text-emerald-400 text-lg leading-none">Live</div>
                                <div class="text-slate-500 text-[11px] mt-0.5">data cuaca & AQI</div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Right Column: Interactive Hub Card -->
                <div class="lg:col-span-5" id="interactive-finder">
                    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-2xl shadow-black/40 space-y-6">
                        
                        <!-- Tab Navigation -->
                        <div class="flex items-center border-b border-slate-800 text-sm font-medium">
                            <button type="button" @click="heroTab = 'finder'" :class="heroTab === 'finder' ? 'text-emerald-400 border-b-2 border-emerald-500' : 'text-slate-500 hover:text-slate-300'" class="flex-1 pb-3 transition-colors text-center">
                                Cari Tempat
                            </button>
                            <button type="button" @click="heroTab = 'status'" :class="heroTab === 'status' ? 'text-emerald-400 border-b-2 border-emerald-500' : 'text-slate-500 hover:text-slate-300'" class="flex-1 pb-3 transition-colors text-center">
                                Sensor Live
                            </button>
                        </div>

                        <!-- TAB 1: QUESTIONNAIRE FINDER -->
                        <div x-show="heroTab === 'finder'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                            <form method="POST" action="{{ route('recommend.process') }}" class="space-y-5">
                                @csrf
                                <input type="hidden" name="preferred_crowd" :value="priority === 'crowd' ? 'low' : 'any'">
                                <input type="hidden" name="transport_mode" :value="activity === 'cycling' ? 'bicycle' : (activity === 'commute' ? 'public_transport' : 'walking')">

                                <!-- Q1: Aktivitas -->
                                <div>
                                    <label class="block text-sm font-medium text-slate-300 mb-2.5">Lagi pengen ngapain?</label>
                                    <div class="grid grid-cols-3 gap-2.5 text-xs">
                                        <label class="cursor-pointer">
                                            <input type="radio" name="activity_type" value="exercise" x-model="activity" class="sr-only">
                                            <div class="p-3 rounded-lg border text-center transition-all flex flex-col items-center justify-center gap-2" :class="activity === 'exercise' ? 'border-emerald-500 bg-emerald-400/10 text-emerald-300' : 'border-slate-800 bg-slate-950 text-slate-400 hover:border-slate-600'">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                                <span>Olahraga</span>
                                            </div>
                                        </label>
                                        <label class="cursor-pointer">
                                            <input type="radio" name="activity_type" value="relax" x-model="activity" class="sr-only">
                                            <div class="p-3 rounded-lg border text-center transition-all flex flex-col items-center justify-center gap-2" :class="activity === 'relax' ? 'border-emerald-500 bg-emerald-400/10 text-emerald-300' : 'border-slate-800 bg-slate-950 text-slate-400 hover:border-slate-600'">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                                                <span>Bersantai</span>
                                            </div>
                                        </label>
                                        <label class="cursor-pointer">
                                            <input type="radio" name="activity_type" value="cycling" x-model="activity" class="sr-only">
                                            <div class="p-3 rounded-lg border text-center transition-all flex flex-col items-center justify-center gap-2" :class="activity === 'cycling' ? 'border-emerald-500 bg-emerald-400/10 text-emerald-300' : 'border-slate-800 bg-slate-950 text-slate-400 hover:border-slate-600'">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                                <span>Bersepeda</span>
                                            </div>
                                        </label>
                                        <label class="cursor-pointer">
                                            <input type="radio" name="activity_type" value="study" x-model="activity" class="sr-only">
                                            <div class="p-3 rounded-lg border text-center transition-all flex flex-col items-center justify-center gap-2" :class="activity === 'study' ? 'border-emerald-500 bg-emerald-400/10 text-emerald-300' : 'border-slate-800 bg-slate-950 text-slate-400 hover:border-slate-600'">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"></path></svg>
                                                <span>Belajar</span>
                                            </div>
                                        </label>
                                        <label class="cursor-pointer">
                                            <input type="radio" name="activity_type" value="walk" x-model="activity" class="sr-only">
                                            <div class="p-3 rounded-lg border text-center transition-all flex flex-col items-center justify-center gap-2" :class="activity === 'walk' ? 'border-emerald-500 bg-emerald-400/10 text-emerald-300' : 'border-slate-800 bg-slate-950 text-slate-400 hover:border-slate-600'">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 6.75V15m6-6v8.25m.503 3.498l4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.752a1.125 1.125 0 00-1.006 0L3.622 6.189C3.24 6.38 3 6.77 3 7.195v10.585c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0z"></path></svg>
                                                <span>Jalan-jalan</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <!-- Q2: Waktu -->
                                <div>
                                    <label class="block text-sm font-medium text-slate-300 mb-2.5">Berangkat jam berapa?</label>
                                    <div class="grid grid-cols-2 gap-2.5 text-xs">
                                        <label class="cursor-pointer">
                                            <input type="radio" name="preferred_time" value="morning" x-model="time" class="sr-only">
                                            <div class="p-3 rounded-lg border text-center transition-all flex items-center justify-center gap-2" :class="time === 'morning' ? 'border-emerald-500 bg-emerald-400/10 text-emerald-300' : 'border-slate-800 bg-slate-950 text-slate-400 hover:border-slate-600'">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                                <span>Pagi (06.00–09.00)</span>
                                            </div>
                                        </label>
                                        <label class="cursor-pointer">
                                            <input type="radio" name="preferred_time" value="afternoon" x-model="time" class="sr-only">
                                            <div class="p-3 rounded-lg border text-center transition-all flex items-center justify-center gap-2" :class="time === 'afternoon' ? 'border-emerald-500 bg-emerald-400/10 text-emerald-300' : 'border-slate-800 bg-slate-950 text-slate-400 hover:border-slate-600'">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path></svg>
                                                <span>Sore (16.00–18.00)</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <!-- Q3: Prioritas -->
                                <div>
                                    <label class="block text-sm font-medium text-slate-300 mb-2.5">Yang paling penting buat kamu?</label>
                                    <div class="grid grid-cols-2 gap-2.5 text-xs">
                                        <label class="cursor-pointer">
                                            <input type="radio" name="user_priority" value="air_quality" x-model="priority" class="sr-only">
                                            <div class="p-3 rounded-lg border text-center transition-all flex items-center justify-center gap-2" :class="priority === 'air_quality' ? 'border-emerald-500 bg-emerald-400/10 text-emerald-300' : 'border-slate-800 bg-slate-950 text-slate-400 hover:border-slate-600'">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path></svg>
                                                <span>Udara Bersih</span>
                                            </div>
                                        </label>
                                        <label class="cursor-pointer">
                                            <input type="radio" name="user_priority" value="crowd" x-model="priority" class="sr-only">
                                            <div class="p-3 rounded-lg border text-center transition-all flex items-center justify-center gap-2" :class="priority === 'crowd' ? 'border-emerald-500 bg-emerald-400/10 text-emerald-300' : 'border-slate-800 bg-slate-950 text-slate-400 hover:border-slate-600'">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                                <span>Suasana Tenang</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <button type="submit" class="w-full py-3 rounded-lg bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-semibold text-sm transition-colors mt-2">
                                    Cariin Tempatnya
                                </button>
                            </form>
                        </div>

                        <!-- TAB 2: LIVE SENSOR STATUS -->
                        <div x-show="heroTab === 'status'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="space-y-3">
                            <div class="p-4 rounded-xl border border-slate-800 bg-slate-950/50">
                                <div class="flex justify-between items-center text-slate-400 text-xs mb-2">
                                    <span>Kualitas Udara Real-Time</span>
                                    <span class="text-emerald-400 font-mono">AQI API</span>
                                </div>
                                <div class="text-xl font-medium text-white">{{ $airQuality['air_quality_status'] ?? 'Baik' }}</div>
                                <p class="text-slate-500 text-xs mt-1">PM2.5 aman buat olahraga di luar.</p>
                            </div>
                            
                            <div class="p-4 rounded-xl border border-slate-800 bg-slate-950/50">
                                <div class="flex justify-between items-center text-slate-400 text-xs mb-2">
                                    <span>Cuaca & Suhu Kota</span>
                                    <span class="text-amber-500 font-mono">BMKG</span>
                                </div>
                                <div class="text-xl font-medium text-white">{{ $weather['weather_description'] ?? 'Cerah' }}, {{ $weather['temperature'] ?? 27.5 }}°C</div>
                                <p class="text-slate-500 text-xs mt-1">Peluang hujan {{ $weather['rain_probability'] ?? 20 }}%, aman buat main ke luar.</p>
                            </div>

                            <a href="{{ route('city.dashboard') }}" class="block w-full py-3 rounded-lg bg-slate-800 hover:bg-slate-700 text-center font-medium text-slate-300 transition-colors text-sm mt-4">
                                Buka Dashboard Lengkap
                            </a>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- LIVE ENVIRONMENT STATUS — bento layout -->
    <section class="py-12 sm:py-16 bg-slate-50 border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 reveal-on-scroll opacity-0 translate-y-8 transition-all duration-1000 ease-out">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8 sm:mb-12">
                <div>
                    <h2 class="font-display text-2xl sm:text-3xl font-semibold tracking-tight text-slate-900">Gimana kondisi hari ini?</h2>
                    <p class="text-slate-500 mt-2">{{ $activeCity->name ?? 'Kota Anda' }}, update tiap saat.</p>
                </div>
                <a href="{{ route('city.dashboard') }}" class="text-sm font-medium text-emerald-600 hover:text-emerald-700 transition-colors">
                    Lihat detail sensor kota
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                <!-- Kualitas Udara: kartu utama, lebih besar -->
                <div class="sm:row-span-2 p-7 bg-white border border-slate-200 rounded-2xl flex flex-col justify-between">
                    <div class="flex items-start justify-between">
                        <div class="p-3 bg-emerald-50 rounded-xl text-emerald-600">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path></svg>
                        </div>
                        <span class="text-[11px] font-medium text-slate-400">Live</span>
                    </div>
                    <div class="mt-6">
                        <div class="text-sm text-slate-500 mb-1">Kualitas Udara</div>
                        <div class="font-display text-3xl font-semibold text-slate-900">{{ $airQuality['air_quality_status'] ?? 'Baik' }}</div>
                        <p class="text-slate-500 text-sm mt-3 leading-relaxed">Aman buat olahraga atau nongkrong outdoor hari ini.</p>
                    </div>
                </div>

                <!-- Cuaca -->
                <div class="p-6 bg-white border border-slate-200 rounded-2xl flex items-center gap-4">
                    <div class="p-2.5 bg-amber-50 rounded-lg text-amber-500 shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path></svg>
                    </div>
                    <div>
                        <div class="text-xs font-medium text-slate-500 mb-1">Cuaca</div>
                        <div class="text-lg font-semibold text-slate-900">{{ $weather['weather_description'] ?? 'Cerah' }}, {{ $weather['temperature'] ?? 27.5 }}°C</div>
                    </div>
                </div>

                <!-- Keramaian -->
                <div class="p-6 bg-white border border-slate-200 rounded-2xl flex items-center gap-4">
                    <div class="p-2.5 bg-blue-50 rounded-lg text-blue-500 shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <div>
                        <div class="text-xs font-medium text-slate-500 mb-1">Keramaian</div>
                        <div class="text-lg font-semibold text-slate-900">Sedang (Nyaman)</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- POPULAR LOCATIONS -->
    <section class="py-16 sm:py-24 bg-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 reveal-on-scroll opacity-0 translate-y-8 transition-all duration-1000 ease-out">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-10 sm:mb-12">
                <div>
                    <h2 class="font-display text-2xl sm:text-3xl font-semibold tracking-tight text-slate-900">Spot yang lagi rame dicari</h2>
                    <p class="text-slate-500 mt-2">Taman dan ruang terbuka hijau di {{ $activeCity->name ?? 'kota ini' }}.</p>
                </div>
                <a href="{{ route('map') }}" class="text-sm font-medium text-slate-600 hover:text-emerald-600 transition-colors">
                    Lihat semua di peta interaktif
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                @forelse($featuredLocations as $loc)
                    <div class="group relative border border-slate-200 rounded-2xl p-6 hover:border-emerald-300 hover:shadow-xl hover:shadow-emerald-100 transition-all duration-300 hover:-rotate-1 hover:-translate-y-1 flex flex-col justify-between bg-white">
                        <span class="absolute -top-2.5 -right-2.5 px-2.5 py-1 bg-emerald-500 text-white text-[10px] font-bold rounded-full rotate-6 shadow-sm">
                            {{ Str::headline($loc->category) }}
                        </span>
                        <div class="space-y-4">
                            <h3 class="font-display text-lg font-semibold text-slate-900 group-hover:text-emerald-600 transition-colors leading-snug pr-8">{{ $loc->name }}</h3>
                            <p class="text-slate-500 text-sm leading-relaxed line-clamp-2">{{ $loc->description }}</p>
                        </div>
                        
                        <div class="mt-8 pt-4 border-t border-slate-100 flex items-center justify-between text-sm">
                            <div class="text-slate-600">
                                Ruang Hijau: <span class="font-semibold text-slate-900">{{ $loc->green_score }}%</span>
                            </div>
                            <a href="{{ route('locations.show', $loc->slug) }}" class="font-medium text-emerald-600 group-hover:text-emerald-700 transition-colors">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center text-slate-500 border border-dashed border-slate-200 rounded-xl">
                        Belum ada lokasi fisik terdaftar untuk {{ $activeCity->name ?? 'kota ini' }}.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- SDG & IMPACT EXPLANATION -->
    <section class="py-16 sm:py-24 bg-slate-50 border-b border-slate-200">
        <div class="max-w-5xl mx-auto px-4 reveal-on-scroll opacity-0 translate-y-8 transition-all duration-1000 ease-out">
            <div class="mb-12 text-center max-w-2xl mx-auto">
                <h2 class="font-display text-3xl font-semibold tracking-tight text-slate-900 mb-4">
                    Kontribusi kecil, dampaknya kerasa
                </h2>
                <p class="text-slate-500 text-lg leading-relaxed">
                    Tiap kali kamu jalan kaki, gowes, atau naik transportasi umum yang dicatat di sini, itu langsung ngurangin emisi CO2 kota.
                </p>
            </div>

            <div class="grid sm:grid-cols-2 gap-8 text-left max-w-4xl mx-auto">
                <div class="border border-slate-200 bg-white p-8 rounded-2xl flex gap-6">
                    <span class="font-display text-5xl font-light text-slate-300 select-none">11</span>
                    <div>
                        <h3 class="font-medium text-slate-900 text-lg mb-2">Kota Berkelanjutan</h3>
                        <p class="text-sm text-slate-500 leading-relaxed">Mendukung akses publik ke ruang hijau yang sehat dan infrastruktur pejalan kaki di wilayah perkotaan.</p>
                    </div>
                </div>

                <div class="border border-slate-200 bg-white p-8 rounded-2xl flex gap-6">
                    <span class="font-display text-5xl font-light text-slate-300 select-none">13</span>
                    <div>
                        <h3 class="font-medium text-slate-900 text-lg mb-2">Penanganan Perubahan Iklim</h3>
                        <p class="text-sm text-slate-500 leading-relaxed">Melacak perkiraan CO2 yang dihindari secara nyata dari pilihan mobilitas harian Anda.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FINAL CALL TO ACTION -->
    <section class="py-20 bg-slate-950 text-white relative border-t-4 border-emerald-500">
        <div class="absolute inset-0 opacity-5 bg-[linear-gradient(to_right,#ffffff12_1px,transparent_1px),linear-gradient(to_bottom,#ffffff12_1px,transparent_1px)] bg-[size:24px_24px]"></div>
        <div class="max-w-3xl mx-auto px-4 text-center relative z-10">
            <h2 class="font-display text-3xl sm:text-4xl font-semibold tracking-tight mb-6">Yuk, mulai dari yang deket-deket dulu</h2>
            <p class="text-slate-400 text-lg mb-10">
                Cari tempat terbaik di {{ $activeCity->name ?? 'kota kamu' }} dan catat kontribusi hijaumu.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('recommend.index') }}" class="px-8 py-3.5 rounded-lg bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-semibold transition-colors">
                    Cari Rekomendasi Tempat
                </a>
                <a href="{{ route('city.dashboard') }}" class="px-8 py-3.5 rounded-lg border border-slate-700 hover:bg-slate-800 text-white font-medium transition-colors">
                    Jelajahi {{ $activeCity->name ?? 'Kota' }}
                </a>
            </div>
        </div>
    </section>

    <!-- Script Intersection Observer -->
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