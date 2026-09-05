<x-layouts.admin title="Data Lingkungan & API — Admin UrbanPulse">
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-slate-200/80">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Log Data Lingkungan & API</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Riwayat penarikan data BMKG & Air Quality API.</p>
            </div>
            <form method="POST" action="{{ route('admin.environment.sync') }}">
                @csrf
                <button type="submit" class="w-full sm:w-auto px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs flex items-center justify-center gap-2 transition-all shadow-xs min-h-[40px]">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    <span>Trigger Manual Sync</span>
                </button>
            </form>
        </div>

        <div class="bg-white border border-slate-200/80 rounded-2xl p-4 sm:p-6 shadow-xs space-y-4">
            <h2 class="text-base font-bold text-slate-900">Catatan Parameter Lingkungan</h2>

            <!-- Desktop View Table -->
            <div class="hidden md:block overflow-x-auto -mx-2 px-2">
                <table class="w-full text-left border-collapse text-xs sm:text-sm min-w-[600px]">
                    <thead>
                        <tr class="border-b border-slate-200/80 text-slate-500 font-semibold uppercase text-[11px] tracking-wider">
                            <th class="pb-3">KOTA</th>
                            <th class="pb-3">SUHU</th>
                            <th class="pb-3">CUACA</th>
                            <th class="pb-3">HUJAN %</th>
                            <th class="pb-3">AQI</th>
                            <th class="pb-3">STATUS AQI</th>
                            <th class="pb-3">RECORDED AT</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-800">
                        @foreach($environmentalData as $env)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3 font-bold text-slate-900">{{ $env->city }}</td>
                                <td class="py-3 font-bold text-slate-900 font-mono">{{ $env->temperature }}°C</td>
                                <td class="py-3 text-slate-600">{{ $env->weather_description }}</td>
                                <td class="py-3 font-bold text-emerald-700 font-mono">{{ $env->rain_probability }}%</td>
                                <td class="py-3 font-bold text-slate-900 font-mono">{{ $env->air_quality_index }}</td>
                                <td class="py-3"><span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-800 border border-emerald-200/80 text-[11px] font-semibold">{{ $env->air_quality_status }}</span></td>
                                <td class="py-3 text-slate-400 font-mono text-xs">{{ $env->recorded_at->format('Y-m-d H:i:s') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile View Responsive Cards -->
            <div class="md:hidden space-y-3">
                @foreach($environmentalData as $env)
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
                                    <span class="text-slate-500 block text-[10px] uppercase font-semibold">AQI</span>
                                    <span class="font-bold text-slate-900 text-sm font-mono">{{ $env->air_quality_index }}</span>
                                </div>
                                <span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 text-xs font-semibold">
                                    {{ $env->air_quality_status }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="pt-4 border-t border-slate-100">
                {{ $environmentalData->links() }}
            </div>
        </div>
    </div>
</x-layouts.admin>
