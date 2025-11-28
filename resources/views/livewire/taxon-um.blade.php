@extends('layouts.base')

@section('body')

<div class="p-6 max-w-6xl mx-auto font-pixel text-gray-800">

    <div class="text-center mb-10">
        <h1 class="text-4xl font-bold text-mc-wood drop-shadow-md bg-mc-sand inline-block px-4 py-1 border-4 border-black transform -rotate-1">
            🌳 POHON KEHIDUPAN 🌳
        </h1>
        <p class="mt-4 text-gray-600">Daftar semua kategori Taksonomi yang telah ditemukan.</p>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($taxons as $item)
            <div class="panel-mc p-4 bg-white hover:bg-gray-50 transition">
                
                <span class="absolute -top-3 -right-3 bg-black text-white text-xs px-2 py-1 border-2 border-white shadow-sm">
                    {{ $item->rank }}
                </span>

                <h3 class="text-xl font-bold text-blue-900 leading-none">
                    {{ $item->name }}
                </h3>
                
                <p class="text-sm text-gray-500 mt-1">
                    Parent: 
                    @if($item->parent)
                        <span class="font-bold underline decoration-dotted">{{ $item->parent->name }}</span>
                    @else
                        <span class="italic text-red-400">KINGDOM</span>
                    @endif
                </p>
                
                <p class="text-xs text-gray-400 mt-4 border-t-2 border-dashed border-gray-300 pt-2">
                    Oleh: {{ $item->user->name ?? 'SYSTEM' }}
                </p>
            </div>
        @endforeach

        @if($taxons->isEmpty())
            <div class="col-span-3 text-center p-10 text-gray-400 border-4 border-dashed border-gray-300 rounded">
                <p class="text-2xl">🌱 Database Kosong</p>
            </div>
        @endif
    </div>

</div>

@endsection