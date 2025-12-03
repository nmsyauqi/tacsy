<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On; 
use App\Models\Taxon;

class TaxonUm extends Component
{
    #[On('taxon-updated')] 
    public function refreshList() {}

    // FUNGSI BARU: Memicu edit
    public function triggerEdit($id)
    {
        // Kirim sinyal ke TaxonEr (Parent Component)
        $this->dispatch('edit-taxon', id: $id); 
    }

    public function render()
    {
        $groupedTaxons = Taxon::with('parent', 'user')
            ->orderBy('name')
            ->get()
            ->groupBy('rank');

        $rankOrder = ['Kingdom', 'Phylum', 'Class', 'Order', 'Family', 'Genus', 'Species'];

        return view('livewire.taxon-um', [
            'groupedTaxons' => $groupedTaxons,
            'rankOrder' => $rankOrder
        ])->layout('layouts.base');
    }
}