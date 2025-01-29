<?php

namespace App\Livewire\Document;

use App\Models\Document;
use App\Models\Filiere;
use App\Models\Module;
use App\Models\Niveau;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class Update extends Component
{
    use WithFileUploads;

    public $document;
    public $niveaux;
    public $modules;
    public $filieres;
    public $pdfUrl;

    public $titre = '';
    public $description = '';
    public $fichier;
    public $filiere;
    public $module;
    public $niveau;

    protected $rules = [
        'titre' => 'required|',
        'description' => 'nullable|max:2000',
        'fichier' => 'nullable|mimes:pdf|max:2048',
        'niveau' => 'required|exists:niveaux,id',
        'filiere' => 'required|exists:filieres,id',
        'module' => 'exists:modules,id',
    ];

    // public function mount(Document $document)
    // {
    //     $this->pdfUrl = asset('storage/' . $document->chemin);
    //     $this->document = $document;

    //     $this->titre = $document->titre;
    //     $this->description = $document->description;
    //     $this->fichier = $document->chemin;
    //     $this->module = $document->module->id;
    //     $this->niveau = $document->niveau->id;
    //     $this->filiere = $document->filiere->id;

    //     // Précharger les niveaux et modules si des données existent
    //     $this->niveaux =  Filiere::query()->findOrFail($this->filiere)->niveaux;
    //     $this->modules =  Niveau::query()->findOrFail($this->niveau)->modules;
    // }

    #[On('documentToEdit')]
    public function documentToEdit($id)
    {
        $this->document = Document::query()->findOrFail($id);
        $this->pdfUrl = asset('storage/' . $this->document->chemin);

        $this->titre = $this->document->titre;
        $this->description = $this->document->description;
        // $this->fichier = $this->document->chemin;
        $this->module = $this->document->module->id;
        $this->niveau = $this->document->niveau->id;
        $this->filiere = $this->document->filiere->id;

        $this->filieres = Filiere::query()->where('establishment_id', Auth::user()->establishment_id)->get();

        $this->niveaux = Niveau::query()
            ->where('filiere_id', $this->filiere)
            ->get();

        $this->modules = Module::query()
            ->where('niveau_id', $this->niveau)
            ->get();

        $this->dispatch('update-event');
    }

    public function selectNiveau()
    {
        $this->niveaux = Niveau::query()->where('filiere_id', $this->filiere)->get();

        $this->modules = Module::query()
            ->where('filiere_id', $this->filiere)
            ->get();

        $this->reset(['niveau', 'module']);

        $this->dispatch('update-filiere-select');
    }

    public function selectModule()
    {
        $this->modules = Module::query()->where('niveau_id', $this->niveau)->get();
        $this->dispatch('update-niveau-select');
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

    public function getFilieres()
    {
        return Filiere::query()->where('establishment_id', Auth::user()->establishment_id)->get();
    }

    public function getFiliere()
    {
        return $this->filiere;
    }

    public function getNiveau()
    {
        return $this->niveau;
    }

    public function getModule()
    {
        return $this->filiere;
    }

    public function update()
    {

        $this->validate();

        $this->document->titre = $this->titre;
        $this->document->description = $this->description;
        $this->document->establishment_id = Auth::user()->establishment_id;
        $this->document->filiere_id = $this->filiere;
        $this->document->niveau_id = $this->niveau;
        $this->document->module_id = $this->module;
        if ($this->fichier) {
            Storage::disk('public')->delete($this->document->chemin);
            $path = $this->fichier->store('documents');
            $this->document->chemin = $path;
        }
        $this->document->update();

        redirect()->route('documents.index')->with('success', 'Fichier modifié avec succès');
    }


    public function render()
    {
        // $filieres = Filiere::query()->where('establishment_id', Auth::user()->establishment_id)->get();

        // $this->niveaux = Niveau::query()
        //     ->whereIn('filiere_id', $filieres->pluck('id'))
        //     ->get();

        // $this->modules = Module::query()
        //     ->whereIn('niveau_id', $this->niveaux->pluck('id'))
        //     ->get();

        // return view('livewire.document.update', [
        //     'filieres' => $filieres,
        //     'niveaux' => $this->niveaux,
        //     'modules' => $this->modules,
        // ]);

        return view('livewire.document.update');
    }
}
