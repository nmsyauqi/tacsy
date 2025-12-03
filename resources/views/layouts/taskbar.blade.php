<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? config('app.name') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />
        
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="min-h-screen bg-gray-50 dark:bg-zinc-900 font-sans antialiased">

        <header class="sticky top-0 z-50 w-full border-b border-zinc-200 bg-white/80 backdrop-blur dark:border-zinc-700 dark:bg-zinc-900/80">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    
                    <div class="flex items-center gap-8">
                        <a href="{{ route('home') }}" class="flex items-center gap-2">
                            <span class="text-2xl">🧬</span>
                            <span class="font-bold text-lg tracking-tight text-zinc-900 dark:text-zinc-100">TACSY</span>
                        </a>

                        <nav class="hidden md:flex items-center gap-6 text-sm font-medium">
                            <a href="{{ route('taxon-um') }}" 
                               class="transition hover:text-indigo-600 {{ request()->routeIs('taxon-um') ? 'text-indigo-600 dark:text-indigo-400' : 'text-zinc-600 dark:text-zinc-400' }}"
                               wire:navigate>
                                Pustaka (Public)
                            </a>

                            @auth
                                <a href="{{ route('dashboard') }}" 
                                   class="transition hover:text-indigo-600 {{ request()->routeIs('dashboard') ? 'text-indigo-600 dark:text-indigo-400' : 'text-zinc-600 dark:text-zinc-400' }}"
                                   wire:navigate>
                                    Admin (CRUD)
                                </a>
                            @endauth
                        </nav>
                    </div>

                    <div class="flex items-center gap-4">
                        @auth
                            <flux:dropdown position="bottom" align="end">
                                <button class="flex items-center gap-2 rounded-full bg-zinc-100 px-3 py-1.5 text-sm font-medium transition hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700">
                                    <span>{{ auth()->user()->name }}</span>
                                    <svg class="h-4 w-4 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </button>

                                <flux:menu class="w-48">
                                    <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>Settings</flux:menu.item>
                                    <div class="h-px bg-zinc-200 dark:bg-zinc-700 my-1"></div>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full text-left">Log Out</flux:menu.item>
                                    </form>
                                </flux:menu>
                            </flux:dropdown>
                        @endauth

                        @guest
                            <div class="flex gap-4 text-sm font-medium">
                                <a href="{{ route('login') }}" class="text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white">Log In</a>
                                <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-500">Register</a>
                            </div>
                        @endguest
                    </div>

                </div>
            </div>
        </header>

        <main class="py-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                {{ $slot ?? '' }}
                @yield('body')
            </div>
        </main>

        @fluxScripts
        @livewireScripts
    </body>
</html>