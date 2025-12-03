<div class="w-full font-pixel text-gray-800 dark:text-gray-200">

    <div class="text-center mb-8">
        <h1 class="text-3xl md:text-5xl font-bold text-mc-wood bg-mc-sand inline-block px-6 py-2 border-4 border-black transform -rotate-1 shadow-[4px_4px_0px_0px_rgba(0,0,0,0.2)]">
            🌳 POHON KEHIDUPAN 🌳
        </h1>
    </div>
    
    <div class="flex flex-col gap-8 pb-8 px-2">
        
        @foreach($rankOrder as $rank)
            <div class="w-full">
                
                <div class="bg-black text-white p-2 text-left px-4 text-xl font-bold border-b-4 border-mc-leaf mb-4 rounded-t-lg flex justify-between items-center">
                    <span>{{ strtoupper($rank) }}</span>
                    <span class="text-xs font-normal opacity-70">Kategori</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @if(isset($groupedTaxons[$rank]))
                        @foreach($groupedTaxons[$rank] as $item)
                            
                            <div class="panel-mc p-3 hover:scale-[1.01] transition-transform duration-200 group relative flex flex-col justify-between min-h-[120px]">
                                
                                @can('update', $item)
                                    <button wire:click="triggerEdit({{ $item->id }})" 
                                            class="absolute top-2 right-2 p-2 bg-yellow-400 text-black border-2 border-black hover:bg-yellow-300 shadow-sm z-20"
                                            title="Edit Data">
                                        ✏️
                                    </button>
                                @endcan

                                <div>
                                    <h3 class="text-2xl font-bold text-mc-wood dark:text-indigo-400 leading-none mb-1 pr-10">
                                        {{ $item->name }}
                                    </h3>
                                    <p class="text-xs text-gray-500 mb-2">ID: {{ $item->id }}</p>
                                </div>
                                
                                <div class="text-sm text-gray-600 dark:text-gray-400 flex justify-between items-center mt-2 border-t border-dashed border-gray-400 pt-2">
                                    <div class="flex items-center gap-1">
                                        <span>🔗</span>
                                        @if($item->parent)
                                            <span class="font-bold">{{ $item->parent->name }}</span>
                                        @else
                                            <span class="opacity-50">Root</span>
                                        @endif
                                    </div>
                                    
                                    <span class="text-xs bg-gray-200 dark:bg-zinc-700 px-1 rounded">
                                        👤 {{ $item->user->name ?? 'System' }}
                                    </span>
                                </div>
                            </div>

                        @endforeach
                    @else
                        <div class="col-span-full text-center p-4 border-2 border-dashed border-gray-300 dark:border-zinc-700 rounded opacity-50">
                            Belum ada data di kategori ini
                        </div>
                    @endif
                </div>
            </div>
        @endforeach

    </div>
</div>