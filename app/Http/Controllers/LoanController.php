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

        $filters = ['loanable_type', 'loanable_id', 'responsible_id', 'borrower_id', 'id'];

        foreach ($filters as $filter) {
            if ($request->filled($filter)) {
                $query->where($filter, $request[$filter]);
            }
        }
        return $query->orderBy('updated_at', 'desc')->with([
            'loanable', 
            'responsible', 
            'borrower',
            'comments',
            'comments.user'
        ])->paginate($recordsPerPage);
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

        $validDates = strtotime($validatedData['end_date']) >= strtotime($validatedData['start_date']);

        // checks if the class of loanable_type exists and the dates are valid
        if (class_exists($validatedData['loanable_type']) && $validDates) {
            $loanable = $validatedData['loanable_type']::findOrFail($validatedData['loanable_id']);

            // checks if the item is functional, is not currently in a loan and if computer, in step 6
            $isComputer = $validatedData['loanable_type'] == 'App\\Models\\Computer';
            if  (!$loanable->borrowed && $loanable['functional'] && ($isComputer && $loanable['current_step'] == 6 || !$isComputer)) {
                
                $loan = new Loan;
                $loan->fill($validatedData);
                $loan->save();

                return response()->json([
                    'message' => 'Empréstimo criado com sucesso.'
                ], 200);
            }
        }

        return response()->json([
            'message' => "Este item não cumpre os pré-requisitos para ser emprestado."
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

        
        if (!class_exists($loan['loanable_type'])) {
            return response()->json([
                'message' => 'Classe não encontrada.'
            ], 404);
        }
        
        if ($loan['return_date'] != null) {
            return response()->json([
                'message' => 'Este empréstimo já foi encerrado.'
            ], 400);
        }
        
        $validDates = strtotime($validatedData['return_date']) >= strtotime($validatedData['start_date']);

        if ($validDates) {
            $loan->return_date = $validatedData['return_date'];
            $loan->save();

            return response()->json([
                'message' => 'Empréstimo editado com sucesso.'
            ], 200);
        } else {
            return response()->json([
                'message' => 'A data de devolução não é válida.'
            ], 400);
        }
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
