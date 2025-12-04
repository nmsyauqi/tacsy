<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On; 
use Livewire\Attributes\Url; // <-- TAMBAHKAN IMPORT INI
use App\Models\Taxon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TaxonEr extends Component
{
    // MENANGKAP URL:
    // Jika urlnya /dashboard?edit=5, maka variabel ini akan berisi 5
    #[Url(as: 'edit')] 
    public $urlEditId = ''; 

    public $name;
    public $rank;
    public $parent_id;
    public $editingTaxonId = null; 

    public $ranks = ['Kingdom', 'Phylum', 'Class', 'Order', 'Family', 'Genus', 'Species'];

    // FUNGSI MOUNT: Dijalankan saat halaman pertama kali dimuat
    public function mount()
    {
        // Jika ada ID di URL, langsung masuk mode edit
        if ($this->urlEditId) {
            $this->edit($this->urlEditId);
        }
    }

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

    // ... (Sisa fungsi cancelEdit, save, render BIARKAN SAMA seperti sebelumnya) ...
    public function cancelEdit()
    {
        $this->reset(['name', 'rank', 'parent_id', 'editingTaxonId', 'urlEditId']);
        // Hapus juga parameter di URL agar bersih
        $this->js("window.history.replaceState(null, '', window.location.pathname)");
    }

    public function save()
    {
        // ... (Copy paste isi fungsi save yang terakhir kamu punya) ...
        $this->validate([
            'name' => [
                'required', 'min:3', 'string',
                Rule::unique('taxa', 'name')->ignore($this->editingTaxonId)
            ],
            'rank' => 'required|string|in:' . implode(',', $this->ranks),
            'parent_id' => [
                'nullable', 'exists:taxa,id',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $parent = Taxon::find($value);
                        $parentIdx = array_search($parent->rank, $this->ranks);
                        $currentIdx = array_search($this->rank, $this->ranks);
                        if ($parentIdx !== false && $currentIdx !== false && $parentIdx >= $currentIdx) {
                            $fail("Hierarki Salah: Induk ({$parent->rank}) tidak boleh lebih rendah/sama dengan data ini ({$this->rank}).");
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
        $query = Taxon::where('rank', '!=', 'Species');

        if ($this->editingTaxonId) {
            $query->where('id', '!=', $this->editingTaxonId);
        }

        $sortedParents = $query->get()->sortBy(function ($taxon) {
            $rankIndex = array_search($taxon->rank, $this->ranks);
            
            return sprintf('%02d_%s', $rankIndex, $taxon->name);
        });

        return view('livewire.taxon-er', [
            'parents' => $sortedParents
        ])->layout('layouts.taskbar');
    }
}