<?php

namespace App\Livewire\Professor;

use App\Models\Coordinateur;
use App\Models\Filiere;
use App\Models\Niveau;
use App\Models\Professor;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $filieres;
    public $niveaux;
    public $filiere;
    public $niveau;
    public $search;

    public $showAllModulesFor = null; // Contrôle de l'affichage des modules
    public $showLessModulesFor = null; // Contrôle de l'affichage des modules limités

    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->filieres = Filiere::query()->where('establishment_id', Auth::user()->establishment_id)->get();
        $this->niveaux = Niveau::query()
            ->whereIn('filiere_id', $this->filieres->pluck('id'))
            ->get();
    }
    // Afficher tous les modules pour un professeur donné
    public function showAllModules($professeurId)
    {
        $this->showAllModulesFor = $professeurId;
        $this->showLessModulesFor = null; // Masquer le bouton "Réduire"
    }

    // Afficher uniquement les 2 premiers modules pour un professeur
    public function showLessModules($professeurId)
    {
        $this->showLessModulesFor = $professeurId;
        $this->showAllModulesFor = null; // Masquer le bouton "Voir tout"
    }

    public function deleteProfesseur($id)
    {
        $user = User::query()->findOrFail($id);
        $user->delete();
    }

    public function sendProfesseur()
    {
        dd('Cette Partie n\est pas encore disponible');
    }

    public function addAsNotCoordinateur(User $user)
    {
        if ($user->role == 'coordinateur') {
            $user->role = 'professeur';
            $user->update();
            $coordinateur = Coordinateur::query()->where('user_id')->first();
            $coordinateur->delete();
            session()->flash('message', "Le professeur " . $user->nom . ' ' . $user->prenom . ' n\'est plus coordinateur');
        }
    }

    public function addAsCoordinateur(User $user)
    {
        if ($user->role != 'coordinateur') {
            $filiere = Filiere::query()->findOrFail('9dbbeb02-a9af-4175-9fe4-8421e5054238');
            $user->role = 'coordinateur';
            $user->update();

            $coordinateur = new Coordinateur();
            $coordinateur->user_id = $user->id;
            $coordinateur->filiere_id = $filiere->id;
            $coordinateur->save();
            session()->flash('message', "Le professeur " . $user->nom . ' ' . $user->prenom . ' a été nommé comme coordinateur');
        }
    }

    public function selectNiveau()
    {
        $this->niveaux = Niveau::query()->where('filiere_id', $this->filiere)->get();
        $this->reset(['niveau']);
        $this->dispatch('test')->self();
    }

    public function getNiveaux()
    {
        return Niveau::query()->where('filiere_id', $this->filiere)->get();
    }

    public function render()
    {
        $professeurs = User::query()
            ->whereIn('role', ['professeur', 'coordinateur'])
            ->where('establishment_id', Auth::user()->establishment_id)
            // Filtrer par filière et niveau via la relation avec Module
            ->whereHas('professor.modules', function ($query) {
                $query->when($this->filiere, function ($query) {
                    $query->where('filiere_id', $this->filiere);
                })
                    ->when($this->niveau, function ($query) {
                        $query->where('niveau_id', $this->niveau);
                    });
            })
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('nom', 'like', '%' . $this->search . '%')
                        ->orWhere('prenom', 'like', '%' . $this->search . '%');
                });
            })
            ->paginate(10);

        foreach ($professeurs as $professeur) {
            $modules = $professeur->professor()->first()->modules;
            $professeur->modulesToShow = ($this->showAllModulesFor === $professeur->id)
                ? $modules : $modules->take(2); // Afficher tous les modules ou seulement 2
            $professeur->has_more_modules = $modules->count() > 2; // Vérifier s'il y a plus de 2 modules
            $professeur->all_modules = $modules; // Conserver tous les modules
        }

        return view('livewire.professor.index', compact('professeurs'));
    }
}
