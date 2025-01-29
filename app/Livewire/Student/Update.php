<?php

namespace App\Livewire\Student;

use App\Models\Filiere;
use App\Models\Niveau;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;

class Update extends Component
{

    public User $user;
    public $niveaux;
    public $filieres;

    public $nom = '';
    public $prenom = '';
    public $email = '';
    public $telephone = '';
    public $filiere;
    public $niveau;

    public function rules()
    {
        return [
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($this->user->id),
            ],
            'telephone' => [
                'required',
                'numeric',
                Rule::unique('users', 'telephone')->ignore($this->user->id),
            ],
            'niveau' => 'required|exists:niveaux,id',
            'filiere' => 'required|exists:filieres,id',
        ];
    }


    #[On('userToEdit')]
    public function userToEdit($id)
    {
        $this->user = User::query()->findOrFail($id);
        $this->nom = $this->user->nom;
        $this->prenom = $this->user->prenom;
        $this->email = $this->user->email;
        $this->telephone = $this->user->telephone;
        $this->filiere = $this->user->student->first()->filiere()->first()->id;
        $this->niveau = $this->user->student->first()->niveau()->first()->id;

        $this->filieres = Filiere::query()->where('establishment_id', Auth::user()->establishment_id)->get();

        $this->niveaux = Niveau::query()
            ->whereIn('filiere_id', $this->filieres->pluck('id'))
            ->get();

        $this->dispatch('update-event');
    }

    public function selectNiveau()
    {
        $this->niveaux = Niveau::query()->where('filiere_id', $this->filiere)->get();
        $this->reset(['niveau']);
        $this->dispatch('update-event');
    }


    public function getNiveaux()
    {
        return Niveau::query()->where('filiere_id', $this->filiere)->get();
    }

    public function getFilieres()
    {
        return $this->filieres;
    }

    public function update()
    {

        $this->validate();
        $this->user->nom = $this->nom;
        $this->user->prenom = $this->prenom;
        $this->user->email = $this->email;
        $this->user->telephone = $this->telephone;
        $this->user->role = 'etudiant';
        $this->user->establishment_id = Auth::user()->establishment_id;
        $this->user->update();

        $student = $this->user->student()->first();
        $student->user_id = $this->user->id;
        $student->filiere_id = $this->filiere;
        $student->niveau_id = $this->niveau;
        $student->update();

        redirect()->route('etudiants.index')->with('success', 'Etudiant ' . $this->nom . ' modifié avec succès');
    }

    public function render()
    {
        return view('livewire.student.update');
    }
}
