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
            'institutionalId' => $validatedData['institutionalId'],
            'telephone' => $validatedData['telephone'],
            'password' => Hash::make($validatedData['password'])
        ]);

        event(new Registered($user));

        return response()->json([
            'message' => "Usuário criado com sucesso! Um e-mail foi encaminhado para o seu endereço para que você possa confirmá-lo."
        ]);
    }

    public function login(Request $request) {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Os dados de acesso não existem ou estão incorretos.'
            ], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }
}
