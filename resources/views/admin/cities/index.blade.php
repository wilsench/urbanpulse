<x-layouts.admin title="Kelola Kota — UrbanPulse CMS">
    <div class="space-y-6">

        <!-- Header Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-200/80">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 uppercase tracking-wider">CMS SYSTEM</span>
                    <span class="text-xs text-slate-500 font-medium">&bull; Manajemen Wilayah & Kota</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Manajemen Kota Indonesia</h1>
                <p class="text-slate-600 text-xs sm:text-sm mt-1">Kelola data kota terverifikasi, koordinat presisi, serta status keaktifan di platform UrbanPulse.</p>
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                <!-- Sync Indonesia API Button -->
                <form method="POST" action="{{ route('admin.cities.sync-indonesia') }}" onsubmit="event.preventDefault(); confirmAction({ title: 'Sinkronisasi Kota Indonesia', message: 'Apakah Anda yakin ingin menyinkronkan data kota utama di Indonesia secara otomatis?', confirmText: 'Ya, Sinkronkan', variant: 'emerald', onConfirm: () => this.submit() }); return false;">
                    @csrf
                    <button type="submit" class="px-3.5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs transition-all flex items-center gap-1.5 cursor-pointer shadow-xs border border-slate-200">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        <span>Sync Kota Indonesia (API)</span>
                    </button>
                </form>

                <!-- Add New City Button -->
                <a href="{{ route('admin.cities.create') }}" class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition-all flex items-center gap-1.5 shadow-md shadow-emerald-600/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>Tambah Kota Baru</span>
                </a>
            </div>
        </div>

        <!-- Metric Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-xs space-y-1">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">TOTAL KOTA TERDAFTAR</span>
                <div class="text-2xl font-extrabold text-slate-900">{{ $totalCities }} <span class="text-xs font-normal text-slate-500">Kota</span></div>
            </div>
            <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-xs space-y-1">
                <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider">KOTA AKTIF SERVIS</span>
                <div class="text-2xl font-extrabold text-emerald-700">{{ $activeCities }} <span class="text-xs font-normal text-emerald-600">Aktif</span></div>
            </div>
            <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-xs space-y-1">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">TOTAL LOKASI TERHUBUNG</span>
                <div class="text-2xl font-extrabold text-slate-900">{{ $totalLocations }} <span class="text-xs font-normal text-slate-500">Titik Tempat</span></div>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs" x-data="{ searching: false }">
            <form method="GET" action="{{ route('admin.cities.index') }}" @submit="searching = true" class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-grow">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg x-show="!searching" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        <svg x-show="searching" class="w-4 h-4 text-emerald-600 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    </div>
                    <input type="text" 
                           name="search" 
                           value="{{ $search }}" 
                           :disabled="searching"
                           placeholder="Cari berdasarkan nama kota, provinsi, atau deskripsi..." 
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 text-xs sm:text-sm focus:outline-none focus:border-emerald-600 focus:bg-white transition-all disabled:opacity-60 disabled:cursor-not-allowed">
                </div>
                <button type="submit" 
                        :disabled="searching"
                        :class="searching ? 'bg-slate-700 cursor-not-allowed' : 'bg-slate-900 hover:bg-slate-800 cursor-pointer'"
                        class="px-5 py-2.5 rounded-xl text-white font-bold text-xs transition-colors shrink-0 flex items-center justify-center gap-1.5 min-w-[100px]">
                    <svg x-show="searching" class="w-3.5 h-3.5 text-emerald-400 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    <span x-text="searching ? 'Mencari...' : 'Cari Kota'"></span>
                </button>
                @if(!empty($search))
                    <a href="{{ route('admin.cities.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-colors flex items-center justify-center shrink-0">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Responsive Hybrid Table & Card View -->
        <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-xs">
            
            <!-- Desktop Table View -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs sm:text-sm">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200/80 text-slate-500 font-bold uppercase text-[11px] tracking-wider">
                            <th class="py-3.5 px-4">Nama Kota & Provinsi</th>
                            <th class="py-3.5 px-4">Koordinat & Presisi</th>
                            <th class="py-3.5 px-4">Lokasi Terhubung</th>
                            <th class="py-3.5 px-4">Status</th>
                            <th class="py-3.5 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($cities as $city)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-4 px-4">
                                    <div class="font-bold text-slate-900 text-sm">{{ $city->name }}</div>
                                    <div class="text-xs text-slate-500">{{ $city->province ?? 'Indonesia' }} &bull; <span class="font-mono text-[11px]">{{ $city->slug }}</span></div>
                                </td>
                                <td class="py-4 px-4 font-mono text-xs text-slate-700">
                                    <div>Lat: {{ number_format($city->latitude, 4) }}</div>
                                    <div>Lng: {{ number_format($city->longitude, 4) }}</div>
                                </td>
                                <td class="py-4 px-4 font-semibold text-slate-800">
                                    <span class="px-2.5 py-1 rounded-lg bg-slate-100 border border-slate-200 text-xs">
                                        📍 {{ $city->locations_count }} Tempat
                                    </span>
                                </td>
                                <td class="py-4 px-4">
                                    <form method="POST" action="{{ route('admin.cities.toggle-active', $city) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="px-2.5 py-1 rounded-full text-xs font-bold transition-all cursor-pointer inline-flex items-center gap-1.5 {{ $city->is_active ? 'bg-emerald-100 text-emerald-800 border border-emerald-200 hover:bg-emerald-200' : 'bg-slate-100 text-slate-600 border border-slate-200 hover:bg-slate-200' }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $city->is_active ? 'bg-emerald-600' : 'bg-slate-400' }}"></span>
                                            <span>{{ $city->is_active ? 'AKTIF' : 'NON-AKTIF' }}</span>
                                        </button>
                                    </form>
                                </td>
                                <td class="py-4 px-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.cities.edit', $city) }}" class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors font-semibold text-xs flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            <span>Edit</span>
                                        </a>
                                        <form method="POST" action="{{ route('admin.cities.destroy', $city) }}" onsubmit="event.preventDefault(); confirmAction({ title: 'Hapus Kota', message: 'Apakah Anda yakin ingin menghapus kota {{ addslashes($city->name) }}?', confirmText: 'Ya, Hapus Kota', variant: 'danger', onConfirm: () => this.submit() }); return false;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 transition-colors font-semibold text-xs flex items-center gap-1 cursor-pointer">
                                                <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                <span>Hapus</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-slate-500">
                                    Belum ada data kota yang tersimpan. Klik "Sync Kota Indonesia (API)" untuk menyinkronkan secara otomatis.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="md:hidden divide-y divide-slate-100">
                @forelse($cities as $city)
                    <div class="p-4 space-y-3">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-bold text-slate-900 text-base">{{ $city->name }}</h3>
                                <p class="text-xs text-slate-500">{{ $city->province ?? 'Indonesia' }}</p>
                            </div>
                            <form method="POST" action="{{ route('admin.cities.toggle-active', $city) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-2.5 py-1 rounded-full text-[10px] font-bold cursor-pointer {{ $city->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $city->is_active ? 'AKTIF' : 'NON-AKTIF' }}
                                </button>
                            </form>
                        </div>

                        <div class="text-xs text-slate-600 font-mono bg-slate-50 p-2.5 rounded-xl border border-slate-100 flex justify-between">
                            <span>Lat: {{ number_format($city->latitude, 4) }}</span>
                            <span>Lng: {{ number_format($city->longitude, 4) }}</span>
                            <span class="font-bold text-emerald-700 font-sans">📍 {{ $city->locations_count }} Tempat</span>
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-1">
                            <a href="{{ route('admin.cities.edit', $city) }}" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('admin.cities.destroy', $city) }}" onsubmit="event.preventDefault(); confirmAction({ title: 'Hapus Kota', message: 'Apakah Anda yakin ingin menghapus kota {{ addslashes($city->name) }}?', confirmText: 'Ya, Hapus Kota', variant: 'danger', onConfirm: () => this.submit() }); return false;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 font-semibold text-xs cursor-pointer">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-slate-500 text-xs">
                        Belum ada kota tersimpan.
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="p-4 border-t border-slate-200/80 bg-slate-50/50">
                {{ $cities->appends(['search' => $search])->links() }}
            </div>
        </div>

    </div>
</x-layouts.admin>
