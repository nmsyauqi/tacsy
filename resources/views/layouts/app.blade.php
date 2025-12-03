@extends('layouts.taskbar')

@section('body')
    {{-- 1. Jika ini halaman Blade biasa (seperti Welcome), tampilkan section 'content' --}}
    @yield('content')

    {{-- 2. Jika ini Komponen Livewire, tampilkan slot --}}
    {{ $slot ?? '' }}
@endsection