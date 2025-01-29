<?php

namespace App\Livewire\Module;

use App\Models\Filiere;
use App\Models\Module;
use Livewire\Component;

class Create extends Component
{

    public $name = '';
    public $description = '';
    public $coefficient;
    public $nombre_heures;
    public $niveau = '';


    public Filiere $filiere;
    public $niveaux;

    protected $rules = [
        'name' => 'required|',
        'coefficient' => 'required|numeric',
        'nombre_heures' => 'required|numeric',
        'niveau' => 'required|exists:niveaux,id'
    ];

    public function mount(Filiere $filiere)
    {
        $this->filiere = $filiere;
        $this->niveaux = $this->filiere->niveaux;
    }

    public function submit()
    {
        $this->validate();
        $module = new Module();
        $module->nom = $this->name;
        $module->description = $this->description;
        $module->coefficient = $this->coefficient;
        $module->nombre_heures = $this->nombre_heures;
        $module->heures_utilisees = 0;
        $module->filiere_id = $this->filiere->id;
        $module->niveau_id = $this->niveau;
        $module->save();
        redirect()->route('modules.index', ['filiere' => $this->filiere->id])->with('success', 'Module ' . $this->name . ' créé avec succès');
    }

    public function render()
    {
        return view('livewire.module.create');
    }
}
