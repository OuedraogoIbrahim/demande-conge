<?php

namespace App\Livewire\Module;

use App\Models\Filiere;
use App\Models\Module;
use App\Models\Niveau;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{

    use WithPagination;

    public Filiere $filiere;
    public $niveaux;
    public $nombre_heures_min;
    public $nombre_heures_max;
    public $nombre_heures_utilise_min;
    public $nombre_heures_utilise_max;

    public $niveau;
    public $search;

    public Module $module;

    protected $paginationTheme = 'bootstrap';

    public function mount($filiere)
    {
        $this->filiere = $filiere;
        $this->niveaux = $this->filiere->niveaux;
    }

    public function sendModule($id)
    {
        $this->dispatch('moduleToEdit', id: $id)->to(Update::class);
    }

    public function deleteModule($id)
    {
        $this->module = Module::query()->findOrFail($id);
        $this->module->delete();
    }
    public function render()
    {
        $modules = $this->filiere->modules()
            ->when($this->niveau, function ($query) {
                $query->where('niveau_id', $this->niveau);
            })
            ->when($this->search, function ($query) {
                $query->where('nom', 'like', '%' . $this->search . '%');
            })
            ->when($this->nombre_heures_min, function ($query) {
                $query->where('nombre_heures', '>=', $this->nombre_heures_min);
            })
            ->when($this->nombre_heures_max, function ($query) {
                $query->where('nombre_heures', '<=', $this->nombre_heures_max);
            })
            ->when($this->nombre_heures_utilise_min, function ($query) {
                $query->where('heures_utilisees', '>=', $this->nombre_heures_utilise_min);
            })
            ->when($this->nombre_heures_utilise_max, function ($query) {
                $query->where('heures_utilisees', '<=', $this->nombre_heures_utilise_max);
            })
            ->paginate(10);
        return view('livewire.module.index', compact('modules'));
    }
}
