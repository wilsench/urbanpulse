<x-layouts.app title="Tanya UrbanPulse — Asisten Kota Berkelanjutan">
    <style>
        @keyframes dotPulse {
            0%, 100% { transform: translateY(0); opacity: 0.4; }
            50% { transform: translateY(-4px); opacity: 1; }
        }
        .animate-dot-1 { animation: dotPulse 1.2s infinite 0s; }
        .animate-dot-2 { animation: dotPulse 1.2s infinite 0.2s; }
        .animate-dot-3 { animation: dotPulse 1.2s infinite 0.4s; }
        .glow-avatar { box-shadow: 0 0 15px rgba(16, 185, 129, 0.4); }
    </style>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10 space-y-6" x-data="aiAssistant()">

        <!-- Header Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-200/80">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider">AI ASSISTANT REAL-TIME</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Tanya UrbanPulse ({{ $activeCity->name ?? 'Kota Anda' }})</h1>
                <p class="text-slate-600 text-xs sm:text-base mt-1">Ajukan pertanyaan tentang rekomendasi tempat, prakiraan cuaca BMKG, hingga indeks kualitas udara.</p>
            </div>
            
            <div class="flex items-center gap-3 self-start sm:self-auto">
                <button type="button" @click="clearChat()" x-show="messages.length > 0" x-transition class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    <span>Bersihkan Obrolan</span>
                </button>
                <div class="text-xs font-bold text-emerald-800 bg-emerald-50 px-3 py-1.5 rounded-xl border border-emerald-200/80 flex items-center gap-1.5 shrink-0">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <span>Data Resmi BMKG & AQI</span>
                </div>
            </div>
        </div>

        <!-- Chat Container Card -->
        <div class="bg-white border border-slate-200/80 rounded-3xl overflow-hidden shadow-lg flex flex-col h-[520px] sm:h-[620px] relative">
            
            <!-- Messages Stream Area -->
            <div class="flex-grow p-4 sm:p-6 overflow-y-auto space-y-6 scroll-smooth" id="chat-messages">

                <!-- Welcome Card -->
                <div class="flex gap-3 sm:gap-4 transition-all duration-300" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-2">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-2xl bg-emerald-600 text-white font-bold text-lg flex items-center justify-center shrink-0 shadow-md shadow-emerald-600/20 glow-avatar">
                        🌱
                    </div>
                    <div class="space-y-3 max-w-2xl">
                        <div class="p-4 sm:p-5 rounded-3xl rounded-tl-sm bg-slate-50 border border-slate-200/80 text-xs sm:text-sm text-slate-800 leading-relaxed space-y-2.5 shadow-xs">
                            <p class="font-bold text-slate-900 text-sm sm:text-base">Halo! Ada yang bisa saya bantu untuk {{ $activeCity->name ?? 'kota Anda' }} hari ini? 🌿</p>
                            <p class="text-slate-600 leading-relaxed">Saya siap menjawab pertanyaan Anda mengenai pilihan tempat olahraga terbuka, prakiraan cuaca, kondisi kualitas udara real-time, hingga tips mobilitas ramah lingkungan.</p>
                            
                            <div class="pt-2 border-t border-slate-200/60">
                                <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">💡 Coba Klik Rekomendasi Pertanyaan Ini:</div>
                                <div class="flex flex-wrap gap-2">
                                    <button type="button" @click="sendQuery('Rekomendasikan tempat jogging terbaik di {{ $activeCity->name ?? 'kota ini' }} sore ini')" class="text-xs text-emerald-800 bg-white hover:bg-emerald-50 p-2.5 rounded-xl border border-emerald-200/80 transition-all hover:scale-[1.02] text-left shadow-xs flex items-center gap-1.5">
                                        <span>🏃</span> "Tempat jogging terbaik sore ini"
                                    </button>
                                    <button type="button" @click="sendQuery('Berapa emisi CO2 yang saya hemat jika bersepeda sejauh 10 km?')" class="text-xs text-emerald-800 bg-white hover:bg-emerald-50 p-2.5 rounded-xl border border-emerald-200/80 transition-all hover:scale-[1.02] text-left shadow-xs flex items-center gap-1.5">
                                        <span>🚲</span> "Emisi CO2 dihemat jika bersepeda 10 km"
                                    </button>
                                    <button type="button" @click="sendQuery('Bagaimana kualitas udara dan cuaca di {{ $activeCity->name ?? 'kota ini' }} hari ini?')" class="text-xs text-emerald-800 bg-white hover:bg-emerald-50 p-2.5 rounded-xl border border-emerald-200/80 transition-all hover:scale-[1.02] text-left shadow-xs flex items-center gap-1.5">
                                        <span>🍃</span> "Kualitas udara & cuaca hari ini"
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="text-[11px] text-slate-400 font-medium px-1 flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            <span>UrbanPulse AI Assistant &bull; Berbasis Data Resmi BMKG & Air Quality</span>
                        </div>
                    </div>
                </div>

                <!-- Dynamic Chat History -->
                <template x-for="(msg, index) in messages" :key="index">
                    <div class="space-y-4 transition-all duration-300">
                        
                        <!-- User Message (Right Side) -->
                        <div class="flex justify-end gap-3" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-3 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
                            <div class="p-4 sm:p-4.5 rounded-3xl rounded-tr-sm bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-medium text-xs sm:text-sm max-w-xl shadow-md shadow-emerald-600/15 leading-relaxed">
                                <p x-text="msg.prompt" class="whitespace-pre-line"></p>
                            </div>
                        </div>

                        <!-- AI Response Message (Left Side) -->
                        <div class="flex gap-3 sm:gap-4" x-show="msg.response" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-3 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
                                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-2xl bg-emerald-600 text-white font-bold text-lg flex items-center justify-center shrink-0 shadow-md shadow-emerald-600/20 glow-avatar">
                                    🌱
                                </div>
                                <div class="space-y-2 max-w-2xl w-full">
                                    <div class="p-4 sm:p-5 rounded-3xl rounded-tl-sm bg-slate-50 border border-slate-200/80 text-xs sm:text-sm text-slate-800 leading-relaxed whitespace-pre-line shadow-xs relative group" x-text="msg.response">
                                    </div>
                                    <div class="flex items-center justify-between text-[11px] text-slate-500 font-medium px-1">
                                        <div class="flex items-center gap-2">
                                            <span class="text-emerald-700 font-bold" x-text="msg.source || 'Data Terverifikasi'"></span>
                                            <span>&bull;</span>
                                            <span x-text="msg.timestamp" class="font-mono text-[10px]"></span>
                                        </div>

                                        <!-- Copy Button -->
                                        <button type="button" @click="copyText(msg.response, index)" class="text-slate-400 hover:text-emerald-700 text-[11px] font-semibold flex items-center gap-1 transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                            <span x-text="msg.copied ? 'Tersalin!' : 'Salin'"></span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                    </div>
                </template>

                <!-- Animated Loading Typing State -->
                <div x-show="loading" x-transition:enter="transition ease-out duration-200" x-transition:leave="transition ease-in duration-150" class="flex gap-3 sm:gap-4">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-2xl bg-emerald-600 text-white font-bold text-lg flex items-center justify-center shrink-0 shadow-md shadow-emerald-600/20 animate-pulse glow-avatar">
                        🌱
                    </div>
                    <div class="p-4 rounded-2xl rounded-tl-sm bg-slate-50 border border-slate-200/80 text-xs sm:text-sm text-emerald-800 font-medium flex items-center gap-3 shadow-xs">
                        <div class="flex items-center gap-1">
                            <span class="w-2 h-2 rounded-full bg-emerald-600 animate-dot-1"></span>
                            <span class="w-2 h-2 rounded-full bg-emerald-600 animate-dot-2"></span>
                            <span class="w-2 h-2 rounded-full bg-emerald-600 animate-dot-3"></span>
                        </div>
                        <span>UrbanPulse AI sedang menyusun rekomendasi berbasis data...</span>
                    </div>
                </div>

            </div>

            <!-- Input Box Form Container -->
            <div class="p-3 sm:p-4 bg-slate-50/90 border-t border-slate-200/80 backdrop-blur-xs">
                <form @submit.prevent="sendQuery()" class="flex items-center gap-2">
                    <div class="relative flex-grow">
                        <input type="text" 
                               x-model="inputPrompt" 
                               @keydown.enter.prevent="sendQuery()" 
                               placeholder="Ketik pertanyaan Anda tentang cuaca, tempat, atau emisi..." 
                               class="w-full px-4 py-3 sm:py-3.5 pr-10 rounded-2xl bg-white border border-slate-300 text-slate-900 placeholder-slate-400 text-xs sm:text-sm focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 min-h-[46px] sm:min-h-[48px] transition-all shadow-xs" 
                               :disabled="loading">
                    </div>
                    <button type="submit" 
                            class="px-5 sm:px-6 py-3 sm:py-3.5 rounded-2xl bg-emerald-600 hover:bg-emerald-700 disabled:bg-slate-300 text-white font-bold text-xs sm:text-sm transition-all shadow-md shadow-emerald-600/20 flex items-center gap-2 min-h-[46px] sm:min-h-[48px] shrink-0 cursor-pointer disabled:cursor-not-allowed" 
                            :disabled="loading || !inputPrompt.trim()">
                        <span class="hidden sm:inline">Kirim</span>
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" :class="{ 'animate-spin': loading }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </button>
                </form>
            </div>

        </div>

    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('aiAssistant', () => ({
                inputPrompt: "{{ $initialQuery ?? '' }}",
                messages: [],
                loading: false,

                init() {
                    if (this.inputPrompt.trim() !== '') {
                        this.sendQuery();
                    }
                },

                clearChat() {
                    confirmAction({
                        title: 'Bersihkan Obrolan',
                        message: 'Apakah Anda yakin ingin membersihkan riwayat obrolan ini?',
                        confirmText: 'Ya, Bersihkan',
                        variant: 'warning',
                        onConfirm: () => {
                            this.messages = [];
                        }
                    });
                },

                copyText(text, index) {
                    navigator.clipboard.writeText(text).then(() => {
                        this.messages[index].copied = true;
                        setTimeout(() => {
                            this.messages[index].copied = false;
                        }, 2000);
                    }).catch(err => {
                        console.error('Failed to copy', err);
                    });
                },

                async sendQuery(customPrompt = null) {
                    const promptText = (customPrompt || this.inputPrompt).trim();
                    if (!promptText || this.loading) return;

                    const targetIndex = this.messages.length;
                    this.messages.push({
                        prompt: promptText,
                        response: null,
                        source: null,
                        timestamp: null,
                        copied: false
                    });

                    this.inputPrompt = '';
                    this.loading = true;

                    this.$nextTick(() => {
                        this.scrollToBottom();
                    });

                    try {
                        const response = await fetch("{{ route('assistant.query') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ prompt: promptText })
                        });

                        if (response.ok) {
                            const data = await response.json();
                            this.messages[targetIndex].response = data.response;
                            this.messages[targetIndex].source = data.source;
                            this.messages[targetIndex].timestamp = data.timestamp;
                        } else {
                            this.messages[targetIndex].response = "Maaf, terjadi kesalahan saat menghubungi asisten AI. Silakan coba lagi.";
                            this.messages[targetIndex].source = "Sistem Error";
                            this.messages[targetIndex].timestamp = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                        }
                    } catch (e) {
                        console.error('Error fetching AI response', e);
                        this.messages[targetIndex].response = "Maaf, koneksi terputus. Silakan periksa jaringan Anda.";
                        this.messages[targetIndex].source = "Koneksi Error";
                        this.messages[targetIndex].timestamp = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                    } finally {
                        this.loading = false;
                        this.$nextTick(() => {
                            this.scrollToBottom();
                        });
                    }
                },

                scrollToBottom() {
                    const container = document.getElementById('chat-messages');
                    if (container) {
                        container.scrollTop = container.scrollHeight;
                    }
                }
            }));
        });
    </script>
</x-layouts.app>
