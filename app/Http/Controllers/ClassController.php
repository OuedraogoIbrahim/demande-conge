<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Classes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('pages.backend.classe.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        if (Gate::denies('create', Classe::class)) {
            abort(403, "Vous n'êtes pas autorisé à effectuer cette action.");
        }
        return view('pages.backend.classe.create');
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
    public function show(Classe $classes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Classe $class)
    {
        //
        if (Gate::denies('update', $class)) {
            abort(403, "Vous n'êtes pas autorisé à effectuer cette action.");
        }
        return view('pages.backend.classe.edit' , compact('class'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Classe $classes)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Classe $classes)
    {
        //
    }
}
