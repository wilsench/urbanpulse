<x-layouts.admin title="Edit Lokasi: {{ $location->name }} — UrbanPulse CMS">
    <div class="max-w-4xl mx-auto space-y-6">

        <!-- Header Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-200/80">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <a href="{{ route('admin.locations.index') }}" class="text-xs font-bold text-emerald-700 hover:underline flex items-center gap-1">
                        <span>&larr; Kembali ke Daftar Lokasi</span>
                    </a>
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Edit Data Lokasi: {{ $location->name }}</h1>
                <p class="text-slate-600 text-xs sm:text-sm mt-1">Perbarui informasi tempat, skor keberlanjutan lingkungan, koordinat, dan status verifikasi.</p>
            </div>
            
            <div class="flex items-center gap-2">
                @if($location->slug)
                    <a href="{{ route('locations.show', $location->slug) }}" target="_blank" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition-colors flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                        <span>Lihat di Web Publik</span>
                    </a>
                @endif
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-8 shadow-xs">
            <form method="POST" action="{{ route('admin.locations.update', $location) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <input type="hidden" name="source_id" value="{{ old('source_id', $location->source_id) }}">

                <!-- Section: Informasi Dasar -->
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 pb-2 border-b border-slate-100">
                        1. Informasi Tempat & Wilayah
                    </h3>
                    
                    <div class="grid sm:grid-cols-2 gap-6">
                        <!-- Location Name -->
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Nama Lokasi <span class="text-rose-500">*</span></label>
                            <input type="text" 
                                   name="name" 
                                   value="{{ old('name', $location->name) }}" 
                                   required 
                                   placeholder="Contoh: Taman Suropati"
                                   class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-900 text-xs sm:text-sm focus:outline-none focus:border-emerald-600 focus:bg-white transition-all">
                            @error('name') <p class="text-xs text-rose-600 font-semibold">{{ $message }}</p> @enderror
                        </div>

                        <!-- City Selection -->
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Kota / Wilayah <span class="text-rose-500">*</span></label>
                            <select name="city_id" required class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-900 text-xs sm:text-sm focus:outline-none focus:border-emerald-600 focus:bg-white transition-all">
                                <option value="">-- Pilih Kota / Kabupaten --</option>
                                @foreach($cities as $c)
                                    <option value="{{ $c->id }}" {{ old('city_id', $location->city_id) == $c->id ? 'selected' : '' }}>
                                        {{ $c->name }} ({{ $c->province ?? 'Indonesia' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('city_id') <p class="text-xs text-rose-600 font-semibold">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid sm:grid-cols-2 gap-6 mt-6">
                        <!-- Category -->
                        @php
                            $defaultCategories = [
                                'park' => 'Taman (Park)',
                                'green_space' => 'Ruang Terbuka Hijau (Green Space)',
                                'public_area' => 'Area Publik / Alun-alun',
                                'transport' => 'Hub Transportasi Ramah Lingkungan',
                                'bike_friendly' => 'Jalur & Fasilitas Sepeda',
                            ];
                            $currentCategory = old('category', $location->category);
                        @endphp
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Kategori Lokasi <span class="text-rose-500">*</span></label>
                            <select name="category" required class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-900 text-xs sm:text-sm focus:outline-none focus:border-emerald-600 focus:bg-white transition-all">
                                @foreach($defaultCategories as $val => $label)
                                    <option value="{{ $val }}" {{ $currentCategory === $val ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                                @if(!array_key_exists($currentCategory, $defaultCategories) && !empty($currentCategory))
                                    <option value="{{ $currentCategory }}" selected>{{ ucfirst($currentCategory) }} (Kustom)</option>
                                @endif
                            </select>
                            @error('category') <p class="text-xs text-rose-600 font-semibold">{{ $message }}</p> @enderror
                        </div>

                        <!-- Source -->
                        @php
                            $defaultSources = [
                                'openstreetmap' => 'OpenStreetMap (OSM)',
                                'curated' => 'Kurasi Internal UrbanPulse',
                                'manual' => 'Input Manual CMS',
                            ];
                            $currentSource = old('source', $location->source);
                        @endphp
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Sumber Data <span class="text-rose-500">*</span></label>
                            <select name="source" required class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-900 text-xs sm:text-sm focus:outline-none focus:border-emerald-600 focus:bg-white transition-all">
                                @foreach($defaultSources as $sVal => $sLabel)
                                    <option value="{{ $sVal }}" {{ $currentSource === $sVal ? 'selected' : '' }}>{{ $sLabel }}</option>
                                @endforeach
                                @if(!array_key_exists($currentSource, $defaultSources) && !empty($currentSource))
                                    <option value="{{ $currentSource }}" selected>{{ ucfirst($currentSource) }}</option>
                                @endif
                            </select>
                            @error('source') <p class="text-xs text-rose-600 font-semibold">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="space-y-2 mt-6">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Deskripsi Tempat</label>
                        <textarea name="description" 
                                  rows="3" 
                                  placeholder="Deskripsi singkat mengenai lokasi, fasilitas, atau daya tarik..."
                                  class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-900 text-xs sm:text-sm focus:outline-none focus:border-emerald-600 focus:bg-white transition-all">{{ old('description', $location->description) }}</textarea>
                        @error('description') <p class="text-xs text-rose-600 font-semibold">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Section: Geografis & Alamat -->
                <div class="pt-4">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 pb-2 border-b border-slate-100">
                        2. Koordinat Geografis & Alamat
                    </h3>

                    <div class="grid sm:grid-cols-2 gap-6">
                        <!-- Latitude -->
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Latitude <span class="text-rose-500">*</span></label>
                            <input type="number" 
                                   step="any" 
                                   name="latitude" 
                                   value="{{ old('latitude', $location->latitude) }}" 
                                   required 
                                   placeholder="-6.5971"
                                   class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-900 font-mono text-xs sm:text-sm focus:outline-none focus:border-emerald-600 focus:bg-white transition-all">
                            @error('latitude') <p class="text-xs text-rose-600 font-semibold">{{ $message }}</p> @enderror
                        </div>

                        <!-- Longitude -->
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Longitude <span class="text-rose-500">*</span></label>
                            <input type="number" 
                                   step="any" 
                                   name="longitude" 
                                   value="{{ old('longitude', $location->longitude) }}" 
                                   required 
                                   placeholder="106.7949"
                                   class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-900 font-mono text-xs sm:text-sm focus:outline-none focus:border-emerald-600 focus:bg-white transition-all">
                            @error('longitude') <p class="text-xs text-rose-600 font-semibold">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="space-y-2 mt-6">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Alamat Lengkap</label>
                        <input type="text" 
                               name="address" 
                               value="{{ old('address', $location->address) }}" 
                               placeholder="Contoh: Jl. Ir. H. Juanda No.13, Paledang, Kecamatan Bogor Tengah"
                               class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-900 text-xs sm:text-sm focus:outline-none focus:border-emerald-600 focus:bg-white transition-all">
                        @error('address') <p class="text-xs text-rose-600 font-semibold">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Section: Metrik Keberlanjutan & Skor -->
                <div class="pt-4">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 pb-2 border-b border-slate-100">
                        3. Metrik Skor Lingkungan & Aksesibilitas
                    </h3>

                    <div class="grid sm:grid-cols-3 gap-6">
                        <!-- Green Score -->
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Skor Hijau (0-100) <span class="text-rose-500">*</span></label>
                            <input type="number" 
                                   min="0" 
                                   max="100" 
                                   name="green_score" 
                                   value="{{ old('green_score', $location->green_score) }}" 
                                   required 
                                   class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-900 font-mono text-xs sm:text-sm focus:outline-none focus:border-emerald-600 focus:bg-white transition-all">
                            @error('green_score') <p class="text-xs text-rose-600 font-semibold">{{ $message }}</p> @enderror
                        </div>

                        <!-- Accessibility Score -->
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Aksesibilitas (0-100) <span class="text-rose-500">*</span></label>
                            <input type="number" 
                                   min="0" 
                                   max="100" 
                                   name="accessibility_score" 
                                   value="{{ old('accessibility_score', $location->accessibility_score) }}" 
                                   required 
                                   class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-900 font-mono text-xs sm:text-sm focus:outline-none focus:border-emerald-600 focus:bg-white transition-all">
                            @error('accessibility_score') <p class="text-xs text-rose-600 font-semibold">{{ $message }}</p> @enderror
                        </div>

                        <!-- Walking Score -->
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Jalan Kaki (0-100) <span class="text-rose-500">*</span></label>
                            <input type="number" 
                                   min="0" 
                                   max="100" 
                                   name="walking_score" 
                                   value="{{ old('walking_score', $location->walking_score) }}" 
                                   required 
                                   class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-900 font-mono text-xs sm:text-sm focus:outline-none focus:border-emerald-600 focus:bg-white transition-all">
                            @error('walking_score') <p class="text-xs text-rose-600 font-semibold">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Section: Status & Pengaturan -->
                <div class="pt-4">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 pb-2 border-b border-slate-100">
                        4. Status & Opsi Fasilitas
                    </h3>

                    <div class="grid sm:grid-cols-3 gap-4">
                        <!-- Bike Friendly -->
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 flex items-center">
                            <label class="relative inline-flex items-center cursor-pointer gap-3 w-full">
                                <input type="checkbox" name="bike_friendly" value="1" class="sr-only peer" {{ old('bike_friendly', $location->bike_friendly) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                                <span class="text-xs sm:text-sm font-semibold text-slate-800">Ramah Sepeda</span>
                            </label>
                        </div>

                        <!-- Active Status -->
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 flex items-center">
                            <label class="relative inline-flex items-center cursor-pointer gap-3 w-full">
                                <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ old('is_active', $location->is_active ?? true) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                                <span class="text-xs sm:text-sm font-semibold text-slate-800">Lokasi Aktif</span>
                            </label>
                        </div>

                        <!-- Verified Status -->
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 flex items-center">
                            <label class="relative inline-flex items-center cursor-pointer gap-3 w-full">
                                <input type="checkbox" name="is_verified" value="1" class="sr-only peer" {{ old('is_verified', $location->is_verified ?? true) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                                <span class="text-xs sm:text-sm font-semibold text-slate-800">Terverifikasi</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Form Submit Actions -->
                <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                    <a href="{{ route('admin.locations.index') }}" class="px-5 py-3 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs sm:text-sm transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs sm:text-sm transition-all shadow-md shadow-emerald-600/20">
                        Simpan Perubahan Lokasi
                    </button>
                </div>

            </form>
        </div>

    </div>
</x-layouts.admin>
