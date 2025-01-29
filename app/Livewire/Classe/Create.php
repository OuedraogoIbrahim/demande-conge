<?php

namespace App\Livewire\Classe;

use App\Models\Classe;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Create extends Component
{
    public $name = '';
    public $descripton = '';

    protected $rules = [
        'name' => 'required|',

    ];

    public function submit()
    {
        $this->validate();
        $filiere = new Classe();
        $filiere->nom = $this->name;
        $filiere->description = $this->descripton;
        $filiere->establishment_id = Auth::user()->establishment_id;
        $filiere->save();
        redirect()->route('classes.index')->with('success', 'Classe ' . $this->name . ' ajoutée avec succès');
    }

    public function render()
    {
        return view('livewire.classe.create');
    }
}
