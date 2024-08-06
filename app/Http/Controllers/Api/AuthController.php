<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //--------------------------------------------------------Fonction de création de compte----------------------------------------------------------
    public function register(UserRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $validated = $request->validated();

            $users = User::create($validated);

            $token = $users->createToken('auth_token')->plainTextToken;

            return $this->jsonResponse(true, 'Utilisateur créé avec succès !', [
                'access_token' => $token,
                'user' => $users,
                'token_type' => 'Bearer',
            ], 201);

        } catch (\Exception $exception) {

            return $this->jsonResponse(false, $exception->getMessage(), [], 500);
        }
    }

    //-----------------------------------------------------------------Fonction de connexion--------------------------------------------------------------
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            return $this->jsonResponse(true, "Bienvenue {$user->name}", [
                'access_token' => $user->createToken('auth_token')->plainTextToken,
                'user' => $user,
                'token_type' => 'Bearer',

            ], 200);
        }

        return $this->jsonResponse(false, "Email et/ou mot de passe incorrect", [], 401);
    }

    //-----------------------------------------------------------------Fonction de déconnexion--------------------------------------------------------
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
}
