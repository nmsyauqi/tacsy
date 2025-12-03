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
        @fluxStyles
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        
        <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('home') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse px-2 mb-4">
                <span class="font-bold text-xl tracking-tighter">🧬 TACSY</span>
            </a>

            <flux:navlist variant="outline">
                <flux:navlist.group heading="Pustaka Taksonomi">
                    
                    <flux:navlist.item icon="layout-grid" :href="route('taxonomy.list')" :current="request()->routeIs('taxonomy.list')" wire:navigate>
                        Taxonum (Pustaka)
                    </flux:navlist.item>

                    @auth
                        <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                            Taxoner (Admin)
                        </flux:navlist.item>
                    @endauth

                </flux:navlist.group>
            </flux:navlist>

            <flux:spacer />

            <flux:navlist variant="outline">
                <flux:navlist.item icon="folder-git-2" href="https://github.com/nmsyauqi/tacsy" target="_blank">
                    Repository
                </flux:navlist.item>
            </flux:navlist>

            @auth
                <flux:dropdown class="hidden lg:block" position="bottom" align="start">
                    <flux:profile :name="auth()->user()->name" :initials="auth()->user()->initials()" icon-trailing="chevrons-up-down" />

                    <flux:menu class="w-[220px]">
                        <div class="p-2 text-sm font-semibold border-b mb-1">{{ auth()->user()->email }}</div>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>Settings</flux:menu.item>
                        
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle">Log Out</flux:menu.item>
                        </form>
                    </flux:menu>
                </flux:dropdown>

                <flux:header class="lg:hidden mt-4 pt-4 border-t border-zinc-200 dark:border-zinc-700">
                    <flux:profile :name="auth()->user()->name" :initials="auth()->user()->initials()" />
                </flux:header>
            @endauth

            @guest
                <div class="grid gap-2 mt-4 p-2 border-t border-zinc-200 dark:border-zinc-700">
                    <a href="{{ route('login') }}" class="block text-center py-2 text-sm font-medium hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded">Log In</a>
                    <a href="{{ route('register') }}" class="block text-center py-2 text-sm font-medium bg-zinc-900 text-white dark:bg-white dark:text-black rounded">Register</a>
                </div>
            @endguest

        </flux:sidebar>

        <flux:main>
            <div class="lg:hidden mb-4">
                <flux:sidebar.toggle icon="bars-2" />
            </div>

            {{ $slot ?? '' }}
            @yield('body')
        </flux:main>

        @fluxScripts
        @livewireScripts
    </body>
</html>