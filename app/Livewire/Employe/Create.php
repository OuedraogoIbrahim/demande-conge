<?php

namespace App\Livewire\Employe;

use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Create extends Component
{

    // public $niveaux;
    // public $filieres;
    public $services;

    public $matricule = '';
    public $nom = '';
    public $prenom = '';
    public $email = '';
    public $service = '';

    protected $rules = [
        'matricule' => 'required|',
        'nom' => 'required|',
        'prenom' => 'required|',
        'email' => 'required|unique:users,email',
        'service' => 'required|exists:services,id',
    ];

    public function mount()
    {
        $this->services = Service::all();
    }

    public function submit()
    {
        $this->validate();

        $user = new User();
        $user->matricule = $this->matricule;
        $user->nom = $this->nom;
        $user->prenom = $this->prenom;
        $user->email = $this->email;
        $user->role = 'employe';
        $user->password = Hash::make('password');
        $user->service_id = $this->service;
        $user->save();

        redirect()->route('employes')->with('success', 'Employé ' . $this->nom . ' créé avec succès');
    }

    public function render()
    {
        return view('livewire.employe.create');
    }
}
