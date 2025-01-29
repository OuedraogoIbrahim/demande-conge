<?php

namespace App\Livewire\Professor;

use App\Models\Coordinateur;
use App\Models\Filiere;
use App\Models\Niveau;
use App\Models\Professor;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Update extends Component
{
    public Professor $professeur;
    public User $user;

    public $niveaux;
    public $modules;

    public $nom;
    public $prenom;
    public $email;
    public $telephone;
    public $filiere;
    public $module = [];
    public $niveau;
    public $coordinateur;

    public function rules()
    {
        return [
            'nom' => 'required',
            'prenom' => 'required',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->user->id)],
            'telephone' => ['required', 'numeric', Rule::unique('users', 'telephone')->ignore($this->user->id)],
            'niveau' => 'required|exists:niveaux,id',
            'filiere' => 'required|exists:filieres,id',
            'module' => 'required|array',
            'module.*' => 'exists:modules,id',
            'coordinateur' => 'required|in:oui,non'
        ];
    }

    public function mount(User $user)
    {
        $this->user = $user;
        $this->professeur = Professor::query()->where('user_id', $user->id)->first();

        $this->nom = $user->nom;
        $this->prenom = $user->prenom;
        $this->email = $user->email;
        $this->telephone = $user->telephone;

        $this->module = $this->professeur->modules->pluck('id')->toArray();
        $this->niveau = $this->professeur->modules->first()?->niveau_id;
        $this->filiere = $this->professeur->modules->first()?->niveau->filiere_id;

        // Précharger les niveaux et modules si des données existent
        $this->niveaux =  Filiere::query()->findOrFail($this->filiere)->niveaux;
        $this->modules =  Niveau::query()->findOrFail($this->niveau)->modules;

        $this->coordinateur = $this->user->role == 'coordinateur' ? 'oui' : 'non';
    }

    public function update()
    {
        $this->validate();

        $this->user->nom = $this->nom;
        $this->user->prenom = $this->prenom;
        $this->user->email = $this->email;
        $this->user->telephone = $this->telephone;
        $this->user->update();

        $this->professeur->modules()->sync($this->module);

        if ($this->coordinateur == 'oui') {
            // Verifier si cet utilisteur etait deja coordinateur
            if (!Coordinateur::query()->where('user_id', $this->user->id)->first()) {
                $coordinateur = new Coordinateur();
                $coordinateur->user_id = $this->user->id;
                $coordinateur->filiere_id = $this->filiere;
                $coordinateur->save();

                $this->user->role = 'coordinateur';
                $this->user->update();
            }
        } else {
            if (Coordinateur::query()->where('user_id', $this->user->id)->first()) {
                $this->user->role = 'professeur';
                $this->user->update();

                $c = Coordinateur::query()->where('user_id', $this->user->id)->first();
                $c->delete();
            }
        }

        redirect()->route('professeurs.index')->with('success', 'Professeur ' . $this->nom . ' mis à jour avec succès.');
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
            $filiereChoice = Filiere::find($this->filiere);
            $this->niveaux = $filiereChoice?->niveaux;
        }

        if ($this->niveau) {
            $niveauChoice = Niveau::find($this->niveau);
            $this->modules = $niveauChoice?->modules;
        }

        return view('livewire.professor.update', [
            'filieres' => $filieres,
            'niveaux' => $this->niveaux,
            'modules' => $this->modules,
        ]);
    }
}
