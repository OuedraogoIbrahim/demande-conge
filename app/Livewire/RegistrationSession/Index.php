<?php

namespace App\Livewire\RegistrationSession;

use App\Models\PendingStudent;
use App\Models\Registration;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{

    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public PendingStudent | Collection $students;

    public function reloading()
    {
        return redirect()->route('session.index');
    }

    public function display(Registration $registration)
    {

        $this->students = $registration->pendingStudents;
    }

    public function deleteSession($id)
    {
        $registration = Registration::query()->findOrFail($id);
        $registration->delete();
    }

    public function acceptStudent(PendingStudent $student)
    {
        $user = new User();
        $user->nom = $student->nom;
        $user->prenom = $student->prenom;
        $user->email = $student->email;
        $user->telephone = $student->telephone;
        $user->role = 'etudiant';
        $user->password = Hash::make('password');
        $user->establishment_id = Auth::user()->establishment_id;
        $user->save();

        $registration = $student->registration;

        $s = new Student();
        $s->user_id = $user->id;
        $s->filiere_id = $registration->filiere_id;
        $s->niveau_id = $registration->niveau_id;
        $s->save();

        $student->delete();
        return redirect()->route('session.index')->with('success', 'La demande a été acceptée avec succès.');
    }

    public function acceptAllStudent()
    {
        $students = PendingStudent::query()->where('registration_id', $this->students->first()->registration_id)->get();

        foreach ($students as $student) {
            $this->acceptStudent($student);
        }

        return redirect()->route('session.index')->with('message', 'Tous les étudiants ont été acceptés avec succès.');
    }

    public function refuseStudent(PendingStudent $student)
    {
        $student->delete();
        return redirect()->route('session.index')->with('success', 'La demande a été refusée avec succès.');
    }

    public function refuseAllStudent()
    {
        $students = PendingStudent::query()->where('registration_id', $this->students->first()->registration_id)->get();

        foreach ($students as $student) {
            $student->delete();
        }

        return redirect()->route('session.index')->with('message', 'Toutes les demandes ont été refusées avec succès.');
    }

    public function render()
    {
        $registrations = Registration::query()->where('establishment_id', Auth::user()->establishment_id)->paginate(10);
        return view('livewire.registration-session.index', compact('registrations'));
    }
}
