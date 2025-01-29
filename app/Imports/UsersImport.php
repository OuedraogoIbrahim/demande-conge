<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Filiere;
use App\Models\Niveau;
use App\Models\Student;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        $successfulImports = 0;
        $failedImports = 0;
        $failedRows = [];

        foreach ($rows as $key => $row) {
            // Appliquer les règles de validation à chaque ligne
            $validator = Validator::make($row->toArray(), [
                'nom' => 'required|string',
                'prenom' => 'required|string',
                'email' => 'required|email|unique:users,email',
                'telephone' => 'required|numeric',
                'filiere' => 'required|string',
                'niveau' => 'required|string',
            ]);

            // Vérifier si la validation a échoué
            if ($validator->fails()) {
                $failedImports++;
                $failedRows[] = ['ligne' => $key, 'erreurs' => $validator->errors()->all()];
                continue;
            }

            // Vérification que la filière existe
            $filiere = Filiere::where([
                'establishment_id' => Auth::user()->establishment_id,
                'nom' => $row['filiere']
            ])->first();

            if (!$filiere) {
                $failedImports++;
                $failedRows[] = ['ligne' => $key, 'erreurs' => ["La filière '{$row['filiere']}' n'existe pas"]];
                continue;
            }

            // Vérification que le niveau existe
            $niveau = Niveau::where([
                'filiere_id' => $filiere->id,
                'nom' => $row['niveau']
            ])->first();

            if (!$niveau) {
                $failedImports++;
                $failedRows[] = ['ligne' => $key + 1, 'erreurs' => ["Le niveau '{$row['niveau']}' n'existe pas"]];
                continue;
            }

            // Création de l'utilisateur
            $user = User::create([
                'nom' => $row['nom'],
                'prenom' => $row['prenom'],
                'email' => $row['email'],
                'telephone' => $row['telephone'],
                'password' => bcrypt('password'),
                'role' => 'etudiant',
                'establishment_id' => Auth::user()->establishment_id
            ]);

            // Création de l'étudiant
            $student = new Student();
            $student->user_id = $user->id;
            $student->filiere_id = $filiere->id;
            $student->niveau_id = $niveau->id;
            $student->save();

            $successfulImports++;
        }

        // Message de résumé
        if ($failedImports > 0) {
            session()->flash('message', "Import terminé avec $successfulImports succès et $failedImports échecs.");
            session()->flash('failedRows', $failedRows); // Optionnel, pour afficher les erreurs détaillées
        } else {
            session()->flash('message', "Import terminé avec succès ! $successfulImports utilisateurs importés.");
        }
    }
}
