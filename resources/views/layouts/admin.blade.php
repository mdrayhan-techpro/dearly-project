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
<body class="bg-[#F4EFEB] text-[#2D2420] font-sans antialiased min-h-screen flex">
    <!-- Admin Sidebar -->
    <aside class="w-64 bg-[#2A1F1B] text-[#E5DCD6] flex flex-col flex-shrink-0 shadow-2xl z-30">
        <!-- Brand Header -->
        <div class="h-20 flex items-center px-6 border-b border-[#3D302B] space-x-3">
            <div class="w-9 h-9 rounded-full bg-[#783C46] border border-[#B79A61] flex items-center justify-center font-serif font-bold text-white text-sm">
                D
            </div>
            <div>
                <span class="font-serif text-lg font-bold text-white tracking-wide">Dearly Admin</span>
                <p class="text-[10px] uppercase tracking-widest text-[#B79A61]">Sanctuary Control</p>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 px-4 py-6 space-y-1.5 text-xs font-medium overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-md transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-[#783C46] text-white font-semibold' : 'text-[#C5B8B1] hover:bg-[#3D302B] hover:text-white' }}">
                <svg class="w-4 h-4 text-[#B79A61]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('admin.users.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-md transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-[#783C46] text-white font-semibold' : 'text-[#C5B8B1] hover:bg-[#3D302B] hover:text-white' }}">
                <svg class="w-4 h-4 text-[#B79A61]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                <span>User Management</span>
            </a>

            <a href="{{ route('admin.questions.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-md transition-colors {{ request()->routeIs('admin.questions.*') ? 'bg-[#783C46] text-white font-semibold' : 'text-[#C5B8B1] hover:bg-[#3D302B] hover:text-white' }}">
                <svg class="w-4 h-4 text-[#B79A61]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Question Bank & Engine</span>
            </a>

            <a href="{{ route('admin.stationery.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-md transition-colors {{ request()->routeIs('admin.stationery.*') ? 'bg-[#783C46] text-white font-semibold' : 'text-[#C5B8B1] hover:bg-[#3D302B] hover:text-white' }}">
                <svg class="w-4 h-4 text-[#B79A61]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                <span>Stationery, Stamps & Seals</span>
            </a>

            <a href="{{ route('admin.reports.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-md transition-colors {{ request()->routeIs('admin.reports.*') ? 'bg-[#783C46] text-white font-semibold' : 'text-[#C5B8B1] hover:bg-[#3D302B] hover:text-white' }}">
                <svg class="w-4 h-4 text-[#B79A61]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <span>Moderation & Safety</span>
                @php
                    $pendingReportsCount = \App\Models\UserReport::where('status', 'open')->count();
                @endphp
                @if($pendingReportsCount > 0)
                    <span class="ml-auto bg-[#783C46] text-white text-[10px] px-2 py-0.5 rounded-full font-bold">
                        {{ $pendingReportsCount }}
                    </span>
                @endif
            </a>

            @if(auth()->user()->isSuperAdmin() || auth()->user()->role === 'admin')
                <div class="pt-4 pb-1">
                    <p class="px-3 text-[10px] uppercase tracking-wider text-[#8A7972] font-semibold">Governance</p>
                </div>

                <a href="{{ route('admin.settings.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-md transition-colors {{ request()->routeIs('admin.settings.*') ? 'bg-[#783C46] text-white font-semibold' : 'text-[#C5B8B1] hover:bg-[#3D302B] hover:text-white' }}">
                    <svg class="w-4 h-4 text-[#B79A61]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>System Settings</span>
                </a>

                <a href="{{ route('admin.audit-logs.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-md transition-colors {{ request()->routeIs('admin.audit-logs.*') ? 'bg-[#783C46] text-white font-semibold' : 'text-[#C5B8B1] hover:bg-[#3D302B] hover:text-white' }}">
                    <svg class="w-4 h-4 text-[#B79A61]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    <span>Audit Logs</span>
                </a>
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
        <header class="h-20 bg-white border-b border-[#E0D7D0] px-8 flex items-center justify-between">
            <h1 class="font-serif text-xl font-bold text-[#2D2420]">@yield('header', 'Administrative Desk')</h1>
            <div class="flex items-center space-x-4">
                <a href="{{ route('post-office.index') }}" class="inline-flex items-center text-xs text-[#783C46] hover:underline font-medium">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Return to Post Office
                </a>
            </div>
        </header>

        <!-- Flash alerts -->
        <div class="px-8 pt-6">
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

        <main class="flex-1 px-8 py-6">
            @yield('content')
        </main>
    </div>
</body>
</html>
