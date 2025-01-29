<?php

namespace App\Livewire\Filiere;

use App\Models\Filiere;
use Livewire\Attributes\On;
use Livewire\Component;

class Update extends Component
{

    public Filiere $filiere;
    public  $name = '';
    public  $description = '';


    protected $rules = [
        'name' => 'required|',
    ];

    #[On('filiereToEdit')]
    public function classeToEdit($id)
    {
        $this->filiere = Filiere::query()->findOrFail($id);
        $this->name = $this->filiere->nom;
        $this->description = $this->filiere->description;
    }

    public function update()
    {
        $this->validate();
        $this->filiere->nom = $this->name;
        $this->filiere->description = $this->description;
        $this->filiere->update();
        redirect()->route('filieres.index')->with('success', 'Filiere ' . $this->name . ' modifié avec succès');
    }

    public function render()
    {
        return view('livewire.filiere.update');
    }
}
