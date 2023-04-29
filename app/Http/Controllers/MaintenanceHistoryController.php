<?php

namespace App\Http\Controllers;

use App\Http\Requests\MaintenanceHistoryRequest;
use Illuminate\Http\Request;
use App\Models\MaintenanceHistory;
use App\Models\Computer;
use Illuminate\Support\Facades\Auth;

class MaintenanceHistoryController extends Controller
{
    public function index(Request $request)
    {
        $computer = Computer::findOrFail($request->computer_id);

        return response()->json([
            'histories' => $computer->maintenanceHistories
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\MaintenanceHistoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MaintenanceHistoryRequest $request)
    {
        $validatedData = $request->validated();

        $computer = Computer::findOrFail($request->id);
        
        if ($computer->current_step != 3) {
            return response()->json([
                'message' => 'Este computador não está na fase adequada para criar um histórico de manutenção.'
            ], 400);
        }

        $maintenance_history = new MaintenanceHistory;

        $maintenance_history->fill($validatedData);

        $maintenance_history->responsible_id = Auth::user()->institutional_id;
        $maintenance_history->computer_id = $request->id;
        
        $maintenance_history->save();

        return response()->json([
            'message' => "Histórico de manutenção criado com sucesso!"
        ], 200);
    }
}
