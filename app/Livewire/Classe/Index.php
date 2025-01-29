<?php

namespace App\Livewire\Classe;

use App\Models\Classe;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search;

    protected $paginationTheme = 'bootstrap';


    public function deleteClasse($id)
    {
        $classe = Classe::query()->findOrFail($id);
        $classe->delete();
    }

    public function sendClasse($id)
    {
        $this->dispatch('classeToEdit', id: $id)->to(Update::class);
    }

    public function render()
    {
        $classes = Classe::query()->where('establishment_id', Auth::user()->establishment_id)
            ->when($this->search, function ($query) {
                $query->where('nom', 'like', '%' . $this->search . '%');
            })
            ->paginate(10);

        return view('livewire.classe.index', compact('classes'));
    }
}
