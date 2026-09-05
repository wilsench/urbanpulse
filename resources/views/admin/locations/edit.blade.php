<x-layouts.admin title="Edit Lokasi — Admin UrbanPulse">
    <div class="max-w-2xl mx-auto space-y-6">
        <div class="pb-4 border-b border-slate-200 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-slate-900">Edit Lokasi: {{ $location->name }}</h1>
            <a href="{{ route('admin.locations.index') }}" class="text-sm text-slate-600 hover:underline">&larr; Kembali</a>
        </div>

        <form method="POST" action="{{ route('admin.locations.update', $location) }}" class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm space-y-4 text-sm">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-slate-700 font-bold mb-1">Nama Lokasi</label>
                <input type="text" name="name" value="{{ old('name', $location->name) }}" required class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-300 text-slate-900 focus:outline-none focus:border-amber-600 min-h-[44px]">
            </div>

            <div>
                <label class="block text-slate-700 font-bold mb-1">Deskripsi</label>
                <textarea name="description" rows="3" class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-300 text-slate-900 focus:outline-none focus:border-amber-600">{{ old('description', $location->description) }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-slate-700 font-bold mb-1">Kategori</label>
                    <select name="category" required class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-300 text-slate-900 min-h-[44px]">
                        <option value="park" {{ $location->category === 'park' ? 'selected' : '' }}>Taman (Park)</option>
                        <option value="green_space" {{ $location->category === 'green_space' ? 'selected' : '' }}>Ruang Terbuka Hijau</option>
                        <option value="public_area" {{ $location->category === 'public_area' ? 'selected' : '' }}>Area Publik</option>
                        <option value="transport" {{ $location->category === 'transport' ? 'selected' : '' }}>Hub Transportasi</option>
                        <option value="bike_friendly" {{ $location->category === 'bike_friendly' ? 'selected' : '' }}>Fasilitas Sepeda</option>
                    </select>
                </div>
                <div>
                    <label class="block text-slate-700 font-bold mb-1">Sumber Data</label>
                    <select name="source" required class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-300 text-slate-900 min-h-[44px]">
                        <option value="openstreetmap" {{ $location->source === 'openstreetmap' ? 'selected' : '' }}>OpenStreetMap</option>
                        <option value="curated" {{ $location->source === 'curated' ? 'selected' : '' }}>Curated / Internal</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-slate-700 font-bold mb-1">Latitude</label>
                    <input type="number" step="any" name="latitude" value="{{ old('latitude', $location->latitude) }}" required class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-300 text-slate-900 min-h-[44px]">
                </div>
                <div>
                    <label class="block text-slate-700 font-bold mb-1">Longitude</label>
                    <input type="number" step="any" name="longitude" value="{{ old('longitude', $location->longitude) }}" required class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-300 text-slate-900 min-h-[44px]">
                </div>
            </div>

            <div>
                <label class="block text-slate-700 font-bold mb-1">Alamat Lengkap</label>
                <input type="text" name="address" value="{{ old('address', $location->address) }}" class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-300 text-slate-900 min-h-[44px]">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-slate-700 font-bold mb-1">Skor Hijau (0-100)</label>
                    <input type="number" name="green_score" value="{{ old('green_score', $location->green_score) }}" min="0" max="100" required class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-300 text-slate-900 min-h-[44px]">
                </div>
                <div>
                    <label class="block text-slate-700 font-bold mb-1">Aksesibilitas (0-100)</label>
                    <input type="number" name="accessibility_score" value="{{ old('accessibility_score', $location->accessibility_score) }}" min="0" max="100" required class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-300 text-slate-900 min-h-[44px]">
                </div>
                <div>
                    <label class="block text-slate-700 font-bold mb-1">Jalan Kaki (0-100)</label>
                    <input type="number" name="walking_score" value="{{ old('walking_score', $location->walking_score) }}" min="0" max="100" required class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-300 text-slate-900 min-h-[44px]">
                </div>
            </div>

            <div class="flex items-center gap-2 pt-2">
                <input type="checkbox" name="bike_friendly" value="1" {{ $location->bike_friendly ? 'checked' : '' }} id="bf" class="rounded bg-slate-100 border-slate-300 text-amber-600">
                <label for="bf" class="text-slate-700 cursor-pointer font-bold">Ramah Sepeda (Bike Friendly)</label>
            </div>

            <button type="submit" class="w-full py-3.5 rounded-2xl bg-amber-600 hover:bg-amber-700 text-white font-bold text-base transition-all shadow-md shadow-amber-600/20 min-h-[48px]">
                Perbarui Data Lokasi
            </button>
        </form>
    </div>
</x-layouts.admin>
