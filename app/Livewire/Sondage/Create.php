<?php

namespace App\Livewire\Sondage;

use App\Models\Filiere;
use App\Models\Niveau;
use App\Models\Poll;
use App\Models\User;
use App\Notifications\SondageNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;

class Create extends Component
{

    public $question;
    public $description;
    public $accessibilite;
    public $date_fin;
    public $options = [];
    public $participant;
    public $filiere;

    public $niveaux;
    public $filieres;
    public $numberOfOption = 2;
    public $name = [];

    public function rules()
    {
        return   [
            'question' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'accessibilite' => 'required|in:professeur,etudiant',
            'date_fin' => 'required|date|after:today|before_or_equal:' . now()->addHours(72)->toDateString(),
            'options' => 'required|array|min:2',
            'options.*.option' => 'required|string|min:2|max:100',
            'participant' => [
                'required',
                function ($attribute, $value, $fail) {
                    if ($value !== 'Tous' && !Niveau::query()->where('id', $value)->exists()) {
                        $fail('Le participant doit être "tous" ou un niveau valide.');
                    }
                },
            ],

            'filiere' => 'required|exists:filieres,id',
        ];
    }


    public function mount()
    {
        // Initialiser le tableau d'options avec des valeurs vides
        $this->options = array_map(function ($index) {
            return [
                'option' => '',
                'votes' => 0
            ];
        }, range(0, $this->numberOfOption - 1));

        $this->filieres = Filiere::query()->where('establishment_id', Auth::user()->establishment_id)->get();

        $this->niveaux = Niveau::query()
            ->whereIn('filiere_id', $this->filieres->pluck('id'))
            ->get();
    }

    public function selectNiveau()
    {
        $this->niveaux = Niveau::query()->where('filiere_id', $this->filiere)->get();
        $this->dispatch('create-event');
    }

    public function getNiveaux()
    {
        return Niveau::query()->where('filiere_id', $this->filiere)->get();
    }

    public function addOption()
    {
        $this->options[] = [
            'option'  => '',
            'votes' => 0
        ];
        $this->numberOfOption++;
    }

    public function removeOption($index)
    {
        unset($this->options[$index]);
        $this->options = array_values($this->options); // Réindexer les options
        $this->numberOfOption--;
    }

    public function resetForm()
    {
        $this->options = array_fill(0, $this->numberOfOption, '');
        $this->reset(['question', 'description', 'accessibilite', 'date_fin', 'filiere', 'participant']);
    }

    public function save()
    {
        $this->validate();

        $sondage = new Poll();
        $sondage->question = $this->question;
        $sondage->description = $this->description;
        $sondage->accessibilite = $this->accessibilite;
        if ($this->participant != 'Tous') {
            $this->participant = Niveau::query()->findOrFail($this->participant)->nom;
        }
        $sondage->participants = $this->participant;
        $sondage->date_fin = $this->date_fin;
        $sondage->filiere_id = $this->filiere;
        $sondage->options = json_encode($this->options);
        $sondage->establishment_id = Auth::user()->establishment_id;
        $sondage->save();

        $users = User::query()->where(['establishment_id' => Auth::user()->establishment_id, 'role' => $this->accessibilite])->get();
        Notification::send($users, new SondageNotification($sondage));

        redirect()->route('sondages.index')->with('success', 'Nouveau sondage créé avec succès');
    }

    public function render()
    {
        // if ($this->date_fin) {
        //     dd($this->date_fin);
        // }

        // $filieres = Filiere::query()->where('establishment_id', Auth::user()->establishment_id)->get();
        // if ($this->filiere) {
        //     $filiereChoice = Filiere::query()->findorFail($this->filiere);
        //     $this->niveaux = $filiereChoice->niveaux;
        // }

        return view('livewire.sondage.create');
    }
}
