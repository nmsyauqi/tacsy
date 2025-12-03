<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Taxon;
use Illuminate\Support\Facades\Auth;

class TaxonUm extends Component
{
    // Agar komponen ini menjadi Full Page Component yang menggunakan Layout
    protected $layout = 'layouts.base'; // Layout minimal yang ada @vite nya

    public $taxons; // Keranjang data
    
    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        // Ambil SEMUA data Takson, urutkan berdasarkan rank
        $this->taxons = Taxon::with('parent')->orderBy('rank')->get();
    }

    // Hanya fungsi render yang tersisa (READ)
    public function render()
    {
        return view('livewire.taxon-um');
    }
}