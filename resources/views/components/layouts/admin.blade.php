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

    <!-- Top Admin Bar -->
    <div class="bg-amber-600 text-white text-xs py-2 px-4 font-bold sticky top-0 z-40 shadow-xs">
        <div class="max-w-7xl mx-auto flex flex-wrap justify-between items-center gap-2">
            <div class="flex items-center gap-2">
                <span class="bg-white text-amber-800 px-2 py-0.5 rounded font-bold text-[10px] tracking-wide">MODE ADMINISTRATOR</span>
                <span class="hidden sm:inline">UrbanPulse Control Center Kota Bogor</span>
            </div>
            <a href="{{ route('landing') }}" class="hover:underline flex items-center gap-1 text-white text-xs font-semibold">
                <span>Kembali ke Website Publik</span> &rarr;
            </a>
        </div>
    </div>

    <!-- Mobile Header Bar for Admin -->
    <div class="md:hidden bg-white border-b border-slate-200 px-4 py-3 flex items-center justify-between sticky top-[33px] z-30 shadow-xs">
        <div class="flex items-center gap-3">
            <button @click="mobileSidebarOpen = true" class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 focus:outline-none min-h-[44px] min-w-[44px] flex items-center justify-center" aria-label="Buka Sidebar Admin">
                <svg class="w-6 h-6 text-slate-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-amber-600 text-white flex items-center justify-center font-bold text-sm">
                    AD
                </div>
                <div>
                    <span class="font-bold text-slate-900 text-base leading-none block">UrbanPulse</span>
                    <span class="text-[10px] font-bold text-amber-700 block">PANEL ADMIN</span>
                </div>
            </div>
        </div>

        <div class="w-8 h-8 rounded-full bg-amber-100 text-amber-800 flex items-center justify-center font-bold text-xs">
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
           class="fixed inset-y-0 left-0 w-72 max-w-[85vw] bg-white z-50 p-6 flex flex-col justify-between shadow-2xl md:hidden overflow-y-auto" 
           x-cloak>
        <div>
            <!-- Header inside Drawer -->
            <div class="flex items-center justify-between pb-6 mb-6 border-b border-slate-100">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-amber-600 text-white flex items-center justify-center font-bold text-lg shadow-sm">
                        AD
                    </div>
                    <div>
                        <span class="font-bold text-slate-900 text-lg block">UrbanPulse</span>
                        <span class="text-xs font-bold text-amber-700 block">PANEL ADMIN</span>
                    </div>
                </a>
                <button @click="mobileSidebarOpen = false" class="p-2 rounded-xl text-slate-500 hover:bg-slate-100 focus:outline-none min-h-[44px] min-w-[44px] flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Navigation Links -->
            <nav class="space-y-1.5 text-sm font-semibold">
                <a href="{{ route('admin.dashboard') }}" 
                   @click="mobileSidebarOpen = false" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all min-h-[48px] {{ request()->routeIs('admin.dashboard') ? 'bg-amber-50 text-amber-900 font-bold border-l-4 border-amber-600 shadow-xs' : 'text-slate-700 hover:bg-slate-100' }}">
                    <span class="text-lg">📊</span>
                    <span>Dashboard Admin</span>
                </a>

                <a href="{{ route('admin.locations.index') }}" 
                   @click="mobileSidebarOpen = false" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all min-h-[48px] {{ request()->routeIs('admin.locations.*') ? 'bg-amber-50 text-amber-900 font-bold border-l-4 border-amber-600 shadow-xs' : 'text-slate-700 hover:bg-slate-100' }}">
                    <span class="text-lg">📍</span>
                    <span>Kelola Lokasi Tempat</span>
                </a>

                <a href="{{ route('admin.environment.index') }}" 
                   @click="mobileSidebarOpen = false" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all min-h-[48px] {{ request()->routeIs('admin.environment.*') ? 'bg-amber-50 text-amber-900 font-bold border-l-4 border-amber-600 shadow-xs' : 'text-slate-700 hover:bg-slate-100' }}">
                    <span class="text-lg">🍃</span>
                    <span>Data Lingkungan & API</span>
                </a>

                <a href="{{ route('admin.users.index') }}" 
                   @click="mobileSidebarOpen = false" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all min-h-[48px] {{ request()->routeIs('admin.users.*') ? 'bg-amber-50 text-amber-900 font-bold border-l-4 border-amber-600 shadow-xs' : 'text-slate-700 hover:bg-slate-100' }}">
                    <span class="text-lg">👥</span>
                    <span>Pengguna & Dampak</span>
                </a>

                <div class="pt-4 border-t border-slate-100 mt-4">
                    <a href="{{ route('landing') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 hover:bg-slate-100 transition-colors min-h-[48px]">
                        <span class="text-lg">🌐</span>
                        <span>Ke Website Publik</span>
                    </a>
                </div>
            </nav>
        </div>

        <!-- Mobile Drawer Footer -->
        <div class="border-t border-slate-200 pt-4 text-sm mt-6">
            <div class="flex items-center gap-3 mb-3 p-2 bg-slate-50 rounded-xl">
                <div class="w-9 h-9 rounded-full bg-amber-100 text-amber-800 flex items-center justify-center font-bold text-sm flex-shrink-0">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>
                <div class="text-xs overflow-hidden">
                    <span class="font-bold text-slate-900 truncate block">{{ auth()->user()->name ?? 'Administrator' }}</span>
                    <span class="text-slate-500 truncate block">{{ auth()->user()->email ?? 'admin@urbanpulse.test' }}</span>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left text-xs text-rose-600 hover:bg-rose-50 font-bold px-3 py-2.5 rounded-xl transition-colors min-h-[44px] flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    <span>Keluar dari Admin</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Container -->
    <div class="flex-grow flex">
        <!-- Desktop Admin Sidebar Navigation -->
        <aside class="w-64 bg-white border-r border-slate-200 p-6 flex-col justify-between hidden md:flex flex-shrink-0 min-h-[calc(100vh-33px)] sticky top-[33px] h-[calc(100vh-33px)] overflow-y-auto">
            <div>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 mb-8 group">
                    <div class="w-10 h-10 rounded-xl bg-amber-600 text-white flex items-center justify-center font-bold text-lg shadow-md shadow-amber-600/20 group-hover:scale-105 transition-transform">
                        AD
                    </div>
                    <div>
                        <span class="font-bold text-slate-900 text-lg tracking-tight block">UrbanPulse</span>
                        <span class="block text-[11px] font-bold text-amber-700 tracking-wider">PANEL ADMIN</span>
                    </div>
                </a>

                <nav class="space-y-1 text-sm font-semibold">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all min-h-[44px] {{ request()->routeIs('admin.dashboard') ? 'bg-amber-50 text-amber-900 font-bold border-l-4 border-amber-600 shadow-xs' : 'text-slate-700 hover:bg-slate-100' }}">
                        <span>📊</span>
                        <span>Dashboard Admin</span>
                    </a>

                    <a href="{{ route('admin.locations.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all min-h-[44px] {{ request()->routeIs('admin.locations.*') ? 'bg-amber-50 text-amber-900 font-bold border-l-4 border-amber-600 shadow-xs' : 'text-slate-700 hover:bg-slate-100' }}">
                        <span>📍</span>
                        <span>Kelola Lokasi Tempat</span>
                    </a>

                    <a href="{{ route('admin.environment.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all min-h-[44px] {{ request()->routeIs('admin.environment.*') ? 'bg-amber-50 text-amber-900 font-bold border-l-4 border-amber-600 shadow-xs' : 'text-slate-700 hover:bg-slate-100' }}">
                        <span>🍃</span>
                        <span>Data Lingkungan & API</span>
                    </a>

                    <a href="{{ route('admin.users.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all min-h-[44px] {{ request()->routeIs('admin.users.*') ? 'bg-amber-50 text-amber-900 font-bold border-l-4 border-amber-600 shadow-xs' : 'text-slate-700 hover:bg-slate-100' }}">
                        <span>👥</span>
                        <span>Pengguna & Dampak</span>
                    </a>
                </nav>
            </div>

            <div class="border-t border-slate-200 pt-4 text-sm">
                <div class="flex items-center gap-3 mb-3 p-2 bg-slate-50 rounded-xl">
                    <div class="w-8 h-8 rounded-full bg-amber-100 text-amber-800 flex items-center justify-center font-bold text-xs flex-shrink-0">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <div class="text-xs overflow-hidden">
                        <span class="font-bold text-slate-900 truncate block">{{ auth()->user()->name ?? 'Administrator' }}</span>
                        <span class="text-slate-500 truncate block">{{ auth()->user()->email ?? 'admin@urbanpulse.test' }}</span>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left text-xs text-rose-600 hover:bg-rose-50 font-bold px-3 py-2 rounded-xl transition-colors min-h-[38px] flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        <span>Keluar dari Admin</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-grow p-4 sm:p-6 lg:p-8 overflow-y-auto max-w-full">
            @if(session('success'))
                <div class="mb-6 bg-emerald-600 text-white px-4 py-3 rounded-2xl text-sm font-semibold flex items-center gap-2 shadow-xs">
                    <span>✓ {{ session('success') }}</span>
                </div>
            @endif

            {{ $slot }}
        </main>
    </div>

</body>
</html>
