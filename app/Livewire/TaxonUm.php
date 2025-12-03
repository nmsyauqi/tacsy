<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On; // Import Attribute On
use App\Models\Taxon;

class TaxonUm extends Component
{
    // Hapus properti $layout jika komponen ini akan di-embed di dalam TaxonEr.
    // Atau biarkan default, Laravel cukup pintar mendeteksi context-nya.

    public $search = '';

    // Listener: Jika ada event 'taxon-updated', jalankan render ulang
    #[On('taxon-updated')] 
    public function refreshList()
    {
        // Kosong saja, Livewire otomatis me-render ulang komponen
    }

    public function render()
    {
        // Logic Filter sederhana
        $taxons = Taxon::query()
            ->when($this->search, fn($q) => $q->where('name', 'like', '%'.$this->search.'%'))
            ->with('parent')
            ->orderBy('rank')
            ->get();

        return view('livewire.taxon-um', [
            'taxons' => $taxons
        ]);
    }
}