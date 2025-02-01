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
                'email' => 'required|exists:users,email',
                'note' => 'required|numeric|between:0,20',
                'filiere' => 'required|string',
                'niveau' => 'required|exists:niveaux,nom',
                'module' => 'required|exists:modules,nom',
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
                'email' => $row['email'],
                // 'nom' => $row['nom'],
                // 'prenom' => $row['prenom'],
                'establishment_id' => Auth::user()->establishment_id,
            ])->first();

            if (!$user) {
                $failedImports++;
                $failedRows[] = [
                    'ligne' => $key + 1,
                    'erreurs' => ["L'utilisateur avec l\'email '{$row['email']}' n'existe pas."]
                ];
                continue;
            }

            // Vérification de la filière
            $filiere = Filiere::query()->where([
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
            $niveau = Niveau::query()->where([
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
                    'erreurs' => ["L'étudiant avec l\'email '{$row['email']}' n'existe pas dans la filière {$filiere->nom} niveau {$niveau->nom}."]
                ];
                continue;
            }

            // Vérification du module
            $module = Module::query()->where([
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

            //Verifier si le prof enseigne ce module
            $professeur = Auth::user()->professor->first();
            $enseigne = $professeur->modules()->where('module_id', $module->id)->exists();

            if (!$enseigne) {
                $failedImports++;
                $failedRows[] = [
                    'ligne' => $key + 1,
                    'erreurs' => ["Vous n'êtes pas autorisé à attribuer une note pour le module '{$module->nom}', car vous ne l'enseignez pas."]
                ];
                continue;
            }

            // Rechercher une note existante
            $existingNote = Note::query()->where('user_id', $user->id)
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
