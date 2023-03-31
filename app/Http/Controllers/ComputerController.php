<?php

namespace App\Http\Controllers;

use App\Http\Requests\ComputerRequest;
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
        $recordsPerPage = 10;

        $query = Computer::query();

        $filters = ['type', 'currentStep'];

        foreach ($filters as $filter) {
            if ($request->filled($filter)) {
                $query->where($filter, $request[$filter]);
            }
        }

        return $query->with('responsible')->simplePaginate($recordsPerPage);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreComputerRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ComputerRequest $request)
    {
        $validatedData = $request->validated();

        $user = Computer::create([
            'type' => $validatedData['type'],
            'manufacturer' => $validatedData['manufacturer'],
            'sanitized' => $validatedData['sanitized'],
            'functional' => $validatedData['functional'],
            'currentStep' => $validatedData['currentStep'],
            'currentStepResponsibleId' => $validatedData['currentStepResponsibleId']
        ]);

        return response()->json([
            'message' => "Computador criado com sucesso!"
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Computer  $computer
     * @return \Illuminate\Http\Response
     */
    public function show(Computer $computer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Computer  $computer
     * @return \Illuminate\Http\Response
     */
    public function edit(Computer $computer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateComputerRequest  $request
     * @param  \App\Models\Computer  $computer
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateComputerRequest $request, Computer $computer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Computer  $computer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Computer $computer)
    {
        //
    }
}
