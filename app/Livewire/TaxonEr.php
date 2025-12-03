<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Taxon;

class TaxonEr extends Component
{
    public $name;
    public $rank;
    
    public function save()
    {
        // 1. Validasi & Simpan
        $this->validate([
            'name' => 'required|min:3',
            'rank' => 'required',
        ]);

        Taxon::create([
            'name' => $this->name,
            'rank' => $this->rank,
            'user_id' => auth()->id(), // Simpan siapa pembuatnya
        ]);

        // 2. Reset Form
        $this->reset(['name', 'rank']);

        // 3. KUNCI RAHASIA: Kirim sinyal ke TaxonUm agar refresh
        $this->dispatch('taxon-updated'); 
    }

    public function render()
    {
        // Menggunakan layout dashboard (sidebar, navbar, dll)
        return view('livewire.taxon-er')->layout('layouts.app');
    }
}