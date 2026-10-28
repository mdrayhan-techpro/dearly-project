<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ config('dearly.supported_locales.' . app()->getLocale() . '.dir', 'ltr') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Dearly') }} — {{ $title ?? 'Letters Find Their Way' }}</title>
    <meta name="description" content="A global nostalgic digital pen-pal and letter-writing platform. Recreating the emotional magic of slow correspondence.">

    <!-- Canonical & Social Sharing (OpenGraph & Twitter Cards) -->
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:site_name" content="{{ config('app.name', 'Dearly') }}">
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ config('app.name', 'Dearly') }} — {{ $title ?? 'Letters Find Their Way' }}">
    <meta property="og:description" content="A global nostalgic digital pen-pal and letter-writing platform. Recreating the emotional magic of slow correspondence.">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('android-chrome-512x512.png') }}?v=2">
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="{{ config('app.name', 'Dearly') }} — {{ $title ?? 'Letters Find Their Way' }}">
    <meta name="twitter:description" content="A global nostalgic digital pen-pal and letter-writing platform. Recreating the emotional magic of slow correspondence.">
    <meta name="twitter:image" content="{{ asset('android-chrome-512x512.png') }}?v=2">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@500;700&family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Amiri:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">

    <!-- Favicon & Mobile Web App Icons -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=2">
    <link rel="icon" type="image/png" sizes="48x48" href="{{ asset('favicon-48x48.png') }}?v=2">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}?v=2">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}?v=2">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}?v=2">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}?v=2">
    <meta name="theme-color" content="#783C46">

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F6F0E5] text-[#342A25] font-sans antialiased min-h-screen flex flex-col selection:bg-[#783C46] selection:text-white">
    <!-- Post Office Header -->
    <header class="border-b border-[#E9DDC7] bg-[#FAF6EE]/90 backdrop-blur-md sticky top-0 z-40 transition-shadow" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Brand / Logo -->
                <div class="flex items-center space-x-3 rtl:space-x-reverse">
                    <a href="{{ auth()->check() ? route('post-office.index') : route('home') }}" class="flex items-center space-x-3 rtl:space-x-reverse group">
                        <div class="wax-seal w-11 h-11 text-base font-serif font-bold shadow-seal group-hover:scale-105 transition-transform">
                            D
                        </div>
                        <div>
                            <span class="font-serif text-2xl font-bold tracking-tight text-[#342A25] group-hover:text-[#783C46] transition-colors">Dearly</span>
                            <span class="hidden sm:block text-[11px] tracking-widest uppercase text-[#B79A61] font-medium">{{ __('Letters Find Their Way') }}</span>
                        </div>
                    </a>
                </div>

                @php
                    $unreadMailCount = auth()->check() ? \App\Models\Letter::inboxFor(auth()->id())->where('is_read', false)->count() : 0;
                    $unreadNotifs = auth()->check() ? \App\Models\Notification::where('user_id', auth()->id())->where('is_read', false)->count() : 0;
                @endphp

                <!-- Navigation Links (Desktop) -->
                <nav class="hidden md:flex items-center space-x-7 rtl:space-x-reverse text-sm font-medium">
                    @auth
                        <a href="{{ route('post-office.index') }}" class="{{ request()->routeIs('post-office.*') ? 'text-[#783C46] font-semibold border-b-2 border-[#783C46]' : 'text-[#342A25] hover:text-[#783C46]' }} pb-1 transition-colors">
                            {{ __('My Post Office') }}
                        </a>
                        <a href="{{ route('discovery.matches') }}" class="{{ request()->routeIs('discovery.*') ? 'text-[#783C46] font-semibold border-b-2 border-[#783C46]' : 'text-[#342A25] hover:text-[#783C46]' }} pb-1 transition-colors">
                            {{ __('Find Pen Friends') }}
                        </a>
                        <a href="{{ route('mailbox.index') }}" class="{{ request()->routeIs('mailbox.*') ? 'text-[#783C46] font-semibold border-b-2 border-[#783C46]' : 'text-[#342A25] hover:text-[#783C46]' }} pb-1 transition-colors relative">
                            {{ __('Mailbox') }}
                            @if($unreadMailCount > 0)
                                <span class="inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-bold leading-none text-white bg-[#783C46] rounded-full ms-1">
                                    {{ $unreadMailCount }}
                                </span>
                            @endif
                        </a>
                        <a href="{{ route('letters.compose') }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold uppercase tracking-wider text-white bg-[#783C46] hover:bg-[#582A32] rounded-md shadow-sm transition-all transform hover:-translate-y-0.5">
                            <svg class="w-3.5 h-3.5 ltr:mr-1.5 rtl:ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            {{ __('Write Letter') }}
                        </a>
                    @else
                        <a href="{{ route('how-it-works') }}" class="text-[#342A25] hover:text-[#783C46] transition-colors">{{ __('How It Works') }}</a>
                        <a href="{{ route('about') }}" class="text-[#342A25] hover:text-[#783C46] transition-colors">{{ __('About') }}</a>
                        <a href="{{ route('faq') }}" class="text-[#342A25] hover:text-[#783C46] transition-colors">{{ __('FAQ') }}</a>
                        <a href="{{ route('login') }}" class="text-[#783C46] hover:text-[#582A32] font-semibold transition-colors">{{ __('Sign In') }}</a>
                        <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold uppercase tracking-wider text-white bg-[#783C46] hover:bg-[#582A32] rounded-md shadow-sm transition-all transform hover:-translate-y-0.5">
                            {{ __('Join Post Office') }}
                        </a>
                    @endauth
                </nav>

                <!-- Right Side Tools: Language Selector, Notifications & User Dropdown & Mobile Toggle -->
                <div class="flex items-center space-x-2 sm:space-x-4 rtl:space-x-reverse">
                    <!-- Language Switcher -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-1 rtl:space-x-reverse text-xs text-[#5A4A42] hover:text-[#783C46] px-2 py-1.5 rounded border border-[#E9DDC7] bg-[#F6F0E5]/70 focus:outline-none">
                            <span>{{ strtoupper(app()->getLocale()) }}</span>
                            <svg class="w-3 h-3 text-[#B79A61]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" @click.away="open = false" x-cloak class="absolute ltr:right-0 rtl:left-0 mt-2 w-44 bg-[#FAF6EE] border border-[#E9DDC7] rounded-md shadow-lg py-1 z-50">
                            @foreach(config('dearly.supported_locales', []) as $code => $locale)
                                <a href="{{ route('locale.switch', $code) }}" class="flex items-center justify-between px-4 py-2 text-xs text-[#342A25] hover:bg-[#E9DDC7]/60 {{ app()->getLocale() === $code ? 'font-bold text-[#783C46]' : '' }}">
                                    <span>{{ $locale['native'] }}</span>
                                    <span class="text-[10px] text-[#B79A61] uppercase">{{ $code }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    @auth
                        <!-- Interactive Postal Notification Center -->
                        <div class="relative" x-data="notificationCenter({{ $unreadNotifs }})">
                            <button @click="isOpen = !isOpen" 
                                    class="relative p-1.5 text-[#5A4A42] hover:text-[#783C46] transition-colors focus:outline-none" 
                                    title="{{ __('Notifications') }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                                <span x-show="unreadCount > 0" 
                                      x-cloak
                                      class="absolute -top-1 ltr:-right-1 rtl:-left-1 min-w-[18px] h-[18px] px-1 text-[10px] font-bold font-mono text-white bg-[#783C46] rounded-full ring-2 ring-[#FAF6EE] flex items-center justify-center animate-pulse"
                                      x-text="unreadCount > 9 ? '9+' : unreadCount">
                                </span>
                            </button>

                            <!-- Dropdown Drawer -->
                            <div x-show="isOpen" 
                                 @click.away="isOpen = false" 
                                 x-cloak
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                                 class="absolute ltr:right-0 rtl:left-0 mt-2 w-80 sm:w-96 bg-[#FAF6EE] border border-[#E9DDC7] rounded-xl shadow-2xl overflow-hidden z-50">
                                
                                <!-- Header -->
                                <div class="px-4 py-3 bg-[#F0E6D2] border-b border-[#E9DDC7] flex items-center justify-between">
                                    <div class="flex items-center space-x-2 rtl:space-x-reverse">
                                        <div class="w-2 h-2 rounded-full bg-[#783C46]"></div>
                                        <h3 class="font-serif font-bold text-sm text-[#342A25]">{{ __('Postal Notifications') }}</h3>
                                    </div>
                                    <button x-show="unreadCount > 0" 
                                            @click="markAllAsRead()" 
                                            class="text-[11px] font-semibold text-[#783C46] hover:text-[#582A32] underline transition-colors">
                                        {{ __('Mark all as read') }}
                                    </button>
                                </div>

                                <!-- Device Push Alert Permission Toggle -->
                                <div class="px-4 py-2 bg-[#FAF6EE] border-b border-[#E9DDC7]/60 flex items-center justify-between text-xs">
                                    <div class="flex items-center space-x-2 rtl:space-x-reverse text-[#5A4A42]">
                                        <svg class="w-4 h-4 text-[#B79A61]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                        <span class="text-[11px]">{{ __('Device Alerts') }}:</span>
                                    </div>
                                    <template x-if="devicePermission === 'granted'">
                                        <span class="inline-flex items-center text-[10px] text-emerald-800 bg-emerald-100 font-semibold px-2 py-0.5 rounded-full">
                                            ✓ {{ __('Active') }}
                                        </span>
                                    </template>
                                    <template x-if="devicePermission !== 'granted'">
                                        <button @click="requestDeviceAlerts()" class="text-[11px] font-semibold text-white bg-[#783C46] hover:bg-[#582A32] px-2.5 py-1 rounded shadow-sm transition-colors">
                                            {{ __('Enable Alerts') }}
                                        </button>
                                    </template>
                                </div>

                                <!-- Notification List -->
                                <div class="max-h-72 overflow-y-auto divide-y divide-[#E9DDC7]/60">
                                    <template x-if="loading && notifications.length === 0">
                                        <div class="p-6 text-center text-xs text-[#5A4A42]">
                                            <p>{{ __('Checking postal ledger...') }}</p>
                                        </div>
                                    </template>

                                    <template x-if="!loading && notifications.length === 0">
                                        <div class="p-6 text-center text-xs text-[#5A4A42] space-y-1">
                                            <p class="font-serif font-semibold text-[#342A25]">{{ __('No notifications at this time.') }}</p>
                                            <p class="text-[11px] text-[#B79A61]">{{ __('Updates about letters and pen-friends will appear here.') }}</p>
                                        </div>
                                    </template>

                                    <template x-for="item in notifications" :key="item.id">
                                        <div @click="markAsRead(item.id, item.action_url)" 
                                             class="p-3.5 hover:bg-[#E9DDC7]/40 cursor-pointer transition-colors"
                                             :class="!item.is_read ? 'bg-[#FAF6EE] border-l-4 border-l-[#783C46]' : 'opacity-75'">
                                            <div class="flex items-start justify-between gap-2">
                                                <div class="space-y-0.5">
                                                    <p class="font-serif font-bold text-xs text-[#342A25]" x-text="item.title"></p>
                                                    <p class="text-[11px] text-[#5A4A42] leading-snug line-clamp-2" x-text="item.message"></p>
                                                    <span class="text-[10px] text-[#B79A61] font-mono inline-block pt-1" x-text="item.time_ago"></span>
                                                </div>
                                                <div x-show="!item.is_read" class="w-2 h-2 rounded-full bg-[#783C46] mt-1 flex-shrink-0"></div>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                <!-- Footer -->
                                <div class="p-2.5 bg-[#F0E6D2] border-t border-[#E9DDC7] text-center">
                                    <a href="{{ route('notifications.index') }}" class="text-xs font-semibold text-[#783C46] hover:text-[#582A32] transition-colors">
                                        {{ __('View All in Postal Ledger') }} &rarr;
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- User Profile Menu -->
                        <div class="relative" x-data="{ userMenu: false }">
                            <button @click="userMenu = !userMenu" class="flex items-center space-x-2 rtl:space-x-reverse text-xs text-[#342A25] font-serif font-semibold focus:outline-none">
                                <span class="w-7 h-7 rounded-full bg-[#E9DDC7] border border-[#B79A61] flex items-center justify-center text-[#783C46]">
                                    {{ substr(auth()->user()->nickname, 0, 1) }}
                                </span>
                                <span class="hidden sm:inline-block max-w-[120px] truncate">{{ auth()->user()->nickname }}</span>
                            </button>
                            <div x-show="userMenu" @click.away="userMenu = false" x-cloak class="absolute ltr:right-0 rtl:left-0 mt-2 w-52 bg-[#FAF6EE] border border-[#E9DDC7] rounded-md shadow-xl py-1 z-50">
                                <div class="px-4 py-2 border-b border-[#E9DDC7] text-xs">
                                    <p class="font-bold text-[#342A25] truncate">{{ auth()->user()->nickname }}</p>
                                    @if(auth()->user()->mailingCode)
                                         <p class="text-[10px] font-mono text-[#783C46] tracking-wider">{{ auth()->user()->mailingCode->code }}</p>
                                    @endif
                                </div>
                                <a href="{{ route('post-office.index') }}" class="block px-4 py-2 text-xs text-[#342A25] hover:bg-[#E9DDC7]/60">{{ __('Post Office Desk') }}</a>
                                <a href="{{ route('mailing-code.show') }}" class="block px-4 py-2 text-xs text-[#342A25] hover:bg-[#E9DDC7]/60">{{ __('My Mailing Code') }}</a>
                                <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-xs text-[#342A25] hover:bg-[#E9DDC7]/60">{{ __('Profile & Privacy') }}</a>
                                @if(auth()->user()->isAdmin())
                                    <div class="border-t border-[#E9DDC7] my-1"></div>
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-xs font-semibold text-[#783C46] hover:bg-[#E9DDC7]/60">
                                        {{ __('Admin Sanctuary') }}
                                    </a>
                                @endif
                                <div class="border-t border-[#E9DDC7] my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-start px-4 py-2 text-xs text-[#783C46] hover:bg-[#E9DDC7]/60">
                                        {{ __('Sign Out') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endauth

                    <!-- Mobile Hamburger Toggle Button -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen"
                            type="button"
                            class="md:hidden inline-flex items-center justify-center p-2 rounded-md text-[#5A4A42] hover:text-[#783C46] hover:bg-[#E9DDC7]/60 focus:outline-none transition-colors"
                            aria-label="{{ __('Toggle Menu') }}">
                        <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        <svg x-show="mobileMenuOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Sliding Drawer Navigation -->
        <div x-show="mobileMenuOpen" 
             x-cloak 
             class="md:hidden fixed inset-0 z-50 flex">
            <!-- Backdrop -->
            <div x-show="mobileMenuOpen"
                 x-transition:enter="transition-opacity ease-linear duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-linear duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="mobileMenuOpen = false"
                 class="fixed inset-0 bg-[#2D2420]/60 backdrop-blur-xs"></div>

            <!-- Drawer Container -->
            <div x-show="mobileMenuOpen"
                 x-transition:enter="transition ease-in-out duration-300 transform"
                 x-transition:enter-start="ltr:-translate-x-full rtl:translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in-out duration-300 transform"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="ltr:-translate-x-full rtl:translate-x-full"
                 class="relative max-w-xs w-full bg-[#FAF6EE] border-r rtl:border-r-0 rtl:border-l border-[#E9DDC7] shadow-2xl flex flex-col justify-between overflow-y-auto z-10">
                 
                 <!-- Top Header & Menu Items -->
                 <div>
                     <!-- Brand Header & Close Button -->
                     <div class="h-20 flex items-center justify-between px-6 border-b border-[#E9DDC7]">
                         <a href="{{ auth()->check() ? route('post-office.index') : route('home') }}" class="flex items-center space-x-3 rtl:space-x-reverse" @click="mobileMenuOpen = false">
                             <div class="wax-seal w-9 h-9 text-sm font-serif font-bold shadow-seal">D</div>
                             <div>
                                 <span class="font-serif text-xl font-bold tracking-tight text-[#342A25]">Dearly</span>
                                 <span class="block text-[10px] tracking-widest uppercase text-[#B79A61] font-medium">{{ __('Letters Find Their Way') }}</span>
                             </div>
                         </a>
                         <button @click="mobileMenuOpen = false" class="p-2 text-[#5A4A42] hover:text-[#783C46] rounded-md focus:outline-none" aria-label="Close menu">
                             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                         </button>
                     </div>

                     <!-- Auth Member Card -->
                     @auth
                         <div class="px-6 py-4 bg-[#F6F0E5]/80 border-b border-[#E9DDC7]">
                             <div class="flex items-center space-x-3 rtl:space-x-reverse">
                                 <span class="w-10 h-10 rounded-full bg-[#E9DDC7] border border-[#B79A61] flex items-center justify-center text-[#783C46] font-serif font-bold text-base">
                                     {{ strtoupper(substr(auth()->user()->nickname, 0, 1)) }}
                                 </span>
                                 <div class="min-w-0 flex-1">
                                     <p class="font-bold text-[#342A25] truncate text-sm">{{ auth()->user()->nickname }}</p>
                                     @if(auth()->user()->mailingCode)
                                         <p class="text-[11px] font-mono text-[#783C46] font-semibold tracking-wider">{{ auth()->user()->mailingCode->code }}</p>
                                     @endif
                                 </div>
                             </div>
                         </div>
                     @endauth

                     <!-- Navigation Links -->
                     <nav class="px-4 py-4 space-y-1 text-sm font-medium">
                         @auth
                             <div class="pb-3 px-1">
                                 <a href="{{ route('letters.compose') }}" @click="mobileMenuOpen = false" class="w-full flex items-center justify-center px-4 py-2.5 text-xs font-semibold uppercase tracking-wider text-white bg-[#783C46] hover:bg-[#582A32] rounded-md shadow-sm transition-all">
                                     <svg class="w-4 h-4 ltr:mr-2 rtl:ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                     {{ __('Write Letter') }}
                                 </a>
                             </div>

                             <a href="{{ route('post-office.index') }}" @click="mobileMenuOpen = false" class="flex items-center space-x-3 rtl:space-x-reverse px-3 py-2.5 rounded-md transition-colors {{ request()->routeIs('post-office.*') ? 'bg-[#783C46] text-white font-semibold' : 'text-[#342A25] hover:bg-[#E9DDC7]/60 hover:text-[#783C46]' }}">
                                 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                 <span>{{ __('My Post Office') }}</span>
                             </a>

                             <a href="{{ route('discovery.matches') }}" @click="mobileMenuOpen = false" class="flex items-center space-x-3 rtl:space-x-reverse px-3 py-2.5 rounded-md transition-colors {{ request()->routeIs('discovery.*') ? 'bg-[#783C46] text-white font-semibold' : 'text-[#342A25] hover:bg-[#E9DDC7]/60 hover:text-[#783C46]' }}">
                                 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                 <span>{{ __('Find Pen Friends') }}</span>
                             </a>

                             <a href="{{ route('mailbox.index') }}" @click="mobileMenuOpen = false" class="flex items-center justify-between px-3 py-2.5 rounded-md transition-colors {{ request()->routeIs('mailbox.*') ? 'bg-[#783C46] text-white font-semibold' : 'text-[#342A25] hover:bg-[#E9DDC7]/60 hover:text-[#783C46]' }}">
                                 <div class="flex items-center space-x-3 rtl:space-x-reverse">
                                     <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                     <span>{{ __('Mailbox') }}</span>
                                 </div>
                                 @if($unreadMailCount > 0)
                                     <span class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none text-white bg-[#783C46] rounded-full">
                                         {{ $unreadMailCount }}
                                     </span>
                                 @endif
                             </a>

                             <a href="{{ route('mailing-code.show') }}" @click="mobileMenuOpen = false" class="flex items-center space-x-3 rtl:space-x-reverse px-3 py-2.5 rounded-md transition-colors {{ request()->routeIs('mailing-code.*') ? 'bg-[#783C46] text-white font-semibold' : 'text-[#342A25] hover:bg-[#E9DDC7]/60 hover:text-[#783C46]' }}">
                                 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                                 <span>{{ __('My Mailing Code') }}</span>
                             </a>

                             <a href="{{ route('profile.show') }}" @click="mobileMenuOpen = false" class="flex items-center space-x-3 rtl:space-x-reverse px-3 py-2.5 rounded-md transition-colors {{ request()->routeIs('profile.*') ? 'bg-[#783C46] text-white font-semibold' : 'text-[#342A25] hover:bg-[#E9DDC7]/60 hover:text-[#783C46]' }}">
                                 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                 <span>{{ __('Profile & Privacy') }}</span>
                             </a>

                             <a href="{{ route('notifications.index') }}" @click="mobileMenuOpen = false" class="flex items-center justify-between px-3 py-2.5 rounded-md transition-colors {{ request()->routeIs('notifications.*') ? 'bg-[#783C46] text-white font-semibold' : 'text-[#342A25] hover:bg-[#E9DDC7]/60 hover:text-[#783C46]' }}">
                                 <div class="flex items-center space-x-3 rtl:space-x-reverse">
                                     <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                     <span>{{ __('Notifications') }}</span>
                                 </div>
                                 @if($unreadNotifs > 0)
                                     <span class="w-2.5 h-2.5 bg-[#783C46] rounded-full"></span>
                                 @endif
                             </a>

                             @if(auth()->user()->isAdmin())
                                 <div class="border-t border-[#E9DDC7] my-2 pt-2">
                                     <a href="{{ route('admin.dashboard') }}" @click="mobileMenuOpen = false" class="flex items-center space-x-3 rtl:space-x-reverse px-3 py-2.5 rounded-md text-[#783C46] font-semibold hover:bg-[#E9DDC7]/60">
                                         <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                         <span>{{ __('Admin Sanctuary') }}</span>
                                     </a>
                                 </div>
                             @endif
                         @else
                             <a href="{{ route('how-it-works') }}" @click="mobileMenuOpen = false" class="flex items-center px-3 py-2.5 rounded-md text-[#342A25] hover:bg-[#E9DDC7]/60 hover:text-[#783C46]">
                                 {{ __('How It Works') }}
                             </a>
                             <a href="{{ route('about') }}" @click="mobileMenuOpen = false" class="flex items-center px-3 py-2.5 rounded-md text-[#342A25] hover:bg-[#E9DDC7]/60 hover:text-[#783C46]">
                                 {{ __('About') }}
                             </a>
                             <a href="{{ route('faq') }}" @click="mobileMenuOpen = false" class="flex items-center px-3 py-2.5 rounded-md text-[#342A25] hover:bg-[#E9DDC7]/60 hover:text-[#783C46]">
                                 {{ __('FAQ') }}
                             </a>
                             <div class="border-t border-[#E9DDC7] my-3 pt-3 space-y-2 px-1">
                                 <a href="{{ route('login') }}" @click="mobileMenuOpen = false" class="w-full flex items-center justify-center px-4 py-2 text-xs font-semibold uppercase tracking-wider text-[#783C46] border border-[#783C46] hover:bg-[#783C46] hover:text-white rounded-md transition-colors">
                                     {{ __('Sign In') }}
                                 </a>
                                 <a href="{{ route('register') }}" @click="mobileMenuOpen = false" class="w-full flex items-center justify-center px-4 py-2 text-xs font-semibold uppercase tracking-wider text-white bg-[#783C46] hover:bg-[#582A32] rounded-md shadow-sm transition-colors">
                                     {{ __('Join Post Office') }}
                                 </a>
                             </div>
                         @endauth
                     </nav>
                 </div>

                 <!-- Footer in Drawer: Language and Sign Out -->
                 <div class="p-4 border-t border-[#E9DDC7] bg-[#F6F0E5]/50 space-y-3">
                     <!-- Quick Language Picker Grid -->
                     <div>
                         <p class="text-[10px] uppercase font-bold tracking-wider text-[#8A7972] mb-1.5">{{ __('Language') }}</p>
                         <div class="grid grid-cols-3 gap-1.5">
                             @foreach(config('dearly.supported_locales', []) as $code => $locale)
                                 <a href="{{ route('locale.switch', $code) }}" class="text-center py-1 px-1 rounded text-[11px] font-medium border {{ app()->getLocale() === $code ? 'bg-[#783C46] text-white border-[#783C46] font-bold' : 'bg-white text-[#5A4A42] border-[#E9DDC7] hover:border-[#783C46]' }}">
                                     {{ strtoupper($code) }}
                                 </a>
                             @endforeach
                         </div>
                     </div>

                     @auth
                         <form method="POST" action="{{ route('logout') }}" class="pt-1">
                             @csrf
                             <button type="submit" class="w-full flex items-center justify-center space-x-2 rtl:space-x-reverse px-3 py-2 text-xs text-[#783C46] hover:bg-[#E9DDC7]/60 rounded-md font-semibold transition-colors">
                                 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                 <span>{{ __('Sign Out') }}</span>
                             </button>
                         </form>
                     @endauth
                 </div>
            </div>
        </div>
    </header>

    <!-- Global Flash & Alert Messages -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
        @if (session('status'))
            <div class="bg-[#EFF4F0] border border-[#405B4B]/30 text-[#1C2E24] px-4 py-3 rounded-md shadow-sm text-sm flex items-center justify-between mb-4" role="alert">
                <div class="flex items-center space-x-2 rtl:space-x-reverse">
                    <svg class="w-5 h-5 text-[#405B4B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('status') }}</span>
                </div>
                <button type="button" @click="$el.parentElement.remove()" class="text-[#405B4B] hover:opacity-75">&times;</button>
            </div>
        @endif

        @if (session('warning'))
            <div class="bg-[#FFF8E7] border border-[#B79A61]/40 text-[#5C4518] px-4 py-3 rounded-md shadow-sm text-sm flex items-center justify-between mb-4" role="alert">
                <div class="flex items-center space-x-2 rtl:space-x-reverse">
                    <svg class="w-5 h-5 text-[#B79A61]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span>{{ session('warning') }}</span>
                </div>
                <button type="button" @click="$el.parentElement.remove()" class="text-[#5C4518] hover:opacity-75">&times;</button>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-[#FDF2F2] border border-[#783C46]/30 text-[#582A32] px-4 py-3 rounded-md shadow-sm text-sm mb-4" role="alert">
                <p class="font-bold font-serif mb-1">{{ __('Please note the following:') }}</p>
                <ul class="list-disc list-inside space-y-0.5 text-xs">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Main Content Area -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Post Office Footer -->
    <footer class="border-t border-[#E9DDC7] bg-[#FAF6EE] mt-16 text-xs text-[#5A4A42]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Col 1: Brand -->
                <div class="space-y-3">
                    <div class="flex items-center space-x-2">
                        <div class="wax-seal w-8 h-8 text-xs font-serif font-bold">D</div>
                        <span class="font-serif text-xl font-bold text-[#342A25]">Dearly</span>
                    </div>
                    <p class="text-xs text-[#5A4A42] leading-relaxed">
                        {{ __('A slow, privacy-first digital correspondence sanctuary designed to bring back the forgotten magic of letters, stamps, and genuine pen friendships.') }}
                    </p>
                    <div class="postal-mark px-3 py-1">
                        {{ __('Est. Post Office') }}
                    </div>
                </div>

                <!-- Col 2: Navigation -->
                <div>
                    <h4 class="font-serif text-sm font-bold text-[#342A25] mb-3">{{ __('Postal Services') }}</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('discovery.choose-length') }}" class="hover:text-[#783C46] transition-colors">{{ __('Find Pen Friends') }}</a></li>
                        <li><a href="{{ route('letters.compose') }}" class="hover:text-[#783C46] transition-colors">{{ __('Write Letter') }}</a></li>
                        <li><a href="{{ route('mailbox.index') }}" class="hover:text-[#783C46] transition-colors">{{ __('Received Letters') }}</a></li>
                        <li><a href="{{ route('mailing-code.show') }}" class="hover:text-[#783C46] transition-colors">{{ __('Private Mailing Code') }}</a></li>
                    </ul>
                </div>

                <!-- Col 3: Principles -->
                <div>
                    <h4 class="font-serif text-sm font-bold text-[#342A25] mb-3">{{ __('Safety & Governance') }}</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('privacy') }}" class="hover:text-[#783C46] transition-colors">{{ __('Privacy Promise') }}</a></li>
                        <li><a href="{{ route('guidelines') }}" class="hover:text-[#783C46] transition-colors">{{ __('Community Guidelines') }}</a></li>
                        <li><a href="{{ route('terms') }}" class="hover:text-[#783C46] transition-colors">{{ __('Terms of Service') }}</a></li>
                        <li><a href="{{ route('faq') }}" class="hover:text-[#783C46] transition-colors">{{ __('Frequently Asked Questions') }}</a></li>
                    </ul>
                </div>

                <!-- Col 4: Postal Seal / Tagline -->
                <div class="bg-[#F6F0E5] p-4 rounded-lg border border-[#E9DDC7] text-center space-y-2">
                    <p class="font-handwriting text-2xl text-[#783C46]">"{{ __('Letters Find Their Way') }}"</p>
                    <p class="text-[11px] text-[#5A4A42]">
                        {{ __('Every letter is encrypted in storage, isolated to authorized participants, and sealed with care.') }}
                    </p>
                    <p class="text-[10px] text-[#B79A61] pt-1">
                        © {{ date('Y') }} Dearly {{ __('Post Office') }}. {{ __('All rights reserved.') }}
                    </p>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
