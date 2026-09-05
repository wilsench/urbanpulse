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
                <button type="button" 
                        @click="syncModalOpen = true" 
                        :disabled="cooldownRemaining > 0 || syncing"
                        class="px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs transition-all shadow-xs flex items-center gap-1.5 min-h-[40px] cursor-pointer disabled:opacity-60 disabled:cursor-not-allowed">
                    <svg class="w-4 h-4 text-emerald-400" :class="{ 'animate-spin': syncing }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    <span x-text="cooldownRemaining > 0 ? 'Cooldown (' + cooldownRemaining + 's)' : (syncing ? 'Mengambil Data...' : 'Sinkronkan Data Lokasi (API)')"></span>
                </button>

                <!-- Add Location Button -->
                <a href="{{ route('admin.locations.create') }}" class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition-all shadow-md shadow-emerald-600/20 min-h-[40px] flex items-center gap-1.5 shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>Tambah Lokasi Manual</span>
                </a>
            </div>
        </div>

        <!-- LIVE BULK SYNC PROGRESS & TERMINAL FEED BOX -->
        <div x-show="bulkSyncing || (bulkState && bulkState.is_running) || bulkCompleted" x-transition class="p-5 rounded-3xl bg-slate-900 text-white shadow-xl space-y-4 border border-slate-800 relative overflow-hidden">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-800 pb-3">
                <div class="flex items-center gap-2.5">
                    <span class="relative flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75" x-show="bulkSyncing"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3" :class="bulkCompleted ? 'bg-emerald-400' : 'bg-amber-500'"></span>
                    </span>
                    <div>
                        <h3 class="font-bold text-sm sm:text-base text-white flex items-center gap-2">
                            <span x-text="bulkCompleted ? '🎉 Sinkronisasi Massal Seluruh Kota Selesai' : 'Sinkronisasi Massal Seluruh Kota Sedang Berjalan...'"></span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-mono bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 uppercase tracking-wider" x-text="bulkProgressPercent + '%'"></span>
                        </h3>
                        <p class="text-xs text-slate-400" x-text="bulkCompleted ? 'Seluruh kota aktif telah berhasil dipindai dan diperbarui!' : 'Memproses Kota: ' + (bulkCurrentCityName || 'Menginisialisasi...') + ' (' + (bulkCurrentIndex || 0) + '/' + (bulkTotalCities || 0) + ')'"></p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <button type="button" @click="resetBulkSyncLock()" class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-xs font-semibold text-slate-300 border border-slate-700 transition-colors">
                        Reset Status Lock
                    </button>
                    <button type="button" x-show="bulkCompleted" @click="window.location.reload()" class="px-3.5 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-xs font-bold text-white shadow-xs transition-colors">
                        Muat Ulang Halaman
                    </button>
                </div>
            </div>

            <!-- Progress Bar Container -->
            <div class="space-y-1.5">
                <div class="w-full bg-slate-800 rounded-full h-3 overflow-hidden p-0.5 border border-slate-700/80">
                    <div class="bg-gradient-to-r from-emerald-500 to-teal-400 h-full rounded-full transition-all duration-300 shadow-xs" :style="'width: ' + bulkProgressPercent + '%'"></div>
                </div>
                <div class="flex flex-wrap justify-between text-[11px] text-slate-400 font-mono gap-2">
                    <span>Kemajuan: <strong class="text-emerald-400 font-bold" x-text="(bulkCurrentIndex || 0) + ' / ' + (bulkTotalCities || 0) + ' Kota'"></strong></span>
                    <span>Total Ditemukan: <strong class="text-emerald-400 font-bold" x-text="bulkTotals?.discovered ?? 0"></strong> | Baru: <strong class="text-emerald-300 font-bold" x-text="'+' + (bulkTotals?.created ?? 0)"></strong> | Diperbarui: <strong class="text-blue-300 font-bold" x-text="bulkTotals?.updated ?? 0"></strong></span>
                </div>
            </div>

            <!-- Live Terminal Output Console Log -->
            <div class="space-y-1.5">
                <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400 flex items-center justify-between">
                    <span>🖥️ Log Aktivitas Sinkronisasi Real-Time:</span>
                    <span class="text-[10px] text-slate-500 font-mono">Live Terminal Stream</span>
                </div>
                <div id="bulkSyncTerminal" class="h-44 overflow-y-auto rounded-2xl bg-slate-950 p-3 font-mono text-xs text-slate-300 space-y-1.5 border border-slate-800/80 shadow-inner">
                    <template x-for="(log, idx) in bulkLogs" :key="idx">
                        <div class="flex items-start gap-2 py-1 border-b border-slate-900/80 leading-relaxed">
                            <span class="text-slate-500 text-[10px] shrink-0 font-mono" x-text="'[' + (log.time || '--:--') + ']'"></span>
                            <span class="px-1.5 py-0.5 rounded text-[10px] font-bold shrink-0 font-mono" :class="{ 'bg-emerald-950 text-emerald-400 border border-emerald-800/60': log.status === 'success', 'bg-rose-950 text-rose-400 border border-rose-800/60': log.status === 'failed' }">
                                <span x-text="'[' + log.step + '/' + (bulkTotalCities || 0) + '] ' + log.city_name"></span>
                            </span>
                            <span class="text-slate-300 flex-grow" x-text="'Ditemukan: ' + log.discovered + ' | Baru: +' + log.created + ' | Diperbarui: ' + log.updated"></span>
                        </div>
                    </template>
                    <div x-show="bulkLogs.length === 0" class="text-slate-500 italic py-2">
                        Memulai sinkronisasi massal seluruh kota...
                    </div>
                </div>
            </div>
        </div>

        <!-- Live Status Box (Single City Log & Realtime Sync State) -->
        <div x-show="(latestLog || syncing || syncError) && !bulkSyncing" x-transition class="p-5 rounded-3xl bg-white border border-slate-200/80 shadow-md space-y-4 relative overflow-hidden">
            
            <!-- Realtime Progress Banner when Fetching -->
            <div x-show="syncing" class="p-4 rounded-2xl bg-slate-900 text-white space-y-2 border border-slate-800 shadow-inner" x-cloak>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <span class="relative flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                        </span>
                        <span class="font-bold text-sm text-white flex items-center gap-1.5">
                            <span>Sinkronisasi Lokasi Realtime:</span>
                            <span class="text-emerald-400 font-mono" x-text="selectedSyncCityName"></span>
                        </span>
                    </div>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-mono bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 uppercase font-bold tracking-wider animate-pulse">FETCHING API</span>
                </div>
                <div class="text-xs text-slate-300 flex items-center gap-2 pt-1 font-mono leading-relaxed">
                    <svg class="w-4 h-4 text-emerald-400 animate-spin shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    <span x-text="syncStepText"></span>
                </div>
            </div>

            <!-- Rate Limit / Warning Notice Banner -->
            <div x-show="!warningDismissed && (syncError || (latestLog?.status === 'partial' && latestLog?.error_message))" class="p-4 rounded-2xl bg-amber-50 border border-amber-300/80 text-amber-950 space-y-2.5 relative" x-cloak>
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-start gap-3">
                        <div class="p-2 rounded-xl bg-amber-100 text-amber-800 shrink-0 mt-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <div class="space-y-1.5 text-xs flex-grow">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <div class="font-bold text-sm text-amber-950 flex items-center gap-2">
                                    <span>⚠️ Status Server OpenStreetMap Overpass</span>
                                </div>
                                <template x-if="cooldownRemaining > 0">
                                    <span class="px-2.5 py-1 rounded-lg bg-amber-200 text-amber-950 font-mono font-bold text-[11px] shadow-xs" x-text="'Cooldown: ' + cooldownRemaining + ' detik'"></span>
                                </template>
                            </div>
                            <p class="text-amber-900 leading-relaxed font-medium text-xs" x-text="syncError || latestLog?.error_message || 'Server OpenStreetMap Overpass sedang mengalami pembatasan kuota (rate-limit / sibuk). Mohon tunggu 15-30 detik sebelum memicu sinkronisasi kembali. Data lokasi lama di database tetap aman.'"></p>
                            <div class="text-[11px] text-amber-800 font-semibold flex items-center gap-1.5 pt-0.5">
                                <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                <span>Jaminan Keamanan: Data lokasi lama di database tetap tersimpan dengan utuh.</span>
                            </div>
                        </div>
                    </div>

                    <button type="button" @click="warningDismissed = true; syncError = null" class="p-1 rounded-lg text-amber-700 hover:text-amber-950 hover:bg-amber-100 transition-colors shrink-0" title="Tutup Notifikasi">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>

            <!-- Status Header & Counters -->
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div class="font-bold text-slate-900 text-sm flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full" :class="syncing ? 'bg-amber-500 animate-ping' : (latestLog?.status === 'success' ? 'bg-emerald-500' : 'bg-amber-500')"></span>
                    <span>Status Log Sinkronisasi Terakhir (Per-Kota)</span>
                    <span class="px-2 py-0.5 rounded text-[10px] uppercase font-mono font-bold" :class="{ 'bg-amber-100 text-amber-800': syncing || latestLog?.status === 'running', 'bg-emerald-100 text-emerald-800': latestLog?.status === 'success', 'bg-amber-100 text-amber-900 border border-amber-300': latestLog?.status === 'partial', 'bg-rose-100 text-rose-800': latestLog?.status === 'failed' }" x-text="syncing ? 'PROSES FETCHING' : (latestLog?.status || 'SIAP')"></span>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" @click="clearAllSyncLogs()" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-[11px] font-semibold transition-colors border border-slate-200 flex items-center gap-1 cursor-pointer">
                        <svg class="w-3 h-3 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        <span>Bersihkan Log</span>
                    </button>
                    <span class="text-[11px] text-slate-500 font-mono" x-text="'Diperbarui: ' + lastUpdatedTime"></span>
                </div>
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

        </div>

        <!-- Filter & Search Control Bar for Locations Table -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
            <form method="GET" action="{{ route('admin.locations.index') }}" id="locationFilterForm" @submit="searching = true" class="flex flex-col sm:flex-row gap-3">
                
                <!-- Searchable City Filter Dropdown -->
                <div class="w-full sm:w-72 relative" @click.outside="tableCityDropdownOpen = false">
                    <input type="hidden" name="city_id" :value="filterCityId">
                    
                    <button type="button" 
                            @click="if(!searching) { tableCityDropdownOpen = !tableCityDropdownOpen; if(tableCityDropdownOpen) $nextTick(() => $refs.tableCitySearchInput.focus()); }"
                            :disabled="searching"
                            class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs sm:text-sm font-semibold flex items-center justify-between focus:outline-none focus:border-emerald-600 focus:bg-white transition-all disabled:opacity-60 disabled:cursor-not-allowed cursor-pointer shadow-xs">
                        <span class="flex items-center gap-2 truncate">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span x-text="selectedTableCityLabel" class="truncate font-bold"></span>
                        </span>
                        <svg class="w-4 h-4 text-slate-400 shrink-0 transition-transform duration-200" :class="{ 'rotate-180': tableCityDropdownOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="tableCityDropdownOpen" 
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute left-0 right-0 top-full mt-1.5 bg-white border border-slate-200 rounded-2xl shadow-xl z-50 p-2 space-y-2" 
                         x-cloak>
                        
                        <!-- Search Box -->
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <input type="text" 
                                   x-model="tableCitySearchQuery"
                                   x-ref="tableCitySearchInput"
                                   placeholder="Cari filter kota / provinsi..." 
                                   class="w-full pl-9 pr-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs focus:outline-none focus:border-emerald-600 focus:bg-white transition-all">
                        </div>

                        <!-- Options List -->
                        <div class="max-h-56 overflow-y-auto space-y-0.5 divide-y divide-slate-50">
                            <template x-for="c in filteredTableCities" :key="c.id">
                                <button type="button" 
                                        @click="selectFilterCity(c.id)" 
                                        class="w-full text-left px-3 py-2.5 rounded-xl hover:bg-emerald-50 hover:text-emerald-950 transition-colors flex items-center justify-between text-xs cursor-pointer group"
                                        :class="filterCityId == c.id ? 'bg-emerald-50 text-emerald-900 font-bold' : 'text-slate-700 font-medium'">
                                    <div>
                                        <div x-text="c.name === 'Semua Kota Indonesia' ? '• Semua Kota Indonesia' : c.name" class="group-hover:text-emerald-950 font-bold"></div>
                                        <div x-text="c.province" class="text-[10px] text-slate-400 group-hover:text-emerald-700"></div>
                                    </div>
                                    <svg x-show="filterCityId == c.id" class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </button>
                            </template>
                            <div x-show="filteredTableCities.length === 0" class="p-3 text-center text-xs text-slate-400 italic">
                                Tidak ditemukan kota "<span x-text="tableCitySearchQuery"></span>"
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search Input -->
                <div class="relative flex-grow">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg x-show="!searching" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        <svg x-show="searching" class="w-4 h-4 text-emerald-600 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    </div>
                    <input type="text" 
                           name="search" 
                           value="{{ $search }}" 
                           :disabled="searching"
                           placeholder="Cari berdasarkan nama lokasi, alamat, atau kategori..." 
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 text-xs sm:text-sm focus:outline-none focus:border-emerald-600 focus:bg-white transition-all disabled:opacity-60 disabled:cursor-not-allowed">
                </div>

                <button type="submit" 
                        :disabled="searching"
                        :class="searching ? 'bg-slate-700 cursor-not-allowed' : 'bg-slate-900 hover:bg-slate-800 cursor-pointer'"
                        class="px-5 py-2.5 rounded-xl text-white font-bold text-xs transition-colors shrink-0 flex items-center justify-center gap-1.5 min-w-[110px]">
                    <svg x-show="searching" class="w-3.5 h-3.5 text-emerald-400 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    <span x-text="searching ? 'Mencari...' : 'Cari Lokasi'"></span>
                </button>
            </form>
        </div>

        <!-- Locations Table with Search Overlay -->
        <div class="bg-white border border-slate-200/80 rounded-3xl overflow-hidden shadow-xs relative">
            <div x-show="searching" class="absolute inset-0 bg-white/75 backdrop-blur-[1px] z-20 flex items-center justify-center" x-cloak>
                <div class="inline-flex items-center gap-2.5 px-4 py-2.5 rounded-2xl bg-slate-900 text-white text-xs font-bold shadow-xl">
                    <svg class="w-4 h-4 text-emerald-400 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    <span>Memproses Pencarian Lokasi...</span>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm">
                    <thead class="bg-slate-50 border-b border-slate-200/80 text-slate-500 font-bold uppercase tracking-wider text-[10px] sm:text-xs">
                        <tr>
                            <th class="py-3.5 px-4 sm:px-6">Lokasi & Kategori</th>
                            <th class="py-3.5 px-4 sm:px-6">Kota / Wilayah</th>
                            <th class="py-3.5 px-4 sm:px-6 text-center">Skor Hijau</th>
                            <th class="py-3.5 px-4 sm:px-6 text-center">Jalan Kaki</th>
                            <th class="py-3.5 px-4 sm:px-6 text-center">Sepeda</th>
                            <th class="py-3.5 px-4 sm:px-6">Sumber Data</th>
                            <th class="py-3.5 px-4 sm:px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($locations as $loc)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3.5 px-4 sm:px-6">
                                    <div class="font-bold text-slate-900 text-sm sm:text-base leading-tight">
                                        {{ $loc->name }}
                                    </div>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-800 font-semibold text-[10px] uppercase">
                                            {{ $loc->category }}
                                        </span>
                                        @if($loc->address)
                                            <span class="text-xs text-slate-500 truncate max-w-[200px] sm:max-w-[280px]" title="{{ $loc->address }}">&bull; {{ $loc->address }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 font-medium text-slate-800 whitespace-nowrap">
                                    <div class="font-semibold text-slate-900">{{ $loc->city->name ?? '-' }}</div>
                                    <div class="text-xs text-slate-500">{{ $loc->city->province ?? '' }}</div>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-center whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold {{ $loc->green_score >= 80 ? 'bg-emerald-100 text-emerald-800' : ($loc->green_score >= 60 ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-700') }}">
                                        {{ $loc->green_score }}/100
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-center whitespace-nowrap">
                                    <span class="font-mono font-semibold text-slate-700">{{ $loc->walking_score }}%</span>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-center whitespace-nowrap">
                                    @if($loc->bike_friendly)
                                        <span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 font-bold text-[10px]">Ya</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-500 font-medium text-[10px]">Tidak</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-600 font-mono text-[10px] font-semibold uppercase">
                                        {{ $loc->source ?? 'manual' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('locations.show', $loc->slug) }}" target="_blank" class="p-1.5 rounded-lg text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 transition-colors" title="Lihat di Web">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                        </a>
                                        <a href="{{ route('admin.locations.edit', $loc->id) }}" class="p-1.5 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-colors" title="Edit Lokasi">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                        <form method="POST" action="{{ route('admin.locations.destroy', $loc->id) }}" onsubmit="event.preventDefault(); confirmAction({ title: 'Hapus Lokasi', message: 'Apakah Anda yakin ingin menghapus {{ addslashes($loc->name) }} secara permanen?', confirmText: 'Ya, Hapus Lokasi', variant: 'danger', onConfirm: () => this.submit() }); return false;" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors" title="Hapus Lokasi">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-slate-400">
                                    <div class="space-y-2">
                                        <svg class="w-8 h-8 mx-auto text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                        <p class="font-medium text-sm text-slate-600">Belum ada lokasi publik yang tersimpan.</p>
                                        <p class="text-xs text-slate-400">Gunakan tombol "Sinkronkan Data Lokasi" di atas untuk mencari dari OpenStreetMap.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($locations->hasPages())
                <div class="p-4 bg-slate-50 border-t border-slate-200/80">
                    {{ $locations->appends(request()->query())->links() }}
                </div>
            @endif
        </div>

        <!-- Sync Trigger Modal -->
        <div x-show="syncModalOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center p-4" 
             x-cloak>
            
            <div @click.outside="if(!bulkSyncing) syncModalOpen = false" 
                 class="bg-white rounded-3xl shadow-2xl max-w-lg w-full p-6 sm:p-8 space-y-6 border border-slate-200 relative overflow-hidden">
                
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <div>
                        <h3 class="font-bold text-slate-900 text-lg sm:text-xl">Sinkronisasi Data Lokasi (API)</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Integrasi OpenStreetMap Overpass Discovery Engine</p>
                    </div>
                    <button type="button" @click="syncModalOpen = false" class="p-1 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200/80 text-xs text-emerald-900 leading-relaxed space-y-1">
                    <div class="font-bold text-emerald-950 flex items-center gap-1">
                        <span>💡 Sinkronisasi Lokasi Per-Kota (Rekomendasi Cepat):</span>
                    </div>
                    <p>Pilih kota spesifik (seperti Kota Bogor, Kota Bandung, Jakarta Selatan) untuk memicu pencarian dan pembaruan lokasi taman/fasilitas publik dari OpenStreetMap (Overpass API) secara instan.</p>
                </div>

                <!-- Single City Sync Form -->
                <div class="space-y-4">
                    <form @submit.prevent="startSingleSync()">
                        <div class="space-y-4">
                            <!-- Searchable City Select Dropdown in Sync Modal -->
                            <div class="space-y-2 relative" @click.outside="syncCityDropdownOpen = false">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Pilih Kota Target</label>
                                <input type="hidden" name="city_id" :value="selectedCityId">

                                <button type="button" 
                                        @click="if(!syncing) { syncCityDropdownOpen = !syncCityDropdownOpen; if(syncCityDropdownOpen) $nextTick(() => $refs.modalCitySearchInput.focus()); }" 
                                        :disabled="syncing"
                                        class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-900 text-xs sm:text-sm font-semibold flex items-center justify-between focus:outline-none focus:border-emerald-600 focus:bg-white transition-all disabled:opacity-60 cursor-pointer shadow-xs">
                                    <span class="flex items-center gap-2 truncate">
                                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        <span x-text="selectedSyncCityLabel" class="truncate font-bold"></span>
                                    </span>
                                    <svg class="w-4 h-4 text-slate-400 shrink-0 transition-transform duration-200" :class="{ 'rotate-180': syncCityDropdownOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </button>

                                <!-- Dropdown Panel -->
                                <div x-show="syncCityDropdownOpen" 
                                     x-transition:enter="transition ease-out duration-150"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-100"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95"
                                     class="absolute left-0 right-0 top-full mt-1.5 bg-white border border-slate-200 rounded-2xl shadow-xl z-50 p-2 space-y-2" 
                                     x-cloak>
                                    
                                    <!-- Search Input -->
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                        </div>
                                        <input type="text" 
                                               x-model="modalCitySearchQuery"
                                               x-ref="modalCitySearchInput"
                                               placeholder="Cari nama kota atau provinsi..." 
                                               class="w-full pl-9 pr-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs focus:outline-none focus:border-emerald-600 focus:bg-white transition-all">
                                    </div>

                                    <!-- Options List -->
                                    <div class="max-h-52 overflow-y-auto space-y-0.5 divide-y divide-slate-50">
                                        <template x-for="c in filteredSyncCities" :key="c.id">
                                            <button type="button" 
                                                    @click="selectedCityId = c.id; syncCityDropdownOpen = false; modalCitySearchQuery = '';" 
                                                    class="w-full text-left px-3 py-2.5 rounded-xl hover:bg-emerald-50 hover:text-emerald-950 transition-colors flex items-center justify-between text-xs cursor-pointer group"
                                                    :class="selectedCityId == c.id ? 'bg-emerald-50 text-emerald-900 font-bold' : 'text-slate-700 font-medium'">
                                                <div>
                                                    <div x-text="c.name" class="group-hover:text-emerald-950 font-bold"></div>
                                                    <div x-text="c.province" class="text-[10px] text-slate-400 group-hover:text-emerald-700"></div>
                                                </div>
                                                <svg x-show="selectedCityId == c.id" class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            </button>
                                        </template>
                                        <div x-show="filteredSyncCities.length === 0" class="p-3 text-center text-xs text-slate-400 italic">
                                            Tidak ada kota yang cocok dengan "<span x-text="modalCitySearchQuery"></span>"
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Radius Select -->
                            <div class="space-y-2">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Radius Jangkauan Pencarian</label>
                                <select name="radius" x-model="selectedRadius" :disabled="syncing" class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-900 text-xs sm:text-sm font-semibold focus:outline-none focus:border-emerald-600 focus:bg-white transition-all disabled:opacity-60">
                                    <option value="5000">5,000 meter (5 km - Area Pusat Kota)</option>
                                    <option value="10000" selected>10,000 meter (10 km - Standar Perkotaan)</option>
                                    <option value="15000">15,000 meter (15 km - Metropolitan)</option>
                                    <option value="25000">25,000 meter (25 km - Karesidenan)</option>
                                </select>
                            </div>

                            <button type="submit" 
                                    :disabled="syncing"
                                    :class="syncing ? 'bg-slate-400 cursor-not-allowed' : 'bg-emerald-600 hover:bg-emerald-700 cursor-pointer'"
                                    class="w-full py-3.5 px-4 rounded-2xl text-white font-bold text-xs sm:text-sm transition-all shadow-md shadow-emerald-600/20 flex items-center justify-center gap-2">
                                <svg class="w-4 h-4 text-white" :class="{ 'animate-spin': syncing }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                <span x-text="syncing ? 'Memproses Sinkronisasi Data...' : 'Mulai Sinkronisasi Kota'"></span>
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
                searching: false,
                syncing: false,
                syncModalOpen: false,
                syncMode: 'single',
                selectedCityId: {{ $activeCity->id ?? ($cities[0]->id ?? 1) }},
                selectedRadius: 10000,
                latestLog: {!! json_encode($syncLogs[0] ?? null) !!},
                lastUpdatedTime: new Date().toLocaleTimeString('id-ID'),
                pollTimer: null,

                // Master cities array for searching
                citiesList: [
                    @foreach($cities as $cOption)
                        { id: {{ $cOption->id }}, name: @json($cOption->name), province: @json($cOption->province) },
                    @endforeach
                ],

                // Searchable Select State for Sync Modal Target City
                syncCityDropdownOpen: false,
                modalCitySearchQuery: '',

                // Searchable Select State for Table Filter
                tableCityDropdownOpen: false,
                tableCitySearchQuery: '',
                filterCityId: '{{ $selectedCityId }}',

                get filteredSyncCities() {
                    if (!this.modalCitySearchQuery.trim()) return this.citiesList;
                    const q = this.modalCitySearchQuery.toLowerCase();
                    return this.citiesList.filter(c => c.name.toLowerCase().includes(q) || c.province.toLowerCase().includes(q));
                },

                get selectedSyncCityLabel() {
                    const found = this.citiesList.find(c => c.id == this.selectedCityId);
                    return found ? `${found.name} (${found.province})` : 'Pilih Kota Target...';
                },

                get filteredTableCities() {
                    const allOpt = { id: 'all', name: 'Semua Kota Indonesia', province: 'Seluruh Wilayah' };
                    let list = [allOpt, ...this.citiesList];
                    if (!this.tableCitySearchQuery.trim()) return list;
                    const q = this.tableCitySearchQuery.toLowerCase();
                    return list.filter(c => c.name.toLowerCase().includes(q) || c.province.toLowerCase().includes(q));
                },

                get selectedTableCityLabel() {
                    if (this.filterCityId === 'all' || !this.filterCityId) return '• Semua Kota Indonesia';
                    const found = this.citiesList.find(c => c.id == this.filterCityId);
                    return found ? `${found.name} (${found.province})` : '• Semua Kota Indonesia';
                },

                selectFilterCity(cityId) {
                    this.filterCityId = cityId;
                    this.tableCityDropdownOpen = false;
                    this.tableCitySearchQuery = '';
                    this.searching = true;
                    this.$nextTick(() => {
                        const form = document.getElementById('locationFilterForm');
                        if (form) form.submit();
                    });
                },

                // Bulk Sync State
                bulkSyncing: false,
                bulkCompleted: false,
                bulkProgressPercent: 0,
                bulkCurrentIndex: 0,
                bulkTotalCities: {{ count($cities) }},
                bulkCurrentCityName: '',
                bulkLogs: [],
                bulkTotals: { discovered: 0, created: 0, updated: 0 },
                bulkState: null,

                init() {
                    if (this.latestLog && this.latestLog.status === 'running') {
                        this.syncing = true;
                    }
                    this.checkStatus();
                    this.startPolling();
                },

                // Realtime Sync Status State
                syncStepText: '',
                syncError: null,
                warningDismissed: false,
                selectedSyncCityName: '',
                cooldownRemaining: 0,
                cooldownTimer: null,

                async clearAllSyncLogs() {
                    confirmAction({
                        title: 'Bersihkan Log Sinkronisasi',
                        message: 'Apakah Anda yakin ingin me-reset dan menghapus seluruh catatan log sinkronisasi?',
                        confirmText: 'Ya, Bersihkan Log',
                        variant: 'warning',
                        onConfirm: async () => {
                            try {
                                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                                await fetch("{{ route('admin.locations.clear-logs') }}", {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': csrfToken
                                    }
                                });
                                this.latestLog = null;
                                this.syncError = null;
                                this.warningDismissed = true;
                                window.location.reload();
                            } catch (e) {
                                console.error("Clear logs failed", e);
                            }
                        }
                    });
                },

                async startSingleSync() {
                    if (this.syncing || this.cooldownRemaining > 0) return;
                    this.syncing = true;
                    this.syncModalOpen = false;
                    this.syncError = null;

                    const city = this.citiesList.find(c => c.id == this.selectedCityId);
                    this.selectedSyncCityName = city ? `${city.name} (${city.province})` : 'Kota Target';
                    this.syncStepText = `[1/3] Menghubungi Server OpenStreetMap Overpass API untuk ${this.selectedSyncCityName}...`;

                    // Timed live progress messages
                    const t1 = setTimeout(() => {
                        if (this.syncing) this.syncStepText = `[2/3] Mengirim query spasial & mencari mirror server Overpass aktif...`;
                    }, 1800);
                    const t2 = setTimeout(() => {
                        if (this.syncing) this.syncStepText = `[3/3] Memproses data lokasi, koordinat presisi, dan skor hijau...`;
                    }, 4500);

                    try {
                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                        const res = await fetch("{{ route('admin.locations.sync') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                city_id: this.selectedCityId,
                                radius: this.selectedRadius
                            })
                        });

                        clearTimeout(t1);
                        clearTimeout(t2);

                        if (res.ok) {
                            const data = await res.json();
                            if (data.log) {
                                this.latestLog = data.log;
                                this.lastUpdatedTime = new Date().toLocaleTimeString('id-ID');
                            }

                            if (data.status === 'partial' || (data.log && data.log.status === 'partial') || (data.message && data.message.includes('rate-limit'))) {
                                this.syncError = data.message || 'Server OpenStreetMap Overpass sedang mengalami pembatasan kuota (rate-limit / sibuk). Mohon tunggu 15-30 detik sebelum memicu sinkronisasi kembali. Data lokasi lama di database tetap aman.';
                                this.startCooldown(25);
                            } else {
                                this.syncStepText = '✅ Sinkronisasi Berhasil! Memuat ulang data lokasi...';
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1000);
                            }
                        } else {
                            const data = await res.json();
                            this.syncError = data.message || 'Server OpenStreetMap Overpass sedang mengalami pembatasan kuota (rate-limit / sibuk). Mohon tunggu 15-30 detik sebelum memicu sinkronisasi kembali. Data lokasi lama di database tetap aman.';
                            this.startCooldown(25);
                        }
                    } catch (e) {
                        console.error("Single sync error", e);
                        this.syncError = 'Server OpenStreetMap Overpass sedang mengalami pembatasan kuota (rate-limit / sibuk). Mohon tunggu 15-30 detik sebelum memicu sinkronisasi kembali. Data lokasi lama di database tetap aman.';
                        this.startCooldown(25);
                    } finally {
                        this.syncing = false;
                    }
                },

                startCooldown(seconds = 25) {
                    this.cooldownRemaining = seconds;
                    if (this.cooldownTimer) clearInterval(this.cooldownTimer);
                    this.cooldownTimer = setInterval(() => {
                        if (this.cooldownRemaining > 0) {
                            this.cooldownRemaining--;
                        } else {
                            clearInterval(this.cooldownTimer);
                            this.cooldownTimer = null;
                        }
                    }, 1000);
                },

                async checkStatus() {
                    try {
                        const res = await fetch("{{ route('admin.locations.sync-status') }}");
                        if (res.ok) {
                            const data = await res.json();
                            if (data.latest_log) {
                                this.latestLog = data.latest_log;
                                this.lastUpdatedTime = new Date().toLocaleTimeString('id-ID');
                            }
                            if (data.bulk_sync) {
                                this.bulkState = data.bulk_sync;
                                this.bulkTotalCities = data.bulk_sync.total_cities || this.bulkTotalCities;
                                this.bulkLogs = data.bulk_sync.logs || [];
                                this.bulkTotals = data.bulk_sync.totals || { discovered: 0, created: 0, updated: 0 };
                                this.bulkCurrentIndex = data.bulk_sync.current_index || 0;
                                this.bulkCurrentCityName = data.bulk_sync.current_city_name || '';
                                
                                if (data.bulk_sync.is_running) {
                                    this.bulkSyncing = true;
                                    this.bulkCompleted = false;
                                    this.bulkProgressPercent = Math.round((this.bulkCurrentIndex / this.bulkTotalCities) * 100);
                                } else if (data.bulk_sync.completed) {
                                    this.bulkSyncing = false;
                                    this.bulkCompleted = true;
                                    this.bulkProgressPercent = 100;
                                }
                            }
                        }
                    } catch (e) {
                        console.error("Status check error", e);
                    }
                },

                startPolling() {
                    if (this.pollTimer) clearInterval(this.pollTimer);
                    this.pollTimer = setInterval(async () => {
                        await this.checkStatus();
                    }, 3000);
                },

                async startBulkSyncStepByStep() {
                    if (this.bulkSyncing) return;

                    this.syncModalOpen = false;
                    this.bulkSyncing = true;
                    this.bulkCompleted = false;
                    this.bulkProgressPercent = 0;
                    this.bulkCurrentIndex = 0;
                    this.bulkLogs = [];
                    this.bulkTotals = { discovered: 0, created: 0, updated: 0 };

                    let step = 0;
                    let completed = false;

                    while (!completed && this.bulkSyncing) {
                        try {
                            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                            const res = await fetch("{{ route('admin.locations.sync-all-step') }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    step_index: step,
                                    radius: this.selectedRadius
                                })
                            });

                            if (!res.ok) {
                                console.error("Bulk sync step error", res.status);
                                break;
                            }

                            const data = await res.json();
                            completed = data.completed;
                            step = data.step_index;
                            this.bulkProgressPercent = data.progress_percent || 0;
                            this.bulkCurrentCityName = data.current_city_name || '';
                            this.bulkCurrentIndex = step;
                            if (data.totals) this.bulkTotals = data.totals;

                            if (data.bulk_state && data.bulk_state.logs) {
                                this.bulkLogs = data.bulk_state.logs;
                            }

                            // Auto-scroll terminal
                            this.$nextTick(() => {
                                const term = document.getElementById('bulkSyncTerminal');
                                if (term) term.scrollTop = term.scrollHeight;
                            });

                            if (completed) {
                                this.bulkSyncing = false;
                                this.bulkCompleted = true;
                                this.bulkProgressPercent = 100;
                                break;
                            }

                            // Gentle 400ms pause between steps to prevent external API rate-limiting
                            await new Promise(resolve => setTimeout(resolve, 400));
                        } catch (err) {
                            console.error("Step execution failed", err);
                            break;
                        }
                    }
                },

                async resetBulkSyncLock() {
                    confirmAction({
                        title: 'Reset Status Lock',
                        message: 'Apakah Anda yakin ingin me-reset status lock sinkronisasi massal?',
                        confirmText: 'Ya, Reset Status',
                        variant: 'warning',
                        onConfirm: async () => {
                            try {
                                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                                await fetch("{{ route('admin.locations.cancel-bulk-sync') }}", {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': csrfToken
                                    }
                                });
                                this.bulkSyncing = false;
                                this.bulkCompleted = false;
                                this.bulkState = null;
                                this.bulkLogs = [];
                                window.location.reload();
                            } catch (e) {
                                console.error("Reset failed", e);
                            }
                        }
                    });
                }
            }));
        });
    </script>
</x-layouts.admin>
