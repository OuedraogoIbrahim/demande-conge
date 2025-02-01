<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Planning;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $events = Planning::query()->where('filiere_id', Auth::user()->coordinateur->first()->filiere_id)->get()->flatMap(function ($event) {
            $startDate = Carbon::parse($event->date_debut);
            $endDate = Carbon::parse($event->date_fin);

            $events = [];
            while ($startDate->lte($endDate)) {
                $currentStart = $startDate->copy()->setTimeFrom(Carbon::parse($event->heure_debut));
                $currentEnd = $startDate->copy()->setTimeFrom(Carbon::parse($event->heure_fin));

                $module = $event->module;
                $classe = Classe::findOrFail($event->classe_id);

                $events[] = [
                    'id' => $event->id,
                    'url' => $classe->id,
                    'title' => $module->nom . " ($event->title)",
                    'start' => $currentStart->format('Y-m-d\TH:i:s'),
                    'end' => $currentEnd->format('Y-m-d\TH:i:s'),
                    'allDay' => false,
                    'extendedProps' => [
                        'calendar' => $event->type === 'cours' ? 'Business' : ($event->type === 'devoir' ? 'Personal' : 'ETC'),
                        'guests' => $module->id,
                    ],
                ];

                $startDate->addDay(); // Passez au jour suivant
            }

            return $events;
        });

        return response()->json($events);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'heure_debut' => 'required|date_format:H:i', // Valider heure au format HH:mm
            'heure_fin' => 'required|date_format:H:i|after_or_equal:heure_debut',
            'module' => 'required|',
            'type' => 'required|',
            'classe' => 'required|',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation échouée',
                'errors' => $validator->errors(),
            ], 422);
        }

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        while ($startDate->lte($endDate)) {
            $event = new Planning();
            $event->title = $request->title; // Utilisez $request pour récupérer les données
            $event->date_debut = $startDate->toDateString();
            $event->date_fin = $startDate->toDateString(); // Même date pour le même jour
            $event->heure_debut = $request->heure_debut; // Utilisez $request
            $event->heure_fin = $request->heure_fin; // Utilisez $request
            $event->module_id = $request->module; // Utilisez $request
            $event->type = $request->type == 'Business' ? 'cours' : ($request->type == 'Personal' ? 'devoir' : 'autre');
            $event->filiere_id = Auth::user()->coordinateur->first()->filiere_id;
            $event->classe_id = $request->classe; // Utilisez $request
            $event->establishment_id = Auth::user()->establishment_id;
            $event->statut = 'en attente';
            $event->save();

            // Passer au jour suivant
            $startDate->addDay();
        }

        return response()->json([
            'id' => 123456789,
            'message' => 'Événement enregistré avec succès',
        ], 201);
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
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
            'module' => 'required|',
            'type' => 'required|',
            'classe' => 'required|',
        ]);

        $event = Planning::query()->findOrFail($id);
        $event->title = $validated['title'];
        $event->date_debut = $validated['start_date'];
        $event->date_fin = $validated['end_date'];
        $event->heure_debut = $validated['heure_debut'];
        $event->heure_fin = $validated['heure_fin'];
        $event->module_id = $validated['module'];
        $event->type = $validated['type'] == 'Business' ? 'cours' : ($validated['type'] == 'Personal' ? 'devoir' : 'autre');
        $event->classe_id = $validated['classe'] ?? null;
        $event->update();

        return response()->json(['message' => 'Événement mis à jour avec succès.', 'event' => $event]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $event = Planning::query()->findOrFail($id);
        $event->delete();

        return response()->json(['message' => 'Événement supprimé avec succès.']);
    }
}
