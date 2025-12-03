<div class="p-6 space-y-6">
    
    <div class="bg-white p-4 rounded shadow">
        <h2 class="text-lg font-bold mb-4">Input Taxonomy Baru</h2>
        <form wire:submit="save">
            <input wire:model="name" type="text" placeholder="Nama Taxon" class="border p-2 rounded">
            <input wire:model="rank" type="text" placeholder="Rank (Kingdom, Genus...)" class="border p-2 rounded">
            
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Simpan</button>
        </form>
    </div>

    <div class="bg-gray-50 p-4 rounded border">
        <h3 class="text-md font-semibold mb-2">Database Saat Ini:</h3>
        
        <livewire:taxon-um /> 
        
    </div>
</div>