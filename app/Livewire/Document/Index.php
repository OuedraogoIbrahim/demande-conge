<?php

namespace App\Livewire\Document;

use App\Models\Document;
use App\Models\Filiere;
use App\Models\Niveau;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
    public $pdfUrl;

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

    public function sendDocument($id)
    {
        $this->dispatch('documentToEdit', id: $id)->to(Update::class);
    }

    public function deleteDocument($id)
    {
        $document = Document::query()->findOrFail($id);
        Storage::disk('public')->delete($document->chemin);
        $document->delete();
    }

    public function ViewFile(Document $document)
    {
        $this->pdfUrl = asset('storage/' . $document->chemin);
    }

    public function render()
    {
        $documents = Document::query()
            ->where('establishment_id', Auth::user()->establishment_id)
            ->whereHas('filiere', function ($query) {
                $query->when($this->filiere, function ($query) {
                    $query->where('filiere_id', $this->filiere);
                });
            })
            ->whereHas('niveau', function ($query) {
                $query->when($this->niveau, function ($query) {
                    $query->where('niveau_id', $this->niveau);
                });
            })
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('titre', 'like', '%' . $this->search . '%');
                });
            })
            ->paginate(10);


        return view('livewire.document.index', compact('documents'));
    }
}
