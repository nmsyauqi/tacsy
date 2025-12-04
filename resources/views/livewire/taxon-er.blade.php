<div class="flex flex-col gap-8 max-w-7xl mx-auto">
    
    <div id="form-editor" class="bg-white dark:bg-zinc-900 p-6 rounded-xl border border-zinc-200 dark:border-zinc-700 shadow-sm relative overflow-hidden transition-all duration-500">
        
        <h2 class="text-xl font-bold mb-6 text-zinc-800 dark:text-zinc-100 flex items-center gap-2">            <span>{{ $editingTaxonId ? '✏️' : '🧬' }}</span> 
            {{ $editingTaxonId ? 'Edit Data Organisme' : 'Tambah Data Organisme' }}
        </h2>

        @if (session('status'))
            <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded-lg border border-green-200 dark:border-green-800 flex items-center gap-2">
                {{ session('status') }}
            </div>
        @endif

        <form wire:submit="save" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300">Nama Latin / Umum</label>
                <input type="text" wire:model="name" 
                       class="w-full rounded-lg border-zinc-300 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm"
                       placeholder="Contoh: Panthera tigris">
                @error('name') <span class="text-red-500 text-xs font-medium">{{ $message }}</span> @enderror
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300">Peringkat (Rank)</label>
                
                {{-- PERUBAHAN DI SINI: Tambahkan .live --}}
                <select wire:model.live="rank" 
                        class="w-full rounded-lg border-zinc-300 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm">
                    <option value="">-- Pilih Tingkatan --</option>
                    @foreach($ranks as $r)
                        <option value="{{ $r }}">{{ $r }}</option>
                    @endforeach
                </select>
                @error('rank') <span class="text-red-500 text-xs font-medium">{{ $message }}</span> @enderror
            </div>

            <div class="md:col-span-2 space-y-2">
                <label class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300">Induk (Parent)</label>
                <select wire:model="parent_id" 
                        class="w-full rounded-lg border-zinc-300 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm">
                    <option value="">-- Tidak Ada Induk (Root/Kingdom) --</option>
                    @foreach($parents as $p)
                        <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->rank }})</option>
                    @endforeach
                </select>
                
                @error('parent_id') 
                    <span class="text-red-500 text-xs font-medium">{{ $message }}</span> 
                @enderror
            </div>

            <div class="md:col-span-2 flex justify-end gap-3 pt-2">
                
                @if($editingTaxonId)
                    <button type="button" wire:click="cancelEdit"
                            class="px-4 py-2 border border-zinc-300 dark:border-zinc-600 text-zinc-700 dark:text-zinc-300 rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800 transition">
                        Batal
                    </button>
                @endif

                <button type="submit" 
                        class="px-6 py-2.5 {{ $editingTaxonId ? 'bg-indigo-600 hover:bg-indigo-700' : 'bg-zinc-900 dark:bg-white hover:opacity-90' }} text-white dark:text-black rounded-lg font-medium shadow-lg transition-all transform hover:-translate-y-0.5">
                    {{ $editingTaxonId ? 'Update Perubahan' : '+ Simpan ke Database' }}
                </button>
            </div>
        </form>
    </div>

    <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 overflow-hidden bg-gray-50 dark:bg-zinc-900/50">
        <div class="p-4 border-b border-zinc-200 dark:border-zinc-700 bg-zinc-100 dark:bg-zinc-800 flex justify-between items-center">
            <h2 class="text-sm font-bold uppercase tracking-wider text-zinc-500">Preview Database</h2>
            <span class="text-xs bg-zinc-200 dark:bg-zinc-700 px-2 py-1 rounded text-zinc-600 dark:text-zinc-300">Mode: Retro View</span>
        </div>
        
        <div class="p-6 overflow-x-auto">
            <livewire:taxon-um />
        </div>
    </div>
</div>