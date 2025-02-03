<?php

namespace App\Livewire\Employe;

use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;

class Update extends Component
{


    public User $user;
    public $services;

    public $nom = '';
    public $prenom = '';
    public $email = '';
    public $matricule = '';
    public $service;

    public function rules()
    {
        return [
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'service' => 'required|exists:services,id',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($this->user->id),
            ],
            'matricule' => [
                'required',
                Rule::unique('users', 'matricule')->ignore($this->user->id),
            ],

        ];
    }


    #[On('userToEdit')]
    public function userToEdit($id)
    {
        $this->user = User::query()->findOrFail($id);
        $this->nom = $this->user->nom;
        $this->prenom = $this->user->prenom;
        $this->email = $this->user->email;
        $this->matricule = $this->user->matricule;
        $this->service = $this->user->service_id;

        $this->services = Service::all();

        $this->dispatch('update-event');
    }


    public function getServices()
    {
        return $this->services;
    }

    public function update()
    {

        $this->validate();
        $this->user->nom = $this->nom;
        $this->user->prenom = $this->prenom;
        $this->user->email = $this->email;
        $this->user->matricule = $this->matricule;
        $this->user->service_id = $this->service;
        $this->user->update();
        redirect()->route('employes')->with('success', 'Employé(s) ' . $this->nom . ' modifié avec succès');
    }

    public function render()
    {
        return view('livewire.employe.update');
    }
}
