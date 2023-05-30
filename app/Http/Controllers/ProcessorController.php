<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProcessorRequest;
use Illuminate\Http\Request;
use App\Models\Processor;
use App\Models\Computer;
use App\Models\TransferHistory;
use Auth;

class ProcessorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $recordsPerPage = 10;

        $query = Processor::query();

        $exactFilters = ['computer_id', 'functional', 'id', 'borrowed'];
        $likeFilters = ['model', 'manufacturer'];

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
     * @param  \App\Http\Requests\ProcessorRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProcessorRequest $request)
    {
        $validatedData = $request->validated();

        $processor = new Processor;

        $processor->fill($validatedData);

        $processor->save();

        return response()->json([
            'message' => "Processador criado com sucesso!"
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Processor  $processor
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $processor = Processor::findOrFail($request->id);

        return response()->json([
            'processor' => $processor
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\ProcessorRequest  $request
     * @param  \App\Models\Processor  $processor
     * @return \Illuminate\Http\Response
     */
    public function update(ProcessorRequest $request)
    {
        $processor = Processor::findOrFail($request->id);
        
        $validatedData = $request->validated();

        $processor->fill($validatedData);

        $changedComputerId = $processor->isDirty('computer_id');
        $changedFunctionalFieldToFalse = $processor->isDirty('functional') && $processor['functional'] == false;

        dd($processor->isDirty());
        if ($processor->isDirty()) {
            if (($changedComputerId || $changedFunctionalFieldToFalse) && !is_null($processor->getOriginal('computer_id'))) {
                $computer = Computer::findOrFail($processor->getOriginal('computer_id'));
            
                if ($computer['current_step'] > 2) {
                    $computer['current_step'] = 2;
                    $computer->save();
                }
            }

            if ($changedComputerId) {
                $transfer_history = new TransferHistory;

                $transfer_history->fill([
                    'source_id' => $processor->getOriginal('computer_id'),
                    'target_id' => $processor['computer_id'],
                    'responsible_id' => Auth::user()->institutional_id,
                    'transferable_id' => $processor['id'],
                    'transferable_type' => 'App\\Models\\Processor'
                ]);
                
                $transfer_history->save();
            }

            $processor->save();
        }
        
        return response()->json([
            'message' => 'Processador editado com sucesso!',
            'processor' => $processor
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Processor  $processor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $processor = Processor::findOrFail($request->id);

        $processor->delete();

        return response()->json([
            'message' => "Processador deletado com sucesso!"
        ], 200);
    }
}
