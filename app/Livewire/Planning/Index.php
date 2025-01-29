<?php

namespace App\Livewire\Planning;

use App\Models\Classe;
use App\Models\Filiere;
use App\Models\Niveau;
use App\Models\Planning;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{

    public  $events;
    public $canEditEvents;
    public $filiere = '';
    public $niveau = '';

    public function mount()
    {
        $this->canEditEvents = in_array(Auth::user()->role, ['superviseur', 'coordinateur']);
    }

    public function selectFiliere($id)
    {
        $this->filiere = $id;
        $this->reset(['niveau']);
    }

    public function render()
    {

        // $filieres = Filiere::query()->where('nom', 'informatique')->where('establishment_id', Auth::user()->establishment_id)->first();
        $filieres = Filiere::query()->find(Auth::user()->coordinateur->first()->filiere_id);
        $modules = $filieres->modules;

        $classes = Classe::query()->where('establishment_id', Auth::user()->establishment_id)->get();

        return view('livewire.planning.index', compact('modules', 'classes'));
    }
}
