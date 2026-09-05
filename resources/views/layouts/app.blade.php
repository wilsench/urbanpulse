<!DOCTYPE html>
<html lang="id" class="h-full" x-data="{ textSize: localStorage.getItem('up_text_size') || 'base', highContrast: localStorage.getItem('up_contrast') === 'true', cityModalOpen: false, searchCityQuery: '' }" :class="{ 'high-contrast': highContrast, 'text-size-base': textSize === 'base', 'text-size-lg': textSize === 'lg', 'text-size-xl': textSize === 'xl' }" x-init="$watch('textSize', val => localStorage.setItem('up_text_size', val)); $watch('highContrast', val => localStorage.setItem('up_contrast', val))">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'UrbanPulse — Asisten Kota Berkelanjutan' }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Favicon & Icons -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen flex flex-col font-sans antialiased">

    <!-- Top Utility & Accessibility Bar (Formatter Settings & City Selector) -->
    <div class="bg-slate-900/95 text-slate-100 text-xs py-2 px-3 sm:px-6 border-b border-slate-800 backdrop-blur-xs relative z-30 top-utility-bar">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row justify-between items-center gap-2.5 sm:gap-4">
            
            <!-- City Selector & SDG Badge -->
            <div class="flex items-center justify-between sm:justify-start w-full sm:w-auto gap-3">
                <button type="button" 
                        @click="cityModalOpen = true" 
                        class="inline-flex items-center gap-2 bg-slate-800/90 hover:bg-slate-800 border border-slate-700/80 rounded-xl px-3 py-1.5 transition-all text-xs font-bold text-emerald-400 cursor-pointer shadow-xs focus:ring-2 focus:ring-emerald-500/50">
                    <svg class="w-3.5 h-3.5 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span class="text-[11px] text-slate-400 font-medium hidden md:inline">Kota:</span>
                    <span class="text-emerald-400 font-bold text-xs">{{ $activeCity->name ?? 'Pilih Kota' }}</span>
                    <svg class="w-3 h-3 text-slate-400 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>

                <span class="hidden lg:inline-block w-1 h-1 rounded-full bg-slate-700"></span>
                <span class="hidden lg:inline-flex items-center gap-1.5 text-[11px] text-slate-400 font-medium">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Kota & Iklim Berkelanjutan (SDG 11 & 13)</span>
                </span>
            </div>

            <!-- Accessibility & Display Formatter Controls -->
            <div class="flex items-center justify-between sm:justify-end w-full sm:w-auto gap-2 sm:gap-3 border-t sm:border-t-0 border-slate-800/80 pt-2 sm:pt-0">
                
                <!-- Text Size Formatter (Segmented Pill Control) -->
                <div class="flex items-center gap-1.5 bg-slate-800/90 border border-slate-700/80 rounded-xl p-1 shadow-xs">
                    <span class="text-[11px] text-slate-400 font-medium px-1.5 hidden md:inline-flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16"></path></svg>
                        Teks:
                    </span>
                    <div class="inline-flex items-center gap-0.5">
                        <button @click="textSize = 'base'" 
                                :class="textSize === 'base' ? 'bg-emerald-600 text-white font-bold shadow-xs' : 'text-slate-300 hover:text-white hover:bg-slate-700/50'" 
                                class="px-2.5 py-0.5 rounded-lg text-xs transition-all flex items-center justify-center min-w-[28px]" 
                                title="Ukuran Standar (100%)">
                            A
                        </button>
                        <button @click="textSize = 'lg'" 
                                :class="textSize === 'lg' ? 'bg-emerald-600 text-white font-bold shadow-xs' : 'text-slate-300 hover:text-white hover:bg-slate-700/50'" 
                                class="px-2.5 py-0.5 rounded-lg text-xs transition-all flex items-center justify-center min-w-[32px]" 
                                title="Ukuran Sedang (+12.5%)">
                            A+
                        </button>
                        <button @click="textSize = 'xl'" 
                                :class="textSize === 'xl' ? 'bg-emerald-600 text-white font-bold shadow-xs' : 'text-slate-300 hover:text-white hover:bg-slate-700/50'" 
                                class="px-2.5 py-0.5 rounded-lg text-xs transition-all flex items-center justify-center min-w-[36px]" 
                                title="Ukuran Besar (+25%)">
                            A++
                        </button>
                    </div>
                </div>

                <!-- High Contrast Toggle Button -->
                <button @click="highContrast = !highContrast" 
                        :class="highContrast ? 'bg-amber-500/20 text-amber-300 border-amber-500/50 font-bold' : 'bg-slate-800/90 hover:bg-slate-800 text-slate-300 hover:text-white border-slate-700/80'"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border text-xs font-medium transition-all shadow-xs shrink-0 cursor-pointer" 
                        title="Beralih Mode Kontras Tinggi">
                    <svg class="w-3.5 h-3.5 text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v18m0-18a9 9 0 110 18 9 9 0 010-18z"></path>
                    </svg>
                    <span x-text="highContrast ? 'Kontras Tinggi' : 'Kontras'"></span>
                </button>

                <!-- Data Transparency Link -->
                <a href="{{ route('data-sources') }}" class="hidden sm:inline-flex items-center gap-1 text-[11px] text-slate-400 hover:text-emerald-400 font-medium px-2 py-1 rounded-lg hover:bg-slate-800/60 transition-colors">
                    <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Sumber Data</span>
                </a>

            </div>
        </div>
    </div>

    <!-- Main Navigation Header -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-40 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 sm:h-20 flex items-center justify-between">
            <!-- Brand Logo -->
            <a href="{{ route('landing') }}" class="flex items-center gap-2.5 sm:gap-3 group min-h-[44px]">
                <x-logo size="md" />
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
    <nav class="lg:hidden fixed bottom-0 left-0 right-0 bg-white/95 backdrop-blur-md border-t border-slate-200 z-50 px-1 py-1.5 pb-[calc(0.375rem+env(safe-area-inset-bottom))] shadow-lg">
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
    <footer class="bg-white border-t border-slate-200 text-slate-600 text-sm sm:text-base pt-8 sm:pt-12 pb-28 lg:pb-12 mt-8 sm:mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 sm:gap-8 mb-8">
                <div class="space-y-3 sm:col-span-2 md:col-span-1">
                    <div class="flex items-center gap-2">
                        <x-logo size="sm" />
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

    <!-- Public City Selection Searchable Modal -->
    <div x-show="cityModalOpen" 
         x-transition:enter="transition ease-out duration-200" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="transition ease-in duration-150" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0" 
         class="fixed inset-0 bg-slate-900/70 backdrop-blur-xs z-50 flex items-center justify-center p-4" 
         @keydown.escape.window="cityModalOpen = false" 
         x-cloak>
        
        <div @click.away="cityModalOpen = false" 
             class="bg-white rounded-3xl shadow-2xl border border-slate-200/90 max-w-md w-full overflow-hidden p-5 sm:p-6 space-y-4">
            
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <h3 class="font-bold text-slate-900 text-base sm:text-lg">Pilih Kota Layanan</h3>
                </div>
                <button type="button" @click="cityModalOpen = false" class="p-1.5 rounded-xl bg-slate-100 text-slate-500 hover:text-slate-900 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Search Input -->
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" 
                       x-model="searchCityQuery" 
                       placeholder="Cari nama kota atau provinsi..." 
                       class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs sm:text-sm focus:outline-none focus:border-emerald-600 focus:bg-white transition-all shadow-xs">
            </div>

            <!-- Cities Options List -->
            <div class="max-h-72 overflow-y-auto space-y-2 pr-1">
                @foreach($allCities as $cityOpt)
                    <form method="POST" action="{{ route('cities.select') }}">
                        @csrf
                        <input type="hidden" name="city_slug" value="{{ $cityOpt->slug }}">
                        <button type="submit" 
                                x-show="!searchCityQuery || '{{ strtolower($cityOpt->name . ' ' . $cityOpt->province) }}'.includes(searchCityQuery.toLowerCase())"
                                class="w-full text-left p-3.5 rounded-2xl border transition-all cursor-pointer flex items-center justify-between {{ isset($activeCity) && $activeCity->id === $cityOpt->id ? 'border-emerald-600 bg-emerald-50/60 shadow-xs' : 'border-slate-200 hover:border-emerald-500 hover:bg-slate-50' }}">
                            <div>
                                <div class="font-bold text-slate-900 text-sm flex items-center gap-2">
                                    <span>{{ $cityOpt->name }}</span>
                                    @if(isset($activeCity) && $activeCity->id === $cityOpt->id)
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-600 text-white uppercase">AKTIF</span>
                                    @endif
                                </div>
                                <div class="text-xs text-slate-500 mt-0.5">{{ $cityOpt->province ?? 'Indonesia' }}</div>
                            </div>
                            <div class="text-xs font-bold text-emerald-700 bg-white px-2.5 py-1 rounded-xl border border-slate-200 shadow-2xs">
                                Pilih &rarr;
                            </div>
                        </button>
                    </form>
                @endforeach
            </div>

        </div>
    </div>

</body>
</html>
