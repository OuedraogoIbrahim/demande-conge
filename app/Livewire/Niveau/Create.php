<?php

namespace App\Livewire\Niveau;

use App\Models\Filiere;
use App\Models\Niveau;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Create extends Component
{

    public $name = '';
    public $description = '';
    public $filiere = '';


    protected $rules = [
        'name' => 'required|',
        'filiere' => 'required|exists:filieres,id',
    ];

    public function submit()
    {
        $this->validate();
        $niveau = new Niveau();
        $niveau->nom = $this->name;
        $niveau->description = $this->description;
        $niveau->filiere_id = $this->filiere;
        $niveau->save();
        redirect()->route('niveaux.index')->with('success', 'Niveau ' . $this->name . ' modifié avec succès');
    }
    public function render()
    {
        $filieres = Filiere::query()->where('establishment_id', Auth::user()->establishment_id)->get();
        return view('livewire.niveau.create', compact('filieres'));
    }
}
