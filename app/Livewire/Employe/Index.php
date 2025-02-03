<?php

namespace App\Livewire\Employe;

use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    public $employes;


    use WithPagination;

    public $services;
    public $service;
    public $niveaux;
    public $niveau;
    public $search;

    public function mount()
    {
        $this->services = Service::all();
    }

    public function deleteEmploye($id)
    {
        $user = User::query()->findOrFail($id);
        $user->delete();
    }

    public function sendUser($id)
    {
        $this->dispatch('userToEdit', id: $id)->to(Update::class);
    }

    public function render()
    {

        $users = User::query()
            // Filtrer par filière et niveau en utilisant la relation avec Student
            ->whereHas('service', function ($query) {
                $query->when($this->service, function ($query) {
                    $query->where('service_id', $this->service);
                });
            })
            // Ajouter une condition pour filtrer par nom ou prénom
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('nom', 'like', '%' . $this->search . '%')
                        ->orWhere('prenom', 'like', '%' . $this->search . '%');
                });
            })
            ->where('id', '!=', Auth::user()->id)
            ->paginate(10);

        return view('livewire.employe.index', compact('users'));
    }
}
