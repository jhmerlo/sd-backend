<?php

namespace App\Http\Controllers;

use App\Http\Requests\GpuRequest;
use Illuminate\Http\Request;
use App\Models\Gpu;
use App\Models\Computer;

class GpuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $recordsPerPage = 10;

        $query = Gpu::query();

        $filters = ['computer_id', 'model', 'manufacturer', 'functional', 'integrated'];

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
     * @param  \App\Http\Requests\GpuRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GpuRequest $request)
    {
        $validatedData = $request->validated();

        $gpu = new Gpu;

        $gpu->fill($validatedData);

        $gpu->save();

        return response()->json([
            'message' => "Gpu criada com sucesso!"
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Gpu  $gpu
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $gpu = Gpu::findOrFail($request->id);

        return response()->json([
            'gpu' => $gpu
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\GpuRequest  $request
     * @param  \App\Models\Gpu  $gpu
     * @return \Illuminate\Http\Response
     */
    public function update(GpuRequest $request)
    {
        $gpu = Gpu::findOrFail($request->id);
        
        $validatedData = $request->validated();

        $gpu->fill($validatedData);

        $changedComputerId = $gpu->isDirty('computer_id');
        $changedFunctionalFieldToFalse = $gpu->isDirty('functional') && $gpu['functional'] == false;

        if ($gpu->isDirty()) {
            if (($changedComputerId || $changedFunctionalFieldToFalse) && !is_null($gpu->getOriginal('computer_id'))) {
                $computer = Computer::findOrFail($gpu->getOriginal('computer_id'));

                //check if exists other functional Gpu
                $existsOtherFunctional = count($computer->ramMemories->where('functional', true)) >= 2;

                if ($computer['current_step'] > 2 && !$existsOtherFunctional) {
                    $computer['current_step'] = 2;
                    $computer->save();
                }
            }
            $gpu->save();
        }
        
        return response()->json([
            'message' => 'Gpu editada com sucesso!',
            'gpu' => $gpu
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Gpu  $gpu
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $gpu = Gpu::findOrFail($request->id);

        $gpu->delete();

        return response()->json([
            'message' => "Gpu deletada com sucesso!"
        ], 200);
    }
}