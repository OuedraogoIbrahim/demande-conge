<?php

namespace App\Livewire\Student;

use App\Imports\NotesImport;
use App\Imports\UsersImport;
use App\Models\Filiere;
use App\Models\Niveau;
use App\Models\Note;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Index extends Component
{

    use WithPagination, WithFileUploads;

    public $file;
    public Note | null $notes;
    public $noteIsOpen = false;
    public $filieres;
    public $filiere;
    public $niveaux;
    public $niveau;
    public $search;
    public $studentCounts;

    protected $rules = [
        'file' => 'required|mimes:xlsx,xls|max:5048',
    ];

    public function mount()
    {
        $this->filieres = Filiere::query()->where('establishment_id', Auth::user()->establishment_id)->get();
        $this->niveaux = Niveau::query()
            ->whereIn('filiere_id', $this->filieres->pluck('id'))
            ->get();

        $this->studentCounts = User::query()
            ->where('establishment_id', Auth::user()->establishment_id)
            ->whereHas('student')
            ->with('student.filiere')
            ->get()
            ->groupBy(function ($user) {
                return $user->student->first()->filiere->nom; // Grouper par l'ID de la filière
            })
            ->map(function ($group, $filiereId) {
                return  $group->count(); // Compter les étudiants pour chaque filière
            });
    }

    public function deleteEtudiant($id)
    {
        $user = User::query()->findOrFail($id);
        $user->delete();
    }

    public function addStudent()
    {
        $this->validate();
        Excel::import(new UsersImport, $this->file->getRealPath());
        // dd($this->file->getRealPath());
    }

    public function addNote()
    {
        $this->validate();
        Excel::import(new NotesImport, $this->file->getRealPath());
        // dd($this->file->getRealPath());
    }

    public function viewNotes($id)
    {
        $this->notes = Note::query()->where('user_id', $id)->first();
        $this->noteIsOpen = true;
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


    public function sendUser($id)
    {
        $this->dispatch('userToEdit', id: $id)->to(Update::class);
    }

    public function render()
    {

        $users = User::query()
            ->where([
                'role' => 'etudiant',
                'establishment_id' => Auth::user()->establishment_id,
            ])
            // Filtrer par filière et niveau en utilisant la relation avec Student
            ->whereHas('student', function ($query) {
                $query->when($this->filiere, function ($query) {
                    $query->where('filiere_id', $this->filiere);
                })
                    ->when($this->niveau, function ($query) {
                        $query->where('niveau_id', $this->niveau);
                    });
            })
            // Ajouter une condition pour filtrer par nom ou prénom
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('nom', 'like', '%' . $this->search . '%')
                        ->orWhere('prenom', 'like', '%' . $this->search . '%');
                });
            })
            ->paginate(10);


        return view('livewire.student.index', compact('users'));
    }
}
