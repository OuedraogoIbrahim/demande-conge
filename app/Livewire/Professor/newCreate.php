<?php

namespace App\Livewire\Professor;

use App\Models\Coordinateur;
use App\Models\Filiere;
use App\Models\Niveau;
use App\Models\Professor;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Create extends Component
{

    public $niveaux;
    public $modules;

    public $nom = '';
    public $prenom = '';
    public $email = '';
    public $telephone = '';
    public $filiere;
    public $module = [];
    public $niveau;
    public $coordinateur;

    protected $rules = [
        'nom' => 'required|',
        'prenom' => 'required|',
        'email' => 'required|unique:users,email',
        'telephone' => 'required|numeric|unique:users,telephone',
        'niveau' => 'required|exists:niveaux,id',
        'filiere' => 'required|exists:filieres,id',
        'module' => 'required|array',
        'module.*' => 'exists:modules,id',
        'coordinateur' => 'required|in:oui,non'
    ];

    public function submit()
    {

        $this->validate();
        $user = new User();
        $user->nom = $this->nom;
        $user->prenom = $this->prenom;
        $user->email = $this->email;
        $user->telephone = $this->telephone;

        if ($this->coordinateur == 'oui') {
            $user->role = 'coordinateur';
        } else {
            $user->role = 'professeur';
        }
        $user->password = Hash::make('password');
        $user->establishment_id = Auth::user()->establishment_id;
        $user->save();

        $professeur = new Professor();
        $professeur->user_id = $user->id;
        $professeur->save();

        if ($this->coordinateur == 'oui') {
            $coordinateur = new Coordinateur();
            $coordinateur->user_id = $user->id;
            $coordinateur->filiere_id = $this->filiere;
            $coordinateur->save();
        }

        $professeur->modules()->attach($this->module);

        redirect()->route('professeurs.index')->with('success', 'Professeur ' . $this->nom . ' créé avec succès');
    }

    public function resetSelection($field)
    {
        if ($field === 'filiere') {
            $this->niveau = null;
            $this->module = null;
            $this->niveaux = null;
            $this->modules = null;
        }

        if ($field === 'niveau') {
            $this->module = null;
            $this->modules = null;
        }
    }

    public function render()
    {
        $filieres = Filiere::query()->where('establishment_id', Auth::user()->establishment_id)->get();

        if ($this->filiere) {
            $filiereChoice = Filiere::query()->findorFail($this->filiere);
            $this->niveaux = $filiereChoice->niveaux;
        }

        if ($this->niveau) {
            $niveauChoice = Niveau::findorFail($this->niveau);
            $this->modules = $niveauChoice->modules;
        }

        return view('livewire.professor.create', [
            'filieres' => $filieres,
            'niveaux' => $this->niveaux,
            'modules' => $this->modules,
        ]);
    }
}
