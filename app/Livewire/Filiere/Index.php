<?php

namespace App\Livewire\Filiere;

use App\Models\Filiere;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{

    use WithPagination;

    public $search;

    protected $paginationTheme = 'bootstrap';

    public function sendFiliere($id)
    {
        $this->dispatch('filiereToEdit', id: $id)->to(Update::class);
    }


    public function deleteFiliere($id)
    {
        $filiere = Filiere::query()->findOrFail($id);
        $filiere->delete();
    }
    public function render()
    {
        $filieres = Filiere::query()->where('establishment_id', Auth::user()->establishment_id)
            ->when($this->search, function ($query) {
                $query->where('nom', 'like', '%' . $this->search . '%');
            })
            ->paginate(10);
        return view('livewire.filiere.index', compact('filieres'));
    }
}
