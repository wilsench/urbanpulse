<x-layouts.app title="Dampak Saya — UrbanPulse">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10 space-y-6 sm:space-y-10">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-200">
            <div>
                <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider block mb-1">DAMPAK LINGKUNGAN</span>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Dampak Saya untuk Kota</h1>
                <p class="text-slate-600 text-sm sm:text-base mt-1">Visualisasi kontribusi nyata Anda mengurangi emisi CO2 dan menjaga keberlanjutan Kota Bogor.</p>
            </div>
        </div>

        <!-- MY IMPACT SUMMARY HERO CARDS -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6">
            <div class="p-5 sm:p-8 rounded-3xl bg-white border border-emerald-200 shadow-sm space-y-1.5 sm:space-y-2">
                <div class="text-[10px] sm:text-xs font-bold text-emerald-800 uppercase">PERKIRAAN CO2 YANG DIHINDARI</div>
                <div class="text-3xl sm:text-4xl font-bold text-emerald-700 my-1 sm:my-2">{{ number_format($totalCo2, 3) }} <span class="text-sm sm:text-base text-slate-500 font-normal">kg</span></div>
                <div class="text-xs sm:text-sm text-slate-600">Estimasi Pengurangan Emisi Karbon Anda</div>
            </div>

            <div class="p-5 sm:p-8 rounded-3xl bg-white border border-slate-200 shadow-sm space-y-1.5 sm:space-y-2">
                <div class="text-[10px] sm:text-xs font-bold text-slate-500 uppercase">TOTAL AKSI HIJAU</div>
                <div class="text-3xl sm:text-4xl font-bold text-slate-900 my-1 sm:my-2">{{ $totalActions }}</div>
                <div class="text-xs sm:text-sm text-slate-600">Aktivitas Berkelanjutan Dicatat</div>
            </div>

            <div class="p-5 sm:p-8 rounded-3xl bg-white border border-slate-200 shadow-sm space-y-1.5 sm:space-y-2">
                <div class="text-[10px] sm:text-xs font-bold text-slate-500 uppercase">POIN HIJAU SAYA</div>
                <div class="text-3xl sm:text-4xl font-bold text-emerald-700 my-1 sm:my-2">+{{ $totalEcoPoints }}</div>
                <div class="text-xs sm:text-sm text-slate-600">Poin Pencapaian Aktivitas Berkelanjutan</div>
            </div>
        </div>

        <!-- CHART VISUALIZATIONS GRID -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8">
            <!-- Weekly CO2 Avoided Trend Line Chart -->
            <div class="bg-white border border-slate-200 rounded-3xl p-4 sm:p-6 lg:p-8 shadow-sm space-y-4">
                <div class="flex justify-between items-center pb-3 border-b border-slate-100 font-bold text-xs sm:text-sm">
                    <span class="text-slate-900">Grafik Pengurangan CO2 (7 Hari Terakhir)</span>
                    <span class="text-emerald-700">Satuan: kg</span>
                </div>
                <div class="h-56 sm:h-64 relative">
                    <canvas id="weeklyChart"></canvas>
                </div>
            </div>

            <!-- Category Breakdown Doughnut Chart -->
            <div class="bg-white border border-slate-200 rounded-3xl p-4 sm:p-6 lg:p-8 shadow-sm space-y-4">
                <div class="flex justify-between items-center pb-3 border-b border-slate-100 font-bold text-xs sm:text-sm">
                    <span class="text-slate-900">Distribusi Jenis Aktivitas</span>
                    <span class="text-emerald-700">Kontribusi</span>
                </div>
                <div class="h-56 sm:h-64 relative">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>

    </div>

    <!-- Chart.js Initialization Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // 1. Weekly Line Chart
            const ctxWeekly = document.getElementById('weeklyChart').getContext('2d');
            new Chart(ctxWeekly, {
                type: 'line',
                data: {
                    labels: {!! json_encode($weeklyLabels) !!},
                    datasets: [{
                        label: 'CO2 Avoided (kg)',
                        data: {!! json_encode($weeklyCo2Values) !!},
                        borderColor: '#059669',
                        backgroundColor: 'rgba(5, 150, 105, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.3,
                        pointBackgroundColor: '#059669'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: { grid: { color: '#f1f5f9' }, ticks: { color: '#475569', font: { family: 'Instrument Sans' } } },
                        y: { grid: { color: '#f1f5f9' }, ticks: { color: '#475569', font: { family: 'Instrument Sans' } } }
                    },
                    plugins: { legend: { display: false } }
                }
            });

            // 2. Category Doughnut Chart
            const ctxCat = document.getElementById('categoryChart').getContext('2d');
            const catLabels = {!! json_encode($categoryBreakdown->pluck('action_type')->map(fn($v) => ucfirst($v))) !!};
            const catValues = {!! json_encode($categoryBreakdown->pluck('total_co2')) !!};

            new Chart(ctxCat, {
                type: 'doughnut',
                data: {
                    labels: catLabels.length ? catLabels : ['Belum Ada Data'],
                    datasets: [{
                        data: catValues.length ? catValues : [1],
                        backgroundColor: ['#059669', '#0284c7', '#0d9488', '#d97706', '#4f46e5']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { color: '#334155', font: { family: 'Instrument Sans', size: 12 } } }
                    }
                }
            });
        });
    </script>
</x-layouts.app>
