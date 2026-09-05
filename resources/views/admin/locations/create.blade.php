<x-layouts.admin title="Tambah Lokasi — Admin UrbanPulse">
    <div class="max-w-2xl mx-auto space-y-6">
        <div class="pb-4 border-b border-slate-200/80 flex justify-between items-center">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Tambah Lokasi Baru</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Input data lokasi publik manual ke database UrbanPulse.</p>
            </div>
            <a href="{{ route('admin.locations.index') }}" class="text-xs text-slate-500 hover:text-slate-800 font-semibold transition-colors flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>Kembali</span>
            </a>
        </div>

        <form method="POST" action="{{ route('admin.locations.store') }}" class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-4 text-xs sm:text-sm">
            @csrf

            <div>
                <label class="block text-slate-700 font-semibold mb-1">Nama Lokasi</label>
                <input type="text" name="name" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 focus:outline-none focus:border-emerald-600 min-h-[40px]">
            </div>

            <div>
                <label class="block text-slate-700 font-semibold mb-1">Deskripsi</label>
                <textarea name="description" rows="3" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 focus:outline-none focus:border-emerald-600"></textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-slate-700 font-semibold mb-1">Kategori</label>
                    <select name="category" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 min-h-[40px] focus:border-emerald-600">
                        <option value="park">Taman (Park)</option>
                        <option value="green_space">Ruang Terbuka Hijau</option>
                        <option value="public_area">Area Publik</option>
                        <option value="transport">Hub Transportasi</option>
                        <option value="bike_friendly">Fasilitas Sepeda</option>
                    </select>
                </div>
                <div>
                    <label class="block text-slate-700 font-semibold mb-1">Sumber Data</label>
                    <select name="source" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 min-h-[40px] focus:border-emerald-600">
                        <option value="openstreetmap">OpenStreetMap</option>
                        <option value="curated">Curated / Internal</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-slate-700 font-semibold mb-1">Latitude</label>
                    <input type="number" step="any" name="latitude" value="-6.5971" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 min-h-[40px]">
                </div>
                <div>
                    <label class="block text-slate-700 font-semibold mb-1">Longitude</label>
                    <input type="number" step="any" name="longitude" value="106.7949" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 min-h-[40px]">
                </div>
            </div>

            <div>
                <label class="block text-slate-700 font-semibold mb-1">Alamat Lengkap</label>
                <input type="text" name="address" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 min-h-[40px]">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-slate-700 font-semibold mb-1">Skor Hijau (0-100)</label>
                    <input type="number" name="green_score" value="85" min="0" max="100" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 min-h-[40px]">
                </div>
                <div>
                    <label class="block text-slate-700 font-semibold mb-1">Aksesibilitas (0-100)</label>
                    <input type="number" name="accessibility_score" value="85" min="0" max="100" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 min-h-[40px]">
                </div>
                <div>
                    <label class="block text-slate-700 font-semibold mb-1">Jalan Kaki (0-100)</label>
                    <input type="number" name="walking_score" value="85" min="0" max="100" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 min-h-[40px]">
                </div>
            </div>

            <div class="flex items-center gap-2 pt-2">
                <input type="checkbox" name="bike_friendly" value="1" checked id="bf" class="rounded bg-slate-100 border-slate-300 text-emerald-600 focus:ring-emerald-500">
                <label for="bf" class="text-slate-700 cursor-pointer font-semibold">Ramah Sepeda (Bike Friendly)</label>
            </div>

            <button type="submit" class="w-full py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs sm:text-sm transition-all shadow-xs min-h-[44px]">
                Simpan Lokasi Baru
            </button>
        </form>
    </div>
</x-layouts.admin>
