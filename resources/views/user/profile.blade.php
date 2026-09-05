<x-layouts.app title="Pengaturan Profil — UrbanPulse">
    <div class="max-w-xl mx-auto px-4 py-6 sm:py-12">
        <div class="bg-white border border-slate-200 rounded-3xl p-5 sm:p-8 shadow-sm space-y-4 sm:space-y-6">
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900">Pengaturan Profil Pengguna</h1>

            <form method="POST" action="{{ route('profile.update') }}" class="space-y-4 text-xs sm:text-sm">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-slate-700 font-bold mb-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-300 text-slate-900 focus:outline-none focus:border-emerald-600 min-h-[44px]">
                </div>

                <div>
                    <label class="block text-slate-700 font-bold mb-1">Email Address</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-300 text-slate-900 focus:outline-none focus:border-emerald-600 min-h-[44px]">
                </div>

                <div class="border-t border-slate-100 pt-4">
                    <label class="block text-slate-700 font-bold mb-1">Password Baru (Opsional)</label>
                    <input type="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah" class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-300 text-slate-900 focus:outline-none focus:border-emerald-600 min-h-[44px]">
                </div>

                <div>
                    <label class="block text-slate-700 font-bold mb-1">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" placeholder="Ulangi password baru" class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-300 text-slate-900 focus:outline-none focus:border-emerald-600 min-h-[44px]">
                </div>

                <button type="submit" class="w-full py-3.5 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm sm:text-base transition-all shadow-md shadow-emerald-600/20 min-h-[48px]">
                    Simpan Perubahan Profil
                </button>
            </form>
        </div>
    </div>
</x-layouts.app>
