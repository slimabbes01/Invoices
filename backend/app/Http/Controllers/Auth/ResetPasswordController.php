<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
    // Méthode pour afficher le formulaire de réinitialisation du mot de passe
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    // Méthode pour envoyer le lien de réinitialisation du mot de passe
    public function sendResetLinkEmail(Request $request)
    {
        // Validation de l'email
        $request->validate(['email' => 'required|email|exists:users,email']);

        // Envoi du lien de réinitialisation
        $status = Password::sendResetLink($request->only('email'));

        // Vérification du statut de l'envoi
        if ($status === Password::RESET_LINK_SENT) {
            return response()->json(['message' => 'Un lien de réinitialisation du mot de passe a été envoyé par e-mail.']);
        }

        // Gestion des erreurs lors de l'envoi
        return response()->json(['message' => 'Erreur lors de l\'envoi du lien de réinitialisation.'], 400);
    }

    // Méthode pour réinitialiser le mot de passe
    public function reset(Request $request)
    {
        // Validation des données
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
            'token' => 'required'
        ]);

        // Trouver l'utilisateur par email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'Utilisateur non trouvé.'], 404);
        }

        // Mettre à jour le mot de passe de l'utilisateur
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['message' => 'Mot de passe réinitialisé avec succès.']);
    }
}
