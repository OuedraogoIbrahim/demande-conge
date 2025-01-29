<?php

namespace App\Livewire\RegistrationSession;

use App\Models\Filiere;
use App\Models\PendingStudent;
use App\Models\Registration;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class StudentRegistration extends Component
{

    public $niveaux;

    public $nom = '';
    public $prenom = '';
    public $email = '';
    public $telephone = '';
    public $password = '';
    public $password_confirmation = '';
    public $filiere;
    public $niveau;

    public Registration $registration;

    protected $rules = [
        'nom' => 'required|',
        'prenom' => 'required|',
        'email' => 'required|unique:pending_students,email',
        'telephone' => 'required|numeric|unique:pending_students,telephone',
    ];

    public function mount(Registration $registration)
    {
        $this->registration = $registration;
    }

    public function submit()
    {
        $this->validate();

        $pendingStudent = new PendingStudent();
        $pendingStudent->nom = $this->nom;
        $pendingStudent->prenom = $this->prenom;
        $pendingStudent->email = $this->email;
        $pendingStudent->telephone = $this->telephone;
        $pendingStudent->establishment_id = $this->registration->establishment_id;
        $pendingStudent->registration_id = $this->registration->id;
        $pendingStudent->save();

        redirect()->route('login')->with('success', 'Inscription reussie');
    }


    public function render()
    {

        return view('livewire.registration-session.student-registration');
    }
}
