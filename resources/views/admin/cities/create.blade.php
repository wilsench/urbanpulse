<x-layouts.admin title="Tambah Kota Baru — UrbanPulse CMS">
    <div class="max-w-4xl mx-auto space-y-6" x-data="cityCreateForm()">

        <!-- Header Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-200/80">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <a href="{{ route('admin.cities.index') }}" class="text-xs font-bold text-emerald-700 hover:underline flex items-center gap-1">
                        <span>&larr; Kembali ke Daftar Kota</span>
                    </a>
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Tambah Kota Baru</h1>
                <p class="text-slate-600 text-xs sm:text-sm mt-1">Tambahkan wilayah kota baru secara manual atau pilih dari database API Kota Indonesia.</p>
            </div>
        </div>

        <!-- API Live Search Selector Card -->
        <div class="p-5 sm:p-6 rounded-3xl bg-gradient-to-r from-slate-900 to-emerald-950 text-white shadow-lg space-y-4 relative overflow-hidden">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                <span class="text-xs font-bold text-emerald-300 uppercase tracking-wider">FITUR OTOMATISASI API KOTA INDONESIA</span>
            </div>
            
            <div>
                <h3 class="text-lg font-bold">Cari & Pilih Kota di Indonesia (Autofill API)</h3>
                <p class="text-slate-300 text-xs sm:text-sm mt-0.5">Ketik nama kota (contoh: Surabaya, Makassar, Medan) untuk otomatis mengisi nama, provinsi, dan koordinat presisi.</p>
            </div>

            <!-- Search Select Box -->
            <div class="relative max-w-xl">
                <input type="text" 
                       x-model="searchQuery" 
                       @input.debounce.250ms="fetchApiCities()" 
                       @focus="dropdownOpen = true" 
                       placeholder="Ketik nama kota (misal: Surabaya, Denpasar, Medan)..." 
                       class="w-full px-4 py-3 rounded-2xl bg-white/10 border border-white/20 text-white placeholder-slate-400 text-xs sm:text-sm focus:outline-none focus:border-emerald-400 focus:bg-white/15 transition-all shadow-inner">
                
                <!-- API Search Results Dropdown -->
                <div x-show="dropdownOpen && apiResults.length > 0" 
                     @click.away="dropdownOpen = false" 
                     x-transition 
                     class="absolute left-0 right-0 top-full mt-2 bg-white text-slate-900 rounded-2xl shadow-xl border border-slate-200 overflow-hidden z-50 max-h-60 overflow-y-auto divide-y divide-slate-100">
                    <template x-for="item in apiResults" :key="item.name">
                        <button type="button" 
                                @click="selectCity(item)" 
                                class="w-full text-left p-3.5 hover:bg-emerald-50 transition-colors flex items-center justify-between text-xs sm:text-sm cursor-pointer">
                            <div>
                                <strong x-text="item.name" class="text-slate-900 block"></strong>
                                <span x-text="item.province" class="text-slate-500 text-xs"></span>
                            </div>
                            <span class="text-xs font-mono text-emerald-700 bg-emerald-50 px-2 py-1 rounded-md border border-emerald-100">
                                <span x-text="item.latitude"></span>, <span x-text="item.longitude"></span>
                            </span>
                        </button>
                    </template>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-8 shadow-xs">
            <form method="POST" action="{{ route('admin.cities.store') }}" class="space-y-6">
                @csrf

                <div class="grid sm:grid-cols-2 gap-6">
                    <!-- City Name -->
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Nama Kota / Kabupaten <span class="text-rose-500">*</span></label>
                        <input type="text" 
                               name="name" 
                               x-model="formData.name" 
                               required 
                               placeholder="Contoh: Kota Surabaya" 
                               class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-900 text-xs sm:text-sm focus:outline-none focus:border-emerald-600 focus:bg-white transition-all">
                        @error('name') <p class="text-xs text-rose-600 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    <!-- Province -->
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Provinsi</label>
                        <input type="text" 
                               name="province" 
                               x-model="formData.province" 
                               placeholder="Contoh: Jawa Timur" 
                               class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-900 text-xs sm:text-sm focus:outline-none focus:border-emerald-600 focus:bg-white transition-all">
                        @error('province') <p class="text-xs text-rose-600 font-semibold">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid sm:grid-cols-3 gap-6">
                    <!-- Country -->
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Negara <span class="text-rose-500">*</span></label>
                        <input type="text" 
                               name="country" 
                               x-model="formData.country" 
                               required 
                               class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-900 text-xs sm:text-sm focus:outline-none focus:border-emerald-600 focus:bg-white transition-all">
                        @error('country') <p class="text-xs text-rose-600 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    <!-- Latitude -->
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Latitude (Garis Lintang) <span class="text-rose-500">*</span></label>
                        <input type="number" 
                               step="any" 
                               name="latitude" 
                               x-model="formData.latitude" 
                               required 
                               placeholder="-7.2575" 
                               class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-900 font-mono text-xs sm:text-sm focus:outline-none focus:border-emerald-600 focus:bg-white transition-all">
                        @error('latitude') <p class="text-xs text-rose-600 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    <!-- Longitude -->
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Longitude (Garis Bujur) <span class="text-rose-500">*</span></label>
                        <input type="number" 
                               step="any" 
                               name="longitude" 
                               x-model="formData.longitude" 
                               required 
                               placeholder="112.7521" 
                               class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-900 font-mono text-xs sm:text-sm focus:outline-none focus:border-emerald-600 focus:bg-white transition-all">
                        @error('longitude') <p class="text-xs text-rose-600 font-semibold">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid sm:grid-cols-2 gap-6">
                    <!-- Timezone -->
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Zona Waktu (Timezone)</label>
                        <select name="timezone" x-model="formData.timezone" class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-900 text-xs sm:text-sm focus:outline-none focus:border-emerald-600 focus:bg-white transition-all">
                            <option value="Asia/Jakarta">WIB (Asia/Jakarta)</option>
                            <option value="Asia/Makassar">WITA (Asia/Makassar)</option>
                            <option value="Asia/Jayapura">WIT (Asia/Jayapura)</option>
                        </select>
                        @error('timezone') <p class="text-xs text-rose-600 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    <!-- Active Toggle -->
                    <div class="flex items-center pt-6">
                        <label class="relative inline-flex items-center cursor-pointer gap-3">
                            <input type="checkbox" name="is_active" value="1" x-model="formData.is_active" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                            <span class="text-xs sm:text-sm font-bold text-slate-800">Aktifkan Kota untuk Servis Publik</span>
                        </label>
                    </div>
                </div>

                <!-- Description -->
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Deskripsi Singkat Wilayah Kota</label>
                    <textarea name="description" 
                              rows="3" 
                              x-model="formData.description" 
                              placeholder="Deskripsi singkat mengenai wilayah kota ini..." 
                              class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-900 text-xs sm:text-sm focus:outline-none focus:border-emerald-600 focus:bg-white transition-all"></textarea>
                    @error('description') <p class="text-xs text-rose-600 font-semibold">{{ $message }}</p> @enderror
                </div>

                <!-- Form Submit Actions -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('admin.cities.index') }}" class="px-5 py-3 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs sm:text-sm transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs sm:text-sm transition-all shadow-md shadow-emerald-600/20">
                        Simpan Kota Baru
                    </button>
                </div>

            </form>
        </div>

    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('cityCreateForm', () => ({
                searchQuery: '',
                dropdownOpen: false,
                apiResults: [],
                formData: {
                    name: '{{ old('name') }}',
                    province: '{{ old('province') }}',
                    country: '{{ old('country', 'Indonesia') }}',
                    latitude: '{{ old('latitude') }}',
                    longitude: '{{ old('longitude') }}',
                    timezone: '{{ old('timezone', 'Asia/Jakarta') }}',
                    description: '{{ old('description') }}',
                    is_active: true
                },

                init() {
                    this.fetchApiCities();
                },

                async fetchApiCities() {
                    try {
                        const res = await fetch(`{{ route('admin.cities.api-search') }}?q=${encodeURIComponent(this.searchQuery)}`);
                        if (res.ok) {
                            this.apiResults = await res.json();
                        }
                    } catch (e) {
                        console.error('Error fetching API cities', e);
                    }
                },

                selectCity(item) {
                    this.formData.name = item.name;
                    this.formData.province = item.province;
                    this.formData.country = 'Indonesia';
                    this.formData.latitude = item.latitude;
                    this.formData.longitude = item.longitude;
                    this.formData.timezone = item.timezone || 'Asia/Jakarta';
                    this.formData.description = `Pusat perkotaan di Provinsi ${item.province} dengan integrasi data lingkungan dan mobilitas ramah lingkungan.`;
                    this.searchQuery = item.name;
                    this.dropdownOpen = false;
                }
            }));
        });
    </script>
</x-layouts.admin>
