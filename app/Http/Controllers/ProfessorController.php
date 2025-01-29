<?php

namespace App\Http\Controllers;

use App\Models\Professor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProfessorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('pages.backend.professeur.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        if (Gate::denies('create', Professor::class)) {
            abort(403, "Vous n'êtes pas autorisé à effectuer cette action.");
        }
        return view('pages.backend.professeur.create');
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
    public function edit(User $professeur)
    {
        //
        $prof = $professeur->professor->first();
        if (Gate::denies('create', $prof)) {
            abort(403, "Vous n'êtes pas autorisé à effectuer cette action.");
        }
        return view('pages.backend.professeur.edit', compact('professeur'));
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
