<?php

namespace App\Http\Controllers;

use App\Http\Requests\PowerSupplyRequest;
use Illuminate\Http\Request;
use App\Models\PowerSupply;
use App\Models\Computer;

class PowerSupplyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $recordsPerPage = 10;

        $query = PowerSupply::query();

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
     * @param  \App\Http\Requests\PowerSupplyRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PowerSupplyRequest $request)
    {
        $validatedData = $request->validated();

        $power_supply = new PowerSupply;

        $power_supply->fill($validatedData);

        $power_supply->save();

        return response()->json([
            'message' => "Fonte de alimentação criada com sucesso!"
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PowerSupply  $power_supply
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $power_supply = PowerSupply::findOrFail($request->id);

        return response()->json([
            'powerSupply' => $power_supply
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\PowerSupplyRequest  $request
     * @param  \App\Models\PowerSupply  $power_supply
     * @return \Illuminate\Http\Response
     */
    public function update(PowerSupplyRequest $request)
    {
        $power_supply = PowerSupply::findOrFail($request->id);
        
        $validatedData = $request->validated();

        $power_supply->fill($validatedData);

        $changedComputerId = $power_supply->isDirty('computer_id');
        $changedFunctionalFieldToFalse = $power_supply->isDirty('functional') && $power_supply['functional'] == false;

        if ($power_supply->isDirty()) {
            if (($changedComputerId || $changedFunctionalFieldToFalse) && !is_null($power_supply->getOriginal('computer_id'))) {
                $computer = Computer::findOrFail($power_supply->getOriginal('computer_id'));
            
                if ($computer['current_step'] > 2) {
                    $computer['current_step'] = 2;
                    $computer->save();
                }
            }
            $power_supply->save();
        }
        
        return response()->json([
            'message' => 'Fonte de alimentação editada com sucesso!',
            'powerSupply' => $power_supply
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PowerSupply  $power_supply
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $power_supply = PowerSupply::findOrFail($request->id);

        $power_supply->delete();

        return response()->json([
            'message' => "Fonte de alimentação deletada com sucesso!"
        ], 200);
    }
}
