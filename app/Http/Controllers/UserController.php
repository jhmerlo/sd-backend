<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function userSelectOptionsIndex (Request $request)
    {
        $users = User::select('name AS label', 'institutional_id AS value')->get();

        return response()->json([
            'users' => $users
        ], 200);
    }
}
