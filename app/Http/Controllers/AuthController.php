<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

// User Request and Model
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;

class AuthController extends Controller
{
    /**
     * Register a new user in the database
     * @param \App\Http\Requests\UserRequest $request
     * @return Illuminate\Http\Response
     */
    public function register(UserRequest $request) {
        $validatedData = $request->validated();

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'institutionalId' => $validatedData['institutional_id'],
            'telephone' => $validatedData['telephone'],
            'password' => Hash::make($validatedData['password'])
        ]);

        event(new Registered($user));

        return response()->json([
            'message' => "Usuário criado com sucesso! Um e-mail foi encaminhado para o seu endereço para que você possa confirmá-lo."
        ], 200);
    }

    public function login(Request $request) {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Os dados de acesso não existem ou estão incorretos.'
            ], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        if ($user['email_verified_at'] == null) {
            return response()->json([
                'message' => 'E-mail não verificado.'
            ], 401);
        }

        if ($user['license'] != 'active') {
            return response()->json([
                'message' => 'Você não possui uma licença ativa. Contate um administrador para conceder acesso ao sistema.'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function revokeAccessTokens () {

        // revoking tokens of logged user

        Auth::user()->tokens()->delete();

        return response()->json([
            'message' => 'Logout realizado com sucesso.'
        ]);
    }
}
