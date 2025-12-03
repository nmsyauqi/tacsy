<div class="flex flex-col gap-6">
    
    <div class="bg-white dark:bg-zinc-900 p-6 rounded-xl border border-zinc-200 dark:border-zinc-700 shadow-sm">
        <h2 class="text-lg font-semibold mb-4 text-zinc-800 dark:text-zinc-100">Tambah Taxonomy Baru</h2>

        @if (session('status'))
            <div class="mb-4 p-2 bg-green-100 text-green-700 rounded text-sm">
                {{ session('status') }}
            </div>
        @endif

        <form wire:submit="save" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Nama Latin / Umum</label>
                <input type="text" wire:model="name" 
                       class="w-full rounded-md border-zinc-300 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                       placeholder="Contoh: Felis catus">
                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Peringkat (Rank)</label>
                <select wire:model="rank" 
                        class="w-full rounded-md border-zinc-300 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">-- Pilih Rank --</option>
                    <option value="Kingdom">Kingdom</option>
                    <option value="Phylum">Phylum</option>
                    <option value="Class">Class</option>
                    <option value="Order">Order</option>
                    <option value="Family">Family</option>
                    <option value="Genus">Genus</option>
                    <option value="Species">Species</option>
                </select>
                @error('rank') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" 
                        class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm font-medium transition">
                    Simpan Data
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white dark:bg-zinc-900 p-6 rounded-xl border border-zinc-200 dark:border-zinc-700 shadow-sm">
        <h2 class="text-lg font-semibold mb-4 text-zinc-800 dark:text-zinc-100">Database Taxonomy</h2>
        
        <livewire:taxon-um />

    </div>
</div>