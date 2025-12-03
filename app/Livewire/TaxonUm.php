<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On; 
use App\Models\Taxon;

class TaxonUm extends Component
{

    public $search = '';

    #[On('taxon-updated')] 
    public function refreshList()
    {
        // 
    }

    public function render()
    {
        $taxons = Taxon::query()
            ->when($this->search, fn($q) => $q->where('name', 'like', '%'.$this->search.'%'))
            ->with('parent')
            ->orderBy('rank')
            ->get();

        return view('livewire.taxon-um', [
            'taxons' => $taxons
        ])->layout('layouts.base');
    }
}