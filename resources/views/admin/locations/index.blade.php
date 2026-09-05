<x-layouts.admin title="Kelola Lokasi & Sinkronisasi — Admin UrbanPulse">
    <div class="space-y-6" x-data="locationSyncAdmin()">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-slate-200/80">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 uppercase tracking-wider">CMS SYSTEM</span>
                    <span class="text-xs text-slate-500 font-medium">&bull; Manajemen & Discovery Engine</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Manajemen & Sinkronisasi Lokasi</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Pencarian & sinkronisasi lokasi publik otomatis berbasis data terbuka OpenStreetMap (Overpass API).</p>
            </div>
            
            <div class="flex flex-wrap items-center gap-2.5">
                <!-- Sync Trigger Modal Button -->
                <button type="button" @click="syncModalOpen = true" class="px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs transition-all shadow-xs flex items-center gap-1.5 min-h-[40px] cursor-pointer">
                    <svg class="w-4 h-4 text-emerald-400" :class="{ 'animate-spin': syncing }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    <span>Sinkronkan Data Lokasi (API)</span>
                </button>

                <!-- Add Location Button -->
                <a href="{{ route('admin.locations.create') }}" class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition-all shadow-md shadow-emerald-600/20 min-h-[40px] flex items-center gap-1.5 shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>Tambah Lokasi Manual</span>
                </a>
            </div>
        </div>

        <!-- Live Status Box (If Running / Recent Log) -->
        <div x-show="latestLog" x-transition class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs space-y-3">
            <div class="flex items-center justify-between">
                <div class="font-bold text-slate-900 text-sm flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Status Log Sinkronisasi Terakhir</span>
                    <span class="px-2 py-0.5 rounded text-[10px] uppercase font-mono font-bold" :class="{ 'bg-amber-100 text-amber-800': latestLog?.status === 'running', 'bg-emerald-100 text-emerald-800': latestLog?.status === 'success', 'bg-slate-200 text-slate-700': latestLog?.status === 'partial', 'bg-rose-100 text-rose-800': latestLog?.status === 'failed' }" x-text="latestLog?.status"></span>
                </div>
                <span class="text-[11px] text-slate-500 font-mono" x-text="'Diperbarui: ' + lastUpdatedTime"></span>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-5 gap-2 text-center text-xs font-mono">
                <div class="p-2 bg-slate-50 rounded-xl border border-slate-200/80">
                    <div class="text-slate-500 text-[10px] font-sans">Ditemukan</div>
                    <div class="text-base font-bold text-slate-900" x-text="latestLog?.discovered_count ?? 0"></div>
                </div>
                <div class="p-2 bg-slate-50 rounded-xl border border-slate-200/80">
                    <div class="text-slate-500 text-[10px] font-sans">Baru Ditambah</div>
                    <div class="text-base font-bold text-emerald-700" x-text="'+' + (latestLog?.created_count ?? 0)"></div>
                </div>
                <div class="p-2 bg-slate-50 rounded-xl border border-slate-200/80">
                    <div class="text-slate-500 text-[10px] font-sans">Diperbarui</div>
                    <div class="text-base font-bold text-blue-700" x-text="latestLog?.updated_count ?? 0"></div>
                </div>
                <div class="p-2 bg-slate-50 rounded-xl border border-slate-200/80">
                    <div class="text-slate-500 text-[10px] font-sans">Duplikat</div>
                    <div class="text-base font-bold text-slate-700" x-text="latestLog?.duplicate_count ?? 0"></div>
                </div>
                <div class="p-2 bg-slate-50 rounded-xl border border-slate-200/80">
                    <div class="text-slate-500 text-[10px] font-sans">Dilewati</div>
                    <div class="text-base font-bold text-slate-500" x-text="latestLog?.skipped_count ?? 0"></div>
                </div>
            </div>

            <template x-if="latestLog?.error_message">
                <div class="p-3 rounded-xl bg-rose-50 border border-rose-200 text-xs text-rose-900" x-text="latestLog.error_message"></div>
            </template>
        </div>

        <!-- Filter & Search Control Bar for Locations Table -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
            <form method="GET" action="{{ route('admin.locations.index') }}" class="flex flex-col sm:flex-row gap-3">
                
                <!-- City Filter Select -->
                <div class="w-full sm:w-64">
                    <select name="city_id" onchange="this.form.submit()" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs sm:text-sm font-semibold focus:outline-none focus:border-emerald-600 focus:bg-white transition-all">
                        <option value="all" {{ $selectedCityId === 'all' ? 'selected' : '' }}>&bull; Semua Kota Indonesia</option>
                        @foreach($cities as $cOption)
                            <option value="{{ $cOption->id }}" {{ $selectedCityId == $cOption->id || (!$selectedCityId && $activeCity && $activeCity->id === $cOption->id) ? 'selected' : '' }}>
                                {{ $cOption->name }} ({{ $cOption->province }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Search Input -->
                <div class="relative flex-grow">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" 
                           name="search" 
                           value="{{ $search }}" 
                           placeholder="Cari berdasarkan nama lokasi, alamat, atau kategori..." 
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 text-xs sm:text-sm focus:outline-none focus:border-emerald-600 focus:bg-white transition-all">
                </div>

                <button type="submit" class="px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs transition-colors shrink-0">
                    Cari Lokasi
                </button>

                @if(!empty($search) || !empty($selectedCityId))
                    <a href="{{ route('admin.locations.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-colors flex items-center justify-center shrink-0">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Locations List Table -->
        <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-xs">
            <div class="p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200/80">
                <h3 class="font-bold text-slate-900 text-base">Daftar Lokasi Terdaftar ({{ $locations->total() }} Tempat)</h3>
                <div class="text-xs text-slate-500 font-medium">
                    Data &copy; <a href="https://www.openstreetmap.org/copyright" target="_blank" class="underline font-semibold text-emerald-700">OpenStreetMap contributors</a>
                </div>
            </div>

            <!-- Desktop View Table -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs sm:text-sm min-w-[700px]">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200/80 text-slate-500 font-bold uppercase text-[11px] tracking-wider">
                            <th class="py-3.5 px-4">Nama Lokasi & ID</th>
                            <th class="py-3.5 px-4">Kategori</th>
                            <th class="py-3.5 px-4">Kota</th>
                            <th class="py-3.5 px-4">Skor Hijau</th>
                            <th class="py-3.5 px-4">Sumber</th>
                            <th class="py-3.5 px-4">Verifikasi</th>
                            <th class="py-3.5 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-800">
                        @forelse($locations as $loc)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-4 px-4">
                                    <div class="font-bold text-slate-900 text-sm">{{ $loc->name }}</div>
                                    <div class="text-[11px] text-slate-400 font-mono">{{ $loc->external_id ?? $loc->source_id ?? 'ID: ' . $loc->id }}</div>
                                    @if($loc->address)
                                        <div class="text-xs text-slate-500 truncate max-w-xs mt-0.5">{{ $loc->address }}</div>
                                    @endif
                                </td>
                                <td class="py-4 px-4 uppercase"><span class="px-2.5 py-1 rounded-md bg-slate-100 text-slate-700 text-[10px] font-mono font-bold">{{ $loc->category }}</span></td>
                                <td class="py-4 px-4 font-semibold text-slate-700">{{ $loc->city->name ?? 'Kota' }}</td>
                                <td class="py-4 px-4 text-emerald-700 font-bold font-mono">{{ $loc->green_score }}%</td>
                                <td class="py-4 px-4 text-slate-600 font-medium uppercase text-xs">
                                    <span class="px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-800 border border-emerald-200/80 text-[10px] font-mono font-bold">{{ $loc->source }}</span>
                                </td>
                                <td class="py-4 px-4">
                                    @if($loc->is_verified)
                                        <span class="px-2.5 py-1 rounded-full bg-blue-50 text-blue-800 border border-blue-200/80 font-bold text-[11px]">Verified</span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 font-medium text-[11px]">OSM Auto</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.locations.edit', $loc) }}" class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition-colors">Edit</a>
                                        <form method="POST" action="{{ route('admin.locations.destroy', $loc) }}" class="inline" onsubmit="return confirm('Hapus lokasi ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 font-semibold text-xs transition-colors cursor-pointer">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-8 text-slate-500 text-xs">
                                    Belum ada lokasi terdaftar. Silakan klik tombol "Sinkronkan Data Lokasi (API)" di atas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile View Responsive Cards -->
            <div class="md:hidden divide-y divide-slate-100">
                @forelse($locations as $loc)
                    <div class="p-4 space-y-3">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <div class="font-bold text-slate-900 text-sm leading-snug">{{ $loc->name }}</div>
                                <div class="text-[11px] text-slate-400 font-mono mt-0.5">{{ $loc->external_id ?? $loc->source_id ?? 'ID: ' . $loc->id }}</div>
                                @if($loc->address)
                                    <div class="text-xs text-slate-500 mt-1 leading-relaxed">{{ $loc->address }}</div>
                                @endif
                            </div>
                            <span class="px-2 py-0.5 rounded bg-slate-200 text-slate-800 text-[10px] font-mono font-bold uppercase shrink-0">
                                {{ $loc->category }}
                            </span>
                        </div>

                        <div class="flex flex-wrap items-center gap-1.5 text-xs pt-2 border-t border-slate-100">
                            <span class="px-2.5 py-1 rounded-md bg-emerald-100 text-emerald-800 font-bold font-mono text-[11px]">
                                Skor: {{ $loc->green_score }}%
                            </span>
                            <span class="px-2.5 py-1 rounded-md bg-slate-100 text-slate-700 font-medium text-[11px]">
                                {{ $loc->city->name ?? 'Kota' }}
                            </span>
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                            <a href="{{ route('admin.locations.edit', $loc) }}" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-semibold text-xs">Edit</a>
                            <form method="POST" action="{{ route('admin.locations.destroy', $loc) }}" class="inline" onsubmit="return confirm('Hapus lokasi ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-800 font-semibold text-xs cursor-pointer">Hapus</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-slate-500 p-4 text-xs">
                        Belum ada lokasi terdaftar.
                    </div>
                @endforelse
            </div>

            <div class="p-4 border-t border-slate-200/80 bg-slate-50/50">
                {{ $locations->appends(['city_id' => $selectedCityId, 'search' => $search])->links() }}
            </div>
        </div>

        <!-- Sync Location Interactive Modal -->
        <div x-show="syncModalOpen" 
             x-transition:enter="transition ease-out duration-200" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100" 
             x-transition:leave="transition ease-in duration-150" 
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0" 
             class="fixed inset-0 bg-slate-900/70 backdrop-blur-xs z-50 flex items-center justify-center p-4" 
             @keydown.escape.window="syncModalOpen = false" 
             x-cloak>
            
            <div @click.away="syncModalOpen = false" 
                 class="bg-white rounded-3xl shadow-2xl border border-slate-200/90 max-w-lg w-full overflow-hidden p-6 space-y-5">
                
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <h3 class="font-bold text-slate-900 text-base sm:text-lg">Sinkronisasi Data Lokasi (API OSM)</h3>
                    </div>
                    <button type="button" @click="syncModalOpen = false" class="p-1.5 rounded-xl bg-slate-100 text-slate-500 hover:text-slate-900 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Best Practice Recommendation Box -->
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200/80 text-xs text-emerald-900 leading-relaxed space-y-1">
                    <div class="font-bold text-emerald-950 flex items-center gap-1">
                        <span>💡 Praktik Terbaik (Best Practice):</span>
                    </div>
                    <p>Pilih kota spesifik untuk sinkronisasi cepat (10-15 km standar), atau pilih <strong>"Sinkronkan Seluruh Kota (Bulk)"</strong> untuk memperbarui semua kota aktif sekaligus secara otomatis di latar belakang server.</p>
                </div>

                <!-- Tab Choices: Per-Kota vs Bulk All -->
                <div class="flex rounded-2xl bg-slate-100 p-1 text-xs font-bold">
                    <button type="button" @click="syncMode = 'single'" :class="syncMode === 'single' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900'" class="flex-1 py-2 rounded-xl transition-all">
                        Per-Kota Spesifik
                    </button>
                    <button type="button" @click="syncMode = 'bulk'" :class="syncMode === 'bulk' ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900'" class="flex-1 py-2 rounded-xl transition-all">
                        ⚡ Seluruh Kota (Bulk All)
                    </button>
                </div>

                <!-- Mode 1: Single City Form -->
                <div x-show="syncMode === 'single'" class="space-y-4">
                    <form method="POST" action="{{ route('admin.locations.sync') }}" @submit="syncModalOpen = false">
                        @csrf
                        <div class="space-y-4">
                            <!-- City Search Select -->
                            <div class="space-y-2">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Pilih Kota Target</label>
                                <select name="city_id" x-model="selectedCityId" class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-900 text-xs sm:text-sm font-semibold focus:outline-none focus:border-emerald-600 focus:bg-white transition-all">
                                    @foreach($cities as $cOption)
                                        <option value="{{ $cOption->id }}">
                                            {{ $cOption->name }} ({{ $cOption->province }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Radius Select -->
                            <div class="space-y-2">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Radius Jangkauan Pencarian</label>
                                <select name="radius" x-model="selectedRadius" class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-900 text-xs sm:text-sm font-semibold focus:outline-none focus:border-emerald-600 focus:bg-white transition-all">
                                    <option value="5000">5,000 meter (5 km - Area Kota)</option>
                                    <option value="10000" selected>10,000 meter (10 km - Standar Perkotaan)</option>
                                    <option value="15000">15,000 meter (15 km - Metropolitans)</option>
                                    <option value="25000">25,000 meter (25 km - Karesidenan)</option>
                                </select>
                            </div>

                            <button type="submit" class="w-full py-3.5 px-4 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs sm:text-sm transition-all shadow-md shadow-emerald-600/20 flex items-center justify-center gap-2 cursor-pointer">
                                <span>Mulai Sinkronisasi Kota</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Mode 2: Bulk All Cities Form -->
                <div x-show="syncMode === 'bulk'" class="space-y-4">
                    <form method="POST" action="{{ route('admin.locations.sync-all') }}" @submit="syncModalOpen = false">
                        @csrf
                        <div class="space-y-4">
                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2">
                                <div class="font-bold text-slate-900 text-xs sm:text-sm">Jangkauan Sinkronisasi Massal:</div>
                                <div class="text-xs text-slate-600 leading-relaxed">
                                    Sistem akan secara berurutan memindai seluruh <strong class="text-emerald-700">{{ count($cities) }} Kota Aktif</strong> yang tersimpan di database.
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Radius per Kota</label>
                                <select name="radius" class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-900 text-xs sm:text-sm font-semibold focus:outline-none focus:border-emerald-600 focus:bg-white transition-all">
                                    <option value="10000" selected>10,000 meter (10 km - Standar Recomendation)</option>
                                    <option value="15000">15,000 meter (15 km)</option>
                                </select>
                            </div>

                            <button type="submit" class="w-full py-3.5 px-4 rounded-2xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs sm:text-sm transition-all shadow-md flex items-center justify-center gap-2 cursor-pointer">
                                <span>⚡ Jalankan Sinkronisasi Massal Seluruh Kota</span>
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>

    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('locationSyncAdmin', () => ({
                syncing: false,
                syncModalOpen: false,
                syncMode: 'single',
                selectedCityId: {{ $activeCity->id ?? ($cities[0]->id ?? 1) }},
                selectedRadius: 10000,
                latestLog: {!! json_encode($syncLogs[0] ?? null) !!},
                lastUpdatedTime: new Date().toLocaleTimeString('id-ID'),
                pollTimer: null,

                init() {
                    if (this.latestLog && this.latestLog.status === 'running') {
                        this.syncing = true;
                        this.startPolling();
                    }
                },

                startPolling() {
                    if (this.pollTimer) clearInterval(this.pollTimer);
                    this.pollTimer = setInterval(async () => {
                        try {
                            const res = await fetch("{{ route('admin.locations.sync-status') }}");
                            if (res.ok) {
                                const data = await res.json();
                                if (data.latest_log) {
                                    this.latestLog = data.latest_log;
                                    this.lastUpdatedTime = new Date().toLocaleTimeString('id-ID');
                                    
                                    if (this.latestLog.status !== 'running') {
                                        this.syncing = false;
                                        clearInterval(this.pollTimer);
                                        setTimeout(() => {
                                            window.location.reload();
                                        }, 1200);
                                    }
                                }
                            }
                        } catch (e) {
                            console.error("Poll error", e);
                        }
                    }, 2500);
                }
            }));
        });
    </script>
</x-layouts.admin>
