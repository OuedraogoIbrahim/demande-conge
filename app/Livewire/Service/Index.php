<?php

namespace App\Livewire\Service;

use App\Models\Service;
use DragonCode\Contracts\Cashier\Auth\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    public $services;


    use WithPagination;

    // public $service;
    // public $niveaux;

    public $niveau;
    public $search;

    public function mount()
    {
        $this->services = Service::all();
    }

    public function deleteService($id)
    {
        $service = Service::query()->findOrFail($id);
        $service->delete();
    }

    public function sendUser($id)
    {
        $this->dispatch('serviceToEdit', id: $id)->to(Update::class);
    }

    public function render()
    {
        // dd($this->search);
        $services = Service::query()
            // Filtrer par filière et niveau en utilisant la relation avec Student
            // ->whereHas('service', function ($query) {
            //     $query->when($this->service, function ($query) {
            //         $query->where('service_id', $this->service);
            //     });
            // })
            // Ajouter une condition pour filtrer par nom ou prénom
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('nom', 'like', '%' . $this->search . '%');
                        // ->orWhere('prenom', 'like', '%' . $this->search . '%');
                });
            })
            // ->where('id', '!=', Auth::user()->id)
            ->paginate(10);

        return view('livewire.service.index', compact('services'));
    }
}
