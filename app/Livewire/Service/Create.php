<?php

namespace App\Livewire\Service;

use App\Models\Service;
use Livewire\Component;

class Create extends Component
{
    
    public $service;
    public $nom ='';

    protected $rules =[
        'nom' => 'required|min:3|max:50',
    ];

    public function submit()
    {
        $this->validate();

        $service = new Service();
        $service->nom = $this->nom;
        $service->save();
        session()->flash('success', 'Service ' . $this->nom . ' créé avec succès');

        $this->reset('nom'); 

        return redirect()->route('services');

    }

    public function render()
    {
        return view('livewire.service.create');
    }
}
