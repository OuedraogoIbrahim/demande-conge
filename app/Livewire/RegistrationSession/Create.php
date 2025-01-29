<?php

namespace App\Livewire\RegistrationSession;

use App\Models\Filiere;
use App\Models\Registration;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;


class Create extends Component
{

    public $niveaux;

    public $titre = '';
    public $date_fin;
    public $filiere;
    public $niveau;

    public function rules()
    {
        return [
            'titre' => 'required|',
            'date_fin' => 'required|date|after:today|before_or_equal:' . Carbon::now()->addDays(7)->format('Y-m-d'),
            'niveau' => 'required|exists:niveaux,id',
            'filiere' => 'required|exists:filieres,id',
        ];
    }

    public function resetSelection($field)
    {
        if ($field === 'filiere') {
            $this->niveau = null;
            $this->niveaux = null;
        }
    }

    public function submit()
    {
        $this->validate();

        if (Registration::query()->where(['filiere_id' => $this->filiere, 'niveau_id' => $this->niveau, 'establishment_id' => Auth::user()->establishment_id])->get()->isNotEmpty())
            return redirect()->route('session.create')->with('success', 'Cette session d\'inscription existe déjà');

        $registration = new Registration();
        $registration->nom = $this->titre;
        $registration->date_fin = $this->date_fin;
        $registration->lien = route('session.etudiant', ['token' => Str::random(40)]);
        $registration->filiere_id = $this->filiere;
        $registration->niveau_id = $this->niveau;
        $registration->establishment_id = Auth::user()->establishment_id;
        $registration->save();

        redirect()->route('session.index')->with('success', 'Session d\'inscription ouverte avec succès');
    }


    public function render()
    {
        $filieres = Filiere::query()->where('establishment_id', Auth::user()->establishment_id)->get();

        if ($this->filiere) {
            $filiereChoice = Filiere::query()->findorFail($this->filiere);
            $this->niveaux = $filiereChoice->niveaux;
        }
        return view('livewire.registration-session.create', [
            'filieres' => $filieres,
            'niveaux' => $this->niveaux,
        ]);
    }
}
