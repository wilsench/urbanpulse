<!DOCTYPE html>
<html lang="id" class="h-full" x-data="{ textSize: localStorage.getItem('up_text_size') || 'base', highContrast: localStorage.getItem('up_contrast') === 'true' }" :class="{ 'high-contrast': highContrast, 'text-size-base': textSize === 'base', 'text-size-lg': textSize === 'lg', 'text-size-xl': textSize === 'xl' }" x-init="$watch('textSize', val => localStorage.setItem('up_text_size', val)); $watch('highContrast', val => localStorage.setItem('up_contrast', val))">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'UrbanPulse — Asisten Kota Berkelanjutan' }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen flex flex-col font-sans antialiased">

    <!-- Top Accessibility & Active City Selector Utility Bar -->
    <div class="bg-slate-900 text-slate-100 text-xs py-2 px-3 sm:px-4 border-b border-slate-800">
        <div class="max-w-7xl mx-auto flex flex-col xs:flex-row justify-between items-center gap-2">
            
            <!-- City Selector Form -->
            <div class="flex items-center gap-2 w-full xs:w-auto justify-between xs:justify-start">
                <form method="POST" action="{{ route('cities.select') }}" class="inline-flex items-center gap-1.5">
                    @csrf
                    <span class="text-xs text-slate-300 font-medium hidden sm:inline">Jelajahi Kota:</span>
                    <select name="city_slug" onchange="this.form.submit()" class="bg-slate-800 text-emerald-400 text-xs font-bold py-1 px-2.5 rounded-lg border border-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 cursor-pointer">
                        @foreach($allCities as $cityOption)
                            <option value="{{ $cityOption->slug }}" {{ isset($activeCity) && $activeCity->id === $cityOption->id ? 'selected' : '' }}>
                                📍 {{ $cityOption->name }} ({{ $cityOption->province }})
                            </option>
                        @endforeach
                    </select>
                </form>

                <span class="hidden md:inline text-slate-700">|</span>
                <span class="hidden md:inline text-slate-300">Pilihan Bijak untuk Kota Berkelanjutan (SDG 11 & 13)</span>
            </div>

            <!-- Accessibility Controls: Text Size & Contrast -->
            <div class="flex items-center gap-2 sm:gap-3">
                <div class="flex items-center gap-1 bg-slate-800 px-1.5 py-0.5 rounded-md border border-slate-700">
                    <span class="text-[11px] text-slate-400 mr-1 hidden sm:inline">Ukuran Teks:</span>
                    <button @click="textSize = 'base'" :class="textSize === 'base' ? 'bg-emerald-600 text-white font-bold' : 'text-slate-300 hover:text-white'" class="px-2 py-0.5 rounded text-xs transition-colors" title="Ukuran Standar (100%)">A</button>
                    <button @click="textSize = 'lg'" :class="textSize === 'lg' ? 'bg-emerald-600 text-white font-bold' : 'text-slate-300 hover:text-white'" class="px-2 py-0.5 rounded text-xs transition-colors" title="Ukuran Sedang (+12.5%)">A+</button>
                    <button @click="textSize = 'xl'" :class="textSize === 'xl' ? 'bg-emerald-600 text-white font-bold' : 'text-slate-300 hover:text-white'" class="px-2 py-0.5 rounded text-xs transition-colors" title="Ukuran Besar (+25%)">A++</button>
                </div>

                <button @click="highContrast = !highContrast" class="flex items-center gap-1 px-2 py-1 rounded-md bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 text-xs font-medium transition-colors" title="Beralih Kontras Tinggi">
                    <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v18m0-18a9 9 0 110 18 9 9 0 010-18z"></path></svg>
                    <span class="hidden sm:inline" x-text="highContrast ? 'Kontras Normal' : 'Tinggi Kontras'"></span>
                    <span class="sm:hidden" x-text="highContrast ? 'Normal' : 'Kontras'"></span>
                </button>

                <a href="{{ route('data-sources') }}" class="text-slate-300 hover:text-emerald-400 font-medium underline hidden sm:inline text-xs">
                    Transparansi Data
                </a>
            </div>
        </div>
    </div>

    <!-- Main Navigation Header -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-40 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 sm:h-20 flex items-center justify-between">
            <!-- Brand Logo -->
            <a href="{{ route('landing') }}" class="flex items-center gap-2.5 sm:gap-3 group min-h-[44px]">
                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold text-lg sm:text-xl shadow-md shadow-emerald-600/20 group-hover:scale-105 transition-transform flex-shrink-0">
                    🌱
                </div>
                <div>
                    <span class="font-bold text-lg sm:text-xl tracking-tight text-slate-900 leading-tight block">UrbanPulse</span>
                    <span class="block text-[10px] sm:text-xs font-medium text-emerald-700 leading-none">Asisten Kota Berkelanjutan</span>
                </div>
            </a>

            <!-- Primary Navigation Links -->
            <nav class="hidden lg:flex items-center gap-1 font-medium">
                <a href="{{ route('landing') }}" class="px-4 py-2.5 rounded-xl text-base transition-colors min-h-[44px] flex items-center {{ request()->routeIs('landing') ? 'bg-emerald-50 text-emerald-700 font-bold' : 'text-slate-700 hover:text-emerald-700 hover:bg-slate-100' }}">
                    Beranda
                </a>
                <a href="{{ route('city.dashboard') }}" class="px-4 py-2.5 rounded-xl text-base transition-colors min-h-[44px] flex items-center {{ request()->routeIs('city.dashboard') || request()->routeIs('map') ? 'bg-emerald-50 text-emerald-700 font-bold' : 'text-slate-700 hover:text-emerald-700 hover:bg-slate-100' }}">
                    Jelajahi Kota
                </a>
                <a href="{{ route('recommend.index') }}" class="px-4 py-2.5 rounded-xl text-base transition-colors min-h-[44px] flex items-center {{ request()->routeIs('recommend.*') ? 'bg-emerald-50 text-emerald-700 font-bold' : 'text-slate-700 hover:text-emerald-700 hover:bg-slate-100' }}">
                    Rekomendasi
                </a>
                <a href="{{ route('assistant.index') }}" class="px-4 py-2.5 rounded-xl text-base transition-colors min-h-[44px] flex items-center {{ request()->routeIs('assistant.*') ? 'bg-emerald-50 text-emerald-700 font-bold' : 'text-slate-700 hover:text-emerald-700 hover:bg-slate-100' }}">
                    <span class="inline-flex items-center gap-1.5">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                        Tanya UrbanPulse
                    </span>
                </a>
                <a href="{{ route('about') }}" class="px-4 py-2.5 rounded-xl text-base transition-colors min-h-[44px] flex items-center {{ request()->routeIs('about') ? 'bg-emerald-50 text-emerald-700 font-bold' : 'text-slate-700 hover:text-emerald-700 hover:bg-slate-100' }}">
                    Tentang
                </a>
            </nav>

            <!-- User Auth & Quick Actions -->
            <div class="flex items-center gap-2 sm:gap-3">
                @auth
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-2 sm:gap-2.5 px-3 sm:px-4 py-2 sm:py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 border border-slate-300 text-sm sm:text-base font-semibold text-slate-800 transition-colors min-h-[44px]">
                            <div class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-emerald-600 text-white flex items-center justify-center font-bold text-xs flex-shrink-0">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <span class="truncate max-w-[80px] xs:max-w-[120px] sm:max-w-none">{{ auth()->user()->name }}</span>
                            <span class="bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded-md text-xs font-bold hidden sm:inline-block">+{{ auth()->user()->eco_points }} pts</span>
                            <svg class="w-4 h-4 text-slate-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" @click.outside="open = false" x-transition class="absolute right-0 mt-2 w-56 sm:w-64 bg-white border border-slate-200 rounded-2xl shadow-xl py-2 z-50">
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2.5 text-sm sm:text-base text-amber-700 hover:bg-amber-50 font-bold">
                                    ⚡ Dashboard Admin
                                </a>
                                <div class="border-t border-slate-100 my-1"></div>
                            @endif
                            <a href="{{ route('dashboard') }}" class="block px-4 py-2.5 text-sm sm:text-base text-slate-700 hover:bg-slate-50 hover:text-emerald-700">
                                Dashboard Saya
                            </a>
                            <a href="{{ route('actions.index') }}" class="block px-4 py-2.5 text-sm sm:text-base text-slate-700 hover:bg-slate-50 hover:text-emerald-700">
                                Catat Aksi Hijau
                            </a>
                            <a href="{{ route('impact.index') }}" class="block px-4 py-2.5 text-sm sm:text-base text-slate-700 hover:bg-slate-50 hover:text-emerald-700">
                                Dampak Saya
                            </a>
                            <a href="{{ route('profile.show') }}" class="block px-4 py-2.5 text-sm sm:text-base text-slate-700 hover:bg-slate-50 hover:text-emerald-700">
                                Pengaturan Profil
                            </a>
                            <div class="border-t border-slate-100 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2.5 text-sm sm:text-base text-rose-600 hover:bg-rose-50 font-medium">
                                    Keluar (Logout)
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base font-semibold text-slate-700 hover:text-emerald-700 transition-colors min-h-[44px] flex items-center">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="px-3.5 sm:px-5 py-2 sm:py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm sm:text-base transition-all shadow-md shadow-emerald-600/20 min-h-[44px] flex items-center justify-center">
                        Mulai
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Flash Notifications -->
    @if(session('success'))
        <div class="bg-emerald-600 text-white px-4 py-3 text-center text-sm sm:text-base font-semibold shadow-inner">
            <div class="max-w-7xl mx-auto flex items-center justify-center gap-2">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('status'))
        <div class="bg-slate-800 text-white px-4 py-3 text-center text-sm sm:text-base">
            {{ session('status') }}
        </div>
    @endif

    <!-- Main Content Area -->
    <main class="flex-grow pb-24 lg:pb-12">
        {{ $slot }}
    </main>

    <!-- Mobile Bottom Navigation Bar (Large Touch Targets for Mobile Access) -->
    <nav class="lg:hidden fixed bottom-0 left-0 right-0 bg-white/95 backdrop-blur-md border-t border-slate-200 z-50 px-1 py-1.5 shadow-lg">
        <div class="grid grid-cols-5 gap-0.5 text-center">
            <a href="{{ route('landing') }}" class="flex flex-col items-center justify-center py-1.5 px-1 rounded-xl transition-colors min-h-[48px] {{ request()->routeIs('landing') ? 'bg-emerald-50 text-emerald-700 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                <span class="text-lg sm:text-xl">🏠</span>
                <span class="text-[10px] sm:text-[11px] mt-0.5 truncate w-full">Beranda</span>
            </a>
            <a href="{{ route('city.dashboard') }}" class="flex flex-col items-center justify-center py-1.5 px-1 rounded-xl transition-colors min-h-[48px] {{ request()->routeIs('city.dashboard') || request()->routeIs('map') ? 'bg-emerald-50 text-emerald-700 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                <span class="text-lg sm:text-xl">🗺️</span>
                <span class="text-[10px] sm:text-[11px] mt-0.5 truncate w-full">Jelajahi</span>
            </a>
            <a href="{{ route('recommend.index') }}" class="flex flex-col items-center justify-center py-1.5 px-1 rounded-xl transition-colors min-h-[48px] {{ request()->routeIs('recommend.*') ? 'bg-emerald-50 text-emerald-700 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                <span class="text-lg sm:text-xl">⭐</span>
                <span class="text-[10px] sm:text-[11px] mt-0.5 truncate w-full">Rekomendasi</span>
            </a>
            <a href="{{ auth()->check() ? route('impact.index') : route('login') }}" class="flex flex-col items-center justify-center py-1.5 px-1 rounded-xl transition-colors min-h-[48px] {{ request()->routeIs('impact.*') || request()->routeIs('dashboard') ? 'bg-emerald-50 text-emerald-700 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                <span class="text-lg sm:text-xl">🌱</span>
                <span class="text-[10px] sm:text-[11px] mt-0.5 truncate w-full">Dampak</span>
            </a>
            <a href="{{ route('assistant.index') }}" class="flex flex-col items-center justify-center py-1.5 px-1 rounded-xl transition-colors min-h-[48px] {{ request()->routeIs('assistant.*') ? 'bg-emerald-50 text-emerald-700 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                <span class="text-lg sm:text-xl">💬</span>
                <span class="text-[10px] sm:text-[11px] mt-0.5 truncate w-full">Tanya</span>
            </a>
        </div>
    </nav>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 text-slate-600 text-sm sm:text-base py-8 sm:py-12 mt-8 sm:mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 sm:gap-8 mb-8">
                <div class="space-y-3 sm:col-span-2 md:col-span-1">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-emerald-600 text-white flex items-center justify-center font-bold text-lg">🌱</div>
                        <span class="font-bold text-xl text-slate-900">UrbanPulse</span>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        Platform rekomendasi keputusan harian perkotaan berkelanjutan berbasis data cuaca, udara, dan lokasi terverifikasi.
                    </p>
                </div>

                <div>
                    <h4 class="font-bold text-slate-900 mb-2.5 sm:mb-3 text-sm sm:text-base">Menu Utama</h4>
                    <ul class="space-y-1.5 sm:space-y-2 text-xs sm:text-sm">
                        <li><a href="{{ route('landing') }}" class="hover:text-emerald-700 transition-colors">Beranda</a></li>
                        <li><a href="{{ route('city.dashboard') }}" class="hover:text-emerald-700 transition-colors">Jelajahi Kondisi Kota</a></li>
                        <li><a href="{{ route('recommend.index') }}" class="hover:text-emerald-700 transition-colors">Rekomendasi Tempat</a></li>
                        <li><a href="{{ route('assistant.index') }}" class="hover:text-emerald-700 transition-colors">Tanya UrbanPulse</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold text-slate-900 mb-2.5 sm:mb-3 text-sm sm:text-base">Aksi & Dampak</h4>
                    <ul class="space-y-1.5 sm:space-y-2 text-xs sm:text-sm">
                        <li><a href="{{ route('actions.index') }}" class="hover:text-emerald-700 transition-colors">Catat Aksi Hijau</a></li>
                        <li><a href="{{ route('impact.index') }}" class="hover:text-emerald-700 transition-colors">Dampak CO2 Saya</a></li>
                        <li><a href="{{ route('data-sources') }}" class="hover:text-emerald-700 transition-colors">Transparansi Data & Sumber</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold text-slate-900 mb-2.5 sm:mb-3 text-sm sm:text-base">Prinsip Data Terverifikasi</h4>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-2.5 sm:mb-3">
                        Data cuaca bersumber dari BMKG, kualitas udara dari Air Quality Open API, dan peta lokasi dari OpenStreetMap.
                    </p>
                    <a href="{{ route('data-sources') }}" class="inline-flex items-center gap-1.5 text-xs sm:text-sm text-emerald-700 font-bold hover:underline">
                        <span>Lihat Penjelasan Sumber Data</span> &rarr;
                    </a>
                </div>
            </div>

            <div class="border-t border-slate-200 pt-6 flex flex-col sm:flex-row justify-between items-center gap-3 text-xs sm:text-sm text-slate-500 text-center sm:text-left">
                <p>&copy; {{ date('Y') }} UrbanPulse Team — Infinitera 2.0 Web Development Competition.</p>
                <div class="flex items-center gap-3 sm:gap-4 font-semibold text-emerald-700 text-xs sm:text-sm">
                    <span>SDG 11: Kota Berkelanjutan</span>
                    <span>SDG 13: Penanganan Iklim</span>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
