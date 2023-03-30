<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Auth\Access\AuthorizationException;
use App\Models\User;

class EmailVerificationController extends Controller
{

    public function resendVerificationEmail(Request $request)
    {
        $user = User::where('email', '=', $request['email'])->firstOrFail();

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Este e-mail já foi verificado.'
            ], 400);
        }

        $user->sendEmailVerificationNotification();

        return response()->json([
            'message' => 'Uma nova mensagem de confirmação foi enviada para a sua caixa de e-mails.'
        ], 200);
    }

    public function verify(Request $request)
    {
        $user = User::find($request->route('id'));

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Este e-mail já foi verificado.'
            ], 400);
        }

        if (!hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            return response()->json([
                'message' => 'Ocorreu um erro ao verificar o e-mail, verifique os dados e tente novamente.'
            ], 400);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return response()->json([
            'message'=>'O e-mail foi verificado com sucesso.'
        ], 200);
    }
}