<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Dearly Administration — {{ $title ?? 'Postmaster Control' }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F4EFEB] text-[#2D2420] font-sans antialiased min-h-screen flex relative" x-data="{ sidebarOpen: false }">
    <!-- Mobile Backdrop Overlay -->
    <div x-show="sidebarOpen" 
         x-cloak
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false" 
         class="fixed inset-0 bg-[#1A120F]/70 backdrop-blur-xs z-40 lg:hidden"></div>

    <!-- Admin Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
           class="fixed inset-y-0 left-0 z-50 w-64 bg-[#2A1F1B] text-[#E5DCD6] flex flex-col flex-shrink-0 shadow-2xl transition-transform duration-300 ease-in-out lg:static lg:translate-x-0">
        <!-- Brand Header -->
        <div class="h-20 flex items-center justify-between px-6 border-b border-[#3D302B]">
            <div class="flex items-center space-x-3">
                <div class="w-9 h-9 rounded-full bg-[#783C46] border border-[#B79A61] flex items-center justify-center font-serif font-bold text-white text-sm">
                    D
                </div>
                <div>
                    <span class="font-serif text-lg font-bold text-white tracking-wide">Dearly Admin</span>
                    <p class="text-[10px] uppercase tracking-widest text-[#B79A61]">Sanctuary Control</p>
                </div>
            </div>
            <!-- Close button on mobile -->
            <button @click="sidebarOpen = false" type="button" class="lg:hidden text-[#C5B8B1] hover:text-white p-1 rounded-md focus:outline-none" aria-label="Close sidebar">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 px-4 py-4 space-y-4 text-xs font-medium overflow-y-auto">
            <!-- Desk -->
            <div class="space-y-1">
                <p class="px-3 text-[10px] uppercase tracking-wider text-[#8A7972] font-semibold">Postmaster Desk</p>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-3 py-2 rounded-md transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-[#783C46] text-white font-semibold' : 'text-[#C5B8B1] hover:bg-[#3D302B] hover:text-white' }}">
                    <svg class="w-4 h-4 text-[#B79A61]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('admin.analytics.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-md transition-colors {{ request()->routeIs('admin.analytics.*') ? 'bg-[#783C46] text-white font-semibold' : 'text-[#C5B8B1] hover:bg-[#3D302B] hover:text-white' }}">
                    <svg class="w-4 h-4 text-[#B79A61]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <span>Postal Analytics & Census</span>
                </a>
            </div>

            <!-- Operations -->
            <div class="space-y-1">
                <p class="px-3 text-[10px] uppercase tracking-wider text-[#8A7972] font-semibold">Postal Operations</p>
                <a href="{{ route('admin.users.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-md transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-[#783C46] text-white font-semibold' : 'text-[#C5B8B1] hover:bg-[#3D302B] hover:text-white' }}">
                    <svg class="w-4 h-4 text-[#B79A61]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <span>Member Accounts</span>
                </a>

                <a href="{{ route('admin.mailing-codes.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-md transition-colors {{ request()->routeIs('admin.mailing-codes.*') ? 'bg-[#783C46] text-white font-semibold' : 'text-[#C5B8B1] hover:bg-[#3D302B] hover:text-white' }}">
                    <svg class="w-4 h-4 text-[#B79A61]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                    <span>Mailing Code Fleet</span>
                </a>

                <a href="{{ route('admin.letters.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-md transition-colors {{ request()->routeIs('admin.letters.*') ? 'bg-[#783C46] text-white font-semibold' : 'text-[#C5B8B1] hover:bg-[#3D302B] hover:text-white' }}">
                    <svg class="w-4 h-4 text-[#B79A61]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span>Postal Traffic Ledger</span>
                </a>

                <a href="{{ route('admin.matches.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-md transition-colors {{ request()->routeIs('admin.matches.*') ? 'bg-[#783C46] text-white font-semibold' : 'text-[#C5B8B1] hover:bg-[#3D302B] hover:text-white' }}">
                    <svg class="w-4 h-4 text-[#B79A61]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    <span>Kindred Pairings (90%+)</span>
                </a>
            </div>

            <!-- Sanctuary & Studio -->
            <div class="space-y-1">
                <p class="px-3 text-[10px] uppercase tracking-wider text-[#8A7972] font-semibold">Sanctuary &amp; Studio</p>
                <a href="{{ route('admin.questions.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-md transition-colors {{ request()->routeIs('admin.questions.*') ? 'bg-[#783C46] text-white font-semibold' : 'text-[#C5B8B1] hover:bg-[#3D302B] hover:text-white' }}">
                    <svg class="w-4 h-4 text-[#B79A61]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Question Bank &amp; Engine</span>
                </a>

                <a href="{{ route('admin.stationery.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-md transition-colors {{ request()->routeIs('admin.stationery.*') ? 'bg-[#783C46] text-white font-semibold' : 'text-[#C5B8B1] hover:bg-[#3D302B] hover:text-white' }}">
                    <svg class="w-4 h-4 text-[#B79A61]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    <span>Stationery, Stamps &amp; Seals</span>
                </a>

                <a href="{{ route('admin.broadcast.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-md transition-colors {{ request()->routeIs('admin.broadcast.*') ? 'bg-[#783C46] text-white font-semibold' : 'text-[#C5B8B1] hover:bg-[#3D302B] hover:text-white' }}">
                    <svg class="w-4 h-4 text-[#B79A61]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                    <span>Postmaster Broadcast &amp; Mail</span>
                </a>

                <a href="{{ route('admin.reports.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-md transition-colors {{ request()->routeIs('admin.reports.*') ? 'bg-[#783C46] text-white font-semibold' : 'text-[#C5B8B1] hover:bg-[#3D302B] hover:text-white' }}">
                    <svg class="w-4 h-4 text-[#B79A61]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span>Moderation &amp; Safety</span>
                    @php
                        $pendingReportsCount = \App\Models\UserReport::where('status', 'open')->count();
                    @endphp
                    @if($pendingReportsCount > 0)
                        <span class="ml-auto bg-[#783C46] text-white text-[10px] px-2 py-0.5 rounded-full font-bold">
                            {{ $pendingReportsCount }}
                        </span>
                    @endif
                </a>
            </div>

            <!-- Governance & System -->
            @if(auth()->user()->isSuperAdmin() || auth()->user()->role === 'admin')
                <div class="space-y-1">
                    <p class="px-3 text-[10px] uppercase tracking-wider text-[#8A7972] font-semibold">Governance &amp; System</p>
                    <a href="{{ route('admin.settings.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-md transition-colors {{ request()->routeIs('admin.settings.*') ? 'bg-[#783C46] text-white font-semibold' : 'text-[#C5B8B1] hover:bg-[#3D302B] hover:text-white' }}">
                        <svg class="w-4 h-4 text-[#B79A61]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>System Settings</span>
                    </a>

                    <a href="{{ route('admin.system.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-md transition-colors {{ request()->routeIs('admin.system.*') ? 'bg-[#783C46] text-white font-semibold' : 'text-[#C5B8B1] hover:bg-[#3D302B] hover:text-white' }}">
                        <svg class="w-4 h-4 text-[#B79A61]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/></svg>
                        <span>System Health &amp; Queues</span>
                    </a>

                    <a href="{{ route('admin.audit-logs.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-md transition-colors {{ request()->routeIs('admin.audit-logs.*') ? 'bg-[#783C46] text-white font-semibold' : 'text-[#C5B8B1] hover:bg-[#3D302B] hover:text-white' }}">
                        <svg class="w-4 h-4 text-[#B79A61]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        <span>Audit Trail</span>
                    </a>
                </div>
            @endif
        </nav>


        <!-- User Info / Exit -->
        <div class="p-4 border-t border-[#3D302B] bg-[#221915] text-xs">
            <div class="flex items-center justify-between">
                <div>
                    <p class="font-bold text-white truncate">{{ auth()->user()->nickname }}</p>
                    <p class="text-[10px] text-[#B79A61] capitalize">{{ str_replace('_', ' ', auth()->user()->role) }}</p>
                </div>
                <a href="{{ route('post-office.index') }}" class="text-[#B79A61] hover:text-white text-xs underline" title="Return to Post Office">
                    Exit Admin
                </a>
            </div>
        </div>
    </aside>

    <!-- Admin Content Area -->
    <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">
        <!-- Top Banner -->
        <header class="h-20 bg-white border-b border-[#E0D7D0] px-4 sm:px-8 flex items-center justify-between">
            <div class="flex items-center space-x-3 min-w-0">
                <!-- Hamburger on Mobile -->
                <button @click="sidebarOpen = !sidebarOpen" type="button" class="lg:hidden p-2 -ml-2 rounded-md text-[#5A4A42] hover:text-[#783C46] hover:bg-[#F4EFEB] focus:outline-none transition-colors" aria-label="Open sidebar">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <h1 class="font-serif text-lg sm:text-xl font-bold text-[#2D2420] truncate">@yield('header', 'Administrative Desk')</h1>
            </div>
            <div class="flex items-center space-x-4 flex-shrink-0">
                <a href="{{ route('post-office.index') }}" class="inline-flex items-center text-xs text-[#783C46] hover:underline font-medium">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span class="hidden sm:inline">Return to Post Office</span>
                    <span class="sm:hidden">Post Office</span>
                </a>
            </div>
        </header>

        <!-- Flash alerts -->
        <div class="px-4 sm:px-8 pt-6">
            @if (session('status'))
                <div class="bg-[#EFF4F0] border border-[#405B4B]/30 text-[#1C2E24] px-4 py-3 rounded-md text-sm mb-4">
                    {{ session('status') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="bg-[#FDF2F2] border border-[#783C46]/30 text-[#582A32] px-4 py-3 rounded-md text-sm mb-4">
                    <ul class="list-disc list-inside space-y-1 text-xs">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <main class="flex-1 px-4 sm:px-8 py-6">
            @yield('content')
        </main>
    </div>
</body>
</html>
