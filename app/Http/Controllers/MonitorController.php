<?php

namespace App\Http\Controllers;

use App\Http\Requests\MonitorRequest;
use Illuminate\Http\Request;
use App\Models\Monitor;
use App\Models\Computer;
use App\Models\TransferHistory;
use Auth;

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

        $exactFilters = ['computer_id', 'functional', 'id', 'borrowed'];
        $likeFilters = ['model', 'manufacturer', 'connections', 'panel'];

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

        return $query->orderBy('updated_at', 'desc')->with([
            'transferHistories', 
            'transferHistories.responsible',
            'comments',
            'comments.user'
        ])->paginate($recordsPerPage);
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
                $existsOtherFunctional = count($computer->monitors->where('functional', true)) >= 2;

                if ($computer['current_step'] > 2 && !$existsOtherFunctional) {
                    $computer['current_step'] = 2;
                    $computer->save();
                }
            }

            if ($changedComputerId) {
                $transfer_history = new TransferHistory;

                $transfer_history->fill([
                    'source_id' => $monitor->getOriginal('computer_id'),
                    'target_id' => $monitor['computer_id'],
                    'responsible_id' => Auth::user()->institutional_id,
                    'transferable_id' => $monitor['id'],
                    'transferable_type' => 'App\\Models\\Monitor'
                ]);
                
                $transfer_history->save();
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
