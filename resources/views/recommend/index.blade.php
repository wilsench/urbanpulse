<x-layouts.app title="Rekomendasi untuk Anda — UrbanPulse">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 space-y-10 sm:space-y-14 reveal-on-scroll opacity-0 translate-y-8 transition-all duration-1000 ease-out" x-data="recommendWizard()">

        <!-- Page Header (Disamakan dengan City Dashboard) -->
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-6 pb-6 border-b border-slate-200">
            <div>
                <h1 class="text-3xl sm:text-4xl font-semibold text-slate-900 tracking-tight">Temukan Tempat Terbaik</h1>
                <p class="text-slate-500 text-base mt-2 flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span>Sesuai dengan preferensi aktivitas, waktu, dan mobilitas Anda.</span>
                </p>
            </div>
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full sm:w-auto">
                <a href="{{ route('city.dashboard') }}" class="px-5 py-2.5 rounded-lg bg-white border border-slate-300 hover:border-emerald-500 hover:text-emerald-700 text-slate-700 font-medium text-sm transition-colors flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path></svg>
                    <span>Kondisi Kota</span>
                </a>
            </div>
        </div>

        <!-- Wizard Container -->
        <div class="bg-white border border-slate-200/80 shadow-md shadow-slate-200/40 rounded-3xl p-6 sm:p-10 mx-auto max-w-4xl">
            
            <!-- Custom Progress Indicator -->
            <div class="mb-10">
                <div class="flex items-center gap-1.5 sm:gap-2 mb-5">
                    <template x-for="stepNum in 4" :key="stepNum">
                        <div class="h-1.5 flex-1 rounded-full transition-all duration-500"
                             :class="currentStep >= stepNum ? 'bg-emerald-500' : 'bg-slate-100'"></div>
                    </template>
                </div>
                <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-2">
                    <div>
                        <span class="text-xs font-mono font-bold tracking-widest text-emerald-600 uppercase" x-text="'Langkah ' + currentStep + ' dari 4'"></span>
                        <h2 class="text-xl sm:text-2xl font-bold text-slate-900 mt-1" x-text="stepTitles[currentStep - 1]"></h2>
                    </div>
                    <div class="text-slate-400 text-sm font-medium" x-show="currentStep < 4">
                        Selanjutnya: <span x-text="stepTitles[currentStep]"></span>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('recommend.process') }}">
                @csrf

                <!-- STEP 1: AKTIVITAS (Detailed Vertical List) -->
                <div x-show="currentStep === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-3">
                    
                    <label class="group relative flex flex-col sm:flex-row sm:items-center gap-4 p-5 cursor-pointer rounded-2xl transition-all border border-slate-200 bg-white hover:border-emerald-300 hover:shadow-md"
                        :class="form.activity_type === 'exercise' ? 'ring-2 ring-emerald-500 bg-emerald-50/20 border-transparent' : ''">
                        <input type="radio" name="activity_type" value="exercise" x-model="form.activity_type" class="sr-only">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 transition-colors"
                            :class="form.activity_type === 'exercise' ? 'bg-emerald-500 text-white' : 'bg-slate-100 text-slate-500 group-hover:bg-emerald-100 group-hover:text-emerald-600'">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <div class="flex-grow">
                            <div class="font-bold text-slate-900 text-lg">Olahraga Fisik</div>
                            <div class="text-sm text-slate-500 mt-0.5">Jogging, senam, atau workout intensitas tinggi.</div>
                        </div>
                        <div class="shrink-0 hidden sm:block">
                            <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center transition-colors" :class="form.activity_type === 'exercise' ? 'border-emerald-500' : 'border-slate-300'">
                                <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 transition-transform scale-0" :class="form.activity_type === 'exercise' ? 'scale-100' : ''"></div>
                            </div>
                        </div>
                    </label>

                    <label class="group relative flex flex-col sm:flex-row sm:items-center gap-4 p-5 cursor-pointer rounded-2xl transition-all border border-slate-200 bg-white hover:border-emerald-300 hover:shadow-md"
                        :class="form.activity_type === 'relax' ? 'ring-2 ring-emerald-500 bg-emerald-50/20 border-transparent' : ''">
                        <input type="radio" name="activity_type" value="relax" x-model="form.activity_type" class="sr-only">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 transition-colors"
                            :class="form.activity_type === 'relax' ? 'bg-emerald-500 text-white' : 'bg-slate-100 text-slate-500 group-hover:bg-emerald-100 group-hover:text-emerald-600'">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                        </div>
                        <div class="flex-grow">
                            <div class="font-bold text-slate-900 text-lg">Bersantai</div>
                            <div class="text-sm text-slate-500 mt-0.5">Duduk santai, piknik ringan, atau sekadar menghirup udara segar.</div>
                        </div>
                        <div class="shrink-0 hidden sm:block">
                            <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center transition-colors" :class="form.activity_type === 'relax' ? 'border-emerald-500' : 'border-slate-300'">
                                <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 transition-transform scale-0" :class="form.activity_type === 'relax' ? 'scale-100' : ''"></div>
                            </div>
                        </div>
                    </label>

                    <label class="group relative flex flex-col sm:flex-row sm:items-center gap-4 p-5 cursor-pointer rounded-2xl transition-all border border-slate-200 bg-white hover:border-emerald-300 hover:shadow-md"
                        :class="form.activity_type === 'study' ? 'ring-2 ring-emerald-500 bg-emerald-50/20 border-transparent' : ''">
                        <input type="radio" name="activity_type" value="study" x-model="form.activity_type" class="sr-only">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 transition-colors"
                            :class="form.activity_type === 'study' ? 'bg-emerald-500 text-white' : 'bg-slate-100 text-slate-500 group-hover:bg-emerald-100 group-hover:text-emerald-600'">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                        <div class="flex-grow">
                            <div class="font-bold text-slate-900 text-lg">Membaca / Belajar</div>
                            <div class="text-sm text-slate-500 mt-0.5">Aktivitas yang butuh konsentrasi dan lingkungan minim distraksi.</div>
                        </div>
                        <div class="shrink-0 hidden sm:block">
                            <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center transition-colors" :class="form.activity_type === 'study' ? 'border-emerald-500' : 'border-slate-300'">
                                <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 transition-transform scale-0" :class="form.activity_type === 'study' ? 'scale-100' : ''"></div>
                            </div>
                        </div>
                    </label>

                    <label class="group relative flex flex-col sm:flex-row sm:items-center gap-4 p-5 cursor-pointer rounded-2xl transition-all border border-slate-200 bg-white hover:border-emerald-300 hover:shadow-md"
                        :class="form.activity_type === 'outdoor' ? 'ring-2 ring-emerald-500 bg-emerald-50/20 border-transparent' : ''">
                        <input type="radio" name="activity_type" value="outdoor" x-model="form.activity_type" class="sr-only">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 transition-colors"
                            :class="form.activity_type === 'outdoor' ? 'bg-emerald-500 text-white' : 'bg-slate-100 text-slate-500 group-hover:bg-emerald-100 group-hover:text-emerald-600'">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 002 2h1.5a2.5 2.5 0 002.5-2.5V11a2 2 0 012-2h1.065"></path></svg>
                        </div>
                        <div class="flex-grow">
                            <div class="font-bold text-slate-900 text-lg">Jalan-jalan Santai</div>
                            <div class="text-sm text-slate-500 mt-0.5">Menikmati ruang terbuka hijau dengan intensitas ringan.</div>
                        </div>
                        <div class="shrink-0 hidden sm:block">
                            <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center transition-colors" :class="form.activity_type === 'outdoor' ? 'border-emerald-500' : 'border-slate-300'">
                                <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 transition-transform scale-0" :class="form.activity_type === 'outdoor' ? 'scale-100' : ''"></div>
                            </div>
                        </div>
                    </label>

                    <label class="group relative flex flex-col sm:flex-row sm:items-center gap-4 p-5 cursor-pointer rounded-2xl transition-all border border-slate-200 bg-white hover:border-emerald-300 hover:shadow-md"
                        :class="form.activity_type === 'cycling' ? 'ring-2 ring-emerald-500 bg-emerald-50/20 border-transparent' : ''">
                        <input type="radio" name="activity_type" value="cycling" x-model="form.activity_type" class="sr-only">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 transition-colors"
                            :class="form.activity_type === 'cycling' ? 'bg-emerald-500 text-white' : 'bg-slate-100 text-slate-500 group-hover:bg-emerald-100 group-hover:text-emerald-600'">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <div class="flex-grow">
                            <div class="font-bold text-slate-900 text-lg">Bersepeda</div>
                            <div class="text-sm text-slate-500 mt-0.5">Mencari rute aman, trek sepeda, atau taman luas.</div>
                        </div>
                        <div class="shrink-0 hidden sm:block">
                            <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center transition-colors" :class="form.activity_type === 'cycling' ? 'border-emerald-500' : 'border-slate-300'">
                                <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 transition-transform scale-0" :class="form.activity_type === 'cycling' ? 'scale-100' : ''"></div>
                            </div>
                        </div>
                    </label>

                </div>
                <!-- STEP 2: KERAMAIAN (Left Accent Border Cards) -->
                <div x-show="currentStep === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="grid sm:grid-cols-3 gap-4">
                    <label class="group relative p-6 cursor-pointer bg-white transition-all shadow-sm hover:shadow-md border-y border-r border-l-[6px] rounded-xl flex flex-col justify-center"
                           :class="form.preferred_crowd === 'low' ? 'border-l-emerald-500 border-emerald-500/30 bg-emerald-50/30 shadow-emerald-500/10 scale-[1.02]' : 'border-l-slate-300 border-slate-200 hover:border-l-emerald-400'">
                        <input type="radio" name="preferred_crowd" value="low" x-model="form.preferred_crowd" class="sr-only">
                        <svg class="w-8 h-8 mb-4 transition-colors" :class="form.preferred_crowd === 'low' ? 'text-emerald-600' : 'text-slate-400 group-hover:text-emerald-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        <h3 class="font-bold text-slate-900 text-lg mb-1">Tenang</h3>
                        <p class="text-xs text-slate-500">Kepadatan pengunjung rendah, cocok untuk privasi.</p>
                    </label>

                    <label class="group relative p-6 cursor-pointer bg-white transition-all shadow-sm hover:shadow-md border-y border-r border-l-[6px] rounded-xl flex flex-col justify-center"
                           :class="form.preferred_crowd === 'medium' ? 'border-l-emerald-500 border-emerald-500/30 bg-emerald-50/30 shadow-emerald-500/10 scale-[1.02]' : 'border-l-slate-300 border-slate-200 hover:border-l-emerald-400'">
                        <input type="radio" name="preferred_crowd" value="medium" x-model="form.preferred_crowd" class="sr-only">
                        <svg class="w-8 h-8 mb-4 transition-colors" :class="form.preferred_crowd === 'medium' ? 'text-emerald-600' : 'text-slate-400 group-hover:text-emerald-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <h3 class="font-bold text-slate-900 text-lg mb-1">Normal</h3>
                        <p class="text-xs text-slate-500">Cukup ramai tapi tetap nyaman untuk beraktivitas.</p>
                    </label>

                    <label class="group relative p-6 cursor-pointer bg-white transition-all shadow-sm hover:shadow-md border-y border-r border-l-[6px] rounded-xl flex flex-col justify-center"
                           :class="form.preferred_crowd === 'any' ? 'border-l-emerald-500 border-emerald-500/30 bg-emerald-50/30 shadow-emerald-500/10 scale-[1.02]' : 'border-l-slate-300 border-slate-200 hover:border-l-emerald-400'">
                        <input type="radio" name="preferred_crowd" value="any" x-model="form.preferred_crowd" class="sr-only">
                        <svg class="w-8 h-8 mb-4 transition-colors" :class="form.preferred_crowd === 'any' ? 'text-emerald-600' : 'text-slate-400 group-hover:text-emerald-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <h3 class="font-bold text-slate-900 text-lg mb-1">Bebas</h3>
                        <p class="text-xs text-slate-500">Tidak masalah dengan tingkat keramaian mana pun.</p>
                    </label>
                </div>

                <!-- STEP 3: WAKTU (Asymmetrical Grid) -->
                <div x-show="currentStep === 3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <label class="group relative p-5 h-36 flex flex-col justify-between cursor-pointer transition-all bg-white border border-slate-200 shadow-sm hover:shadow-md hover:border-emerald-300 rounded-tl-[2rem] rounded-br-[2rem] rounded-tr-md rounded-bl-md"
                           :class="form.preferred_time === 'morning' ? 'ring-2 ring-emerald-500 bg-emerald-50/30 border-transparent' : ''">
                        <input type="radio" name="preferred_time" value="morning" x-model="form.preferred_time" class="sr-only">
                        <div class="self-end transition-colors" :class="form.preferred_time === 'morning' ? 'text-emerald-500' : 'text-slate-300 group-hover:text-emerald-400'">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <div class="text-left font-bold text-slate-900 text-lg leading-tight">Pagi <br><span class="text-xs font-medium text-slate-500">06:00 - 09:00</span></div>
                    </label>

                    <label class="group relative p-5 h-36 flex flex-col justify-between cursor-pointer transition-all bg-white border border-slate-200 shadow-sm hover:shadow-md hover:border-emerald-300 rounded-tl-[2rem] rounded-br-[2rem] rounded-tr-md rounded-bl-md"
                           :class="form.preferred_time === 'afternoon' ? 'ring-2 ring-emerald-500 bg-emerald-50/30 border-transparent' : ''">
                        <input type="radio" name="preferred_time" value="afternoon" x-model="form.preferred_time" class="sr-only">
                        <div class="self-end transition-colors" :class="form.preferred_time === 'afternoon' ? 'text-emerald-500' : 'text-slate-300 group-hover:text-emerald-400'">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path></svg>
                        </div>
                        <div class="text-left font-bold text-slate-900 text-lg leading-tight">Sore <br><span class="text-xs font-medium text-slate-500">16:00 - 18:00</span></div>
                    </label>

                    <label class="group relative p-5 h-36 flex flex-col justify-between cursor-pointer transition-all bg-white border border-slate-200 shadow-sm hover:shadow-md hover:border-emerald-300 rounded-tl-[2rem] rounded-br-[2rem] rounded-tr-md rounded-bl-md"
                           :class="form.preferred_time === 'evening' ? 'ring-2 ring-emerald-500 bg-emerald-50/30 border-transparent' : ''">
                        <input type="radio" name="preferred_time" value="evening" x-model="form.preferred_time" class="sr-only">
                        <div class="self-end transition-colors" :class="form.preferred_time === 'evening' ? 'text-emerald-500' : 'text-slate-300 group-hover:text-emerald-400'">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                        </div>
                        <div class="text-left font-bold text-slate-900 text-lg leading-tight">Malam <br><span class="text-xs font-medium text-slate-500">18:00 - 21:00</span></div>
                    </label>

                    <label class="group relative p-5 h-36 flex flex-col justify-between cursor-pointer transition-all bg-white border border-slate-200 shadow-sm hover:shadow-md hover:border-emerald-300 rounded-tl-[2rem] rounded-br-[2rem] rounded-tr-md rounded-bl-md"
                           :class="form.preferred_time === 'any' ? 'ring-2 ring-emerald-500 bg-emerald-50/30 border-transparent' : ''">
                        <input type="radio" name="preferred_time" value="any" x-model="form.preferred_time" class="sr-only">
                        <div class="self-end transition-colors" :class="form.preferred_time === 'any' ? 'text-emerald-500' : 'text-slate-300 group-hover:text-emerald-400'">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div class="text-left font-bold text-slate-900 text-lg leading-tight">Kapan<br>Saja</div>
                    </label>
                </div>

                <!-- STEP 4: TRANSPORTASI (Geometric Pills) -->
                <div x-show="currentStep === 4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="flex flex-wrap gap-4">
                        <label class="cursor-pointer">
                            <input type="radio" name="transport_mode" value="bicycle" x-model="form.transport_mode" class="sr-only">
                            <div class="px-6 py-4 border transition-all shadow-sm flex items-center gap-3 rounded-tl-2xl rounded-br-2xl rounded-tr-md rounded-bl-md"
                                 :class="form.transport_mode === 'bicycle' ? 'bg-emerald-600 border-emerald-600 text-white shadow-emerald-500/30' : 'bg-white border-slate-200 text-slate-600 hover:border-emerald-400 hover:shadow-md'">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                <span class="font-semibold text-base">Sepeda Pribadi</span>
                            </div>
                        </label>

                        <label class="cursor-pointer">
                            <input type="radio" name="transport_mode" value="walking" x-model="form.transport_mode" class="sr-only">
                            <div class="px-6 py-4 border transition-all shadow-sm flex items-center gap-3 rounded-tl-2xl rounded-br-2xl rounded-tr-md rounded-bl-md"
                                 :class="form.transport_mode === 'walking' ? 'bg-emerald-600 border-emerald-600 text-white shadow-emerald-500/30' : 'bg-white border-slate-200 text-slate-600 hover:border-emerald-400 hover:shadow-md'">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 4a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 21v-4l5-4v-6L6 9"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 21v-6l-5-4V7l6 3v5"></path></svg>
                                <span class="font-semibold text-base">Jalan Kaki</span>
                            </div>
                        </label>

                        <label class="cursor-pointer">
                            <input type="radio" name="transport_mode" value="public_transport" x-model="form.transport_mode" class="sr-only">
                            <div class="px-6 py-4 border transition-all shadow-sm flex items-center gap-3 rounded-tl-2xl rounded-br-2xl rounded-tr-md rounded-bl-md"
                                 :class="form.transport_mode === 'public_transport' ? 'bg-emerald-600 border-emerald-600 text-white shadow-emerald-500/30' : 'bg-white border-slate-200 text-slate-600 hover:border-emerald-400 hover:shadow-md'">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path></svg>
                                <span class="font-semibold text-base">Transportasi Publik</span>
                            </div>
                        </label>

                        <label class="cursor-pointer">
                            <input type="radio" name="transport_mode" value="any" x-model="form.transport_mode" class="sr-only">
                            <div class="px-6 py-4 border transition-all shadow-sm flex items-center gap-3 rounded-tl-2xl rounded-br-2xl rounded-tr-md rounded-bl-md"
                                 :class="form.transport_mode === 'any' ? 'bg-emerald-600 border-emerald-600 text-white shadow-emerald-500/30' : 'bg-white border-slate-200 text-slate-600 hover:border-emerald-400 hover:shadow-md'">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 13v4c0 .6.4 1 1 1h2"></path><circle cx="7" cy="17" r="2" stroke-width="1.5"></circle><circle cx="17" cy="17" r="2" stroke-width="1.5"></circle></svg>
                                <span class="font-semibold text-base">Kendaraan Pribadi</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Navigation Controls (Asymmetrical Layout) -->
                <div class="flex items-center justify-between mt-12 pt-6 border-t border-slate-100">
                    <div>
                        <button type="button" @click="prevStep()" x-show="currentStep > 1" class="text-slate-400 hover:text-slate-800 text-sm font-semibold transition-colors flex items-center gap-1.5 focus:outline-none py-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Kembali
                        </button>
                    </div>

                    <button type="button" @click="nextStep()" x-show="currentStep < 4" class="px-8 py-3.5 bg-slate-900 hover:bg-emerald-600 text-white text-sm font-bold transition-all shadow-md rounded-tl-2xl rounded-br-2xl flex items-center gap-2 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                        Selanjutnya
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>

                    <button type="submit" x-show="currentStep === 4" class="px-8 py-3.5 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-bold transition-all shadow-lg shadow-emerald-500/30 rounded-tl-2xl rounded-br-2xl flex items-center gap-2 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                        Proses Rekomendasi
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </button>
                </div>
            </form>
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
                        entry.target.classList.remove('opacity-0', 'translate-y-8');
                        entry.target.classList.add('opacity-100', 'translate-y-0');
                        observer.unobserve(entry.target); 
                    }
                });
            }, observerOptions);

            const revealElements = document.querySelectorAll('.reveal-on-scroll');
            revealElements.forEach(el => observer.observe(el));
        });

        document.addEventListener('alpine:init', () => {
            Alpine.data('recommendWizard', () => ({
                currentStep: 1,
                stepTitles: ['Aktivitas Utama', 'Preferensi Suasana', 'Pemilihan Waktu', 'Metode Mobilitas'],
                form: {
                    activity_type: 'exercise',
                    preferred_crowd: 'low',
                    preferred_time: 'afternoon',
                    transport_mode: 'bicycle'
                },
                nextStep() {
                    if (this.currentStep < 4) this.currentStep++;
                },
                prevStep() {
                    if (this.currentStep > 1) this.currentStep--;
                }
            }));
        });
    </script>
</x-layouts.app>