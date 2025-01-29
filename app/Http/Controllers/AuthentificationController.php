<?php

namespace App\Http\Controllers;

use App\Models\establishment;
use App\Models\Supervisor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthentificationController extends Controller
{
    //
    public function registerForm(): View
    {
        $pageConfigs = ['myLayout' => 'blank'];
        return view('authentifcation.register', compact('pageConfigs'));
    }

    public function register(Request $request)
    {

        $valid = $request->validate([
            'nom' => 'required',
            'prenom' => 'required',
            'email' => 'required|email|unique:users,email',
            'etablissement' => 'required',
            'logo' => 'nullable|mimes:jpg,jpeg,png',
            'password' => 'required|min:8|confirmed',
            'telephone' => 'required|unique:users,telephone',
            // 'terms' => 'accepted'
        ]);

        // Création de l'établissement
        $etablissement = new establishment();
        $etablissement->nom = $valid['etablissement'];
        $etablissement->save();

        if ($valid['logo']) {
            $path = $request->file('logo')->store('logo/' . $etablissement->id);
            $etablissement->logo = $path;
            $etablissement->update();
        }

        // Création de l'utilisateur
        $user = new User();
        $user->nom = $valid['nom'];
        $user->prenom = $valid['prenom'];
        $user->email = $valid['email'];
        $user->telephone = $valid['telephone'];

        $user->role = 'superviseur';
        $user->password = Hash::make($valid['password']);

        $user->establishment_id = $etablissement->id;
        $user->save();

        // Création du superviseur
        $superviseur = new Supervisor();
        $superviseur->user_id = $user->id;
        $superviseur->save();

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    public function loginForm(): View
    {
        $pageConfigs = ['myLayout' => 'blank'];
        return view('authentifcation.login', compact('pageConfigs'));
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'Les informations saisies ne sont pas correctes.',
        ])->onlyInput('email');
    }

    public function passwordForgotten(): View
    {
        $pageConfigs = ['myLayout' => 'blank'];
        return view('authentifcation.passwordForgotten', compact('pageConfigs'));
    }

    public function deconnexion(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function changePasswordForm()
    {
        if (!Hash::check('password', Auth::user()->password)) {
            return redirect()->route('dashboard');
        }
        return view('authentifcation.changePassword');
    }

    public function changePassword(Request $request)
    {
        $valid = $request->validate([
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);

        if ($valid['password'] == 'password') {
            return back()->withErrors([
                'password' => 'Le nouveau mot de passe doit être différent de password',
            ]);
        }

        $user = User::query()->find(Auth::user()->id);

        $user->update([
            'password' => bcrypt($valid['password']),
        ]);
        return redirect()->route('dashboard');
    }
}
