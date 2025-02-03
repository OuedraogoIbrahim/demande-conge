<?php

namespace App\Livewire\Service;

use App\Models\Service;
use Livewire\Attributes\On;
use Livewire\Component;

class Update extends Component
{
    public Service $service;
    public $services;

    public $nom = '';

    public function rules()
    {
        return [
            'nom' => 'required|string',
        ];
    }

    #[On('serviceToEdit')]
    public function serviceToEdit($id)
    {
        $this->service = Service::query()->findOrFail($id);
        $this->nom = $this->service->nom;

        $this->dispatch('update-event');
    }

    public function getServices()
    {
        return $this->services;
    }

    public function update()
    {
        $this->validate();
        $this->service->nom = $this->nom;
        $this->service->update();

        redirect()->route('services')->with('success', 'Service(s) ' . $this->nom . ' modifié avec succès');

    }

    public function render()
    {
        return view('livewire.service.update');
    }
}
