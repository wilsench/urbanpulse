<x-layouts.app title="Peta Lokasi — {{ $activeCity->name ?? 'Kota Anda' }} — UrbanPulse">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6" x-data="cityMap()">
        
        <!-- Header & Category Filter Bar -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-6 border-b border-slate-200">
            <div>
                <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider block mb-1">PETA INTERAKTIF</span>
                <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Taman & Fasilitas di {{ $activeCity->name ?? 'Kota Anda' }}</h1>
                <p class="text-slate-600 text-base mt-1">Pilih lokasi fisik untuk melihat kualitas ruang hijau dan aksesibilitas.</p>
            </div>

            <!-- Category Filter Buttons (Min 44px touch targets) -->
            <div class="flex flex-wrap gap-2 text-sm">
                <button @click="filterCategory('all')" :class="activeCategory === 'all' ? 'bg-emerald-600 text-white font-bold' : 'bg-white border border-slate-300 text-slate-700 hover:bg-slate-100'" class="px-4 py-2.5 rounded-xl transition-colors min-h-[44px]">
                    Semua (<span x-text="locations.length"></span>)
                </button>
                <button @click="filterCategory('park')" :class="activeCategory === 'park' ? 'bg-emerald-600 text-white font-bold' : 'bg-white border border-slate-300 text-slate-700 hover:bg-slate-100'" class="px-4 py-2.5 rounded-xl transition-colors min-h-[44px]">
                    Taman & Ruang Hijau
                </button>
                <button @click="filterCategory('public_area')" :class="activeCategory === 'public_area' ? 'bg-emerald-600 text-white font-bold' : 'bg-white border border-slate-300 text-slate-700 hover:bg-slate-100'" class="px-4 py-2.5 rounded-xl transition-colors min-h-[44px]">
                    Area Publik
                </button>
                <button @click="filterCategory('transport')" :class="activeCategory === 'transport' ? 'bg-emerald-600 text-white font-bold' : 'bg-white border border-slate-300 text-slate-700 hover:bg-slate-100'" class="px-4 py-2.5 rounded-xl transition-colors min-h-[44px]">
                    Hub Sepeda & Bus
                </button>
            </div>
        </div>

        <!-- Main Map Container & Sidebar -->
        <div class="grid lg:grid-cols-3 gap-6">
            <!-- Map Display Container -->
            <div class="lg:col-span-2 relative rounded-3xl overflow-hidden border border-slate-200 bg-white shadow-sm h-[360px] sm:h-[450px] lg:h-[520px]" id="map-container">
                <div id="leaflet-map" class="w-full h-full"></div>
            </div>

            <!-- Side Location Cards Listing -->
            <div class="space-y-4 max-h-[520px] overflow-y-auto pr-1">
                <div class="text-sm font-bold text-slate-700 flex justify-between items-center pb-2 border-b border-slate-200">
                    <span>DAFTAR LOKASI ({{ strtoupper($activeCity->name ?? 'KOTA') }})</span>
                    <span class="text-emerald-700" x-text="filteredLocations.length + ' Tempat'"></span>
                </div>

                <template x-for="loc in filteredLocations" :key="loc.id">
                    <div @click="focusMap(loc)" class="p-5 rounded-2xl bg-white border border-slate-200 hover:border-emerald-600 cursor-pointer transition-all hover:shadow-md space-y-2">
                        <div class="flex justify-between items-start">
                            <h3 class="font-bold text-slate-900 text-base" x-text="loc.name"></h3>
                            <span class="px-2.5 py-0.5 rounded-md text-xs font-bold bg-emerald-50 text-emerald-800 uppercase" x-text="loc.category"></span>
                        </div>
                        <p class="text-sm text-slate-600 line-clamp-1" x-text="loc.address"></p>

                        <div class="flex items-center justify-between text-sm pt-2 border-t border-slate-100">
                            <span class="text-emerald-700 font-bold">Ruang Hijau: <span x-text="loc.green_score"></span>%</span>
                            <a :href="'/locations/' + loc.slug" class="text-emerald-700 font-bold hover:underline">Detail Tempat &rarr;</a>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('cityMap', () => ({
                locations: {!! $locationsJson !!},
                filteredLocations: [],
                activeCategory: 'all',
                map: null,
                markers: [],

                init() {
                    this.filteredLocations = this.locations;
                    
                    const defaultLat = {{ $activeCity->latitude ?? -6.5910 }};
                    const defaultLng = {{ $activeCity->longitude ?? 106.7960 }};

                    this.map = L.map('leaflet-map').setView([defaultLat, defaultLng], 13);

                    L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/">CARTO</a>'
                    }).addTo(this.map);

                    this.renderMarkers();
                },

                filterCategory(cat) {
                    this.activeCategory = cat;
                    if (cat === 'all') {
                        this.filteredLocations = this.locations;
                    } else {
                        this.filteredLocations = this.locations.filter(l => l.category === cat || (cat === 'park' && l.category === 'green_space'));
                    }
                    this.renderMarkers();
                },

                renderMarkers() {
                    this.markers.forEach(m => this.map.removeLayer(m));
                    this.markers = [];

                    this.filteredLocations.forEach(loc => {
                        const marker = L.marker([loc.latitude, loc.longitude]).addTo(this.map);
                        
                        const popupContent = `
                            <div class="p-2 font-sans min-w-[220px]">
                                <div class="font-bold text-slate-900 text-base mb-1">${loc.name}</div>
                                <div class="text-xs text-slate-600 mb-2">${loc.address || '{{ $activeCity->name ?? "Kota" }}'}</div>
                                
                                <div class="space-y-1 text-xs border-y border-slate-100 py-2 my-2 text-slate-700">
                                    <div>Kualitas Udara: <strong class="text-emerald-700">${loc.aqi_status}</strong></div>
                                    <div>Ruang Hijau: <strong class="text-emerald-700">${loc.green_score}%</strong></div>
                                    <div>Aksesibilitas: <strong class="text-slate-900">${loc.accessibility_score}%</strong></div>
                                </div>
                                
                                <div class="mt-2 text-right">
                                    <a href="/locations/${loc.slug}" class="inline-block px-3 py-1.5 rounded-lg bg-emerald-600 text-white font-bold text-xs hover:bg-emerald-700">Lihat Detail Tempat</a>
                                </div>
                            </div>
                        `;

                        marker.bindPopup(popupContent);
                        this.markers.push(marker);
                    });
                },

                focusMap(loc) {
                    this.map.setView([loc.latitude, loc.longitude], 16);
                    const found = this.markers.find(m => m.getLatLng().lat === loc.latitude && m.getLatLng().lng === loc.longitude);
                    if (found) {
                        found.openPopup();
                    }
                }
            }));
        });
    </script>
</x-layouts.app>
