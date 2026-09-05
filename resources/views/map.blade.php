<x-layouts.app title="Peta Lokasi — {{ $activeCity->name ?? 'Kota Anda' }} — UrbanPulse">
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />
    <script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>

    <style>
        .marker-cluster-small { background-color: rgba(16, 185, 129, 0.35); }
        .marker-cluster-small div { background-color: rgba(5, 150, 105, 0.9); color: #fff; font-weight: 700; font-family: sans-serif; }
        .marker-cluster-medium { background-color: rgba(16, 185, 129, 0.45); }
        .marker-cluster-medium div { background-color: rgba(4, 120, 87, 0.95); color: #fff; font-weight: 700; font-family: sans-serif; }
        .marker-cluster-large { background-color: rgba(16, 185, 129, 0.55); }
        .marker-cluster-large div { background-color: rgba(6, 95, 70, 1); color: #fff; font-weight: 700; font-family: sans-serif; }

        @keyframes pulseCircle {
            0% { stroke-width: 2px; stroke-opacity: 0.9; }
            50% { stroke-width: 6px; stroke-opacity: 0.4; }
            100% { stroke-width: 2px; stroke-opacity: 0.9; }
        }
        .leaflet-interactive.pulse-highlight {
            animation: pulseCircle 2s infinite ease-in-out;
        }
    </style>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6" x-data="cityMap()">
        
        <!-- Header Bar -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-6 border-b border-slate-200/80">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider">PETA INTERAKTIF REAL-TIME</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Taman & Fasilitas di {{ $activeCity->name ?? 'Kota Anda' }}</h1>
                <p class="text-slate-600 text-xs sm:text-base mt-1">Eksplorasi lokasi fisik, tingkat ruang hijau, dan aksesibilitas ramah lingkungan.</p>
            </div>

            <!-- Stats & Quick Actions -->
            <div class="flex items-center gap-3 self-start md:self-auto">
                <div class="bg-emerald-50 border border-emerald-200/80 px-3.5 py-2 rounded-2xl flex items-center gap-2 text-xs font-bold text-emerald-900">
                    <span class="text-base">📍</span>
                    <span><strong x-text="locations.length">0</strong> Lokasi Terverifikasi</span>
                </div>
                <button type="button" @click="fitAllBounds()" class="px-3.5 py-2 rounded-2xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition-all shadow-xs flex items-center gap-1.5 cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg>
                    <span>Pusatkan Peta</span>
                </button>
            </div>
        </div>

        <!-- Search & Filter Control Bar -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 bg-slate-50/90 border border-slate-200/80 p-3 sm:p-4 rounded-2xl">
            <!-- Search Input -->
            <div class="relative flex-grow max-w-md">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" 
                       x-model="searchQuery" 
                       @input.debounce.200ms="applyFilters()" 
                       placeholder="Cari nama tempat atau alamat..." 
                       class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-white border border-slate-300 text-slate-900 placeholder-slate-400 text-xs sm:text-sm focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 shadow-xs">
            </div>

            <!-- Category Filter Buttons -->
            <div class="flex flex-wrap items-center gap-1.5 text-xs sm:text-sm">
                <button type="button" @click="filterCategory('all')" :class="activeCategory === 'all' ? 'bg-emerald-600 text-white font-bold shadow-xs' : 'bg-white border border-slate-300 text-slate-700 hover:bg-slate-100'" class="px-3.5 py-2 rounded-xl transition-all min-h-[38px] flex items-center gap-1 cursor-pointer">
                    <span>Semua</span>
                    <span class="text-[10px] px-1.5 py-0.5 rounded-full bg-black/10" x-text="locations.length"></span>
                </button>
                <button type="button" @click="filterCategory('park')" :class="activeCategory === 'park' ? 'bg-emerald-600 text-white font-bold shadow-xs' : 'bg-white border border-slate-300 text-slate-700 hover:bg-slate-100'" class="px-3.5 py-2 rounded-xl transition-all min-h-[38px] cursor-pointer">
                    🌳 Taman & Ruang Hijau
                </button>
                <button type="button" @click="filterCategory('public_area')" :class="activeCategory === 'public_area' ? 'bg-emerald-600 text-white font-bold shadow-xs' : 'bg-white border border-slate-300 text-slate-700 hover:bg-slate-100'" class="px-3.5 py-2 rounded-xl transition-all min-h-[38px] cursor-pointer">
                    🏛️ Area Publik
                </button>
                <button type="button" @click="filterCategory('transport')" :class="activeCategory === 'transport' ? 'bg-emerald-600 text-white font-bold shadow-xs' : 'bg-white border border-slate-300 text-slate-700 hover:bg-slate-100'" class="px-3.5 py-2 rounded-xl transition-all min-h-[38px] cursor-pointer">
                    🚲 Hub Sepeda & Bus
                </button>
            </div>
        </div>

        <!-- Main Map Display Container & Sidebar Listing -->
        <div class="grid lg:grid-cols-3 gap-6">
            <!-- Map Container -->
            <div class="lg:col-span-2 relative rounded-3xl overflow-hidden border border-slate-200 bg-slate-100 shadow-md h-[400px] sm:h-[480px] lg:h-[560px]" id="map-container">
                <div id="leaflet-map" class="w-full h-full z-10"></div>
                
                <!-- Quick Map Overlay Info Badge -->
                <div class="absolute bottom-4 left-4 z-20 bg-white/90 backdrop-blur-xs border border-slate-200 p-2.5 rounded-2xl shadow-lg text-[11px] font-medium text-slate-700 hidden sm:flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Klik lokasi untuk melihat sorotan lingkaran & navigasi Google Maps.</span>
                </div>
            </div>

            <!-- Side Location Cards Listing -->
            <div class="space-y-3 max-h-[560px] overflow-y-auto pr-1">
                <div class="text-xs font-bold text-slate-500 uppercase tracking-wider flex justify-between items-center pb-2 border-b border-slate-200">
                    <span>DAFTAR LOKASI</span>
                    <span class="text-emerald-700 font-bold text-xs" x-text="filteredLocations.length + ' Tempat Ditemukan'"></span>
                </div>

                <div x-show="filteredLocations.length === 0" class="p-8 rounded-2xl bg-slate-50 border border-slate-200 text-center space-y-2 text-slate-500 text-xs sm:text-sm">
                    <p class="font-bold text-slate-700">Tidak ada lokasi yang cocok</p>
                    <p>Coba gunakan kata kunci lain atau pilih kategori "Semua".</p>
                </div>

                <template x-for="loc in filteredLocations" :key="loc.id">
                    <div @click="focusMap(loc)" 
                         :class="selectedLocationId === loc.id ? 'border-emerald-600 ring-2 ring-emerald-500/20 bg-emerald-50/30' : 'border-slate-200/90 bg-white hover:border-emerald-500'" 
                         class="p-4 rounded-2xl border cursor-pointer transition-all hover:shadow-md space-y-2 group">
                        
                        <div class="flex justify-between items-start gap-2">
                            <h3 class="font-bold text-slate-900 text-sm sm:text-base group-hover:text-emerald-700 transition-colors" x-text="loc.name"></h3>
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-50 text-emerald-800 uppercase shrink-0" x-text="loc.category"></span>
                        </div>
                        <p class="text-xs text-slate-500 line-clamp-1" x-text="loc.address || '{{ $activeCity->name ?? "Kota" }}'"></p>

                        <div class="flex items-center justify-between text-xs pt-2 border-t border-slate-100 font-medium">
                            <span class="text-emerald-700 font-bold">Ruang Hijau: <span x-text="loc.green_score"></span>%</span>
                            
                            <div class="flex items-center gap-2">
                                <!-- Direct Navigation Link -->
                                <a :href="'https://www.google.com/maps/dir/?api=1&destination=' + loc.latitude + ',' + loc.longitude" 
                                   target="_blank" 
                                   rel="noopener noreferrer" 
                                   @click.stop 
                                   class="px-2.5 py-1 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-bold text-[11px] transition-colors flex items-center gap-1">
                                    <span>🧭 Navigasi</span>
                                </a>

                                <a :href="'/locations/' + loc.slug" @click.stop class="text-emerald-700 font-bold hover:underline flex items-center gap-0.5">
                                    <span>Detail</span>
                                    <span>&rarr;</span>
                                </a>
                            </div>
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
                searchQuery: '',
                selectedLocationId: null,
                map: null,
                clusterGroup: null,
                markersMap: new Map(),
                activeCircle: null,

                init() {
                    this.filteredLocations = this.locations;
                    
                    const defaultLat = {{ $activeCity->latitude ?? -6.5910 }};
                    const defaultLng = {{ $activeCity->longitude ?? 106.7960 }};

                    this.map = L.map('leaflet-map', {
                        preferCanvas: true
                    }).setView([defaultLat, defaultLng], 13);

                    L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> &copy; <a href="https://carto.com/">CARTO</a>'
                    }).addTo(this.map);

                    this.renderMarkers();
                },

                filterCategory(cat) {
                    this.activeCategory = cat;
                    this.applyFilters();
                },

                applyFilters() {
                    const query = this.searchQuery.toLowerCase().trim();
                    this.filteredLocations = this.locations.filter(loc => {
                        const matchCat = (this.activeCategory === 'all') ||
                                         (loc.category === this.activeCategory) ||
                                         (this.activeCategory === 'park' && (loc.category === 'green_space' || loc.category === 'park'));
                        
                        const matchQuery = !query || 
                                           loc.name.toLowerCase().includes(query) || 
                                           (loc.address && loc.address.toLowerCase().includes(query));

                        return matchCat && matchQuery;
                    });

                    this.renderMarkers();
                },

                renderMarkers() {
                    if (this.clusterGroup) {
                        this.map.removeLayer(this.clusterGroup);
                    }
                    if (this.activeCircle) {
                        this.map.removeLayer(this.activeCircle);
                        this.activeCircle = null;
                    }

                    this.clusterGroup = L.markerClusterGroup({
                        chunkedLoading: true,
                        maxClusterRadius: 45,
                        spiderfyOnMaxZoom: true,
                        showCoverageOnHover: false,
                        zoomToBoundsOnClick: true
                    });

                    this.markersMap.clear();

                    this.filteredLocations.forEach(loc => {
                        const marker = L.marker([loc.latitude, loc.longitude]);
                        
                        const popupContent = `
                            <div class="p-2.5 font-sans min-w-[240px]">
                                <div class="font-bold text-slate-900 text-sm sm:text-base mb-0.5">${loc.name}</div>
                                <div class="text-xs text-slate-500 mb-2">${loc.address || '{{ $activeCity->name ?? "Kota" }}'}</div>
                                
                                <div class="space-y-1.5 text-xs border-y border-slate-100 py-2 my-2 text-slate-700">
                                    <div class="flex justify-between"><span>Kualitas Udara:</span><strong class="text-emerald-700">${loc.aqi_status}</strong></div>
                                    <div class="flex justify-between"><span>Ruang Hijau:</span><strong class="text-emerald-700">${loc.green_score}%</strong></div>
                                    <div class="flex justify-between"><span>Aksesibilitas:</span><strong class="text-slate-900">${loc.accessibility_score}%</strong></div>
                                </div>
                                
                                <div class="mt-3 flex items-center justify-between gap-2">
                                    <a href="https://www.google.com/maps/dir/?api=1&destination=${loc.latitude},${loc.longitude}" 
                                       target="_blank" 
                                       rel="noopener noreferrer" 
                                       class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-bold text-xs transition-colors">
                                        🧭 Petunjuk Arah
                                    </a>
                                    <a href="/locations/${loc.slug}" class="inline-block px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-xs">
                                        Detail &rarr;
                                    </a>
                                </div>
                            </div>
                        `;

                        marker.bindPopup(popupContent);
                        
                        marker.on('click', () => {
                            this.highlightLocation(loc);
                        });

                        this.clusterGroup.addLayer(marker);
                        this.markersMap.set(loc.id, marker);
                    });

                    this.map.addLayer(this.clusterGroup);
                },

                highlightLocation(loc) {
                    this.selectedLocationId = loc.id;
                    if (this.activeCircle) {
                        this.map.removeLayer(this.activeCircle);
                    }

                    this.activeCircle = L.circle([loc.latitude, loc.longitude], {
                        color: '#10b981',
                        fillColor: '#10b981',
                        fillOpacity: 0.25,
                        radius: 120,
                        weight: 3,
                        dashArray: '6, 6',
                        className: 'pulse-highlight'
                    }).addTo(this.map);
                },

                focusMap(loc) {
                    this.highlightLocation(loc);
                    const marker = this.markersMap.get(loc.id);
                    if (marker) {
                        this.clusterGroup.zoomToShowLayer(marker, () => {
                            marker.openPopup();
                        });
                    } else {
                        this.map.setView([loc.latitude, loc.longitude], 16);
                    }
                },

                fitAllBounds() {
                    if (this.activeCircle) {
                        this.map.removeLayer(this.activeCircle);
                        this.activeCircle = null;
                    }
                    this.selectedLocationId = null;

                    if (this.filteredLocations.length > 0) {
                        const bounds = L.latLngBounds(this.filteredLocations.map(l => [l.latitude, l.longitude]));
                        this.map.fitBounds(bounds, { padding: [40, 40] });
                    }
                }
            }));
        });
    </script>
</x-layouts.app>
