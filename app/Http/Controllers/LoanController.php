<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoanRequest;
use Illuminate\Http\Request;
use App\Models\Loan;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $recordsPerPage = 10;

        $query = Loan::query();

        $filters = ['computer_id', 'model', 'manufacturer', 'functional', 'integrated'];

        foreach ($filters as $filter) {
            if ($request->filled($filter)) {
                $query->where($filter, $request[$filter]);
            }
        }
        return $query->with(['loanable', 'responsible', 'borrower'])->simplePaginate($recordsPerPage);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\LoanRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LoanRequest $request)
    {
        $validatedData = $request->validated();

        $validDates = $validatedData['end_date'] > $validatedData['start_date'] && $validatedData['return_date'] > $validatedData['start_date'];
    
        // checks if the class of loanable_type exists and the dates are valid
        if (class_exists($validatedData['loanable_type']) && $validDates) {
            $loanable = $validatedData['loanable_type']::findOrFail($validatedData['loanable_id']);

            // checks if the item is functional, is not currently in a loan and if computer, in step 6
            $isComputer = $validatedData['loanable_type'] == 'App\\Models\\Computer';
            if  (is_null($loanable->loan) && $loanable['functional'] && ($isComputer && $loanable['current_step'] == 6 || !$isComputer)) {
                
                $loan = new Loan;
                $loan->fill($validatedData);
                $loan->save();

                return response()->json([
                    'message' => 'Empréstimo criado com sucesso.'
                ], 200);
            }
        }

        return response()->json([
            'message' => "Bad Request."
        ], 400);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $loan = Loan::with(['responsible', 'borrower'])->findOrFail($request->id);

        return response()->json([
            'loan' => $loan
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\LoanRequest  $request
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function update(LoanRequest $request)
    {
        $validatedData = $request->validated();
        $loan = Loan::findOrFail($request->id);

        $loan->fill($validatedData);

        $validDates = $loan['end_date'] > $loan['start_date'] && $loan['return_date'] > $loan['start_date'];

        if (!class_exists($loan['loanable_type'])) {
            return response()->json([
                'message' => 'Classe não encontrada.'
            ], 404);
        }

        $loanable = $loan['loanable_type']::findOrFail($loan['loanable_id']);

        // checks if the item is functional, is not currently in a loan and if computer, in step 6
        $isComputer = $loan['loanable_type'] == 'App\\Models\\Computer';
    
        // troca => inserir muitos empréstimos para um único computador. é considerado emprestado quando count($loanable->loans->where('return_date', 'null') == 0)
        if ($loan->isDirty() && $validDates && $loanable['functional'] && ($isComputer && $loanable['current_step'] == 6 || !$isComputer)){
            if ($loan->isDirty('loanable_id') || $loan->isDirty('loanable_type')) {
                if (is_null($loanable->loan)) {
                    $loan->save();
                    return response()->json([
                        'message' => 'Empréstimo editado com sucesso.'
                    ], 200);
                } else {
                    return response()->json([
                        'message' => 'Este item já está em um empréstimo.'
                    ], 400);
                }
            } else {
                $loan->save();
                return response()->json([
                    'message' => 'Empréstimo editado com sucesso.'
                ], 200);
            }
        }

        if (!$loan->isDirty()){
            return response()->json([
                'message' => 'Empréstimo editado com sucesso.'
            ], 200);
        }

        return response()->json([
            'message' => "Bad Request."
        ], 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $loan = Loan::findOrFail($request->id);

        $loan->delete();

        return response()->json([
            'message' => "Empréstimo deletado com sucesso!"
        ], 200);
    }
}
