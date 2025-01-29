<?php

namespace App\Livewire\Filiere;

use App\Models\Filiere;
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
        $filiere = new Filiere();
        $filiere->nom = $this->name;
        $filiere->description = $this->descripton;
        $filiere->establishment_id = Auth::user()->establishment_id;
        $filiere->save();
        redirect()->route('filieres.index')->with('success', 'Filiere ' . $this->name . ' modifiée avec succès');
    }
    public function render()
    {
        return view('livewire.filiere.create');
    }
}
