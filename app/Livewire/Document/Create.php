<?php

namespace App\Livewire\Document;

use App\Models\Document;
use App\Models\Filiere;
use App\Models\Module;
use App\Models\Niveau;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{

    use WithFileUploads;

    public $niveaux;
    public $modules;
    public $filieres;

    public $titre = '';
    public $description = '';
    public $fichier;
    public $filiere;
    public $module;
    public $niveau;

    protected $rules = [
        'titre' => 'required|',
        'description' => 'nullable|max:2000',
        'fichier' => 'required|mimes:pdf|max:2048',
        'niveau' => 'required|exists:niveaux,id',
        'filiere' => 'required|exists:filieres,id',
        'module' => 'exists:modules,id',
    ];

    public function mount()
    {
        $this->filieres = Filiere::query()->where('establishment_id', Auth::user()->establishment_id)->get();

        $this->niveaux = Niveau::query()
            ->whereIn('filiere_id', $this->filieres->pluck('id'))
            ->get();

        $this->modules = Module::query()
            ->whereIn('niveau_id', $this->niveaux->pluck('id'))
            ->get();
    }

    public function selectNiveau()
    {
        $this->niveaux = Niveau::query()->where('filiere_id', $this->filiere)->get();

        $this->modules = Module::query()
            ->whereIn('niveau_id', $this->niveaux->pluck('id'))
            ->get();

        $this->dispatch('create-niveau-event');
    }

    public function selectModule()
    {
        $this->modules = Module::query()->where('niveau_id', $this->niveau)->get();
        $this->dispatch('create-module-event');
    }

    public function getNiveaux()
    {
        // return Niveau::query()->where('filiere_id', $this->filiere)->get();
        return $this->niveaux;
    }

    public function getModules()
    {
        // return Niveau::query()->where('filiere_id', $this->filiere)->get();
        return $this->modules;
    }


    public function submit()
    {

        $this->validate();

        $document = new Document();
        $document->titre = $this->titre;
        $document->description = $this->description;
        $document->establishment_id = Auth::user()->establishment_id;
        $document->filiere_id = $this->filiere;
        $document->niveau_id = $this->niveau;
        $document->module_id = $this->module;
        $path = $this->fichier->store('documents/' . Auth::user()->establishment_id);
        $document->chemin = $path;
        $document->save();

        redirect()->route('documents.index')->with('success', 'Fichier ajouté avec succès');
    }

    public function render()
    {

        return view('livewire.document.create');
    }
}
