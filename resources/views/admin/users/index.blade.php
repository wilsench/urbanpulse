<x-layouts.admin title="Manajemen Pengguna — Admin UrbanPulse">
    <div class="space-y-6">
        <div class="flex items-center justify-between pb-6 border-b border-slate-200">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Analitik Pengguna & Dampak</h1>
                <p class="text-sm text-slate-600 mt-1">Daftar pengguna terdaftar dan kontribusi emisi CO2 yang dihemat.</p>
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-3xl p-4 sm:p-6 shadow-sm space-y-4">
            <div class="overflow-x-auto -mx-2 px-2">
                <table class="w-full text-left border-collapse text-xs sm:text-sm min-w-[650px]">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-500 font-bold">
                            <th class="pb-3">NAMA PENGGUNA</th>
                            <th class="pb-3">EMAIL</th>
                            <th class="pb-3">ROLE</th>
                            <th class="pb-3">TOTAL AKSI</th>
                            <th class="pb-3">PERKIRAAN CO2 DIHINDARI</th>
                            <th class="pb-3">POIN HIJAU</th>
                            <th class="pb-3">TERDAFTAR</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-800">
                        @foreach($users as $usr)
                            <tr class="hover:bg-slate-50">
                                <td class="py-3.5 font-bold text-slate-900">{{ $usr->name }}</td>
                                <td class="py-3.5 text-slate-600">{{ $usr->email }}</td>
                                <td class="py-3.5"><span class="px-2.5 py-1 rounded text-xs font-bold uppercase {{ $usr->isAdmin() ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-700' }}">{{ $usr->role }}</span></td>
                                <td class="py-3.5 font-bold text-slate-900">{{ $usr->activities_count }}</td>
                                <td class="py-3.5 font-bold text-emerald-700">+{{ number_format($usr->activities_sum_co2_avoided_kg ?? 0, 2) }} kg</td>
                                <td class="py-3.5 font-bold text-amber-700">+{{ $usr->eco_points }} pts</td>
                                <td class="py-3.5 text-slate-500 font-mono">{{ $usr->created_at->format('d/m/Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pt-4 border-t border-slate-100">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-layouts.admin>
