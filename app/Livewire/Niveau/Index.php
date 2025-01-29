<?php

namespace App\Livewire\Niveau;

use App\Models\Filiere;
use App\Models\Niveau;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{

    use WithPagination;

    public $filieres;
    public $filiere;
    public $search;

    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->filieres = Filiere::query()->where('establishment_id', Auth::user()->establishment_id)->get();
    }

    public function deleteNiveau($id)
    {
        $niveau = Niveau::query()->findOrFail($id);
        $niveau->delete();
    }

    public function sendNiveau($id)
    {
        $this->dispatch('niveauToEdit', id: $id)->to(Update::class);
    }


    public function render()
    {
        $niveaux = Niveau::query()
            ->when($this->filiere, function ($query) {
                $query->where('filiere_id', $this->filiere);
            })
            ->when($this->search, function ($query) {
                $query->where('nom', 'like', '%' . $this->search . '%');
            })
            ->paginate(10);

        return view('livewire.niveau.index', compact('niveaux'));
    }
}
