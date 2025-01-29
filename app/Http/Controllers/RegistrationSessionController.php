<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Illuminate\Http\Request;

class RegistrationSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('pages.backend.registrationSession.index');
    }

    public function studentRegister(string $token)
    {
        $registration = Registration::query()->where('lien', route('session.etudiant', ['token' => $token]))->first();
        if (!$registration)
            abort(404);

        if ($registration->date_fin < now()->format('Y-m-d'))
            abort(403, 'Lien expiré');

        return view('pages.backend.registrationSession.student-registration', compact('registration'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('pages.backend.registrationSession.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
