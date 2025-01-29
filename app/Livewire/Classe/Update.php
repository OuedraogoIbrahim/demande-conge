<?php

namespace App\Livewire\Classe;

use App\Models\Classe;
use Livewire\Attributes\On;
use Livewire\Component;

class Update extends Component
{
    public Classe $classe;
    public $name = '';
    public $description = '';

    protected $rules = [
        'name' => 'required|',
    ];

    #[On('classeToEdit')]
    public function classeToEdit($id)
    {
        $this->classe = Classe::query()->findOrFail($id);
        $this->name = $this->classe->nom;
        $this->description = $this->classe->description;
    }


    public function update()
    {
        $this->validate();
        $this->classe->nom = $this->name;
        $this->classe->description = $this->description;
        $this->classe->update();
        redirect()->route('classes.index')->with('success', 'Classe ' . $this->name . ' modifiée avec succès');
    }

    public function render()
    {
        return view('livewire.classe.update');
    }
}
