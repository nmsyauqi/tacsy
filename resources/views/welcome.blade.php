@extends('layouts.app')

@section('content')
<div class="flex flex-col items-center justify-center min-h-[60vh] text-center space-y-6">
    
    <div class="text-6xl">🧬</div>
    
    <h1 class="text-4xl font-bold text-zinc-900 dark:text-zinc-100 tracking-tight">
        Selamat Datang di TACSY
    </h1>
    
    <p class="text-lg text-zinc-600 dark:text-zinc-400 max-w-2xl">
        Aplikasi Taksonomi Sistematis untuk mengelola dan memetakan hierarki kehidupan.
        Mulai dari Kingdom hingga Species.
    </p>

    <div class="flex gap-4 pt-4">
        <a href="{{ route('taxon-um') }}" class="px-6 py-3 bg-zinc-900 dark:bg-white text-white dark:text-black rounded-full font-semibold hover:opacity-90 transition">
            Jelajahi Pustaka
        </a>
        @guest
            <a href="{{ route('register') }}" class="px-6 py-3 border border-zinc-300 dark:border-zinc-600 rounded-full font-semibold hover:bg-zinc-100 dark:hover:bg-zinc-800 transition">
                Daftar Akun
            </a>
        @endguest
    </div>

</div>
@endsection