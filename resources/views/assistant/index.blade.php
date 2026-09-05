<x-layouts.app title="Tanya UrbanPulse — Asisten Kota Berkelanjutan">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-6" x-data="aiAssistant()">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-200">
            <div>
                <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider block mb-1">ASISTEN PINTAR</span>
                <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Tanya UrbanPulse ({{ $activeCity->name ?? 'Kota Anda' }})</h1>
                <p class="text-slate-600 text-base mt-1">Ajukan pertanyaan seputar rekomendasi tempat, cuaca, atau emisi CO2 di {{ $activeCity->name ?? 'kota Anda' }}.</p>
            </div>
            <div class="text-xs font-bold text-emerald-800 bg-emerald-100 px-3.5 py-2 rounded-xl border border-emerald-200 self-start sm:self-auto">
                🌱 Data Terverifikasi Aktif
            </div>
        </div>

        <!-- Chat Container Card -->
        <div class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-sm flex flex-col h-[480px] sm:h-[580px]">
            
            <!-- Messages Stream Area -->
            <div class="flex-grow p-6 overflow-y-auto space-y-6" id="chat-messages">

                <!-- Welcome Message -->
                <div class="flex gap-4">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-600 text-white font-bold text-lg flex items-center justify-center flex-shrink-0 shadow-md shadow-emerald-600/20">
                        🌱
                    </div>
                    <div class="space-y-3 max-w-2xl">
                        <div class="p-5 rounded-3xl bg-slate-50 border border-slate-200 text-base text-slate-800 leading-relaxed space-y-2">
                            <p class="font-bold text-slate-900">Halo! Ada yang bisa saya bantu di {{ $activeCity->name ?? 'kota Anda' }} hari ini? 🌿</p>
                            <p class="text-slate-600">Saya siap menjawab pertanyaan Anda mengenai pilihan tempat olahraga, kondisi kualitas udara, hingga tips mobilitas ramah lingkungan di {{ $activeCity->name ?? 'kota ini' }}.</p>
                            
                            <div class="pt-2">
                                <div class="text-xs font-bold text-slate-500 uppercase mb-2">Contoh Pertanyaan yang Sering Diajukan:</div>
                                <div class="flex flex-wrap gap-2">
                                    <button type="button" @click="inputPrompt = 'Rekomendasikan tempat jogging terbaik di {{ $activeCity->name ?? 'kota ini' }} sore ini'; sendQuery()" class="text-xs text-emerald-800 bg-emerald-50 hover:bg-emerald-100 p-2 rounded-xl border border-emerald-200 transition-colors text-left">
                                        💡 "Rekomendasikan tempat jogging terbaik sore ini"
                                    </button>
                                    <button type="button" @click="inputPrompt = 'Berapa emisi CO2 yang saya hemat jika bersepeda sejauh 10 km?'; sendQuery()" class="text-xs text-emerald-800 bg-emerald-50 hover:bg-emerald-100 p-2 rounded-xl border border-emerald-200 transition-colors text-left">
                                        💡 "Berapa emisi CO2 yang dihemat jika bersepeda 10 km?"
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="text-xs text-slate-400 font-medium">UrbanPulse Assistant &bull; Berbasis Data Resmi BMKG & Air Quality</div>
                    </div>
                </div>

                <!-- Dynamic Chat History -->
                <template x-for="(msg, i) in messages" :key="i">
                    <div class="space-y-6">
                        <!-- User Message -->
                        <div class="flex justify-end gap-3">
                            <div class="p-5 rounded-3xl bg-emerald-600 text-white font-medium text-base max-w-xl shadow-sm">
                                <p x-text="msg.prompt"></p>
                            </div>
                        </div>

                        <!-- AI Response -->
                        <div class="flex gap-4">
                            <div class="w-10 h-10 rounded-2xl bg-emerald-600 text-white font-bold text-lg flex items-center justify-center flex-shrink-0 shadow-md shadow-emerald-600/20">
                                🌱
                            </div>
                            <div class="space-y-2 max-w-2xl">
                                <div class="p-5 rounded-3xl bg-slate-50 border border-slate-200 text-base text-slate-800 leading-relaxed whitespace-pre-line" x-text="msg.response"></div>
                                <div class="flex items-center gap-2 text-xs text-slate-500 font-medium">
                                    <span class="text-emerald-700 font-bold" x-text="msg.source"></span>
                                    <span>&bull;</span>
                                    <span x-text="msg.timestamp"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- Loading State Indicator -->
                <div x-show="loading" class="flex gap-4">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-600 text-white font-bold text-lg flex items-center justify-center flex-shrink-0 animate-pulse">
                        🌱
                    </div>
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-sm text-emerald-800 font-semibold flex items-center gap-3">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-600 animate-ping"></span>
                        <span>UrbanPulse sedang menyiapkan penjelasan berbasis data...</span>
                    </div>
                </div>

            </div>

            <!-- Input Box Form (Min 44px touch targets) -->
            <div class="p-4 bg-slate-50 border-t border-slate-200">
                <form @submit.prevent="sendQuery()" class="flex gap-3">
                    <input type="text" x-model="inputPrompt" placeholder="Ketik pertanyaan Anda di sini..." class="flex-grow px-5 py-3.5 rounded-2xl bg-white border border-slate-300 text-slate-900 placeholder-slate-400 text-base focus:outline-none focus:border-emerald-600 min-h-[48px]" :disabled="loading">
                    <button type="submit" class="px-7 py-3.5 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-base transition-all shadow-md shadow-emerald-600/20 flex items-center gap-2 min-h-[48px]" :disabled="loading || !inputPrompt.trim()">
                        <span>Kirim</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
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

                async sendQuery() {
                    const promptText = this.inputPrompt.trim();
                    if (!promptText || this.loading) return;

                    this.inputPrompt = '';
                    this.loading = true;

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
                            this.messages.push(data);
                            this.$nextTick(() => {
                                const container = document.getElementById('chat-messages');
                                container.scrollTop = container.scrollHeight;
                            });
                        }
                    } catch (e) {
                        console.error('Error fetching AI response', e);
                    } finally {
                        this.loading = false;
                    }
                }
            }));
        });
    </script>
</x-layouts.app>
