<x-layouts.admin title="Manajemen Pengguna — Admin UrbanPulse">
    <div class="space-y-6">
        <div class="flex items-center justify-between pb-5 border-b border-slate-200/80">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Analitik Pengguna & Dampak</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Daftar pengguna terdaftar dan kontribusi emisi CO2 yang dihemat.</p>
            </div>
        </div>

        <div class="bg-white border border-slate-200/80 rounded-2xl p-4 sm:p-6 shadow-xs space-y-4">
            <!-- Desktop View Table -->
            <div class="hidden md:block overflow-x-auto -mx-2 px-2">
                <table class="w-full text-left border-collapse text-xs sm:text-sm min-w-[650px]">
                    <thead>
                        <tr class="border-b border-slate-200/80 text-slate-500 font-semibold uppercase text-[11px] tracking-wider">
                            <th class="pb-3">NAMA PENGGUNA</th>
                            <th class="pb-3">EMAIL</th>
                            <th class="pb-3">ROLE</th>
                            <th class="pb-3">TOTAL AKSI</th>
                            <th class="pb-3">CO2 DIHINDARI</th>
                            <th class="pb-3">POIN HIJAU</th>
                            <th class="pb-3">TERDAFTAR</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-800">
                        @foreach($users as $usr)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3 font-bold text-slate-900">{{ $usr->name }}</td>
                                <td class="py-3 text-slate-600 text-xs">{{ $usr->email }}</td>
                                <td class="py-3">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-mono font-semibold uppercase {{ $usr->isAdmin() ? 'bg-amber-100 text-amber-900 border border-amber-200' : 'bg-slate-100 text-slate-700' }}">
                                        {{ $usr->role }}
                                    </span>
                                </td>
                                <td class="py-3 font-bold text-slate-900 font-mono">{{ $usr->activities_count }}</td>
                                <td class="py-3 font-bold text-emerald-700 font-mono">+{{ number_format($usr->activities_sum_co2_avoided_kg ?? 0, 2) }} kg</td>
                                <td class="py-3 font-bold text-slate-900 font-mono">+{{ $usr->eco_points }} pts</td>
                                <td class="py-3 text-slate-400 font-mono text-xs">{{ $usr->created_at->format('d/m/Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile View Responsive Cards -->
            <div class="md:hidden space-y-3">
                @foreach($users as $usr)
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 space-y-3">
                        <div class="flex items-center justify-between gap-2 pb-2 border-b border-slate-200/80">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-full bg-slate-800 text-white flex items-center justify-center font-bold text-xs shrink-0">
                                    {{ strtoupper(substr($usr->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900 text-xs leading-snug">{{ $usr->name }}</div>
                                    <div class="text-[11px] text-slate-500 truncate max-w-[170px]">{{ $usr->email }}</div>
                                </div>
                            </div>
                            <span class="px-2 py-0.5 rounded text-[10px] font-mono font-semibold uppercase shrink-0 {{ $usr->isAdmin() ? 'bg-amber-100 text-amber-900' : 'bg-slate-200 text-slate-700' }}">
                                {{ $usr->role }}
                            </span>
                        </div>

                        <div class="grid grid-cols-3 gap-2 text-xs text-center font-mono">
                            <div class="p-2 bg-white rounded-lg border border-slate-200/80">
                                <span class="text-slate-500 block text-[10px] font-sans uppercase font-semibold">Total Aksi</span>
                                <span class="font-bold text-slate-900 text-xs">{{ $usr->activities_count }}</span>
                            </div>
                            <div class="p-2 bg-white rounded-lg border border-slate-200/80">
                                <span class="text-slate-500 block text-[10px] font-sans uppercase font-semibold">CO2 Hemat</span>
                                <span class="font-bold text-emerald-700 text-xs">+{{ number_format($usr->activities_sum_co2_avoided_kg ?? 0, 1) }}kg</span>
                            </div>
                            <div class="p-2 bg-white rounded-lg border border-slate-200/80">
                                <span class="text-slate-500 block text-[10px] font-sans uppercase font-semibold">Poin</span>
                                <span class="font-bold text-slate-900 text-xs">+{{ $usr->eco_points }}</span>
                            </div>
                        </div>

                        <div class="text-[11px] text-slate-400 font-mono text-right pt-1 border-t border-slate-200/80">
                            Terdaftar: {{ $usr->created_at->format('d/m/Y') }}
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="pt-4 border-t border-slate-100">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-layouts.admin>
