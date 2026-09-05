<!DOCTYPE html>
<html lang="id" class="h-full" x-data="{ mobileSidebarOpen: false }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Panel Admin — UrbanPulse' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen flex flex-col font-sans antialiased">

    <!-- Top Minimalist Admin Bar -->
    <div class="bg-slate-900 text-slate-200 text-xs py-2 px-4 border-b border-slate-800 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto flex justify-between items-center gap-2">
            <div class="flex items-center gap-2">
                <span class="bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 px-2 py-0.5 rounded font-mono font-semibold text-[10px] tracking-wider uppercase">ADMIN</span>
                <span class="hidden sm:inline text-slate-400 font-medium">Control Center Kota Bogor</span>
            </div>
            <a href="{{ route('landing') }}" class="hover:text-emerald-400 flex items-center gap-1.5 text-slate-300 text-xs font-medium transition-colors">
                <span>Website Publik</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
        </div>
    </div>

    <!-- Mobile Header Bar for Admin -->
    <div class="md:hidden bg-white border-b border-slate-200/80 px-4 py-3 flex items-center justify-between sticky top-[33px] z-30">
        <div class="flex items-center gap-3">
            <button @click="mobileSidebarOpen = true" class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 focus:outline-none min-h-[40px] min-w-[40px] flex items-center justify-center" aria-label="Buka Sidebar Admin">
                <svg class="w-5 h-5 text-slate-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-emerald-600 text-white flex items-center justify-center font-bold text-xs shadow-xs">
                    UP
                </div>
                <div>
                    <span class="font-bold text-slate-900 text-sm leading-none block">UrbanPulse</span>
                    <span class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider block">CMS Admin</span>
                </div>
            </div>
        </div>

        <div class="w-7 h-7 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-xs">
            {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
        </div>
    </div>

    <!-- Mobile Slide-Over Drawer Overlay & Sidebar -->
    <div x-show="mobileSidebarOpen" 
         x-transition:enter="transition opacity ease-out duration-200" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="transition opacity ease-in duration-150" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0" 
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 md:hidden" 
         @click="mobileSidebarOpen = false" 
         x-cloak>
    </div>

    <aside x-show="mobileSidebarOpen" 
           x-transition:enter="transition transform ease-out duration-300" 
           x-transition:enter-start="-translate-x-full" 
           x-transition:enter-end="translate-x-0" 
           x-transition:leave="transition transform ease-in duration-200" 
           x-transition:leave-start="translate-x-0" 
           x-transition:leave-end="-translate-x-full" 
           class="fixed inset-y-0 left-0 w-72 max-w-[85vw] bg-white z-50 p-5 flex flex-col justify-between shadow-xl md:hidden overflow-y-auto" 
           x-cloak>
        <div>
            <!-- Header inside Drawer -->
            <div class="flex items-center justify-between pb-5 mb-5 border-b border-slate-100">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-emerald-600 text-white flex items-center justify-center font-bold text-sm shadow-xs">
                        UP
                    </div>
                    <div>
                        <span class="font-bold text-slate-900 text-base block leading-none">UrbanPulse</span>
                        <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider block mt-0.5">Control Center</span>
                    </div>
                </a>
                <button @click="mobileSidebarOpen = false" class="p-1.5 rounded-lg text-slate-400 hover:bg-slate-100 focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Navigation Links -->
            <nav class="space-y-1 text-xs font-semibold">
                <a href="{{ route('admin.dashboard') }}" 
                   @click="mobileSidebarOpen = false" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all min-h-[44px] {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-50 text-emerald-800 font-bold border-l-2 border-emerald-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="w-4 h-4 shrink-0 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path></svg>
                    <span>Dashboard Overview</span>
                </a>

                <a href="{{ route('admin.cities.index') }}" 
                   @click="mobileSidebarOpen = false" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all min-h-[44px] {{ request()->routeIs('admin.cities.*') ? 'bg-emerald-50 text-emerald-800 font-bold border-l-2 border-emerald-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="w-4 h-4 shrink-0 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m3 0h1m-1-4h.01M9 16h.01M9 12h.01M9 8h.01M15 16h.01M15 12h.01M15 8h.01"></path></svg>
                    <span>Kelola Kota</span>
                </a>

                <a href="{{ route('admin.locations.index') }}" 
                   @click="mobileSidebarOpen = false" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all min-h-[44px] {{ request()->routeIs('admin.locations.*') ? 'bg-emerald-50 text-emerald-800 font-bold border-l-2 border-emerald-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="w-4 h-4 shrink-0 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span>Kelola Lokasi</span>
                </a>

                <a href="{{ route('admin.environment.index') }}" 
                   @click="mobileSidebarOpen = false" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all min-h-[44px] {{ request()->routeIs('admin.environment.*') ? 'bg-emerald-50 text-emerald-800 font-bold border-l-2 border-emerald-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="w-4 h-4 shrink-0 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    <span>Data Lingkungan & API</span>
                </a>

                <a href="{{ route('admin.users.index') }}" 
                   @click="mobileSidebarOpen = false" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all min-h-[44px] {{ request()->routeIs('admin.users.*') ? 'bg-emerald-50 text-emerald-800 font-bold border-l-2 border-emerald-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="w-4 h-4 shrink-0 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <span>Manajemen Pengguna</span>
                </a>
            </nav>
        </div>

        <!-- Mobile Drawer Footer -->
        <div class="border-t border-slate-100 pt-4 text-xs mt-6">
            <div class="flex items-center gap-2.5 mb-3 p-2 bg-slate-50 rounded-xl">
                <div class="w-8 h-8 rounded-full bg-slate-800 text-white flex items-center justify-center font-bold text-xs shrink-0">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>
                <div class="overflow-hidden">
                    <span class="font-bold text-slate-900 truncate block text-xs">{{ auth()->user()->name ?? 'Administrator' }}</span>
                    <span class="text-slate-500 truncate block text-[11px]">{{ auth()->user()->email ?? 'admin@urbanpulse.test' }}</span>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left text-xs text-rose-600 hover:bg-rose-50 font-semibold px-3 py-2 rounded-xl transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    <span>Keluar Sesi Admin</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Container -->
    <div class="flex-grow flex">
        <!-- Desktop Admin Sidebar Navigation -->
        <aside class="w-64 bg-white border-r border-slate-200/80 p-5 flex-col justify-between hidden md:flex shrink-0 min-h-[calc(100vh-33px)] sticky top-[33px] h-[calc(100vh-33px)] overflow-y-auto">
            <div>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 mb-6 px-2">
                    <div class="w-8 h-8 rounded-lg bg-emerald-600 text-white flex items-center justify-center font-bold text-sm shadow-xs">
                        UP
                    </div>
                    <div>
                        <span class="font-bold text-slate-900 text-base leading-none block">UrbanPulse</span>
                        <span class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider mt-0.5">Control Center</span>
                    </div>
                </a>

                <nav class="space-y-1 text-xs font-semibold">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all min-h-[40px] {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-50 text-emerald-800 font-bold border-l-2 border-emerald-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <svg class="w-4 h-4 shrink-0 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path></svg>
                        <span>Dashboard Overview</span>
                    </a>

                    <a href="{{ route('admin.cities.index') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all min-h-[40px] {{ request()->routeIs('admin.cities.*') ? 'bg-emerald-50 text-emerald-800 font-bold border-l-2 border-emerald-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <svg class="w-4 h-4 shrink-0 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m3 0h1m-1-4h.01M9 16h.01M9 12h.01M9 8h.01M15 16h.01M15 12h.01M15 8h.01"></path></svg>
                        <span>Kelola Kota</span>
                    </a>

                    <a href="{{ route('admin.locations.index') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all min-h-[40px] {{ request()->routeIs('admin.locations.*') ? 'bg-emerald-50 text-emerald-800 font-bold border-l-2 border-emerald-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <svg class="w-4 h-4 shrink-0 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span>Kelola Lokasi</span>
                    </a>

                    <a href="{{ route('admin.environment.index') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all min-h-[40px] {{ request()->routeIs('admin.environment.*') ? 'bg-emerald-50 text-emerald-800 font-bold border-l-2 border-emerald-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <svg class="w-4 h-4 shrink-0 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        <span>Data Lingkungan & API</span>
                    </a>

                    <a href="{{ route('admin.users.index') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all min-h-[40px] {{ request()->routeIs('admin.users.*') ? 'bg-emerald-50 text-emerald-800 font-bold border-l-2 border-emerald-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <svg class="w-4 h-4 shrink-0 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <span>Manajemen Pengguna</span>
                    </a>
                </nav>
            </div>

            <div class="border-t border-slate-100 pt-4 text-xs">
                <div class="flex items-center gap-2.5 mb-3 p-2 bg-slate-50 rounded-xl border border-slate-100">
                    <div class="w-7 h-7 rounded-full bg-slate-800 text-white flex items-center justify-center font-bold text-xs shrink-0">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <div class="overflow-hidden">
                        <span class="font-bold text-slate-900 truncate block text-xs">{{ auth()->user()->name ?? 'Administrator' }}</span>
                        <span class="text-slate-500 truncate block text-[11px]">{{ auth()->user()->email ?? 'admin@urbanpulse.test' }}</span>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left text-xs text-rose-600 hover:bg-rose-50 font-semibold px-2.5 py-2 rounded-xl transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        <span>Keluar Sesi Admin</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-grow p-4 sm:p-6 lg:p-8 overflow-y-auto max-w-full">
            @if(session('success'))
                <div class="mb-6 bg-emerald-600 text-white px-4 py-3 rounded-2xl text-xs sm:text-sm font-semibold flex items-center gap-2 shadow-xs">
                    <svg class="w-4 h-4 text-white shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            {{ $slot }}
        </main>
    </div>

</body>
</html>
