<x-layouts.app title="Rekomendasi untuk Anda — UrbanPulse">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10" x-data="recommendWizard()">

        <!-- Header -->
        <div class="text-center max-w-2xl mx-auto mb-6 sm:mb-10 space-y-2 sm:space-y-3">
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-semibold">
                Rekomendasi Pintar
            </span>
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Rekomendasi Tempat Terbaik untuk Anda</h1>
            <p class="text-slate-600 text-xs sm:text-base">Jawab 4 pertanyaan sederhana di bawah ini untuk menemukan tempat aktivitas luar ruangan yang paling pas.</p>
        </div>

        <!-- Wizard Card -->
        <div class="bg-white border border-slate-200 rounded-3xl p-4 sm:p-8 lg:p-10 shadow-sm space-y-6 sm:space-y-8">
            
            <!-- Simple Step Progress Bar -->
            <div class="flex items-center justify-between pb-4 sm:pb-6 border-b border-slate-100 text-xs sm:text-sm font-semibold">
                <template x-for="stepNum in 4" :key="stepNum">
                    <div class="flex items-center gap-1.5 sm:gap-2" :class="currentStep >= stepNum ? 'text-emerald-700 font-bold' : 'text-slate-400'">
                        <span class="w-7 h-7 sm:w-8 sm:h-8 rounded-full flex items-center justify-center border text-xs sm:text-sm" :class="currentStep >= stepNum ? 'border-emerald-600 bg-emerald-50 text-emerald-700' : 'border-slate-200 bg-slate-50 text-slate-400'" x-text="stepNum"></span>
                        <span class="hidden sm:inline" x-text="stepTitles[stepNum - 1]"></span>
                    </div>
                </template>
            </div>

            <form method="POST" action="{{ route('recommend.process') }}">
                @csrf

                <!-- STEP 1: AKTIVITAS -->
                <div x-show="currentStep === 1" x-transition>
                    <h2 class="text-lg sm:text-xl font-bold text-slate-900 mb-1 sm:mb-2">1. Apa yang ingin Anda lakukan?</h2>
                    <p class="text-slate-600 text-xs sm:text-sm mb-4 sm:mb-6">Pilih jenis kegiatan harian Anda.</p>

                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 sm:gap-4">
                        <label class="cursor-pointer">
                            <input type="radio" name="activity_type" value="exercise" x-model="form.activity_type" class="sr-only">
                            <div class="p-3.5 sm:p-5 rounded-2xl border text-center transition-all min-h-[72px] sm:min-h-[90px] flex flex-col items-center justify-center" :class="form.activity_type === 'exercise' ? 'border-emerald-600 bg-emerald-50 text-emerald-900 font-bold ring-2 ring-emerald-600' : 'border-slate-200 bg-slate-50 text-slate-700 hover:bg-slate-100'">
                                <span class="text-2xl sm:text-3xl mb-0.5">🏃</span>
                                <span class="text-xs sm:text-base">Olahraga</span>
                            </div>
                        </label>

                        <label class="cursor-pointer">
                            <input type="radio" name="activity_type" value="relax" x-model="form.activity_type" class="sr-only">
                            <div class="p-3.5 sm:p-5 rounded-2xl border text-center transition-all min-h-[72px] sm:min-h-[90px] flex flex-col items-center justify-center" :class="form.activity_type === 'relax' ? 'border-emerald-600 bg-emerald-50 text-emerald-900 font-bold ring-2 ring-emerald-600' : 'border-slate-200 bg-slate-50 text-slate-700 hover:bg-slate-100'">
                                <span class="text-2xl sm:text-3xl mb-0.5">🧘</span>
                                <span class="text-xs sm:text-base">Bersantai</span>
                            </div>
                        </label>

                        <label class="cursor-pointer">
                            <input type="radio" name="activity_type" value="study" x-model="form.activity_type" class="sr-only">
                            <div class="p-3.5 sm:p-5 rounded-2xl border text-center transition-all min-h-[72px] sm:min-h-[90px] flex flex-col items-center justify-center" :class="form.activity_type === 'study' ? 'border-emerald-600 bg-emerald-50 text-emerald-900 font-bold ring-2 ring-emerald-600' : 'border-slate-200 bg-slate-50 text-slate-700 hover:bg-slate-100'">
                                <span class="text-2xl sm:text-3xl mb-0.5">📚</span>
                                <span class="text-xs sm:text-base">Belajar</span>
                            </div>
                        </label>

                        <label class="cursor-pointer">
                            <input type="radio" name="activity_type" value="outdoor" x-model="form.activity_type" class="sr-only">
                            <div class="p-3.5 sm:p-5 rounded-2xl border text-center transition-all min-h-[72px] sm:min-h-[90px] flex flex-col items-center justify-center" :class="form.activity_type === 'outdoor' ? 'border-emerald-600 bg-emerald-50 text-emerald-900 font-bold ring-2 ring-emerald-600' : 'border-slate-200 bg-slate-50 text-slate-700 hover:bg-slate-100'">
                                <span class="text-2xl sm:text-3xl mb-0.5">🌳</span>
                                <span class="text-xs sm:text-base">Jalan-jalan</span>
                            </div>
                        </label>

                        <label class="cursor-pointer col-span-2 sm:col-span-1">
                            <input type="radio" name="activity_type" value="cycling" x-model="form.activity_type" class="sr-only">
                            <div class="p-3.5 sm:p-5 rounded-2xl border text-center transition-all min-h-[72px] sm:min-h-[90px] flex flex-col items-center justify-center" :class="form.activity_type === 'cycling' ? 'border-emerald-600 bg-emerald-50 text-emerald-900 font-bold ring-2 ring-emerald-600' : 'border-slate-200 bg-slate-50 text-slate-700 hover:bg-slate-100'">
                                <span class="text-2xl sm:text-3xl mb-0.5">🚲</span>
                                <span class="text-xs sm:text-base">Bersepeda</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- STEP 2: KERAMAIAN -->
                <div x-show="currentStep === 2" x-transition>
                    <h2 class="text-lg sm:text-xl font-bold text-slate-900 mb-1 sm:mb-2">2. Tingkat keramaian seperti apa yang Anda harapkan?</h2>
                    <p class="text-slate-600 text-xs sm:text-sm mb-4 sm:mb-6">Pilih suasana tempat yang Anda inginkan.</p>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 sm:gap-4">
                        <label class="cursor-pointer">
                            <input type="radio" name="preferred_crowd" value="low" x-model="form.preferred_crowd" class="sr-only">
                            <div class="p-4 sm:p-5 rounded-2xl border text-center transition-all min-h-[60px] sm:min-h-[70px] flex flex-col items-center justify-center" :class="form.preferred_crowd === 'low' ? 'border-emerald-600 bg-emerald-50 text-emerald-900 font-bold ring-2 ring-emerald-600' : 'border-slate-200 bg-slate-50 text-slate-700 hover:bg-slate-100'">
                                <span class="text-xs sm:text-base font-bold text-emerald-700 mb-0.5">🍃 Tenang (Sepi)</span>
                                <span class="text-[11px] sm:text-xs text-slate-500">Tidak banyak pengunjung</span>
                            </div>
                        </label>

                        <label class="cursor-pointer">
                            <input type="radio" name="preferred_crowd" value="medium" x-model="form.preferred_crowd" class="sr-only">
                            <div class="p-4 sm:p-5 rounded-2xl border text-center transition-all min-h-[60px] sm:min-h-[70px] flex flex-col items-center justify-center" :class="form.preferred_crowd === 'medium' ? 'border-emerald-600 bg-emerald-50 text-emerald-900 font-bold ring-2 ring-emerald-600' : 'border-slate-200 bg-slate-50 text-slate-700 hover:bg-slate-100'">
                                <span class="text-xs sm:text-base font-bold text-slate-900 mb-0.5">👥 Sedang (Nyaman)</span>
                                <span class="text-[11px] sm:text-xs text-slate-500">Pengunjung normal</span>
                            </div>
                        </label>

                        <label class="cursor-pointer">
                            <input type="radio" name="preferred_crowd" value="any" x-model="form.preferred_crowd" class="sr-only">
                            <div class="p-4 sm:p-5 rounded-2xl border text-center transition-all min-h-[60px] sm:min-h-[70px] flex flex-col items-center justify-center" :class="form.preferred_crowd === 'any' ? 'border-emerald-600 bg-emerald-50 text-emerald-900 font-bold ring-2 ring-emerald-600' : 'border-slate-200 bg-slate-50 text-slate-700 hover:bg-slate-100'">
                                <span class="text-xs sm:text-base font-bold text-slate-700 mb-0.5">✨ Bebas</span>
                                <span class="text-[11px] sm:text-xs text-slate-500">Kategori keramaian apa saja</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- STEP 3: WAKTU -->
                <div x-show="currentStep === 3" x-transition>
                    <h2 class="text-lg sm:text-xl font-bold text-slate-900 mb-1 sm:mb-2">3. Kapan Anda berencana ke sana?</h2>
                    <p class="text-slate-600 text-xs sm:text-sm mb-4 sm:mb-6">Pilih waktu untuk memperhitungkan cuaca.</p>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 sm:gap-4">
                        <label class="cursor-pointer">
                            <input type="radio" name="preferred_time" value="morning" x-model="form.preferred_time" class="sr-only">
                            <div class="p-3 sm:p-4 rounded-2xl border text-center transition-all min-h-[50px] sm:min-h-[60px] flex flex-col justify-center items-center" :class="form.preferred_time === 'morning' ? 'border-emerald-600 bg-emerald-50 text-emerald-900 font-bold ring-2 ring-emerald-600' : 'border-slate-200 bg-slate-50 text-slate-700 hover:bg-slate-100'">
                                <span class="text-xs sm:text-sm font-bold">🌅 Pagi hari</span>
                            </div>
                        </label>

                        <label class="cursor-pointer">
                            <input type="radio" name="preferred_time" value="afternoon" x-model="form.preferred_time" class="sr-only">
                            <div class="p-3 sm:p-4 rounded-2xl border text-center transition-all min-h-[50px] sm:min-h-[60px] flex flex-col justify-center items-center" :class="form.preferred_time === 'afternoon' ? 'border-emerald-600 bg-emerald-50 text-emerald-900 font-bold ring-2 ring-emerald-600' : 'border-slate-200 bg-slate-50 text-slate-700 hover:bg-slate-100'">
                                <span class="text-xs sm:text-sm font-bold">🌤️ Sore hari</span>
                            </div>
                        </label>

                        <label class="cursor-pointer">
                            <input type="radio" name="preferred_time" value="evening" x-model="form.preferred_time" class="sr-only">
                            <div class="p-3 sm:p-4 rounded-2xl border text-center transition-all min-h-[50px] sm:min-h-[60px] flex flex-col justify-center items-center" :class="form.preferred_time === 'evening' ? 'border-emerald-600 bg-emerald-50 text-emerald-900 font-bold ring-2 ring-emerald-600' : 'border-slate-200 bg-slate-50 text-slate-700 hover:bg-slate-100'">
                                <span class="text-xs sm:text-sm font-bold">🌙 Malam hari</span>
                            </div>
                        </label>

                        <label class="cursor-pointer">
                            <input type="radio" name="preferred_time" value="any" x-model="form.preferred_time" class="sr-only">
                            <div class="p-3 sm:p-4 rounded-2xl border text-center transition-all min-h-[50px] sm:min-h-[60px] flex flex-col justify-center items-center" :class="form.preferred_time === 'any' ? 'border-emerald-600 bg-emerald-50 text-emerald-900 font-bold ring-2 ring-emerald-600' : 'border-slate-200 bg-slate-50 text-slate-700 hover:bg-slate-100'">
                                <span class="text-xs sm:text-sm font-bold">⏰ Kapan saja</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- STEP 4: TRANSPORTASI -->
                <div x-show="currentStep === 4" x-transition>
                    <h2 class="text-lg sm:text-xl font-bold text-slate-900 mb-1 sm:mb-2">4. Bagaimana Anda berangkat ke sana?</h2>
                    <p class="text-slate-600 text-xs sm:text-sm mb-4 sm:mb-6">Pilih mode perjalanan Anda.</p>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 sm:gap-4">
                        <label class="cursor-pointer">
                            <input type="radio" name="transport_mode" value="bicycle" x-model="form.transport_mode" class="sr-only">
                            <div class="p-3 sm:p-4 rounded-2xl border text-center transition-all min-h-[50px] sm:min-h-[60px] flex flex-col justify-center items-center" :class="form.transport_mode === 'bicycle' ? 'border-emerald-600 bg-emerald-50 text-emerald-900 font-bold ring-2 ring-emerald-600' : 'border-slate-200 bg-slate-50 text-slate-700 hover:bg-slate-100'">
                                <span class="text-xs sm:text-sm font-bold">🚲 Sepeda</span>
                            </div>
                        </label>

                        <label class="cursor-pointer">
                            <input type="radio" name="transport_mode" value="walking" x-model="form.transport_mode" class="sr-only">
                            <div class="p-3 sm:p-4 rounded-2xl border text-center transition-all min-h-[50px] sm:min-h-[60px] flex flex-col justify-center items-center" :class="form.transport_mode === 'walking' ? 'border-emerald-600 bg-emerald-50 text-emerald-900 font-bold ring-2 ring-emerald-600' : 'border-slate-200 bg-slate-50 text-slate-700 hover:bg-slate-100'">
                                <span class="text-xs sm:text-sm font-bold">🚶 Jalan kaki</span>
                            </div>
                        </label>

                        <label class="cursor-pointer">
                            <input type="radio" name="transport_mode" value="public_transport" x-model="form.transport_mode" class="sr-only">
                            <div class="p-3 sm:p-4 rounded-2xl border text-center transition-all min-h-[50px] sm:min-h-[60px] flex flex-col justify-center items-center" :class="form.transport_mode === 'public_transport' ? 'border-emerald-600 bg-emerald-50 text-emerald-900 font-bold ring-2 ring-emerald-600' : 'border-slate-200 bg-slate-50 text-slate-700 hover:bg-slate-100'">
                                <span class="text-xs sm:text-sm font-bold">🚌 Bus / KRL</span>
                            </div>
                        </label>

                        <label class="cursor-pointer">
                            <input type="radio" name="transport_mode" value="any" x-model="form.transport_mode" class="sr-only">
                            <div class="p-3 sm:p-4 rounded-2xl border text-center transition-all min-h-[50px] sm:min-h-[60px] flex flex-col justify-center items-center" :class="form.transport_mode === 'any' ? 'border-emerald-600 bg-emerald-50 text-emerald-900 font-bold ring-2 ring-emerald-600' : 'border-slate-200 bg-slate-50 text-slate-700 hover:bg-slate-100'">
                                <span class="text-xs sm:text-sm font-bold">🚗 Kendaraan</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Navigation Controls -->
                <div class="flex items-center justify-between mt-6 sm:mt-10 pt-4 sm:pt-6 border-t border-slate-100">
                    <button type="button" @click="prevStep()" x-show="currentStep > 1" class="px-4 sm:px-5 py-2.5 sm:py-3 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-800 text-sm sm:text-base font-semibold transition-colors min-h-[44px]">
                        &larr; Kembali
                    </button>
                    <div x-show="currentStep === 1"></div>

                    <button type="button" @click="nextStep()" x-show="currentStep < 4" class="px-5 sm:px-6 py-2.5 sm:py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm sm:text-base font-bold transition-all shadow-md shadow-emerald-600/20 min-h-[44px]">
                        Lanjut &rarr;
                    </button>

                    <button type="submit" x-show="currentStep === 4" class="px-6 sm:px-8 py-2.5 sm:py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm sm:text-base font-bold transition-all shadow-lg shadow-emerald-600/25 flex items-center gap-2 min-h-[48px]">
                        <span>Lihat Rekomendasi</span> &rarr;
                    </button>
                </div>
            </form>
        </div>

    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('recommendWizard', () => ({
                currentStep: 1,
                stepTitles: ['Aktivitas', 'Keramaian', 'Waktu', 'Transportasi'],
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
