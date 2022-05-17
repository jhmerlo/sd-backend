<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDistributionCenterRequest;
use App\Http\Requests\UpdateDistributionCenterRequest;
use App\Models\DistributionCenter;

class DistributionCenterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreDistributionCenterRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDistributionCenterRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DistributionCenter  $distributionCenter
     * @return \Illuminate\Http\Response
     */
    public function show(DistributionCenter $distributionCenter)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DistributionCenter  $distributionCenter
     * @return \Illuminate\Http\Response
     */
    public function edit(DistributionCenter $distributionCenter)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateDistributionCenterRequest  $request
     * @param  \App\Models\DistributionCenter  $distributionCenter
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDistributionCenterRequest $request, DistributionCenter $distributionCenter)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DistributionCenter  $distributionCenter
     * @return \Illuminate\Http\Response
     */
    public function destroy(DistributionCenter $distributionCenter)
    {
        //
    }
}
