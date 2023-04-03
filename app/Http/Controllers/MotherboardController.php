<?php

namespace App\Http\Controllers;

use App\Http\Requests\MotherboardRequest;
use Illuminate\Http\Request;
use App\Models\Motherboard;
use App\Models\Computer;

class MotherboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $recordsPerPage = 10;

        $query = Motherboard::query();

        $filters = ['computer_id', 'model', 'manufacturer', 'functional'];

        foreach ($filters as $filter) {
            if ($request->filled($filter)) {
                $query->where($filter, $request[$filter]);
            }
        }

        return $query->simplePaginate($recordsPerPage);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreMotherboardRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MotherboardRequest $request)
    {
        $validatedData = $request->validated();

        $motherboard = Motherboard::create([
            'manufacturer' => $validatedData['manufacturer'],
            'functional' => $validatedData['functional'],
            'model' => $validatedData['model'],
            'computer_id' => $validatedData['computer_id']
        ]);

        return response()->json([
            'message' => "Placa mãe criada com sucesso!"
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Motherboard  $motherboard
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $motherboard = Motherboard::findOrFail($request->id);

        return response()->json([
            'motherboard' => $motherboard
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateMotherboardRequest  $request
     * @param  \App\Models\Motherboard  $motherboard
     * @return \Illuminate\Http\Response
     */
    public function update(MotherboardRequest $request)
    {
        $motherboard = Motherboard::findOrFail($request->id);
        $validatedData = $request->validated();

        $motherboard->fill($validatedData);


        $changedComputerId = $motherboard->isDirty('computer_id') && $motherboard->getOriginal('computer_id') != null;
        $changedFunctionalFieldToFalse = $motherboard->isDirty('functional') && $motherboard['functional'] == false;

        // situação 1: id do computador mudou = computador de origem deve voltar etapa 2
        // situação 2: id do computador não mudou mas functional = false => id do computador de origem deve ir para a etapa 2
        // situação 3: muda o id do computador e o functional = false => computador de origem deve ir para a etapa 2 e o computador destino nada acontece

        if (count($motherboard->getDirty()) > 0) {
            if ($changedComputerId || $changedFunctionalFieldToFalse) {
                $computer = Computer::findOrFail($motherboard->getOriginal('computer_id'));
                
                if ($computer['current_step'] > 2) {
                    $computer['current_step'] = 2;
                    $computer->save();
                }
            }
            $motherboard->save();
        }
        
        return response()->json([
            'message' => 'Placa mãe editada com sucesso!',
            'motherboard' => $motherboard
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Motherboard  $motherboard
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $motherboard = Motherboard::findOrFail($request->id);

        $motherboard->delete();

        return response()->json([
            'message' => "Placa mãe deletada com sucesso!"
        ], 200);
    }
}
