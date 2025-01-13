<?php

namespace App\Http\Controllers;

use App\Models\TourPrices;
use App\Http\Requests\StoreTourPricesRequest;
use App\Http\Requests\UpdateTourPricesRequest;

class TourPricesController extends Controller
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
     * @param  \App\Http\Requests\StoreTourPricesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTourPricesRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TourPrices  $tourPrices
     * @return \Illuminate\Http\Response
     */
    public function show(TourPrices $tourPrices)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TourPrices  $tourPrices
     * @return \Illuminate\Http\Response
     */
    public function edit(TourPrices $tourPrices)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTourPricesRequest  $request
     * @param  \App\Models\TourPrices  $tourPrices
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTourPricesRequest $request, TourPrices $tourPrices)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TourPrices  $tourPrices
     * @return \Illuminate\Http\Response
     */
    public function destroy(TourPrices $tourPrices)
    {
        //
    }
}
