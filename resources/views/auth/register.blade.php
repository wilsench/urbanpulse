<x-layouts.app title="Daftar Akun — UrbanPulse">
    <div class="max-w-md mx-auto px-4 py-16">
        <div class="bg-white border border-slate-200 rounded-3xl p-8 shadow-sm space-y-6">
            <div class="text-center">
                <x-logo size="lg" class="mx-auto mb-3" />
                <h1 class="text-2xl font-bold text-slate-900">Daftar Akun UrbanPulse</h1>
                <p class="text-sm text-slate-600 mt-1">Dapatkan 50 Poin Hijau bonus pendaftaran awal.</p>
            </div>

            @if ($errors->any())
                <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm font-semibold">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-4 text-sm">
                @csrf

                <div>
                    <label class="block text-slate-700 font-bold mb-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" required autofocus class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-300 text-slate-900 focus:outline-none focus:border-emerald-600 min-h-[44px]">
                </div>

                <div>
                    <label class="block text-slate-700 font-bold mb-1">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-300 text-slate-900 focus:outline-none focus:border-emerald-600 min-h-[44px]">
                </div>

                <div>
                    <label class="block text-slate-700 font-bold mb-1">Password</label>
                    <input type="password" name="password" required class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-300 text-slate-900 focus:outline-none focus:border-emerald-600 min-h-[44px]">
                </div>

                <div>
                    <label class="block text-slate-700 font-bold mb-1">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" required class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-300 text-slate-900 focus:outline-none focus:border-emerald-600 min-h-[44px]">
                </div>

                <button type="submit" class="w-full py-3.5 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-base transition-all shadow-md shadow-emerald-600/20 min-h-[48px]">
                    Daftar Akun Baru &rarr;
                </button>
            </form>

            <div class="text-center text-sm text-slate-600 pt-2 border-t border-slate-100">
                Sudah memiliki akun? <a href="{{ route('login') }}" class="text-emerald-700 hover:underline font-bold">Masuk di Sini</a>
            </div>
        </div>
    </div>
</x-layouts.app>
