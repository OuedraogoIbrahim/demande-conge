<?php

namespace App\Livewire\Sondage;

use App\Models\Filiere;
use App\Models\Niveau;
use App\Models\Poll;
use App\Models\User;
use Carbon\Carbon;
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
    public $estExpire = false;

    protected $paginationTheme = 'bootstrap';


    public function mount()
    {
        $this->filieres = Filiere::query()->where('establishment_id', Auth::user()->establishment_id)->get();
        $this->niveaux = Niveau::query()
            ->whereIn('filiere_id', $this->filieres->pluck('id'))
            ->get();
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

    public function deleteSondage($id)
    {
        $sondage = Poll::query()->findOrFail($id);
        $sondage->delete();
    }


    public function render()
    {

        $user = User::query()->find(Auth::user()->id);
        $sondages = Poll::query()->where('establishment_id', Auth::user()->establishment_id);

        if (Auth::user()->role == 'superviseur' || Auth::user()->role == 'coordinateur') {
            $sondages = $sondages
                ->when($this->filiere, function ($query) {
                    $query->where('filiere_id', $this->filiere);
                })
                ->when($this->niveau, function ($query) {
                    $query->where('participants', $this->niveau);
                })
                ->when($this->search, function ($query) {
                    $query->where(function ($query) {
                        $query->where('question', 'like', '%' . $this->search . '%');
                    });
                })
                ->when($this->estExpire, function ($query) {
                    $query->where('date_fin', '>=', now());
                })
                ->paginate(10);
        } elseif (Auth::user()->role == 'etudiant') {
            $student = $user->student->first();
            if ($student && $student->filiere && $student->niveau) {
                $sondages = $sondages
                    ->where('accessibilite', 'etudiant')
                    ->where('filiere_id', $student->filiere->id)
                    ->whereIn('participants', ['Tous', $student->niveau->nom])
                    ->when($this->filiere, function ($query) {
                        $query->where('filiere_id', $this->filiere);
                    })
                    ->when($this->niveau, function ($query) {
                        $query->where('participants', $this->niveau);
                    })
                    ->when($this->search, function ($query) {
                        $query->where(function ($query) {
                            $query->where('question', 'like', '%' . $this->search . '%');
                        });
                    })
                    ->when($this->estExpire, function ($query) {
                        $query->where('date_fin', '>=', now());
                    })
                    ->paginate(10);
            } else {
                $sondages = collect(); // Aucun sondage si l'étudiant est mal configuré
            }
        } elseif (Auth::user()->role == 'professeur') {
            $professor = $user->professor->first();
            if ($professor) {
                // Récupération des filières et niveaux
                $filieresNiveaux = $professor->modules()->with(['filiere', 'niveau'])->get()->map(function ($module) {
                    return [
                        'filiere_id' => $module->filiere->id ?? null,
                        'niveau_nom' => $module->niveau->nom ?? null,
                    ];
                })->filter(function ($item) {
                    return $item['filiere_id'] && $item['niveau_nom']; // Éviter les null
                })->unique();

                $filiereIds = $filieresNiveaux->pluck('filiere_id')->toArray();
                $niveauNoms = $filieresNiveaux->pluck('niveau_nom')->toArray();

                $sondages = $sondages
                    ->where('accessibilite', 'professeur')
                    ->whereIn('filiere_id', $filiereIds)
                    ->whereIn('participants', ['Tous', ...$niveauNoms])
                    ->when($this->filiere, function ($query) {
                        $query->where('filiere_id', $this->filiere);
                    })
                    ->when($this->niveau, function ($query) {
                        $query->where('participants', $this->niveau);
                    })
                    ->when($this->search, function ($query) {
                        $query->where(function ($query) {
                            $query->where('question', 'like', '%' . $this->search . '%');
                        });
                    })
                    ->when($this->estExpire, function ($query) {
                        $query->where('date_fin', '>=', now());
                    })
                    ->paginate(10);
            } else {
                $sondages = collect(); // Aucun sondage si le professeur est mal configuré
            }
        }

        return view('livewire.sondage.index', compact('sondages'));
    }
}
