<?php

namespace App\Livewire\Dashboard;

use App\Imports\NotesImport;
use App\Models\Module;
use App\Models\Planning;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class Professeur extends Component
{

    use WithFileUploads;
    public $file;

    protected $rules = [
        'file' => 'required|mimes:xlsx,xls|max:5048',
    ];

    public $professeur;
    public $modules;
    public $devoirs;
    public $coordinateurs;
    public $cours; // Les cours qui ont eu lieu hier , aujourdhui ou demain. Uniquement ceux en attente

    public $nombreModules; // Nombre de modules enseignes
    public $nombreCours; // Nombre de cours d'aujourd'hui
    public $nombreEtudiants; // Nombre d'etudiants dans chaque filiere enseigne par le prof

    public function mount()
    {
        $this->professeur = Auth::user()->professor->first();

        $this->modules = $this->professeur->modules()
            ->with(['filiere', 'niveau'])
            ->get();

        $this->nombreEtudiants = Student::query()->select('filiere_id', 'niveau_id')
            ->whereIn('filiere_id', $this->modules->pluck('filiere_id'))
            ->whereIn('niveau_id', $this->modules->pluck('niveau_id'))
            ->groupBy('filiere_id', 'niveau_id')
            ->selectRaw('filiere_id, niveau_id, COUNT(*) as nombre_etudiants')
            ->get();

        $this->nombreModules = $this->modules->count();

        $this->devoirs = Planning::query()->whereIn('module_id', $this->modules->pluck('id'))
            ->where('type', 'devoir')
            ->where('date_debut', '>', now())
            ->orderBy('date_debut', 'asc')
            ->get();

        $this->coordinateurs = User::query()->where(['role' => 'coordinateur', 'establishment_id' => Auth::user()->establishment_id])->get();
        $this->cours = Planning::query()
            ->where('type', 'cours')
            ->whereBetween('date_debut', [Carbon::yesterday(), Carbon::tomorrow()])
            ->where('statut', 'en attente')
            ->get();

        $this->nombreCours =  Planning::query()
            ->where('type', 'cours')
            ->where('date_debut', now())
            ->where('statut', 'en attente')
            ->count();
    }

    public function addNote()
    {
        $this->validate();
        Excel::import(new NotesImport, $this->file->getRealPath());
    }

    public function markAsDone(Planning $cours)
    {
        $module = Module::query()->findOrFail($cours->module_id);

        $heureDebut = Carbon::parse($cours->heure_debut);
        $heureFin = Carbon::parse($cours->heure_fin);
        $dureeCours = $heureDebut->diffInHours($heureFin);

        $heuresRestantes = $module->nombre_heures - $module->heures_utilisees;

        if ($heuresRestantes == 0) {
            redirect()->route('dashboard')->with('error', 'Ce module a été déjà entièrement complété.');
        } else {
            if ($dureeCours > $heuresRestantes) {
                $dureeCours = $heuresRestantes;
            }

            $module->heures_utilisees += $dureeCours;
            $module->update();

            $cours->statut = 'terminer';
            $cours->update();

            redirect()->route('dashboard')->with('message', 'Le cours a été marqué comme éffectué avec succès.');
        }
    }


    public function render()
    {
        return view('livewire.dashboard.professeur');
    }
}
