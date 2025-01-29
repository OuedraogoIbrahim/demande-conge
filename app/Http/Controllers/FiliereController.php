<?php

namespace App\Http\Controllers;

use App\Models\Filiere;
use App\Http\Requests\StoreFiliereRequest;
use App\Http\Requests\UpdateFiliereRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class FiliereController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('pages.backend.filiere.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //

        if (Gate::denies('create', Filiere::class)) {
            abort(403, "Vous n'êtes pas autorisé à effectuer cette action.");
        }
        return view('pages.backend.filiere.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFiliereRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Filiere $filiere)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Filiere $filiere)
    {
        //
        if (Gate::denies('update', $filiere)) {
            abort(403, "Vous n'êtes pas autorisé à effectuer cette action.");
        }
        return view('pages.backend.filiere.edit', compact('filiere'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFiliereRequest $request, Filiere $filiere)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Filiere $filiere)
    {
        //
    }
}
