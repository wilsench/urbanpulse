<!DOCTYPE html>
<html lang="id" class="h-full" 
      x-data="{ 
          textSize: localStorage.getItem('up_text_size') || 'base', 
          highContrast: localStorage.getItem('up_contrast') === 'true', 
          cityModalOpen: false, 
          searchCityQuery: '' 
      }" 
      x-init="
          $watch('textSize', val => { 
              localStorage.setItem('up_text_size', val);
              document.documentElement.classList.remove('text-size-base', 'text-size-lg', 'text-size-xl');
              document.documentElement.classList.add('text-size-' + val);
          }); 
          $watch('highContrast', val => { 
              localStorage.setItem('up_contrast', val);
              document.documentElement.classList.toggle('high-contrast', val);
          });
      ">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'UrbanPulse — Asisten Kota Berkelanjutan' }}</title>
    
    <!-- Script Anti-FOUC Tema & Aksesibilitas (Dijalankan SEBELUM Alpine & Body Render) -->
    <script>
        try {
            const savedTextSize = localStorage.getItem('up_text_size') || 'base';
            const isHighContrast = localStorage.getItem('up_contrast') === 'true';
            document.documentElement.classList.add('text-size-' + savedTextSize);
            if (isHighContrast) document.documentElement.classList.add('high-contrast');
        } catch (e) {}
    </script>

    <!-- Pengaman Anti-Flicker Alpine.js (Modal & Kontrol Aksesibilitas) -->
    <style>
        [x-cloak] { display: none !important; }
    </style>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen flex flex-col font-sans antialiased w-full">

    <!-- Top Utility & Accessibility Bar (System Layer) -->
    <div class="bg-slate-950 text-slate-400 text-xs py-2 relative z-30 w-full shadow-inner">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full flex flex-col sm:flex-row justify-between items-center gap-3">
            
            <!-- City Selector & System Status -->
            <div class="flex items-center justify-between sm:justify-start w-full sm:w-auto gap-4 shrink-0">
                <button type="button" @click="cityModalOpen = true" class="group inline-flex items-center gap-2 bg-slate-900 hover:bg-slate-800 border border-slate-800 rounded-full px-4 py-1.5 transition-colors cursor-pointer focus:outline-none focus:ring-2 focus:ring-emerald-500/50">
                    <svg class="w-3.5 h-3.5 text-emerald-500 group-hover:animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span class="font-medium hidden md:inline text-slate-500">Lokasi:</span>
                    <span class="text-emerald-400 font-semibold">{{ $activeCity->name ?? 'Pilih Kota' }}</span>
                </button>

                <!-- Clean Status Indicator -->
                <div class="hidden lg:flex items-center gap-2 text-[11px] font-medium tracking-wide">
                    <span class="relative flex h-2 w-2">
                    </span>
                </div>
            </div>

            <!-- Accessibility Controls -->
            <!-- x-cloak DITAMBAHKAN di sini: grup ini disembunyikan sampai Alpine.js selesai
                 menghitung state textSize/highContrast yang benar, sehingga tombol A/A+/A++
                 dan Kontras tidak sempat tampil dalam kondisi "netral" lalu tiba-tiba
                 berubah ke kondisi aktif (yang terlihat seperti berkedip/seolah baru diklik). -->
            <div x-cloak class="flex items-center justify-between sm:justify-end w-full sm:w-auto gap-3 border-t sm:border-t-0 border-slate-800/80 pt-2 sm:pt-0 shrink-0">
                
                <!-- Text Size Pill -->
                <div class="flex items-center bg-slate-900 border border-slate-800 rounded-full p-0.5">
                    <button type="button" @click="textSize = 'base'" :class="textSize === 'base' ? 'bg-emerald-600/20 text-emerald-400 font-bold' : 'hover:text-slate-200'" class="px-3 py-1 rounded-full text-xs transition-colors min-w-[32px]">A</button>
                    <button type="button" @click="textSize = 'lg'" :class="textSize === 'lg' ? 'bg-emerald-600/20 text-emerald-400 font-bold' : 'hover:text-slate-200'" class="px-3 py-1 rounded-full text-xs transition-colors min-w-[32px]">A+</button>
                    <button type="button" @click="textSize = 'xl'" :class="textSize === 'xl' ? 'bg-emerald-600/20 text-emerald-400 font-bold' : 'hover:text-slate-200'" class="px-3 py-1 rounded-full text-xs transition-colors min-w-[36px]">A++</button>
                </div>

                <!-- High Contrast Toggle -->
                <button type="button" @click="highContrast = !highContrast" :class="highContrast ? 'text-amber-400 bg-amber-400/10 border-amber-400/30' : 'hover:text-slate-200 hover:bg-slate-900 border-transparent'" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full border transition-colors cursor-pointer focus:outline-none">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v18m0-18a9 9 0 110 18 9 9 0 010-18z"></path></svg>
                    <span x-text="highContrast ? 'Kontras Tinggi' : 'Kontras'">Kontras</span>
                </button>

                <a href="{{ route('data-sources') }}" class="hidden sm:inline-flex items-center gap-1.5 text-[11px] hover:text-emerald-400 font-medium px-2 py-1 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 018 0z"></path></svg>
                    <span>Sumber Data</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Connecting Accent Line -->
    <div class="h-[2px] w-full bg-gradient-to-r from-slate-200 via-emerald-400 to-slate-200 relative z-40"></div>

    <!-- Main Navigation Header -->
    <header class="bg-white/95 backdrop-blur-md border-b border-slate-200 sticky top-0 z-40 w-full shadow-sm shadow-slate-200/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 sm:h-20 flex items-center justify-between w-full gap-4 lg:gap-8">
            
            <!-- Brand Logo -->
            <a href="{{ route('landing') }}" class="flex items-center gap-3 group shrink-0 py-2">
                <x-logo size="md" />
                <div class="flex flex-col justify-center">
                    <div class="font-bold text-xl sm:text-2xl tracking-tighter leading-none flex items-center">
                        <span class="text-slate-900 group-hover:text-slate-700 transition-colors">Urban</span>
                        <span class="text-emerald-600 relative">
                            Pulse
                            <svg class="absolute -top-1 -right-2.5 w-2 h-2 text-emerald-400 animate-ping" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle></svg>
                        </span>
                    </div>
                    <span class="text-[9px] sm:text-[10px] font-medium text-slate-500 uppercase tracking-widest mt-1 leading-none">Asisten Kota Berkelanjutan</span>
                </div>
            </a>

            <!-- Primary Navigation Links -->
            <nav class="hidden lg:flex flex-1 justify-center items-stretch h-full gap-2 xl:gap-6 font-medium">
                <a href="{{ route('landing') }}" class="relative h-full flex items-center px-2 xl:px-3 text-sm xl:text-base transition-colors group {{ request()->routeIs('landing') ? 'text-slate-900 font-semibold' : 'text-slate-500 hover:text-slate-900' }}">
                    <span>Beranda</span>
                    <span class="absolute bottom-[-1px] left-0 w-full h-[2px] bg-emerald-500 rounded-t-full transition-transform origin-left {{ request()->routeIs('landing') ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-100 opacity-30' }}"></span>
                </a>
                <a href="{{ route('city.dashboard') }}" class="relative h-full flex items-center px-2 xl:px-3 text-sm xl:text-base transition-colors group {{ request()->routeIs('city.dashboard') || request()->routeIs('map') ? 'text-slate-900 font-semibold' : 'text-slate-500 hover:text-slate-900' }}">
                    <span>Jelajahi Kota</span>
                    <span class="absolute bottom-[-1px] left-0 w-full h-[2px] bg-emerald-500 rounded-t-full transition-transform origin-left {{ request()->routeIs('city.dashboard') || request()->routeIs('map') ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-100 opacity-30' }}"></span>
                </a>
                <a href="{{ route('recommend.index') }}" class="relative h-full flex items-center px-2 xl:px-3 text-sm xl:text-base transition-colors group {{ request()->routeIs('recommend.*') ? 'text-slate-900 font-semibold' : 'text-slate-500 hover:text-slate-900' }}">
                    <span>Rekomendasi</span>
                    <span class="absolute bottom-[-1px] left-0 w-full h-[2px] bg-emerald-500 rounded-t-full transition-transform origin-left {{ request()->routeIs('recommend.*') ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-100 opacity-30' }}"></span>
                </a>
                <a href="{{ route('assistant.index') }}" class="relative h-full flex items-center px-2 xl:px-3 text-sm xl:text-base transition-colors group {{ request()->routeIs('assistant.*') ? 'text-slate-900 font-semibold' : 'text-slate-500 hover:text-slate-900' }}">
                    <span class="inline-flex items-center gap-1.5">
                        <svg class="w-4 h-4 {{ request()->routeIs('assistant.*') ? 'text-emerald-500' : 'text-slate-400 group-hover:text-emerald-500' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                        Tanya
                    </span>
                    <span class="absolute bottom-[-1px] left-0 w-full h-[2px] bg-emerald-500 rounded-t-full transition-transform origin-left {{ request()->routeIs('assistant.*') ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-100 opacity-30' }}"></span>
                </a>
                <a href="{{ route('about') }}" class="relative h-full flex items-center px-2 xl:px-3 text-sm xl:text-base transition-colors group {{ request()->routeIs('about') ? 'text-slate-900 font-semibold' : 'text-slate-500 hover:text-slate-900' }}">
                    <span>Tentang</span>
                    <span class="absolute bottom-[-1px] left-0 w-full h-[2px] bg-emerald-500 rounded-t-full transition-transform origin-left {{ request()->routeIs('about') ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-100 opacity-30' }}"></span>
                </a>
            </nav>

            <!-- User Auth & Quick Actions -->
            <div class="flex items-center justify-end gap-3 sm:gap-4 shrink-0">
                @auth
                    <div class="relative" x-data="{ open: false }">
                        <button type="button" @click="open = !open" class="flex items-center gap-2 px-1.5 py-1.5 rounded-full bg-white border border-slate-200 hover:border-emerald-300 hover:shadow-sm transition-all focus:outline-none min-h-[44px]">
                            <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-emerald-600 text-white flex items-center justify-center font-bold text-xs shrink-0 shadow-inner">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <span class="truncate max-w-[80px] sm:max-w-[120px] text-sm font-semibold text-slate-800">{{ auth()->user()->name }}</span>
                            <span class="bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded-full text-[10px] font-bold border border-emerald-100 hidden sm:inline-block">+{{ auth()->user()->eco_points }}</span>
                            <svg class="w-4 h-4 text-slate-400 shrink-0 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" @click.outside="open = false" x-transition x-cloak class="absolute right-0 mt-2 w-56 sm:w-64 bg-white border border-slate-200 rounded-2xl shadow-xl py-2 z-50">
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2.5 text-sm sm:text-base text-amber-700 hover:bg-amber-50 font-bold">⚡ Dashboard Admin</a>
                                <div class="border-t border-slate-100 my-1"></div>
                            @endif
                            <a href="{{ route('dashboard') }}" class="block px-4 py-2.5 text-sm sm:text-base text-slate-700 hover:bg-slate-50 hover:text-emerald-700">Dashboard Saya</a>
                            <a href="{{ route('actions.index') }}" class="block px-4 py-2.5 text-sm sm:text-base text-slate-700 hover:bg-slate-50 hover:text-emerald-700">Catat Aksi Hijau</a>
                            <a href="{{ route('impact.index') }}" class="block px-4 py-2.5 text-sm sm:text-base text-slate-700 hover:bg-slate-50 hover:text-emerald-700">Dampak Saya</a>
                            <a href="{{ route('profile.show') }}" class="block px-4 py-2.5 text-sm sm:text-base text-slate-700 hover:bg-slate-50 hover:text-emerald-700">Pengaturan Profil</a>
                            <div class="border-t border-slate-100 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2.5 text-sm sm:text-base text-rose-600 hover:bg-rose-50 font-medium">Keluar (Logout)</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="px-2 py-2 text-sm sm:text-base font-semibold text-slate-500 hover:text-slate-900 transition-colors min-h-[44px] flex items-center">Masuk</a>
                    <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-full bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-sm sm:text-base transition-all shadow-[0_4px_14px_0_rgba(16,185,129,0.39)] hover:shadow-[0_6px_20px_rgba(16,185,129,0.23)] min-h-[44px] flex items-center justify-center shrink-0">
                        Mulai
                    </a>
                @endauth
            </div>
        </div>
    </header>

    @if(session('success'))
        <div class="bg-emerald-600 text-white px-4 py-3 text-center text-sm sm:text-base font-medium shadow-inner">
            <div class="max-w-7xl mx-auto flex items-center justify-center gap-2">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-emerald-200 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <main class="flex-grow pb-24 lg:pb-12 w-full">
        {{ $slot }}
    </main>

    <!-- Mobile Bottom Navigation Bar -->
    <nav class="lg:hidden fixed bottom-0 left-0 right-0 bg-white/95 backdrop-blur-md border-t border-slate-200 z-50 px-1 py-1 shadow-[0_-4px_20px_-10px_rgba(0,0,0,0.1)]">
        <div class="flex justify-between items-center text-center">
            <a href="{{ route('landing') }}" class="relative flex-1 flex flex-col items-center justify-center py-2 transition-colors min-h-[48px] group {{ request()->routeIs('landing') ? 'text-emerald-600' : 'text-slate-400 hover:text-slate-900' }}">
                <span class="absolute top-0 left-1/2 -translate-x-1/2 w-8 h-0.5 rounded-b-full transition-all duration-300 {{ request()->routeIs('landing') ? 'bg-emerald-500' : 'bg-transparent group-hover:bg-slate-200' }}"></span>
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ request()->routeIs('landing') ? '2' : '1.5' }}" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span class="text-[10px] font-medium tracking-wide">Beranda</span>
            </a>
            <a href="{{ route('city.dashboard') }}" class="relative flex-1 flex flex-col items-center justify-center py-2 transition-colors min-h-[48px] group {{ request()->routeIs('city.dashboard') || request()->routeIs('map') ? 'text-emerald-600' : 'text-slate-400 hover:text-slate-900' }}">
                <span class="absolute top-0 left-1/2 -translate-x-1/2 w-8 h-0.5 rounded-b-full transition-all duration-300 {{ request()->routeIs('city.dashboard') || request()->routeIs('map') ? 'bg-emerald-500' : 'bg-transparent group-hover:bg-slate-200' }}"></span>
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ request()->routeIs('city.dashboard') || request()->routeIs('map') ? '2' : '1.5' }}" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                <span class="text-[10px] font-medium tracking-wide">Jelajahi</span>
            </a>
            <a href="{{ route('recommend.index') }}" class="relative flex-1 flex flex-col items-center justify-center py-2 transition-colors min-h-[48px] group {{ request()->routeIs('recommend.*') ? 'text-emerald-600' : 'text-slate-400 hover:text-slate-900' }}">
                <span class="absolute top-0 left-1/2 -translate-x-1/2 w-8 h-0.5 rounded-b-full transition-all duration-300 {{ request()->routeIs('recommend.*') ? 'bg-emerald-500' : 'bg-transparent group-hover:bg-slate-200' }}"></span>
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ request()->routeIs('recommend.*') ? '2' : '1.5' }}" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                <span class="text-[10px] font-medium tracking-wide">Rekomendasi</span>
            </a>
            <a href="{{ auth()->check() ? route('impact.index') : route('login') }}" class="relative flex-1 flex flex-col items-center justify-center py-2 transition-colors min-h-[48px] group {{ request()->routeIs('impact.*') || request()->routeIs('dashboard') ? 'text-emerald-600' : 'text-slate-400 hover:text-slate-900' }}">
                <span class="absolute top-0 left-1/2 -translate-x-1/2 w-8 h-0.5 rounded-b-full transition-all duration-300 {{ request()->routeIs('impact.*') || request()->routeIs('dashboard') ? 'bg-emerald-500' : 'bg-transparent group-hover:bg-slate-200' }}"></span>
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ request()->routeIs('impact.*') || request()->routeIs('dashboard') ? '2' : '1.5' }}" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                <span class="text-[10px] font-medium tracking-wide">Dampak</span>
            </a>
            <a href="{{ route('assistant.index') }}" class="relative flex-1 flex flex-col items-center justify-center py-2 transition-colors min-h-[48px] group {{ request()->routeIs('assistant.*') ? 'text-emerald-600' : 'text-slate-400 hover:text-slate-900' }}">
                <span class="absolute top-0 left-1/2 -translate-x-1/2 w-8 h-0.5 rounded-b-full transition-all duration-300 {{ request()->routeIs('assistant.*') ? 'bg-emerald-500' : 'bg-transparent group-hover:bg-slate-200' }}"></span>
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ request()->routeIs('assistant.*') ? '2' : '1.5' }}" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                <span class="text-[10px] font-medium tracking-wide">Tanya</span>
            </a>
        </div>
    </nav>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 text-slate-600 text-sm sm:text-base pt-8 sm:pt-12 pb-28 lg:pb-12 mt-8 sm:mt-12 w-full">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
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
    <div 
        x-show="cityModalOpen" 
        x-cloak
        @keydown.escape.window="cityModalOpen = false"
        x-transition:enter="transition ease-out duration-200" 
        x-transition:enter-start="opacity-0" 
        x-transition:enter-end="opacity-100" 
        x-transition:leave="transition ease-in duration-150" 
        x-transition:leave-start="opacity-100" 
        x-transition:leave-end="opacity-0" 
        class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 flex items-center justify-center p-4"
    >
        <div 
            @click.away="cityModalOpen = false" 
            class="bg-white rounded-[2rem] shadow-2xl border border-slate-100 max-w-md w-full overflow-hidden p-6 space-y-5"
        >
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                    </div>
                    <h3 class="font-bold text-slate-900 text-lg">Pilih Kota Layanan</h3>
                </div>
                <button 
                    type="button" 
                    @click="cityModalOpen = false" 
                    class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-50 text-slate-400 hover:bg-slate-100 hover:text-slate-900 transition-colors focus:outline-none"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Search Input -->
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input 
                    type="text" 
                    x-model="searchCityQuery" 
                    placeholder="Cari nama kota atau provinsi..." 
                    class="w-full pl-11 pr-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-sm focus:outline-none focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 transition-all"
                >
            </div>

            <!-- Cities Options List -->
            <div class="max-h-[60vh] overflow-y-auto space-y-2 pr-2 scrollbar-thin scrollbar-thumb-slate-200 scrollbar-track-transparent">
                @foreach($allCities as $cityOpt)
                    <form method="POST" action="{{ route('cities.select') }}">
                        @csrf
                        <input type="hidden" name="city_slug" value="{{ $cityOpt->slug }}">
                        
                        @php 
                            $isActive = isset($activeCity) && $activeCity->id === $cityOpt->id;
                        @endphp

                        <button 
                            type="submit" 
                            x-show="!searchCityQuery || '{{ strtolower($cityOpt->name . ' ' . $cityOpt->province) }}'.includes(searchCityQuery.toLowerCase())" 
                            class="w-full text-left p-4 rounded-2xl border transition-all cursor-pointer flex items-center justify-between group {{ $isActive ? 'border-emerald-500 bg-emerald-50/50 shadow-sm' : 'border-slate-100 hover:border-emerald-300 hover:bg-white hover:shadow-md' }}"
                        >
                            <div>
                                <div class="font-bold text-slate-900 text-sm flex items-center gap-2">
                                    <span>{{ $cityOpt->name }}</span>
                                    @if($isActive)
                                        <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-emerald-600 text-white uppercase tracking-wider">Aktif</span>
                                    @endif
                                </div>
                                <div class="text-xs text-slate-500 mt-1 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m22 4v-4m-3.146-5.146a2 2 0 10-2.828 0l-.146.146M3 17h18M5 21h14M9 13v4m6-4v4m-6-8V7a2 2 0 012-2h2a2 2 0 012 2v2"></path></svg>
                                    {{ $cityOpt->province ?? 'Indonesia' }}
                                </div>
                            </div>
                            <div class="w-8 h-8 rounded-full border flex items-center justify-center transition-colors {{ $isActive ? 'bg-emerald-600 border-emerald-600 text-white' : 'bg-slate-50 border-slate-200 text-slate-400 group-hover:bg-emerald-50 group-hover:border-emerald-200 group-hover:text-emerald-600' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7"></path></svg>
                            </div>
                        </button>
                    </form>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Global Custom Confirm Modal -->
    <x-confirm-modal />
</body>
</html>