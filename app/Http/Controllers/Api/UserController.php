<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Mockery\Exception;

class UserController extends Controller
{
    //------------------------------------------------------------------Affichage des utilisateurs-------------------------------------------------------
    public function index()
    {
        try {
            //if (Auth::user()->isAdmin()) {
                return $this->jsonResponse(true, 'Liste des utilisateurs récupérée avec succès', User::all());
           // } else {
             //   return $this->jsonResponse(true, 'Informations de l\'utilisateur connecté récupérées avec succès', Auth::user());
            //}
        } catch (\Exception $e) {
            return $this->jsonResponse(false, $e->getMessage(), [], $e->getCode() ?: 500);
        }
    }

    //------------------------------------------------------------------Sauvegarde des utilisateurs------------------------------------------------------------
    public function store(UserRequest $request)
    {
        try {
            $validated = $request->validated();

            $users = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'telephone' => $validated['telephone'],
                'password' => Hash::make($validated['password']),
                'role' => "admin",
            ]);

            return $this->jsonResponse(true, 'Utilisateur créé avec succès !', $users, 201);

        } catch (\Exception $exception) {

            return $this->jsonResponse(false, $exception->getMessage(), [], 500);
        }
    }

    //---------------------------------------------------------------Fonction de détails de l'utilisateur--------------------------------------------------
    public function show(string $id)
    {
        try {
            $user = User::findOrFail($id);
            return $this->jsonResponse(true, "Détails de l'utilisateur", $user, 200);

        } catch (Exception $exception) {
            return $this->jsonResponse(false, $exception->getMessage(), [], 500);
        }
    }

    //-------------------------------------------------------------------Fonction de modification----------------------------------------------------------
    public function update(Request $request, string $id): \Illuminate\Http\JsonResponse
    {

        try {
            // Validation des données d'entrée
            $validated = $request->validate([
                'name' => ['required', 'min:2', 'regex:/^[\pL\s]+$/u'],
                'telephone' => ['required', 'regex:/^\+?\d+$/'],
                'email' => ['required', 'email', 'unique:users,email,'.$id],

            ]);


            // Recherche de l'utilisateur
            $user = User::findOrFail($id);

            // Mise à jour des données de l'utilisateur
            $user->name = $validated['name'];
            $user->telephone = $validated['telephone'];
            $user->email = $validated['email'];
            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }

            return $this->jsonResponse(true, "Utilisateur modifié avec succès !", $user->update($validated), 201);

        } catch (\Exception $e) {
            return $this->jsonResponse(false, $e->getMessage(), [], $e->getCode() ?: 500);
        }
    }

    //--------------------------------------------------------------------Suppression d'utilisateur------------------------------------------------------
    public function destroy(string $id): \Illuminate\Http\JsonResponse
    {
        try {
            $user = User::findOrFail($id);

            // Supprime l'utilisateur s'il est trouvé
            $user->delete();

            return $this->jsonResponse(true, "Utilisateur supprimé avec succès", [], 200);
        } catch (ModelNotFoundException $exception) {
            // Gestion des exceptions de modèle non trouvé
            return $this->jsonResponse(false, "Utilisateur non trouvé", [], 404);
        } catch (\Exception $exception) {
            // Gestion des autres exceptions
            return $this->jsonResponse(false, $exception->getMessage(), [], 500);
        }
    }

}
