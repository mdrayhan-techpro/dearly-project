<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ config('dearly.supported_locales.' . app()->getLocale() . '.dir', 'ltr') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Dearly') }} — {{ $title ?? 'Letters Find Their Way' }}</title>
    <meta name="description" content="A global nostalgic digital pen-pal and letter-writing platform. Recreating the emotional magic of slow correspondence.">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@500;700&family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F6F0E5] text-[#342A25] font-sans antialiased min-h-screen flex flex-col selection:bg-[#783C46] selection:text-white">
    <!-- Post Office Header -->
    <header class="border-b border-[#E9DDC7] bg-[#FAF6EE]/90 backdrop-blur-md sticky top-0 z-40 transition-shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Brand / Logo -->
                <div class="flex items-center space-x-3">
                    <a href="{{ auth()->check() ? route('post-office.index') : route('home') }}" class="flex items-center space-x-3 group">
                        <div class="wax-seal w-11 h-11 text-base font-serif font-bold shadow-seal group-hover:scale-105 transition-transform">
                            D
                        </div>
                        <div>
                            <span class="font-serif text-2xl font-bold tracking-tight text-[#342A25] group-hover:text-[#783C46] transition-colors">Dearly</span>
                            <span class="hidden sm:block text-[11px] tracking-widest uppercase text-[#B79A61] font-medium">Letters Find Their Way</span>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <nav class="hidden md:flex items-center space-x-7 text-sm font-medium">
                    @auth
                        <a href="{{ route('post-office.index') }}" class="{{ request()->routeIs('post-office.*') ? 'text-[#783C46] font-semibold border-b-2 border-[#783C46]' : 'text-[#342A25] hover:text-[#783C46]' }} pb-1 transition-colors">
                            My Post Office
                        </a>
                        <a href="{{ route('discovery.matches') }}" class="{{ request()->routeIs('discovery.*') ? 'text-[#783C46] font-semibold border-b-2 border-[#783C46]' : 'text-[#342A25] hover:text-[#783C46]' }} pb-1 transition-colors">
                            Find Pen Friends
                        </a>
                        <a href="{{ route('mailbox.index') }}" class="{{ request()->routeIs('mailbox.*') ? 'text-[#783C46] font-semibold border-b-2 border-[#783C46]' : 'text-[#342A25] hover:text-[#783C46]' }} pb-1 transition-colors relative">
                            Mailbox
                            @php
                                $unreadMailCount = \App\Models\Letter::inboxFor(auth()->id())->where('is_read', false)->count();
                            @endphp
                            @if($unreadMailCount > 0)
                                <span class="inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-bold leading-none text-white bg-[#783C46] rounded-full ml-1">
                                    {{ $unreadMailCount }}
                                </span>
                            @endif
                        </a>
                        <a href="{{ route('letters.compose') }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold uppercase tracking-wider text-white bg-[#783C46] hover:bg-[#582A32] rounded-md shadow-sm transition-all transform hover:-translate-y-0.5">
                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            Write Letter
                        </a>
                    @else
                        <a href="{{ route('how-it-works') }}" class="text-[#342A25] hover:text-[#783C46] transition-colors">How It Works</a>
                        <a href="{{ route('about') }}" class="text-[#342A25] hover:text-[#783C46] transition-colors">About</a>
                        <a href="{{ route('faq') }}" class="text-[#342A25] hover:text-[#783C46] transition-colors">FAQ</a>
                        <a href="{{ route('login') }}" class="text-[#783C46] hover:text-[#582A32] font-semibold transition-colors">Sign In</a>
                        <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold uppercase tracking-wider text-white bg-[#783C46] hover:bg-[#582A32] rounded-md shadow-sm transition-all transform hover:-translate-y-0.5">
                            Join Post Office
                        </a>
                    @endauth
                </nav>

                <!-- Right Side Tools: Language Selector, Notifications & User Dropdown -->
                <div class="flex items-center space-x-4">
                    <!-- Language Switcher -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-1 text-xs text-[#5A4A42] hover:text-[#783C46] px-2 py-1.5 rounded border border-[#E9DDC7] bg-[#F6F0E5]/70 focus:outline-none">
                            <span>{{ strtoupper(app()->getLocale()) }}</span>
                            <svg class="w-3 h-3 text-[#B79A61]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-44 bg-[#FAF6EE] border border-[#E9DDC7] rounded-md shadow-lg py-1 z-50">
                            @foreach(config('dearly.supported_locales', []) as $code => $locale)
                                <a href="{{ route('locale.switch', $code) }}" class="flex items-center justify-between px-4 py-2 text-xs text-[#342A25] hover:bg-[#E9DDC7]/60 {{ app()->getLocale() === $code ? 'font-bold text-[#783C46]' : '' }}">
                                    <span>{{ $locale['native'] }}</span>
                                    <span class="text-[10px] text-[#B79A61] uppercase">{{ $code }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    @auth
                        <!-- Notification Bell -->
                        @php
                            $unreadNotifs = \App\Models\Notification::where('user_id', auth()->id())->where('is_read', false)->count();
                        @endphp
                        <a href="{{ route('notifications.index') }}" class="relative p-1.5 text-[#5A4A42] hover:text-[#783C46] transition-colors" title="Notifications">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            @if($unreadNotifs > 0)
                                <span class="absolute top-0.5 right-0.5 w-2.5 h-2.5 bg-[#783C46] rounded-full ring-2 ring-[#FAF6EE]"></span>
                            @endif
                        </a>

                        <!-- User Profile Menu -->
                        <div class="relative" x-data="{ userMenu: false }">
                            <button @click="userMenu = !userMenu" class="flex items-center space-x-2 text-xs text-[#342A25] font-serif font-semibold focus:outline-none">
                                <span class="w-7 h-7 rounded-full bg-[#E9DDC7] border border-[#B79A61] flex items-center justify-center text-[#783C46]">
                                    {{ substr(auth()->user()->nickname, 0, 1) }}
                                </span>
                                <span class="hidden sm:inline-block max-w-[120px] truncate">{{ auth()->user()->nickname }}</span>
                            </button>
                            <div x-show="userMenu" @click.away="userMenu = false" x-cloak class="absolute right-0 mt-2 w-52 bg-[#FAF6EE] border border-[#E9DDC7] rounded-md shadow-xl py-1 z-50">
                                <div class="px-4 py-2 border-b border-[#E9DDC7] text-xs">
                                    <p class="font-bold text-[#342A25] truncate">{{ auth()->user()->nickname }}</p>
                                    @if(auth()->user()->mailingCode)
                                        <p class="text-[10px] font-mono text-[#783C46] tracking-wider">{{ auth()->user()->mailingCode->code }}</p>
                                    @endif
                                </div>
                                <a href="{{ route('post-office.index') }}" class="block px-4 py-2 text-xs text-[#342A25] hover:bg-[#E9DDC7]/60">Post Office Desk</a>
                                <a href="{{ route('mailing-code.show') }}" class="block px-4 py-2 text-xs text-[#342A25] hover:bg-[#E9DDC7]/60">My Mailing Code</a>
                                <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-xs text-[#342A25] hover:bg-[#E9DDC7]/60">Profile & Privacy</a>
                                @if(auth()->user()->isAdmin())
                                    <div class="border-t border-[#E9DDC7] my-1"></div>
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-xs font-semibold text-[#783C46] hover:bg-[#E9DDC7]/60">
                                        Admin Sanctuary
                                    </a>
                                @endif
                                <div class="border-t border-[#E9DDC7] my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-xs text-[#783C46] hover:bg-[#E9DDC7]/60">
                                        Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Global Flash & Alert Messages -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
        @if (session('status'))
            <div class="bg-[#EFF4F0] border border-[#405B4B]/30 text-[#1C2E24] px-4 py-3 rounded-md shadow-sm text-sm flex items-center justify-between mb-4" role="alert">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 text-[#405B4B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('status') }}</span>
                </div>
                <button type="button" @click="$el.parentElement.remove()" class="text-[#405B4B] hover:opacity-75">&times;</button>
            </div>
        @endif

        @if (session('warning'))
            <div class="bg-[#FFF8E7] border border-[#B79A61]/40 text-[#5C4518] px-4 py-3 rounded-md shadow-sm text-sm flex items-center justify-between mb-4" role="alert">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 text-[#B79A61]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span>{{ session('warning') }}</span>
                </div>
                <button type="button" @click="$el.parentElement.remove()" class="text-[#5C4518] hover:opacity-75">&times;</button>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-[#FDF2F2] border border-[#783C46]/30 text-[#582A32] px-4 py-3 rounded-md shadow-sm text-sm mb-4" role="alert">
                <p class="font-bold font-serif mb-1">Please note the following:</p>
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
                        A slow, privacy-first digital correspondence sanctuary designed to bring back the forgotten magic of letters, stamps, and genuine pen friendships.
                    </p>
                    <div class="postal-mark px-3 py-1">
                        Est. Post Office
                    </div>
                </div>

                <!-- Col 2: Navigation -->
                <div>
                    <h4 class="font-serif text-sm font-bold text-[#342A25] mb-3">Postal Services</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('discovery.choose-length') }}" class="hover:text-[#783C46] transition-colors">Find a Pen Friend</a></li>
                        <li><a href="{{ route('letters.compose') }}" class="hover:text-[#783C46] transition-colors">Letter-Writing Studio</a></li>
                        <li><a href="{{ route('mailbox.index') }}" class="hover:text-[#783C46] transition-colors">Received Letters</a></li>
                        <li><a href="{{ route('mailing-code.show') }}" class="hover:text-[#783C46] transition-colors">Private Mailing Code</a></li>
                    </ul>
                </div>

                <!-- Col 3: Principles -->
                <div>
                    <h4 class="font-serif text-sm font-bold text-[#342A25] mb-3">Safety & Governance</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('privacy') }}" class="hover:text-[#783C46] transition-colors">Privacy Promise</a></li>
                        <li><a href="{{ route('guidelines') }}" class="hover:text-[#783C46] transition-colors">Community Guidelines</a></li>
                        <li><a href="{{ route('terms') }}" class="hover:text-[#783C46] transition-colors">Terms of Service</a></li>
                        <li><a href="{{ route('faq') }}" class="hover:text-[#783C46] transition-colors">Frequently Asked Questions</a></li>
                    </ul>
                </div>

                <!-- Col 4: Postal Seal / Tagline -->
                <div class="bg-[#F6F0E5] p-4 rounded-lg border border-[#E9DDC7] text-center space-y-2">
                    <p class="font-handwriting text-2xl text-[#783C46]">"Letters Find Their Way"</p>
                    <p class="text-[11px] text-[#5A4A42]">
                        Every letter is encrypted in storage, isolated to authorized participants, and sealed with care.
                    </p>
                    <p class="text-[10px] text-[#B79A61] pt-1">
                        © {{ date('Y') }} Dearly Post Office. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
