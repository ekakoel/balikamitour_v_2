<?php

namespace App\Http\Controllers;

use App\Models\ToursImages;
use App\Http\Requests\StoreToursImagesRequest;
use App\Http\Requests\UpdateToursImagesRequest;

class ToursImagesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','verified']);
    }
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
     * @param  \App\Http\Requests\StoreToursImagesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreToursImagesRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ToursImages  $toursImages
     * @return \Illuminate\Http\Response
     */
    public function show(ToursImages $toursImages)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ToursImages  $toursImages
     * @return \Illuminate\Http\Response
     */
    public function edit(ToursImages $toursImages)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateToursImagesRequest  $request
     * @param  \App\Models\ToursImages  $toursImages
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateToursImagesRequest $request, ToursImages $toursImages)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ToursImages  $toursImages
     * @return \Illuminate\Http\Response
     */
    public function destroy(ToursImages $toursImages)
    {
        //
    }
}
