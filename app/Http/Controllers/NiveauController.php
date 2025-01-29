<?php

namespace App\Http\Controllers;

use App\Models\Niveau;
use App\Http\Requests\StoreNiveauRequest;
use App\Http\Requests\UpdateNiveauRequest;
use Illuminate\Support\Facades\Gate;

class NiveauController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('pages.backend.niveau.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        if (Gate::denies('create', Niveau::class)) {
            abort(403, "Vous n'êtes pas autorisé à effectuer cette action.");
        }
        return view('pages.backend.niveau.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNiveauRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Niveau $niveau)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Niveau $niveau)
    {
        //
        if (Gate::denies('update', $niveau)) {
            abort(403, "Vous n'êtes pas autorisé à effectuer cette action.");
        }
        return view('pages.backend.niveau.edit', compact('niveau'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNiveauRequest $request, Niveau $niveau)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Niveau $niveau)
    {
        //
    }
}
