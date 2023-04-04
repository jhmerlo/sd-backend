<?php

namespace App\Http\Controllers;

use App\Http\Requests\RamMemoryRequest;
use Illuminate\Http\Request;
use App\Models\RamMemory;
use App\Models\Computer;

class RamMemoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $recordsPerPage = 10;

        $query = RamMemory::query();

        $filters = ['computer_id', 'model', 'manufacturer', 'functional', 'technology'];

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
     * @param  \App\Http\Requests\RamMemoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RamMemoryRequest $request)
    {
        $validatedData = $request->validated();

        $ram_memory = new RamMemory;

        $ram_memory->fill($validatedData);

        $ram_memory->save();

        return response()->json([
            'message' => "Memória RAM criada com sucesso!"
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RamMemory  $ram_memory
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $ram_memory = RamMemory::findOrFail($request->id);

        return response()->json([
            'ramMemory' => $ram_memory
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\RamMemoryRequest  $request
     * @param  \App\Models\RamMemory  $ram_memory
     * @return \Illuminate\Http\Response
     */
    public function update(RamMemoryRequest $request)
    {
        $ram_memory = RamMemory::findOrFail($request->id);
        
        $validatedData = $request->validated();

        $ram_memory->fill($validatedData);

        $changedComputerId = $ram_memory->isDirty('computer_id');
        $changedFunctionalFieldToFalse = $ram_memory->isDirty('functional') && $ram_memory['functional'] == false;

        if ($ram_memory->isDirty()) {
            if (($changedComputerId || $changedFunctionalFieldToFalse) && !is_null($ram_memory->getOriginal('computer_id'))) {
                $computer = Computer::findOrFail($ram_memory->getOriginal('computer_id'));

                //check if exists other functional ramMemory
                $existsOtherFunctional = count($computer->ramMemories->where('functional', true)) >= 2;

                if ($computer['current_step'] > 2 && !$existsOtherFunctional) {
                    $computer['current_step'] = 2;
                    $computer->save();
                }
            }
            $ram_memory->save();
        }
        
        return response()->json([
            'message' => 'Memória RAM editada com sucesso!',
            'ramMemory' => $ram_memory
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RamMemory  $ram_memory
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $ram_memory = RamMemory::findOrFail($request->id);

        $ram_memory->delete();

        return response()->json([
            'message' => "Memória RAM editada com sucesso!"
        ], 200);
    }
}
