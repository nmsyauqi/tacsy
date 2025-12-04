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

    public function getAllowedParentRanks()
    {
        // Jika Rank belum dipilih, jangan tampilkan apa-apa (atau tampilkan semua)
        if (!$this->rank) return $this->ranks;

        // Cari posisi index rank yang dipilih saat ini
        $currentIndex = array_search($this->rank, $this->ranks);

        // Ambil semua rank yang indexnya LEBIH KECIL (Lebih tinggi hierarkinya)
        // Contoh: Pilih 'Family' (index 4), maka ambil index 0,1,2,3
        return array_slice($this->ranks, 0, $currentIndex);
    }

    public function updatedRank()
    {
        // Reset pilihan parent karena daftar parent-nya pasti berubah
        $this->reset('parent_id'); 
    }

    public function render()
    {
        // 1. Tentukan Rank Induk yang Valid (HANYA SATU TINGKAT DI ATAS)
        $allowedParentRank = null;

        if ($this->rank) {
            // Cari posisi index rank saat ini (Contoh: Genus = index 5)
            $currentIndex = array_search($this->rank, $this->ranks);

            // Bapaknya adalah index - 1 (Contoh: Family = index 4)
            // Pastikan index tidak negatif (Kingdom tidak punya bapak)
            if ($currentIndex !== false && $currentIndex > 0) {
                $allowedParentRank = $this->ranks[$currentIndex - 1];
            }
        }

        // 2. Buat Query
        $query = Taxon::query();

        if ($allowedParentRank) {
            // Jika ada rank bapak yang valid, ambil data yang rank-nya ITU SAJA
            $query->where('rank', $allowedParentRank);
        } else {
            // Jika tidak ada bapak (misal pilih Kingdom, atau belum pilih rank), 
            // kosongkan list parent agar user tidak bingung
            $query->whereNull('id'); // Trik agar hasil kosong
        }

        // 3. Filter Diri Sendiri (Mode Edit)
        if ($this->editingTaxonId) {
            $query->where('id', '!=', $this->editingTaxonId);
        }

        // 4. Sortir & Render
        return view('livewire.taxon-er', [
            'parents' => $query->orderBy('name')->get()
        ])->layout('layouts.taskbar');
    }
}