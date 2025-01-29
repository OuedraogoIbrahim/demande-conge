<?php

namespace App\Livewire\Planning;

use App\Models\Classe;
use App\Models\Filiere;
use App\Models\Module;
use App\Models\Niveau;
use App\Models\Planning;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class Create extends Component
{

    public $niveaux;
    public $modules;
    public $filiere;
    public $module;
    public $niveau;
    public $classe;
    public $date;

    public $start;
    public $end;
    public $isDuty = false;
    // public $allDay;
    public $showForm = false;
    public $event;

    protected $rules = [
        'start' => 'required',
        'end' => 'required',
        'niveau' => 'required|exists:niveaux,id',
        'filiere' => 'required|exists:filieres,id',
        'module' => 'required|exists:modules,id',
        'classe' => 'required|exists:classes,id',
        'date' => 'required|date',
        'isDuty' => 'required|boolean'
        // 'allDay' => 'boolean',
    ];


    #[On('addEvent')]
    public function addEvent($event)
    {
        $this->reset(['end', 'date', 'isDuty', 'filiere', 'module', 'classe', 'event']);

        $this->showForm = true;
        $this->date = Carbon::parse($event)->format('Y-m-d');
        $this->start =  Carbon::parse($event)->format('h:m');
    }

    #[On('updateEvent')]
    public function updateEvent($event)
    {
        $this->showForm = true;

        $this->event = Planning::query()->findOrFail($event['id']);
        $module = Module::query()->findOrFail($this->event->module_id);
        $this->start = Carbon::parse($event['start'])->format('H:i');
        $this->end = Carbon::parse($event['end'])->format('H:i');
        $this->date = $this->event->date;
        $this->filiere = $module->filiere->id;
        $this->module = $this->event->module_id;
        $this->niveau = $module->niveau_id;
        $this->classe = $this->event->classe_id;
        $this->isDuty = $this->event->is_duty ? true : false;
    }

    public function save()
    {
        $this->validate();

        if ($this->event) {
            $this->event->heure_debut = $this->start;
            $this->event->heure_fin = $this->end;
            $this->event->date = $this->date;
            $this->event->is_duty = $this->isDuty;
            $this->event->module_id = $this->module;
            $this->event->classe_id = $this->classe;
            $this->event->update();
            return redirect()->route('plannings.index');
        } else {
            $event = new Planning();
            $event->heure_debut = $this->start;
            $event->heure_fin = $this->end;
            $event->establishment_id = Auth::user()->establishment_id;
            $event->statut = 'en attente';
            $event->date = $this->date;
            $event->is_duty = $this->isDuty;
            $event->module_id = $this->module;
            $event->classe_id = $this->classe;
            $event->save();
            return redirect()->route('plannings.index');
        }
    }

    public function cancel()
    {
        $this->showForm = false;
        $this->reset(['start', 'end', 'date', 'isDuty', 'filiere', 'module', 'classe']);
    }

    public function resetSelection($field)
    {
        if ($field === 'filiere') {
            $this->niveau = null;
            $this->module = null;
            $this->niveaux = null;
            $this->modules = null;
        }

        if ($field === 'niveau') {
            $this->module = null;
            $this->modules = null;
        }

        if ($field == 'module') {
            $this->classe = '';
        }
    }


    public function render()
    {

        $filieres = Filiere::query()->where('establishment_id', Auth::user()->establishment_id)->get();
        $classes = Classe::query()->where('establishment_id', Auth::user()->establishment_id)->get();

        if ($this->filiere) {
            $filiereChoice = Filiere::query()->findorFail($this->filiere);
            $this->niveaux = $filiereChoice->niveaux;
        }

        if ($this->niveau) {
            $niveauChoice = Niveau::findorFail($this->niveau);
            $this->modules = $niveauChoice->modules;
        }
        return view('livewire.planning.create', [
            'filieres' => $filieres,
            'niveaux' => $this->niveaux,
            'modules' => $this->modules,
            'classes' => $classes
        ]);
    }
}
