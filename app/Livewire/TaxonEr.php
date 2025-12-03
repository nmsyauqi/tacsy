<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Taxon;
use Illuminate\Support\Facades\Auth;

class TaxonEr extends Component
{
    public $name;
    public $rank;

    protected $rules = [
        'name' => 'required|min:3|string',
        'rank' => 'required|string',
    ];

    public function save()
    {
        $this->validate();

        Taxon::create([
            'name' => $this->name,
            'rank' => $this->rank,
            'user_id' => Auth::id(), 
            'parent_id' => null, 
        ]);

        $this->reset(['name', 'rank']);

        $this->dispatch('taxon-updated'); 
        
        session()->flash('status', 'Taxonomy berhasil ditambahkan!');
    }

    public function render()
    {
        return view('livewire.taxon-er');
    }
}