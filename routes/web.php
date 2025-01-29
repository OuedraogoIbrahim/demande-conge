<?php

use App\Http\Controllers\AuthentificationController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\FiliereController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\NiveauController;
use App\Http\Controllers\ParametreController;
use App\Http\Controllers\PlanningController;
use App\Http\Controllers\ProfessorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegistrationSessionController;
use App\Http\Controllers\SondageController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::middleware('guest')->group(function () {
    Route::get('/inscription', [AuthentificationController::class, 'registerForm'])->name('register');
    Route::post('/inscription', [AuthentificationController::class, 'register']);

    Route::get('/connexion', [AuthentificationController::class, 'loginForm'])->name('login');
    Route::post('/connexion', [AuthentificationController::class, 'login']);

    // Inscription a une session d'inscription
    // Route::get('session/etudiant/create/{token}', [RegistrationSessionController::class, 'studentRegister'])->name('session.etudiant');

    Route::get('/mot-de-passe-oublie', [AuthentificationController::class, 'passwordForgotten'])->name('password.forgotten');
});

Route::get('changement-mot-de-passe', [AuthentificationController::class, 'changePasswordForm'])->middleware('auth')->name('change.password');
Route::post('changement-mot-de-passe', [AuthentificationController::class, 'changePassword'])->middleware('auth');



Route::middleware(['auth', 'change.password'])->group(function () {
    Route::post('deconnexion', [AuthentificationController::class, 'deconnexion'])->name('logout');
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('profile', ProfileController::class)->name('profile');
    Route::get('parametres', ParametreController::class)->name('parametres');

    Route::resource('filieres', FiliereController::class)->except(['show', 'update', 'destroy', 'store']);
    Route::resource('modules', ModuleController::class)->except(['show', 'update', 'destroy', 'store']);
    Route::resource('niveaux', NiveauController::class)->except(['show', 'update', 'destroy', 'store']);
    Route::resource('professeurs', ProfessorController::class)->except(['show', 'update', 'destroy', 'store']);
    Route::resource('etudiants', StudentController::class)->only(['index']);
    Route::resource('classes', ClassController::class)->except(['show', 'update', 'destroy', 'store']);
    Route::resource('plannings', PlanningController::class)->except(['create', 'edit', 'show', 'update', 'destroy', 'store']);
    Route::get('mon-planning', [PlanningController::class, 'myPlanning'])->name('mon.planning');
    Route::resource('sondages', SondageController::class)->except(['edit', 'update', 'destroy', 'store']);
    Route::resource('documents', DocumentController::class)->except(['show', 'update', 'destroy', 'store']);

    // Affichage et creation d'une session d'inscription
    // Route::get('session/inscription', [RegistrationSessionController::class, 'index'])->name('session.index');
    // Route::get('session/inscription/create', [RegistrationSessionController::class, 'create'])->name('session.create');
});

Route::resource('events', EventController::class);
