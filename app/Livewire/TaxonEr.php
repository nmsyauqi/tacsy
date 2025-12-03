<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On; 
use App\Models\Taxon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule; // <-- Pastikan import ini ada

class TaxonEr extends Component
{
    public $name;
    public $rank;
    public $parent_id;
    
    public $editingTaxonId = null; 

    // Urutan: 0 (Tertinggi) -> 6 (Terendah)
    public $ranks = ['Kingdom', 'Phylum', 'Class', 'Order', 'Family', 'Genus', 'Species'];

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
            // 1. Validasi Nama Unik
            'name' => [
                'required', 
                'min:3', 
                'string',
                Rule::unique('taxa', 'name')->ignore($this->editingTaxonId)
            ],
            
            'rank' => 'required|string|in:' . implode(',', $this->ranks),
            
            // 2. Validasi Hierarki
            'parent_id' => [
                'nullable',
                'exists:taxa,id',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $parent = Taxon::find($value);
                        
                        $parentIdx = array_search($parent->rank, $this->ranks);
                        $currentIdx = array_search($this->rank, $this->ranks);

                        // Parent harus punya index lebih kecil (rank lebih tinggi)
                        if ($parentIdx !== false && $currentIdx !== false) {
                            if ($parentIdx >= $currentIdx) {
                                $fail("Hierarki Salah: Induk ({$parent->rank}) tidak boleh lebih tinggi atau sama dengan data ini ({$this->rank}).");
                            }
                        }
                    }
                },
            ],
        ], [
            'name.unique' => 'Nama organisme ini sudah ada di database! Data ganda ditolak.',
        ]);

        if ($this->editingTaxonId) {
            $taxon = Taxon::find($this->editingTaxonId);
            $taxon->update([
                'name' => $this->name,
                'rank' => $this->rank,
                'parent_id' => $this->parent_id ?: null,
            ]);
            $message = 'Data berhasil diperbarui!';
        } else {
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
        $potentialParents = Taxon::where('rank', '!=', 'Species')
            ->orderBy('name')
            ->get();

        return view('livewire.taxon-er', [
            'parents' => $potentialParents
        ])->layout('layouts.taskbar');
    }
}