<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On; 
use App\Models\Taxon;
use Illuminate\Support\Facades\Auth;

class TaxonEr extends Component
{
    public $name;
    public $rank;
    public $parent_id;
    
    // Properti baru untuk melacak mode Edit
    public $editingTaxonId = null; 

    public $ranks = ['Kingdom', 'Phylum', 'Class', 'Order', 'Family', 'Genus', 'Species'];

    // LISTENER: Menangkap sinyal saat tombol Edit di bawah diklik
    #[On('edit-taxon')]
    public function edit($id)
    {
        $taxon = Taxon::find($id);
        
        if ($taxon) {
            $this->editingTaxonId = $taxon->id;
            $this->name = $taxon->name;
            $this->rank = $taxon->rank;
            $this->parent_id = $taxon->parent_id;
        }
    }

    // Fungsi untuk batal edit dan kembali ke mode tambah
    public function cancelEdit()
    {
        $this->reset(['name', 'rank', 'parent_id', 'editingTaxonId']);
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|min:3|string',
            'rank' => 'required|string',
            'parent_id' => 'nullable|exists:taxa,id',
        ]);

        if ($this->editingTaxonId) {
            // --- LOGIKA UPDATE ---
            $taxon = Taxon::find($this->editingTaxonId);
            $taxon->update([
                'name' => $this->name,
                'rank' => $this->rank,
                'parent_id' => $this->parent_id ?: null,
            ]);
            $message = 'Data berhasil diperbarui!';
        } else {
            // --- LOGIKA CREATE ---
            Taxon::create([
                'name' => $this->name,
                'rank' => $this->rank,
                'user_id' => Auth::id(),
                'parent_id' => $this->parent_id ?: null,
            ]);
            $message = 'Data berhasil ditambahkan!';
        }

        // Reset form ke mode awal
        $this->cancelEdit();
        
        // Refresh list di bawah
        $this->dispatch('taxon-updated'); 
        
        session()->flash('status', $message);
    }

    public function render()
    {
        $potentialParents = Taxon::where('rank', '!=', 'Species')->orderBy('name')->get();

        return view('livewire.taxon-er', [
            'parents' => $potentialParents
        ]);
    }
}