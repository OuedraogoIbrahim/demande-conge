<?php

namespace App\Livewire\Professor;

use App\Models\Coordinateur;
use App\Models\Filiere;
use App\Models\Module;
use App\Models\Niveau;
use App\Models\Professor;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Create extends Component
{

    public $niveaux;
    public $modules;
    public $filieres;
    public $filieresCollection;

    public $nom = '';
    public $prenom = '';
    public $email = '';
    public $telephone = '';
    public $filiere = [];
    public $module = [];
    public $coordinateur;

    protected function rules()
    {
        $rules = [
            'nom' => 'required',
            'prenom' => 'required',
            'email' => 'required|unique:users,email',
            'telephone' => 'required|numeric|unique:users,telephone',
            'filiere' => 'required|array',
            'filiere.*' => 'exists:filieres,nom',
            'module' => 'required|array',
            // 'niveaux' => 'required|array',
        ];

        foreach ($this->module as $filiereId => $modules) {
            foreach ($modules as $index => $module) {
                $rules["module.$filiereId.$index.id"] = 'required|exists:modules,id';
            }
        }

        return $rules;
    }


    public function submit()
    {

        // $this->validate();

        $moduleIds = [];
        foreach ($this->module as $module) {
            foreach ($module as $m) {
                $moduleIds[] = $m['id'];
            }
        }

        $user = new User();
        $user->nom = $this->nom;
        $user->prenom = $this->prenom;
        $user->email = $this->email;
        $user->telephone = $this->telephone;
        $user->role = 'professeur';
        $user->password = Hash::make('password');
        $user->establishment_id = Auth::user()->establishment_id;
        $user->save();

        $professeur = new Professor();
        $professeur->user_id = $user->id;
        $professeur->save();

        $professeur->modules()->attach($moduleIds);

        redirect()->route('professeurs.index')->with('success', 'Professeur ' . $this->nom . ' créé avec succès');
    }

    public function mount()
    {
        $this->filieres = Filiere::query()
            ->where('establishment_id', Auth::user()->establishment_id)
            ->pluck('nom') // Récupère uniquement les noms
            ->toArray();

        $this->filieresCollection = Filiere::query()->where('establishment_id', Auth::user()->establishment_id)->get();
    }

    public function selectNiveau()
    {
        $choices = Filiere::query()
            ->where('establishment_id', Auth::user()->establishment_id)
            ->whereIn('nom', $this->filiere)
            ->with('niveaux') // Charger les niveaux liés
            ->get();

        if ($choices->isEmpty()) {
            abort('403');
        }

        $this->niveaux = $choices->mapWithKeys(function ($filiere) {
            return [
                $filiere->id => $filiere->niveaux->map(function ($niveau) {
                    return [
                        'id' => $niveau->id,
                        'nom' => $niveau->nom, // Transformer les objets Niveau en tableau simple
                    ];
                }),
            ];
        });

        $this->dispatch('create-niveau-event');
    }

    public function selectModule()
    {
        $modules = [];

        foreach ($this->niveaux as $filiereId => $niveaux) {
            foreach ($niveaux as $niveau) {
                if (is_array($niveau)) {
                    continue;
                }

                $niveauInstance = Niveau::query()
                    ->where('filiere_id', $filiereId)
                    ->where('nom', $niveau)
                    ->firstOrFail();

                if ($niveauInstance) {
                    $modules[$filiereId][] = $niveauInstance->modules->map(function ($module) use ($niveauInstance) {
                        return [
                            'id' => $module->id,
                            'nom' => $module->nom . ' (' . $niveauInstance->nom . ')',
                        ];
                    })->toArray();
                }
            }
        }

        $this->modules = $modules;

        // Déclencher l'événement pour mettre à jour l'interface utilisateur
        $this->dispatch('create-module-event');
    }


    public function getNiveaux()
    {
        return $this->niveaux;
    }

    public function getModules()
    {
        return $this->niveaux;
    }

    public function getFiliere($id)
    {
        return Filiere::query()->findOrFail($id);
    }


    public function resetValue($field)
    {
        if ($field === 'niveau')
            $this->reset(['niveaux', 'modules', 'module']);

        if ($field === 'module')
            $this->reset(['modules', 'module']);
    }


    public function render()
    {
        return view('livewire.professor.create');
    }
}
