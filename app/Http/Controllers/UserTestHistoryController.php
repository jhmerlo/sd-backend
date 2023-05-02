<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserTestHistoryRequest;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Models\UserTestHistory;
use App\Models\Computer;

class UserTestHistoryController extends Controller
{
    public function index(Request $request)
    {
        $computer = Computer::findOrFail($request->computer_id);
        
        return response()->json([
            'histories' => $computer->userTestHistories
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\UserTestHistoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserTestHistoryRequest $request)
    {
        $validatedData = $request->validated();

        $computer = Computer::findOrFail($request->id);
        
        if ($computer->current_step != 5) {
            return response()->json([
                'message' => 'Este computador não está na fase adequada para criar um histórico de testes de usuário.'
            ], 400);
        }

        $user_tests_history = new UserTestHistory;

        $user_tests_history->fill($validatedData);

        $user_tests_history->responsible_id = Auth::user()->institutional_id;
        $user_tests_history->computer_id = $request->id;

        $user_tests_history->save();

        return response()->json([
            'message' => "Histórico de teste de usuário criado com sucesso!"
        ], 200);
    }
}
