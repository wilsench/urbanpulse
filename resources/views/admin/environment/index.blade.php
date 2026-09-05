<x-layouts.admin title="Data Lingkungan — UrbanPulse">
    <div class="space-y-6" x-data="environmentAdmin()">
        
        <!-- Header Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-slate-200/80">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 uppercase tracking-wider">INFORMASI LINGKUNGAN</span>
                    <span class="text-xs text-slate-500 font-medium">&bull; Pembaruan Cuaca & Kualitas Udara Kota</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Data Cuaca & Kualitas Udara</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Informasi suhu, perkiraan cuaca, serta kebersihan udara di berbagai kota.</p>
            </div>
            
            <button type="button" 
                    @click="triggerSync()" 
                    :disabled="syncing"
                    :class="syncing ? 'bg-slate-700 cursor-not-allowed' : 'bg-emerald-600 hover:bg-emerald-700 cursor-pointer'"
                    class="w-full sm:w-auto px-4 py-2.5 rounded-xl text-white font-bold text-xs flex items-center justify-center gap-2 transition-all shadow-xs min-h-[40px] shrink-0">
                <svg class="w-4 h-4 text-white" :class="{ 'animate-spin': syncing }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                <span x-text="syncing ? 'Sedang Memperbarui Data...' : 'Perbarui Data Seluruh Kota'"></span>
            </button>
        </div>

        <!-- Success Toast Alert Banner -->
        <template x-if="syncSuccessMsg">
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-300/80 text-emerald-950 flex items-center justify-between shadow-sm" x-transition>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <span class="text-xs sm:text-sm font-bold" x-text="syncSuccessMsg"></span>
                </div>
                <button type="button" @click="syncSuccessMsg = null" class="p-1 rounded-lg text-emerald-700 hover:bg-emerald-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </template>

        <!-- Active Background Sync Realtime Banner -->
        <div x-show="syncing" x-transition class="p-4 rounded-2xl bg-slate-900 text-white space-y-2 border border-slate-800 shadow-xl" x-cloak>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <span class="relative flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                    </span>
                    <span class="font-bold text-sm text-white">Pembaruan Data Sedang Berlangsung...</span>
                </div>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-mono bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 uppercase font-bold tracking-wider animate-pulse">MEMPERBARUI</span>
            </div>
            <div class="text-xs text-slate-300 flex items-center gap-2 pt-1 font-mono leading-relaxed">
                <svg class="w-4 h-4 text-emerald-400 animate-spin shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                <span x-text="syncStepText"></span>
            </div>
        </div>

        <!-- Table Container with Overlay Loading State -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-4 sm:p-6 shadow-xs space-y-4 relative overflow-hidden">
            
            <!-- Backdrop Loading Overlay -->
            <div x-show="syncing" class="absolute inset-0 bg-white/80 backdrop-blur-xs z-30 flex flex-col items-center justify-center space-y-3" x-cloak>
                <div class="inline-flex items-center gap-3 px-5 py-3 rounded-2xl bg-slate-900 text-white text-xs sm:text-sm font-bold shadow-2xl border border-slate-800">
                    <svg class="w-5 h-5 text-emerald-400 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    <span>Sedang Memperbarui Informasi Cuaca & Udara Seluruh Kota...</span>
                </div>
                <p class="text-xs text-slate-500 font-medium">Proses berjalan dengan lancar, Anda tetap dapat melihat halaman ini.</p>
            </div>

            <div class="flex items-center justify-between">
                <h2 class="text-base font-bold text-slate-900">Catatan Kondisi Udara & Cuaca</h2>
                <span class="text-xs text-slate-400 font-mono">Total Catatan: {{ $environmentalData->total() }} Data</span>
            </div>

            <!-- Desktop View Table -->
            <div class="hidden md:block overflow-x-auto -mx-2 px-2">
                <table class="w-full text-left border-collapse text-xs sm:text-sm min-w-[600px]">
                    <thead>
                        <tr class="border-b border-slate-200/80 text-slate-500 font-semibold uppercase text-[11px] tracking-wider">
                            <th class="pb-3">KOTA</th>
                            <th class="pb-3">SUHU</th>
                            <th class="pb-3">PERKIRAAN CUACA</th>
                            <th class="pb-3">PELUANG HUJAN</th>
                            <th class="pb-3">INDEKS UDARA (AQI)</th>
                            <th class="pb-3">KONDISI UDARA</th>
                            <th class="pb-3">WAKTU PEMBARUAN</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-800">
                        @forelse($environmentalData as $env)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3 font-bold text-slate-900">{{ $env->city }}</td>
                                <td class="py-3 font-bold text-slate-900 font-mono">{{ $env->temperature }}°C</td>
                                <td class="py-3 text-slate-600">{{ $env->weather_description }}</td>
                                <td class="py-3 font-bold text-emerald-700 font-mono">{{ $env->rain_probability }}%</td>
                                <td class="py-3 font-bold text-slate-900 font-mono">{{ $env->air_quality_index }}</td>
                                <td class="py-3"><span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-800 border border-emerald-200/80 text-[11px] font-semibold">{{ $env->air_quality_status }}</span></td>
                                <td class="py-3 text-slate-400 font-mono text-xs">{{ $env->recorded_at->format('d M Y, H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-slate-400 italic">Belum ada catatan data cuaca dan udara yang tersimpan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile View Responsive Cards -->
            <div class="md:hidden space-y-3">
                @forelse($environmentalData as $env)
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 space-y-2.5">
                        <div class="flex items-center justify-between pb-2 border-b border-slate-200/80">
                            <span class="font-bold text-slate-900 text-sm">{{ $env->city }}</span>
                            <span class="text-[11px] text-slate-400 font-mono">{{ $env->recorded_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <div class="p-2.5 bg-white rounded-lg border border-slate-200/80">
                                <span class="text-slate-500 block text-[10px] uppercase font-semibold">Suhu & Cuaca</span>
                                <span class="font-bold text-slate-900 text-sm font-mono">{{ $env->temperature }}°C</span>
                                <span class="text-slate-500 block text-[11px] truncate">{{ $env->weather_description }}</span>
                            </div>
                            <div class="p-2.5 bg-white rounded-lg border border-slate-200/80">
                                <span class="text-slate-500 block text-[10px] uppercase font-semibold">Peluang Hujan</span>
                                <span class="font-bold text-emerald-700 text-sm font-mono">{{ $env->rain_probability }}%</span>
                            </div>
                            <div class="p-2.5 bg-white rounded-lg border border-slate-200/80 col-span-2 flex items-center justify-between">
                                <div>
                                    <span class="text-slate-500 block text-[10px] uppercase font-semibold">Indeks Kebersihan Udara</span>
                                    <span class="font-bold text-slate-900 text-sm font-mono">{{ $env->air_quality_index }}</span>
                                </div>
                                <span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 text-xs font-semibold">
                                    {{ $env->air_quality_status }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-slate-400 italic">Belum ada data tersimpan.</div>
                @endforelse
            </div>

            <div class="pt-4 border-t border-slate-100">
                {{ $environmentalData->links() }}
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('environmentAdmin', () => ({
                syncing: false,
                syncStepText: '',
                syncSuccessMsg: null,
                syncErrorMsg: null,

                triggerSync() {
                    confirmAction({
                        title: 'Perbarui Data Cuaca & Udara',
                        message: 'Apakah Anda ingin memperbarui data cuaca dan kualitas udara untuk seluruh kota sekarang?',
                        confirmText: 'Ya, Perbarui Sekarang',
                        variant: 'emerald',
                        onConfirm: () => {
                            this.startBackgroundSync();
                        }
                    });
                },

                async startBackgroundSync() {
                    if (this.syncing) return;
                    this.syncing = true;
                    this.syncSuccessMsg = null;
                    this.syncErrorMsg = null;
                    this.syncStepText = '[1/3] Menghubungi layanan informasi cuaca dan kualitas udara...';

                    const t1 = setTimeout(() => {
                        if (this.syncing) this.syncStepText = '[2/3] Mengumpulkan informasi cuaca dan kebersihan udara di seluruh kota...';
                    }, 1500);

                    const t2 = setTimeout(() => {
                        if (this.syncing) this.syncStepText = '[3/3] Menyimpan informasi terbaru untuk Anda...';
                    }, 3500);

                    try {
                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                        const res = await fetch("{{ route('admin.environment.sync') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            }
                        });

                        clearTimeout(t1);
                        clearTimeout(t2);

                        if (res.ok) {
                            const data = await res.json();
                            this.syncSuccessMsg = data.message || 'Pembaruan data cuaca dan kualitas udara selesai!';
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } else {
                            const data = await res.json();
                            alert(data.message || 'Gagal memperbarui data cuaca kota.');
                        }
                    } catch (e) {
                        console.error("Environment sync error", e);
                        alert('Koneksi terputus sejenak. Silakan coba beberapa saat lagi ya.');
                    } finally {
                        this.syncing = false;
                    }
                }
            }));
        });
    </script>
</x-layouts.admin>
