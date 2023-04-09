<?php

namespace App\Http\Controllers;

use App\Http\Requests\MonitorRequest;
use Illuminate\Http\Request;
use App\Models\Monitor;
use App\Models\Computer;

class MonitorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $recordsPerPage = 10;

        $query = Monitor::query();

        $filters = ['computer_id', 'model', 'manufacturer', 'functional', 'connections', 'size', 'panel'];

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
     * @param  \App\Http\Requests\MonitorRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MonitorRequest $request)
    {
        $validatedData = $request->validated();

        $monitor = new Monitor;

        $monitor->fill($validatedData);

        $monitor->save();

        return response()->json([
            'message' => "Monitor criado com sucesso!"
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Monitor  $monitor
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $monitor = Monitor::findOrFail($request->id);

        return response()->json([
            'monitor' => $monitor
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\MonitorRequest  $request
     * @param  \App\Models\Monitor  $monitor
     * @return \Illuminate\Http\Response
     */
    public function update(MonitorRequest $request)
    {
        $monitor = Monitor::findOrFail($request->id);
        
        $validatedData = $request->validated();

        $monitor->fill($validatedData);

        $changedComputerId = $monitor->isDirty('computer_id');
        $changedFunctionalFieldToFalse = $monitor->isDirty('functional') && $monitor['functional'] == false;

        if ($monitor->isDirty()) {
            if (($changedComputerId || $changedFunctionalFieldToFalse) && !is_null($monitor->getOriginal('computer_id'))) {
                $computer = Computer::findOrFail($monitor->getOriginal('computer_id'));

                //check if exists other functional Monitor
                $existsOtherFunctional = count($computer->ramMemories->where('functional', true)) >= 2;

                if ($computer['current_step'] > 2 && !$existsOtherFunctional) {
                    $computer['current_step'] = 2;
                    $computer->save();
                }
            }
            $monitor->save();
        }
        
        return response()->json([
            'message' => 'Monitor editado com sucesso!',
            'monitor' => $monitor
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Monitor  $monitor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $monitor = Monitor::findOrFail($request->id);

        $monitor->delete();

        return response()->json([
            'message' => "Monitor deletado com sucesso!"
        ], 200);
    }
}