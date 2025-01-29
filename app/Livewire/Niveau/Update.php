<?php

namespace App\Livewire\Niveau;

use App\Models\Filiere;
use App\Models\Niveau;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class Update extends Component
{


    public Niveau $niveau;
    public  $name = '';
    public  $description = '';
    public  $filiere = '';

    public $filieres;

    protected $rules = [
        'name' => 'required|',
        'filiere' => 'required|exists:filieres,id'
    ];

    #[On('niveauToEdit')]
    public function niveauToEdit($id)
    {
        $this->niveau = Niveau::query()->findOrFail($id);
        $this->name = $this->niveau->nom;
        $this->description = $this->niveau->desccription;
        $this->filiere = $this->niveau->filiere_id;

        $this->filieres = Filiere::query()->where('establishment_id', Auth::user()->establishment_id)->get();

        $this->dispatch('update-event')->self();
    }


    public function getFilieres()
    {
        return $this->filieres;
    }

    public function update()
    {
        $this->validate();
        $this->niveau->nom = $this->name;
        $this->niveau->description = $this->description;
        $this->niveau->filiere_id = $this->filiere;
        $this->niveau->update();
        redirect()->route('niveaux.index')->with('success', 'Niveau ' . $this->name . ' modifié avec succès');
    }

    public function render()
    {
        return view('livewire.niveau.update');
    }
}
