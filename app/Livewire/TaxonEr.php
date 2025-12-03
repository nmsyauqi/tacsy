<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On; 
use App\Models\Taxon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TaxonEr extends Component
{
    public $name;
    public $rank;
    public $parent_id;
    
    // Properti untuk melacak mode Edit
    public $editingTaxonId = null; 

    public $ranks = ['Kingdom', 'Phylum', 'Class', 'Order', 'Family', 'Genus', 'Species'];

    // Listener Edit
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

    public function cancelEdit()
    {
        $this->reset(['name', 'rank', 'parent_id', 'editingTaxonId']);
    }

    public function save()
    {
        $this->validate([
            'name' => [
                'required', 
                'min:3', 
                'string',
                // Aturan: Harus UNIK di tabel 'taxa' kolom 'name'
                // ->ignore(...): Kecuali data itu sendiri (saat mode Edit)
                Rule::unique('taxa', 'name')->ignore($this->editingTaxonId)
            ],
            'rank' => 'required|string',
            'parent_id' => 'nullable|exists:taxa,id',
        ], [
            // Pesan Error Custom (Biar lebih jelas ke user)
            'name.unique' => 'Nama organisme ini sudah ada di database! Data ganda ditolak.',
        ]);

        if ($this->editingTaxonId) {
            // Update
            $taxon = Taxon::find($this->editingTaxonId);
            $taxon->update([
                'name' => $this->name,
                'rank' => $this->rank,
                'parent_id' => $this->parent_id ?: null,
            ]);
            $message = 'Data berhasil diperbarui!';
        } else {
            // Create
            Taxon::create([
                'name' => $this->name,
                'rank' => $this->rank,
                'user_id' => Auth::id(),
                'parent_id' => $this->parent_id ?: null,
            ]);
            $message = 'Data berhasil ditambahkan!';
        }

        $this->cancelEdit();
        $this->dispatch('taxon-updated'); 
        session()->flash('status', $message);
    }

    public function render()
    {
        // BAGIAN PENTING: Mendefinisikan variabel $potentialParents
        $potentialParents = Taxon::where('rank', '!=', 'Species')
            ->orderBy('name')
            ->get();

        return view('livewire.taxon-er', [
            'parents' => $potentialParents
        ])->layout('layouts.taskbar'); // Menggunakan Layout Taskbar
    }
}