<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Filiere;
use App\Models\Niveau;
use App\Models\Module;
use App\Models\Note;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class NotesImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        $successfulImports = 0;
        $failedImports = 0;
        $failedRows = [];

        foreach ($rows as $key => $row) {
            // Validation des données
            $validator = Validator::make($row->toArray(), [
                'nom' => 'required|string',
                'prenom' => 'required|string',
                'note' => 'required|numeric|between:0,20',
                'filiere' => 'required|string',
                'niveau' => 'required|string',
                'module' => 'required|string',
            ]);

            if ($validator->fails()) {
                $failedImports++;
                $failedRows[] = [
                    'ligne' => $key + 1,
                    'erreurs' => $validator->errors()->all()
                ];
                continue;
            }

            // Vérification de l'utilisateur
            $user = User::where([
                'nom' => $row['nom'],
                'prenom' => $row['prenom'],
                'establishment_id' => Auth::user()->establishment_id,
            ])->first();

            if (!$user) {
                $failedImports++;
                $failedRows[] = [
                    'ligne' => $key + 1,
                    'erreurs' => ["L'utilisateur '{$row['nom']} {$row['prenom']}' n'existe pas."]
                ];
                continue;
            }


            // Vérification de la filière
            $filiere = Filiere::where([
                'establishment_id' => Auth::user()->establishment_id,
                'nom' => $row['filiere'],
            ])->first();

            if (!$filiere) {
                $failedImports++;
                $failedRows[] = [
                    'ligne' => $key + 1,
                    'erreurs' => ["La filière '{$row['filiere']}' n'existe pas."]
                ];
                continue;
            }

            // Vérification du niveau
            $niveau = Niveau::where([
                'filiere_id' => $filiere->id,
                'nom' => $row['niveau'],
            ])->first();

            if (!$niveau) {
                $failedImports++;
                $failedRows[] = [
                    'ligne' => $key + 1,
                    'erreurs' => ["Le niveau '{$row['niveau']}' n'existe pas."]
                ];
                continue;
            }

            $student = $user->student()
                ->where([
                    'filiere_id' => $filiere->id,
                    'niveau_id' => $niveau->id,
                ])
                ->first();

            if (!$student) {
                $failedImports++;
                $failedRows[] = [
                    'ligne' => $key + 1,
                    'erreurs' => ["L'étudiant '{$row['nom']} {$row['prenom']}' n'existe pas."]
                ];
                continue;
            }

            // Vérification du module
            $module = Module::where([
                'filiere_id' => $filiere->id,
                'niveau_id' => $niveau->id,
                'nom' => $row['module'],
            ])->first();

            if (!$module) {
                $failedImports++;
                $failedRows[] = [
                    'ligne' => $key + 1,
                    'erreurs' => ["Le module '{$row['module']}' n'existe pas."]
                ];
                continue;
            }

            // Rechercher une note existante
            $existingNote = Note::where('user_id', $user->id)
                ->where('establishment_id', Auth::user()->establishment_id)
                ->first();

            if ($existingNote) {
                // Mise à jour de la note
                $existingData = json_decode($existingNote->donnees, true);
                $existingData[$module->nom] = ['note' => $row['note']];
                $existingNote->donnees = json_encode($existingData);
                $existingNote->save();

                $successfulImports++;
            } else {
                // Création d'une nouvelle note
                Note::create([
                    'donnees' => json_encode([
                        $module->nom => ['note' => $row['note']]
                    ]),
                    'user_id' => $user->id,
                    'establishment_id' => Auth::user()->establishment_id,
                ]);

                $successfulImports++;
            }
        }

        // Flash messages
        session()->flash('message', "$successfulImports notes importées avec succès.");
        if ($failedImports > 0) {
            session()->flash('error', "$failedImports lignes ont échoué.");
            session()->flash('failedRows', $failedRows);
        }
    }
}
