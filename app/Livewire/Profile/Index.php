<?php

namespace App\Livewire\Profile;

use App\Models\establishment;
use App\Models\Note;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Index extends Component
{

    public $user;
    public $nom = '';
    public $prenom = '';
    public $email = '';
    public $telephone = '';
    public $password = '';
    public $role = '';
    public $etablissement = '';
    public $modulesEnseignes;
    public $filiereCoordinateur;

    public $hasDeleted = false;


    public function rules()
    {
        return [
            'nom' => 'required',
            'prenom' => 'required',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->user->id)],
            'telephone' => ['required', 'numeric', Rule::unique('users', 'telephone')->ignore($this->user->id)],
            'password' => ['nullable', 'different:password'],
        ];
    }

    public function mount()
    {
        $this->user = Auth::user();
        $this->nom = $this->user->nom;
        $this->prenom = $this->user->prenom;
        $this->email = $this->user->email;
        $this->telephone = $this->user->telephone;
        $this->role = $this->user->role;
        $this->password = $this->user->password;
        $this->etablissement = $this->user->establishment->nom;

        if ($this->user->role == 'coordinateur' || $this->role == 'professeur') {
            if ($this->user->role == 'coordinateur') {
                $coordinateur = $this->user->coordinateur->first();
                $this->filiereCoordinateur = $coordinateur->filiere->nom;;
            }
            $professor = $this->user->professor->first();
            $this->modulesEnseignes = $professor->modules;
        }
    }

    public function update()
    {
        $this->validate();

        $this->user->nom = $this->nom;
        $this->user->prenom = $this->prenom;
        $this->user->email = $this->email;
        $this->user->telephone = $this->telephone;
        $this->user->update();

        return redirect()->route('profile')->with('success', 'Profile mis à jour avec succès');
    }

    public function reinitialiser()
    {
        $this->nom = $this->user->nom;
        $this->prenom = $this->user->prenom;
        $this->email = $this->user->email;
        $this->telephone = $this->user->telephone;
    }

    public function deleteAccount()
    {
        if ($this->user->role == 'superviseur') {
            $establishment = establishment::query()->find($this->user->establishment_id);
            $establishment->delete();
        } else {
            $this->user->delete();
        }
        redirect()->route('register')->with('success', 'Compte supprimé avec succès');
    }

    public function render()
    {
        $notes = Note::query()->where('user_id', $this->user->id)->first();
        return view('livewire.profile.index', compact('notes'));
    }
}
