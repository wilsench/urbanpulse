<x-layouts.admin title="Edit Kota {{ $city->name }} — UrbanPulse CMS">
    <div class="max-w-4xl mx-auto space-y-6">

        <!-- Header Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-200/80">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <a href="{{ route('admin.cities.index') }}" class="text-xs font-bold text-emerald-700 hover:underline flex items-center gap-1">
                        <span>&larr; Kembali ke Daftar Kota</span>
                    </a>
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Edit Data Kota: {{ $city->name }}</h1>
                <p class="text-slate-600 text-xs sm:text-sm mt-1">Perbarui koordinat, status keaktifan, atau deskripsi informasi kota.</p>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-8 shadow-xs">
            <form method="POST" action="{{ route('admin.cities.update', $city) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid sm:grid-cols-2 gap-6">
                    <!-- City Name -->
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Nama Kota / Kabupaten <span class="text-rose-500">*</span></label>
                        <input type="text" 
                               name="name" 
                               value="{{ old('name', $city->name) }}" 
                               required 
                               class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-900 text-xs sm:text-sm focus:outline-none focus:border-emerald-600 focus:bg-white transition-all">
                        @error('name') <p class="text-xs text-rose-600 font-semibold">{{ $message }}</p> @errorEnd
                    </div>

                    <!-- Province -->
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Provinsi</label>
                        <input type="text" 
                               name="province" 
                               value="{{ old('province', $city->province) }}" 
                               class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-900 text-xs sm:text-sm focus:outline-none focus:border-emerald-600 focus:bg-white transition-all">
                        @error('province') <p class="text-xs text-rose-600 font-semibold">{{ $message }}</p> @errorEnd
                    </div>
                </div>

                <div class="grid sm:grid-cols-3 gap-6">
                    <!-- Country -->
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Negara <span class="text-rose-500">*</span></label>
                        <input type="text" 
                               name="country" 
                               value="{{ old('country', $city->country) }}" 
                               required 
                               class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-900 text-xs sm:text-sm focus:outline-none focus:border-emerald-600 focus:bg-white transition-all">
                        @error('country') <p class="text-xs text-rose-600 font-semibold">{{ $message }}</p> @errorEnd
                    </div>

                    <!-- Latitude -->
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Latitude <span class="text-rose-500">*</span></label>
                        <input type="number" 
                               step="any" 
                               name="latitude" 
                               value="{{ old('latitude', $city->latitude) }}" 
                               required 
                               class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-900 font-mono text-xs sm:text-sm focus:outline-none focus:border-emerald-600 focus:bg-white transition-all">
                        @error('latitude') <p class="text-xs text-rose-600 font-semibold">{{ $message }}</p> @errorEnd
                    </div>

                    <!-- Longitude -->
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Longitude <span class="text-rose-500">*</span></label>
                        <input type="number" 
                               step="any" 
                               name="longitude" 
                               value="{{ old('longitude', $city->longitude) }}" 
                               required 
                               class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-900 font-mono text-xs sm:text-sm focus:outline-none focus:border-emerald-600 focus:bg-white transition-all">
                        @error('longitude') <p class="text-xs text-rose-600 font-semibold">{{ $message }}</p> @errorEnd
                    </div>
                </div>

                <div class="grid sm:grid-cols-2 gap-6">
                    <!-- Timezone -->
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Zona Waktu (Timezone)</label>
                        <select name="timezone" class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-900 text-xs sm:text-sm focus:outline-none focus:border-emerald-600 focus:bg-white transition-all">
                            <option value="Asia/Jakarta" {{ old('timezone', $city->timezone) === 'Asia/Jakarta' ? 'selected' : '' }}>WIB (Asia/Jakarta)</option>
                            <option value="Asia/Makassar" {{ old('timezone', $city->timezone) === 'Asia/Makassar' ? 'selected' : '' }}>WITA (Asia/Makassar)</option>
                            <option value="Asia/Jayapura" {{ old('timezone', $city->timezone) === 'Asia/Jayapura' ? 'selected' : '' }}>WIT (Asia/Jayapura)</option>
                        </select>
                        @error('timezone') <p class="text-xs text-rose-600 font-semibold">{{ $message }}</p> @errorEnd
                    </div>

                    <!-- Active Toggle -->
                    <div class="flex items-center pt-6">
                        <label class="relative inline-flex items-center cursor-pointer gap-3">
                            <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ old('is_active', $city->is_active) ? 'checked' : '' }}>
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
                              class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-900 text-xs sm:text-sm focus:outline-none focus:border-emerald-600 focus:bg-white transition-all">{{ old('description', $city->description) }}</textarea>
                    @error('description') <p class="text-xs text-rose-600 font-semibold">{{ $message }}</p> @errorEnd
                </div>

                <!-- Form Submit Actions -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('admin.cities.index') }}" class="px-5 py-3 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs sm:text-sm transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs sm:text-sm transition-all shadow-md shadow-emerald-600/20">
                        Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>

    </div>
</x-layouts.admin>
