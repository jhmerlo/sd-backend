<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Http\Requests\ComputerRequest;
use App\Http\Requests\SortingRequest;
use App\Http\Requests\ResponsibleRequest;
use App\Http\Requests\MaintenanceRequest;
use App\Http\Requests\NetworkAndPeripheralsRequest;
use App\Http\Requests\UserTestsRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Computer;
use App\Models\User;

class ComputerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $recordsPerPage = 12;

        $query = Computer::query();

        $exactFilters = ['type', 'current_step', 'patrimony', 'current_step_responsible_id', 'id'];
        $likeFilters = ['manufacturer', 'operational_system'];

        foreach ($exactFilters as $filter) {
            if ($request->filled($filter)) {
                $query->where($filter, $request[$filter]);
            }
        }

        foreach ($likeFilters as $filter) {
            if ($request->filled($filter)) {
                $query->where($filter, 'ILIKE', '%'. $request[$filter] . '%');
            }
        }

        return $query->orderBy('updated_at', 'desc')->with(['responsible', 'loans', 'loans.responsible', 'loans.borrower'])->paginate($recordsPerPage);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreComputerRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SortingRequest $request)
    {
        $validatedData = $request->validated();

        $computer = new Computer;
        $computer->fill($validatedData);
        $computer->current_step_responsible_id = Auth::user()->institutional_id;

        $computer->current_step = 1;

        $computer->save();

        return response()->json([
            'computer' => $computer,
            'message' => "Computador criado com sucesso!"
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Computer  $computer
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $computer = Computer::with([
            'responsible', 
            'motherboard', 
            'processor', 
            'ramMemories', 
            'storageDevices', 
            'powerSupply', 
            'monitors', 
            'gpus',
            'comments',
            'comments.user',
            'maintenanceHistories',
            'maintenanceHistories.responsible',
            'userTestHistories',
            'userTestHistories.responsible'
        ])->findOrFail($request->id);

        return response()->json([
            'computer' => $computer
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateComputerRequest  $request
     * @param  \App\Models\Computer  $computer
     * @return \Illuminate\Http\Response
     */
    public function sortingUpdate (SortingRequest $request)
    {
        $computer = Computer::findOrFail($request->id);

        if ($computer->current_step != 1) {
            return response()->json([
                'message' => 'Computador não está na fase adequada para esta solicitação.'
            ], 400);
        }

        $validatedData = $request->validated();

        $computer->fill($validatedData);

        $computer->current_step = 2;

        $computer->save();

        return response()->json([
            'message' => 'Computador movido para a próxima etapa.'
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateComputerRequest  $request
     * @param  \App\Models\Computer  $computer
     * @return \Illuminate\Http\Response
     */
    public function hardwareTestsUpdate (Request $request)
    {
        $computer = Computer::findOrFail($request->id);

        if ($computer->current_step != 2) {
            return response()->json([
                'message' => 'Computador não está na fase adequada para esta solicitação.'
            ], 400);
        }

        $motherboard = $computer->motherboard;
        $processor = $computer->processor;
        $gpus = $computer->gpus;
        $monitors = $computer->monitors;
        $power_supply = $computer->powerSupply;
        $ram_memories = $computer->ramMemories;
        $storage_devices = $computer->storageDevices;

        $validMotherboard = is_null($motherboard) ? false : $motherboard->functional;
        $validProcessor = is_null($processor) ? false : $processor->functional;
        $validPowerSupply = is_null($power_supply) ? false : $power_supply->functional;
        $validGpu = is_null($gpus) ? false : count($gpus->where('functional', true)) >= 1;
        $validMonitor = is_null($monitors) ? false : count($monitors->where('functional', true)) >= 1;
        $validRamMemory = is_null($ram_memories) ? false : count($ram_memories->where('functional', true)) >= 1;
        $validStorageDevice = is_null($storage_devices) ? false : count($storage_devices->where('functional', true)) >= 1;

        if ($validMotherboard && $validProcessor && $validGpu && $validMonitor && $validPowerSupply && $validRamMemory && $validStorageDevice) {
            $computer->current_step = 3;
            $computer->save();

            return response()->json([
                'message' => 'Computador movido para a próxima etapa.'
            ], 200);
        } else {
            return response()->json([
                'message' => 'Computador não possui os componentes mínimos para seguir para a próxima etapa.'
            ], 400);
        }
    }

    public function maintenanceUpdate (Request $request)
    {
        $computer = Computer::findOrFail($request->id);

        if ($computer->current_step != 3) {
            return response()->json([
                'message' => 'Computador não está na fase adequada para esta solicitação.'
            ], 400);
        }

        $computer->current_step = 4;

        $computer->save();

        return response()->json([
            'message' => 'Computador movido para a próxima etapa.'
        ], 200);
    }

    public function networkAndPeripheralsUpdate (NetworkAndPeripheralsRequest $request)
    {
        $computer = Computer::findOrFail($request->id);
        $validatedData = $request->validated();

        if ($computer->current_step != 4) {
            return response()->json([
                'message' => 'Computador não está na fase adequada para esta solicitação.'
            ], 400);
        }

        $computer->fill($validatedData);

        $computer->current_step = 5;

        $computer->save();

        return response()->json([
            'message' => 'Computador movido para a próxima etapa.'
        ], 200);
    }

    public function userTestsUpdate (UserTestsRequest $request)
    {
        $computer = Computer::findOrFail($request->id);
        $validatedData = $request->validated();

        if ($computer->current_step != 5) {
            return response()->json([
                'message' => 'Computador não está na fase adequada para esta solicitação.'
            ], 400);
        }

        $computer->fill($validatedData);

        $computer->current_step = 6;
        $computer->functional = true;

        $computer->save();

        return response()->json([
            'message' => 'Computador movido para a próxima etapa.'
        ], 200);
    }

    public function resetSteps (Request $request)
    {
        $computer = Computer::findOrFail($request->id);

        if ($computer->current_step == 1) {
            return response()->json([
                'message' => 'Este computador já se encontra na etapa de triagem.'
            ], 400);
        } 

        if ($computer->borrowed) {
            return response()->json([
                'message' => 'Este computador está emprestado e não pode retornar para a triagem.'
            ], 400);
        }

        $computer->current_step = 1;

        $computer->save();

        return response()->json([
            'message' => 'Computador movido para a etapa inicial.'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Computer  $computer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $computer = Computer::findOrFail($request->id);

        $computer->delete();

        return response()->json([
            'message' => "Computador deletado com sucesso!"
        ], 200);
    }

    public function changeResponsible(ResponsibleRequest $request)
    {  
        $computer = Computer::findOrFail($request->id);
        $validatedData = $request->validated();

        $computer->current_step_responsible_id = $validatedData['current_step_responsible_id'];

        $computer->save();

        return response()->json([
            'message' => "Responsável alterado com sucesso!"
        ], 200);
    }
}
