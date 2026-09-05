<x-layouts.admin title="Kelola Lokasi & Sinkronisasi — Admin UrbanPulse">
    <div class="space-y-8" x-data="locationSyncAdmin()">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-200">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Manajemen & Sinkronisasi Lokasi {{ $activeCity->name ?? 'Kota' }}</h1>
                <p class="text-sm text-slate-600 mt-1">Pencarian & sinkronisasi lokasi publik otomatis berbasis data terbuka OpenStreetMap (Overpass API).</p>
            </div>
            <a href="{{ route('admin.locations.create') }}" class="px-5 py-3 rounded-2xl bg-amber-600 hover:bg-amber-700 text-white font-bold text-sm transition-all shadow-md shadow-amber-600/20 min-h-[44px] flex items-center">
                + Tambah Lokasi Manual
            </a>
        </div>

        <!-- OPENSTREETMAP AUTOMATED LOCATION DISCOVERY PANEL WITH LIVE BACKGROUND POLLING -->
        <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-6 border-b border-slate-100">
                <div class="space-y-2">
                    <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 font-bold text-xs">
                        🌱 OTOMATISASI PENEMUAN LOKASI (OPENSTREETMAP / OVERPASS API)
                    </span>
                    <h2 class="text-xl font-bold text-slate-900">Sinkronisasi Lokasi Latar Belakang (Queue Job)</h2>
                    <p class="text-sm text-slate-600 max-w-2xl leading-relaxed">
                        Proses sinkronisasi berjalan di server belakang tanpa membuat peramban membeku. Status dan statistik akan diperbarui secara real-time.
                    </p>
                </div>

                <!-- Synchronize Locations Form Button -->
                <form method="POST" action="{{ route('admin.locations.sync') }}" @submit.prevent="triggerSync()" class="bg-slate-50 p-4 rounded-2xl border border-slate-200 space-y-3 w-full md:w-80">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">KOTA TARGET:</label>
                        <select name="city_id" x-model="selectedCityId" class="w-full bg-white text-slate-900 border border-slate-300 rounded-xl px-3 py-2 text-sm font-semibold focus:outline-none focus:border-emerald-600">
                            @foreach($cities as $cOption)
                                <option value="{{ $cOption->id }}" {{ $activeCity && $activeCity->id === $cOption->id ? 'selected' : '' }}>
                                    📍 {{ $cOption->name }} ({{ $cOption->province }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">RADIUS PENCARIAN (METERS):</label>
                        <select name="radius" x-model="selectedRadius" class="w-full bg-white text-slate-900 border border-slate-300 rounded-xl px-3 py-2 text-sm font-semibold focus:outline-none focus:border-emerald-600">
                            <option value="5000">5,000 meter (5 km)</option>
                            <option value="10000" selected>10,000 meter (10 km - Standar)</option>
                            <option value="15000">15,000 meter (15 km)</option>
                            <option value="25000">25,000 meter (25 km)</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full py-3 px-5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm transition-all shadow-md shadow-emerald-600/20 flex items-center justify-center gap-2 min-h-[44px]" :disabled="syncing">
                        <span x-show="!syncing">🔄 Sinkronkan Lokasi Otomatis</span>
                        <span x-show="syncing" class="inline-flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-white animate-ping"></span>
                            Proses Latar Belakang Berjalan...
                        </span>
                    </button>
                </form>
            </div>

            <!-- LIVE REALTIME SYNC STATUS MONITORING BOX -->
            <div x-show="latestLog" x-transition class="p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="font-bold text-slate-900 text-base flex items-center gap-2">
                        <span>⚡ Status Sinkronisasi Real-Time</span>
                        <span class="px-2.5 py-0.5 rounded text-xs uppercase font-bold" :class="{ 'bg-amber-100 text-amber-800 animate-pulse': latestLog?.status === 'running', 'bg-emerald-100 text-emerald-800': latestLog?.status === 'success', 'bg-orange-100 text-orange-800': latestLog?.status === 'partial', 'bg-rose-100 text-rose-800': latestLog?.status === 'failed' }" x-text="latestLog?.status"></span>
                    </div>
                    <span class="text-xs text-slate-500 font-mono" x-text="'Diperbarui: ' + lastUpdatedTime"></span>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 text-center text-xs">
                    <div class="p-3 bg-white rounded-xl border border-slate-200">
                        <div class="text-slate-500">Ditemukan</div>
                        <div class="text-lg font-bold text-slate-900" x-text="latestLog?.discovered_count ?? 0"></div>
                    </div>
                    <div class="p-3 bg-white rounded-xl border border-slate-200">
                        <div class="text-slate-500">Baru</div>
                        <div class="text-lg font-bold text-emerald-700" x-text="'+' + (latestLog?.created_count ?? 0)"></div>
                    </div>
                    <div class="p-3 bg-white rounded-xl border border-slate-200">
                        <div class="text-slate-500">Diperbarui</div>
                        <div class="text-lg font-bold text-blue-700" x-text="latestLog?.updated_count ?? 0"></div>
                    </div>
                    <div class="p-3 bg-white rounded-xl border border-slate-200">
                        <div class="text-slate-500">Duplikat</div>
                        <div class="text-lg font-bold text-slate-700" x-text="latestLog?.duplicate_count ?? 0"></div>
                    </div>
                    <div class="p-3 bg-white rounded-xl border border-slate-200">
                        <div class="text-slate-500">Dilewati</div>
                        <div class="text-lg font-bold text-slate-500" x-text="latestLog?.skipped_count ?? 0"></div>
                    </div>
                </div>

                <template x-if="latestLog?.error_message">
                    <div class="p-3 rounded-xl bg-amber-50 border border-amber-200 text-xs text-amber-900" x-text="latestLog.error_message"></div>
                </template>
            </div>
        </div>

        <!-- LOCATIONS LIST TABLE -->
        <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-200">
                <h3 class="font-bold text-slate-900 text-lg">Daftar Lokasi Terdaftar ({{ $locations->total() }} Tempat)</h3>
                <div class="text-xs text-slate-500 font-medium">
                    Data Lokasi © <a href="https://www.openstreetmap.org/copyright" target="_blank" class="underline font-bold text-emerald-700">OpenStreetMap contributors</a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-500 font-bold">
                            <th class="pb-3">NAMA LOKASI & ID EXTERNAL</th>
                            <th class="pb-3">KATEGORI</th>
                            <th class="pb-3">KOTA</th>
                            <th class="pb-3">SKOR HIJAU</th>
                            <th class="pb-3">SUMBER DATA</th>
                            <th class="pb-3">VERIFIKASI</th>
                            <th class="pb-3 text-right">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-800">
                        @forelse($locations as $loc)
                            <tr class="hover:bg-slate-50">
                                <td class="py-3.5">
                                    <div class="font-bold text-slate-900 text-base">{{ $loc->name }}</div>
                                    <div class="text-xs text-slate-500 font-mono">{{ $loc->external_id ?? $loc->source_id ?? 'ID: ' . $loc->id }}</div>
                                    @if($loc->address)
                                        <div class="text-xs text-slate-500 truncate max-w-xs">{{ $loc->address }}</div>
                                    @endif
                                </td>
                                <td class="py-3.5 uppercase"><span class="px-2.5 py-1 rounded bg-slate-100 text-slate-700 text-xs font-bold">{{ $loc->category }}</span></td>
                                <td class="py-3.5 font-semibold text-slate-700">{{ $loc->city->name ?? 'Kota' }}</td>
                                <td class="py-3.5 text-emerald-700 font-bold">{{ $loc->green_score }}%</td>
                                <td class="py-3.5 text-slate-600 font-medium uppercase text-xs">
                                    <span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-800 border border-emerald-200">{{ $loc->source }}</span>
                                </td>
                                <td class="py-3.5">
                                    @if($loc->is_verified)
                                        <span class="px-2 py-0.5 rounded bg-blue-100 text-blue-800 font-bold text-xs">✓ Verified Admin</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-600 font-medium text-xs">Otomatis OSM</span>
                                    @endif
                                </td>
                                <td class="py-3.5 text-right space-x-3">
                                    <a href="{{ route('admin.locations.edit', $loc) }}" class="text-amber-700 font-bold hover:underline">Edit</a>
                                    <form method="POST" action="{{ route('admin.locations.destroy', $loc) }}" class="inline" onsubmit="return confirm('Hapus lokasi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-600 font-bold hover:underline">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-8 text-slate-500">
                                    Belum ada lokasi terdaftar. Silakan klik tombol <strong>"Sinkronkan Lokasi Otomatis"</strong> di atas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pt-4 border-t border-slate-100">
                {{ $locations->links() }}
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('locationSyncAdmin', () => ({
                syncing: false,
                selectedCityId: {{ $activeCity->id ?? ($cities[0]->id ?? 1) }},
                selectedRadius: 10000,
                latestLog: {!! json_encode($syncLogs[0] ?? null) !!},
                lastUpdatedTime: new Date().toLocaleTimeString('id-ID'),
                pollTimer: null,

                init() {
                    if (this.latestLog && this.latestLog.status === 'running') {
                        $this.syncing = true;
                        $this.startPolling();
                    }
                },

                async triggerSync() {
                    this.syncing = true;
                    try {
                        const response = await fetch("{{ route('admin.locations.sync') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                city_id: this.selectedCityId,
                                radius: this.selectedRadius
                            })
                        });

                        if (response.ok) {
                            const data = await response.json();
                            this.latestLog = data.log;
                            this.lastUpdatedTime = new Date().toLocaleTimeString('id-ID');
                            this.startPolling();
                        } else {
                            window.location.reload();
                        }
                    } catch (e) {
                        console.error("Sync trigger error", e);
                        window.location.reload();
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
                                        // Auto refresh table after 1 second when completed
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
