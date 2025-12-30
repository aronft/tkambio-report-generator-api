<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthLoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(AuthLoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        $canCheckHashedCredentials = Hash::check($request->password, $user->password);
        if (!$user || !$canCheckHashedCredentials) {
            return response()->json(['message' => 'Error al iniciar sesión. Credenciales incorrectas'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }
}
