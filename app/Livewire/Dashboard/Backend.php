<?php

namespace App\Livewire\Dashboard;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Backend extends Component
{

    public $user;
    public function mount()
    {
        $this->user = Auth::user();
    }
    public function render()
    {
        if ($this->user->role == 'professeur') {
            // $modules = $this->
        }
        return view('livewire.dashboard.backend');
    }
}
