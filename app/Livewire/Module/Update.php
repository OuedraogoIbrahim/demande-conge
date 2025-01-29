<?php

namespace App\Livewire\Module;

use App\Models\Filiere;
use App\Models\Module;
use App\Models\Niveau;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class Update extends Component
{

    public Module $module;
    public  $niveaux;

    public $name;
    public $description;
    public $coefficient;
    public $nombre_heures;
    public $niveau = '';
    public $filiere = '';

    protected $rules = [
        'name' => 'required|',
        'coefficient' => 'required|numeric',
        'niveau' => 'required|exists:niveaux,id',
        'nombre_heures' => 'required|numeric',
        'filiere' => 'required|exists:filieres,id',
    ];


    public function mount() {}

    #[On('moduleToEdit')]
    public function classeToEdit($id)
    {
        $this->module = Module::query()->findOrFail($id);
        $this->name = $this->module->nom;
        $this->description = $this->module->description;
        $this->coefficient = $this->module->coefficient;
        $this->nombre_heures = $this->module->nombre_heures;
        $this->niveau = $this->module->niveau_id;
        $this->filiere = $this->module->filiere_id;

        $this->niveaux = $this->module->filiere->niveaux;

        $this->dispatch('update-event');
    }

    public function update()
    {
        $this->validate();
        $this->module->nom = $this->name;
        $this->module->description = $this->description;
        $this->module->coefficient = $this->coefficient;
        $this->module->nombre_heures = $this->nombre_heures;
        $this->module->niveau_id = $this->niveau;
        $this->module->filiere_id = $this->filiere;
        $this->module->update();
        redirect()->route('modules.index', ['filiere' => $this->filiere])->with('success', 'Module ' . $this->name . ' modifié avec succès');
    }

    public function getNiveaux()
    {
        return $this->niveaux;
    }

    public function render()
    {
        return view('livewire.module.update');
    }
}
