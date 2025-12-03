<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On; 
use App\Models\Taxon;

class TaxonUm extends Component
{
    #[On('taxon-updated')] 
    public function refreshList() {}

    public function triggerEdit($id)
    {
        // UBAH JADI REDIRECT:
        // Mengarahkan ke route 'dashboard' dengan parameter query '?edit=ID'
        return redirect()->route('dashboard', ['edit' => $id]);
    }

    public function render()
    {
        // ... (kode query render tetap sama) ...
        $groupedTaxons = Taxon::with('parent', 'user')
            ->orderBy('name')
            ->get()
            ->groupBy('rank');

        $rankOrder = ['Kingdom', 'Phylum', 'Class', 'Order', 'Family', 'Genus', 'Species'];

        return view('livewire.taxon-um', [
            'groupedTaxons' => $groupedTaxons,
            'rankOrder' => $rankOrder
        ])->layout('layouts.taskbar');
    }
}