<?php

namespace App\Livewire\Student;

use App\Models\Filiere;
use App\Models\Niveau;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Create extends Component
{
    public $niveaux;
    public $filieres;

    public $nom = '';
    public $prenom = '';
    public $email = '';
    public $telephone = '';
    public $filiere;
    public $niveau;

    protected $rules = [
        'nom' => 'required|',
        'prenom' => 'required|',
        'email' => 'required|unique:users,email',
        'telephone' => 'required|numeric|unique:users,telephone',
        'niveau' => 'required|exists:niveaux,id',
        'filiere' => 'required|exists:filieres,id',

    ];

    public function mount()
    {
        $this->filieres = Filiere::query()->where('establishment_id', Auth::user()->establishment_id)->get();

        $this->niveaux = Niveau::query()
            ->whereIn('filiere_id', $this->filieres->pluck('id'))
            ->get();
    }

    public function submit()
    {

        $this->validate();

        $user = new User();
        $user->nom = $this->nom;
        $user->prenom = $this->prenom;
        $user->email = $this->email;
        $user->telephone = $this->telephone;
        $user->role = 'etudiant';
        $user->password = Hash::make('password');
        $user->establishment_id = Auth::user()->establishment_id;
        $user->save();

        $student = new Student();
        $student->user_id = $user->id;
        $student->filiere_id = $this->filiere;
        $student->niveau_id = $this->niveau;
        $student->save();

        redirect()->route('etudiants.index')->with('success', 'Etudiant ' . $this->nom . ' créé avec succès');
    }

    public function selectNiveau()
    {
        $this->niveaux = Niveau::query()->where('filiere_id', $this->filiere)->get();
        $this->dispatch('create-event');
    }

    public function getNiveaux()
    {
        return Niveau::query()->where('filiere_id', $this->filiere)->get();
    }

    public function render()
    {
        return view('livewire.student.create');
    }
}
