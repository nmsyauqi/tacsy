<div class="w-full font-pixel text-gray-800 dark:text-gray-200">

    <div class="text-center mb-8">
        <h1 class="text-3xl md:text-5xl font-bold text-mc-wood bg-mc-sand inline-block px-6 py-2 border-4 border-black transform -rotate-1 shadow-[4px_4px_0px_0px_rgba(0,0,0,0.2)]">
            🌳 POHON KEHIDUPAN 🌳
        </h1>
    </div>
    
    <div class="flex gap-6 overflow-x-auto pb-8 px-2 snap-x">
        
        @foreach($rankOrder as $rank)
            <div class="min-w-[280px] w-[300px] flex-shrink-0 snap-start">
                
                <div class="bg-black text-white p-2 text-center text-xl font-bold border-b-4 border-mc-leaf mb-4 rounded-t-lg">
                    {{ strtoupper($rank) }}
                </div>

                <div class="space-y-4">
                    @if(isset($groupedTaxons[$rank]))
                        @foreach($groupedTaxons[$rank] as $item)
                            
                            <div class="panel-mc p-3 hover:scale-[1.02] transition-transform duration-200 group relative">
                                
                                @auth
                                    <button wire:click="triggerEdit({{ $item->id }})" 
                                            class="absolute top-2 right-2 p-1 bg-yellow-400 text-black border-2 border-black hover:bg-yellow-300 shadow-sm z-10 opacity-0 group-hover:opacity-100 transition-opacity"
                                            title="Edit Data">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </button>
                                @endauth

                                <h3 class="text-2xl font-bold text-mc-wood dark:text-indigo-400 leading-none mb-1 pr-6">
                                    {{ $item->name }}
                                </h3>
                                
                                <div class="text-sm text-gray-600 dark:text-gray-400 flex items-center gap-1 mt-2 border-t border-dashed border-gray-400 pt-2">
                                    <span>🔗</span>
                                    @if($item->parent)
                                        <span>Induk: {{ $item->parent->name }}</span>
                                    @else
                                        <span class="opacity-50">Root</span>
                                    @endif
                                </div>
                            </div>

                        @endforeach
                    @else
                        <div class="text-center p-4 border-2 border-dashed border-gray-300 dark:border-zinc-700 rounded opacity-50">
                            Kosong
                        </div>
                    @endif
                </div>
            </div>
        @endforeach

    </div>
</div>