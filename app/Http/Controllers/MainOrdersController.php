<?php

namespace App\Http\Controllers;

use App\Models\MainOrders;
use App\Http\Requests\StoreMainOrdersRequest;
use App\Http\Requests\UpdateMainOrdersRequest;

class MainOrdersController extends Controller
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
     * @param  \App\Http\Requests\StoreMainOrdersRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMainOrdersRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MainOrders  $mainOrders
     * @return \Illuminate\Http\Response
     */
    public function show(MainOrders $mainOrders)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MainOrders  $mainOrders
     * @return \Illuminate\Http\Response
     */
    public function edit(MainOrders $mainOrders)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateMainOrdersRequest  $request
     * @param  \App\Models\MainOrders  $mainOrders
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMainOrdersRequest $request, MainOrders $mainOrders)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MainOrders  $mainOrders
     * @return \Illuminate\Http\Response
     */
    public function destroy(MainOrders $mainOrders)
    {
        //
    }
}
