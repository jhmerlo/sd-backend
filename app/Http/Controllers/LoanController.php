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
            $isComputer = $validatedData['loanable_type'] == 'App\Models\Computer';
            if  (is_null($loanable->loan()) && $loanable['functional'] && ($isComputer && $loanable['current_step'] == 6 || !$isComputer)) {
                
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
    public function show(Loan $loan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function edit(Loan $loan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateLoanRequest  $request
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLoanRequest $request, Loan $loan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Loan $loan)
    {
        //
    }
}
