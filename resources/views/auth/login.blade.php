<x-layouts.app title="Masuk — UrbanPulse">
    <div class="max-w-md mx-auto px-4 py-16">
        <div class="bg-white border border-slate-200 rounded-3xl p-8 shadow-sm space-y-6">
            <div class="text-center">
                <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white font-bold text-2xl mx-auto flex items-center justify-center mb-3 shadow-md shadow-emerald-600/20">🌱</div>
                <h1 class="text-2xl font-bold text-slate-900">Masuk ke UrbanPulse</h1>
                <p class="text-sm text-slate-600 mt-1">Akses pencatatan dampak CO2 avoided dan riwayat aktivitas hijau Anda.</p>
            </div>

            @if ($errors->any())
                <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm font-semibold">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4 text-sm">
                @csrf

                <div>
                    <label class="block text-slate-700 font-bold mb-1">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-300 text-slate-900 focus:outline-none focus:border-emerald-600 min-h-[44px]">
                </div>

                <div>
                    <label class="block text-slate-700 font-bold mb-1">Password</label>
                    <input type="password" name="password" required class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-300 text-slate-900 focus:outline-none focus:border-emerald-600 min-h-[44px]">
                </div>

                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2 text-slate-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded bg-slate-100 border-slate-300 text-emerald-600">
                        <span>Ingat Saya</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-3.5 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-base transition-all shadow-md shadow-emerald-600/20 min-h-[48px]">
                    Masuk Sekarang &rarr;
                </button>
            </form>

            <!-- Quick Demo Credentials -->
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-xs text-slate-700 space-y-1">
                <div class="text-emerald-800 font-bold">AKUN DEMO KOMPETISI:</div>
                <div>Demo User: <span class="font-bold text-slate-900">user@urbanpulse.id</span> / <span class="font-bold text-slate-900">password123</span></div>
                <div>Admin User: <span class="font-bold text-slate-900">admin@urbanpulse.id</span> / <span class="font-bold text-slate-900">password123</span></div>
            </div>

            <div class="text-center text-sm text-slate-600 pt-2 border-t border-slate-100">
                Belum memiliki akun? <a href="{{ route('register') }}" class="text-emerald-700 hover:underline font-bold">Daftar Akun Baru</a>
            </div>
        </div>
    </div>
</x-layouts.app>
