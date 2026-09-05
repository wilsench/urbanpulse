<x-layouts.app title="Catat Aksi Hijau — UrbanPulse">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10 space-y-6 sm:space-y-10">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-200">
            <div>
                <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider block mb-1">AKSI BERKELANJUTAN</span>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Catat Aksi Hijau</h1>
                <p class="text-slate-600 text-sm sm:text-base mt-1">Catat aktivitas mobilitas ramah lingkungan Anda untuk menghitung emisi CO2 yang berhasil dihemat.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
            <!-- Form Record Action -->
            <div class="lg:col-span-1 bg-white border border-slate-200 rounded-3xl p-5 sm:p-6 shadow-sm space-y-4 sm:space-y-6">
                <h2 class="text-lg sm:text-xl font-bold text-slate-900">Tambah Aktivitas Baru</h2>

                <form method="POST" action="{{ route('actions.store') }}" class="space-y-4 text-xs sm:text-sm">
                    @csrf

                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Jenis Aktivitas</label>
                        <select name="action_type" required class="w-full px-3.5 sm:px-4 py-2.5 sm:py-3 rounded-2xl bg-slate-50 border border-slate-300 text-slate-900 focus:outline-none focus:border-emerald-600 min-h-[44px]">
                            <option value="cycling">🚲 Bersepeda</option>
                            <option value="walking">🚶 Jalan Kaki</option>
                            <option value="public_transport">🚌 Transportasi Publik (Bus / KRL)</option>
                            <option value="carpooling">🚗 Carpooling / Berbagi Tumpangan</option>
                            <option value="other">🌱 Aksi Berkelanjutan Lainnya</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Lokasi (Opsional)</label>
                        <select name="location_id" class="w-full px-3.5 sm:px-4 py-2.5 sm:py-3 rounded-2xl bg-slate-50 border border-slate-300 text-slate-900 focus:outline-none focus:border-emerald-600 min-h-[44px]">
                            <option value="">-- Pilih Lokasi Terdaftar --</option>
                            @foreach($locations as $loc)
                                <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Jarak Tempuh (km)</label>
                        <input type="number" step="0.1" name="distance_km" placeholder="Contoh: 8.5" required class="w-full px-3.5 sm:px-4 py-2.5 sm:py-3 rounded-2xl bg-slate-50 border border-slate-300 text-slate-900 focus:outline-none focus:border-emerald-600 min-h-[44px]">
                    </div>

                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Tanggal Aktivitas</label>
                        <input type="date" name="performed_at" value="{{ date('Y-m-d') }}" required class="w-full px-3.5 sm:px-4 py-2.5 sm:py-3 rounded-2xl bg-slate-50 border border-slate-300 text-slate-900 focus:outline-none focus:border-emerald-600 min-h-[44px]">
                    </div>

                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Catatan (Opsional)</label>
                        <textarea name="notes" rows="2" placeholder="Tuliskan rincian..." class="w-full px-3.5 sm:px-4 py-2.5 sm:py-3 rounded-2xl bg-slate-50 border border-slate-300 text-slate-900 focus:outline-none focus:border-emerald-600"></textarea>
                    </div>

                    <button type="submit" class="w-full py-3 sm:py-3.5 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm sm:text-base transition-all shadow-md shadow-emerald-600/20 min-h-[48px]">
                        Simpan Aksi & Hitung Emisi
                    </button>
                </form>
            </div>

            <!-- Table History List -->
            <div class="lg:col-span-2 bg-white border border-slate-200 rounded-3xl p-5 sm:p-8 shadow-sm space-y-4 sm:space-y-6">
                <h2 class="text-lg sm:text-xl font-bold text-slate-900">Riwayat Aksi Hijau Saya</h2>

                <!-- Desktop View Table -->
                <div class="hidden sm:block overflow-x-auto -mx-2 px-2">
                    <table class="w-full text-left border-collapse text-xs sm:text-sm min-w-[500px]">
                        <thead>
                            <tr class="border-b border-slate-200 text-slate-500 font-bold">
                                <th class="pb-3">AKTIVITAS</th>
                                <th class="pb-3">JARAK</th>
                                <th class="pb-3">PERKIRAAN CO2 DIHINDARI</th>
                                <th class="pb-3">POIN HIJAU</th>
                                <th class="pb-3">TANGGAL</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-800">
                            @forelse($activities as $act)
                                <tr class="hover:bg-slate-50">
                                    <td class="py-3.5 font-bold text-slate-900 uppercase">{{ $act->action_type }}</td>
                                    <td class="py-3.5">{{ $act->distance_km }} km</td>
                                    <td class="py-3.5 text-emerald-700 font-bold">+{{ $act->co2_avoided_kg }} kg</td>
                                    <td class="py-3.5 text-slate-900 font-bold">+{{ $act->eco_points_earned }} pts</td>
                                    <td class="py-3.5 text-slate-500 font-mono">{{ $act->performed_at->format('d/m/Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-slate-500">
                                        Belum ada riwayat aktivitas. Tambahkan di formulir sebelah kiri.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile View Responsive Cards -->
                <div class="sm:hidden space-y-3">
                    @forelse($activities as $act)
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-2.5 shadow-xs">
                            <div class="flex items-center justify-between pb-2 border-b border-slate-200/80">
                                <span class="font-bold text-slate-900 text-sm uppercase">🌱 {{ $act->action_type }}</span>
                                <span class="text-xs text-slate-500 font-mono">{{ $act->performed_at->format('d/m/Y') }}</span>
                            </div>
                            <div class="grid grid-cols-3 gap-2 text-xs text-center">
                                <div class="p-2 bg-white rounded-xl border border-slate-200/80">
                                    <span class="text-slate-500 block text-[10px] uppercase font-bold">Jarak</span>
                                    <span class="font-bold text-slate-900 text-xs">{{ $act->distance_km }} km</span>
                                </div>
                                <div class="p-2 bg-white rounded-xl border border-slate-200/80">
                                    <span class="text-slate-500 block text-[10px] uppercase font-bold">CO2 Hemat</span>
                                    <span class="font-bold text-emerald-700 text-xs">+{{ $act->co2_avoided_kg }} kg</span>
                                </div>
                                <div class="p-2 bg-white rounded-xl border border-slate-200/80">
                                    <span class="text-slate-500 block text-[10px] uppercase font-bold">Poin</span>
                                    <span class="font-bold text-amber-700 text-xs">+{{ $act->eco_points_earned }} pts</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-slate-500 bg-slate-50 rounded-2xl border border-slate-200 p-4 text-sm">
                            Belum ada riwayat aktivitas. Tambahkan di formulir di atas.
                        </div>
                    @endforelse
                </div>

                <div class="pt-4 border-t border-slate-100">
                    {{ $activities->links() }}
                </div>
            </div>
        </div>

    </div>
</x-layouts.app>
