<?php

namespace App\Http\Controllers;

use App\Http\Requests\BorrowerRequest;
use App\Models\Borrower;
use Illuminate\Http\Request;

class BorrowerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $recordsPerPage = 10;

        $query = Borrower::query();

        $filters = ['institutional_id', 'email', 'name'];

        foreach ($filters as $filter) {
            if ($request->filled($filter)) {
                $query->where($filter, $request[$filter]);
            }
        }

        return $query->simplePaginate($recordsPerPage);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(BorrowerRequest $request)
    {
        $validatedData = $request->validated();

        $borrower = new Borrower;

        $borrower->fill($validatedData);

        $borrower->save();

        return response()->json([
            'message' => "Tomador de empréstimo criado com sucesso!"
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Borrower  $borrower
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $borrower = Borrower::findOrFail($request->id);

        return response()->json([
            'borrower' => $borrower
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBorrowerRequest  $request
     * @param  \App\Models\Borrower  $borrower
     * @return \Illuminate\Http\Response
     */
    public function update(BorrowerRequest $request)
    {
        $borrower = Borrower::findOrFail($request->id);
        $validatedData = $request->validated();

        $borrower->fill($validatedData);

        $borrower->save();

        return response()->json([
            'message' => 'Tomador de empréstimo editado com sucesso.',
            'borrower' => $borrower
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Borrower  $borrower
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $borrower = Borrower::findOrFail($request->id);
        $borrower->delete();

        return response()->json([
            'message' => 'Tomador de empréstimo deletado com sucesso.'
        ]);

    }
}
