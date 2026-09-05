<x-layouts.admin title="Data Lingkungan & API — Admin UrbanPulse">
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-200">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Log Data Lingkungan & API</h1>
                <p class="text-sm text-slate-600 mt-1">Riwayat penarikan data BMKG & Air Quality API.</p>
            </div>
            <form method="POST" action="{{ route('admin.environment.sync') }}">
                @csrf
                <button type="submit" class="w-full sm:w-auto px-5 py-3 rounded-2xl bg-amber-600 hover:bg-amber-700 text-white font-bold text-sm min-h-[44px]">
                    ⚡ Trigger Manual Sync
                </button>
            </form>
        </div>

        <div class="bg-white border border-slate-200 rounded-3xl p-4 sm:p-6 shadow-sm space-y-4">
            <h2 class="text-base font-bold text-slate-900">Catatan Parameter Lingkungan</h2>

            <div class="overflow-x-auto -mx-2 px-2">
                <table class="w-full text-left border-collapse text-xs sm:text-sm min-w-[600px]">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-500 font-bold">
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
                            <tr class="hover:bg-slate-50">
                                <td class="py-3.5 font-bold text-slate-900">{{ $env->city }}</td>
                                <td class="py-3.5 font-bold text-slate-900">{{ $env->temperature }}°C</td>
                                <td class="py-3.5">{{ $env->weather_description }}</td>
                                <td class="py-3.5 font-bold text-emerald-700">{{ $env->rain_probability }}%</td>
                                <td class="py-3.5 font-bold text-slate-900">{{ $env->air_quality_index }}</td>
                                <td class="py-3.5"><span class="px-2.5 py-1 rounded bg-emerald-50 text-emerald-800 text-xs font-bold">{{ $env->air_quality_status }}</span></td>
                                <td class="py-3.5 text-slate-500 font-mono">{{ $env->recorded_at->format('Y-m-d H:i:s') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pt-4 border-t border-slate-100">
                {{ $environmentalData->links() }}
            </div>
        </div>
    </div>
</x-layouts.admin>
