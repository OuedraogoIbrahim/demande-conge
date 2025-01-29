<?php

namespace App\Livewire\Parametre;

use App\Models\establishment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class Index extends Component
{

    use WithFileUploads;

    public $etablissement;
    public $nom = '';
    public $logo;

    public $hasConfirmed = false;


    public function rules()
    {
        return [
            'nom' => 'required',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1048',
        ];
    }

    public function mount()
    {
        $this->etablissement = establishment::query()->find(Auth::user()->establishment_id);
        $this->nom = $this->etablissement->nom;

        $this->logo = $this->etablissement->logo;
    }

    public function update()
    {
        $this->validate();
        $this->etablissement->nom = $this->nom;

        if ($this->logo) {
            $path = $this->logo->store('logo/' . Auth::user()->establishment_id);
            $this->etablissement->logo = $path;
        }

        $this->etablissement->update();

        return redirect()->route('parametres')->with('success', 'Paramètres mis à jour avec succès');
    }

    public function reinitialiser()
    {
        $this->nom = $this->etablissement->nom;
        $this->logo = $this->etablissement->logo;
    }

    public function delete()
    {
        $this->etablissement->delete();
        redirect()->route('dashboard')->with('success', 'Etablissement supprimé avec succès');
    }

    public function render()
    {
        return view('livewire.parametre.index');
    }
}
