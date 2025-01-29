<?php

namespace App\Livewire\Planning;

use App\Models\Planning;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MyPlanning extends Component
{

    public $modules;
    public $eventsStudent;
    public User $user;

    public function mount()
    {
        $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = Carbon::now()->endOfWeek(Carbon::SATURDAY);

        $this->user = Auth::user();

        if ($this->user->role == 'professeur' || $this->user->role == 'coordinateur') {
            $professeur = $this->user->professor->first();

            $this->modules = $professeur->modules()->with(['plannings' => function ($query) use ($startOfWeek, $endOfWeek) {
                $query->whereBetween('date_debut', [$startOfWeek, $endOfWeek])
                    ->orderBy('date_debut', 'asc');
            }])->get();
        }

        if ($this->user->role == 'etudiant' || $this->user->role == 'chef_de_classe') {
            $etudiant = $this->user->student->first();
            $niveau = $etudiant->niveau->id;
            $modulesId = $etudiant->filiere->modules()->where('niveau_id', $niveau)->pluck('id');
            $this->eventsStudent = Planning::query()->whereIn('module_id', $modulesId)->whereBetween('date_debut', [$startOfWeek, $endOfWeek])->orderBy('date_debut')->get();
        }
    }

    public function render()
    {
        return view('livewire.planning.my-planning');
    }
}
