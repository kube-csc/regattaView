<?php

namespace App\Http\Controllers;

use App\Models\RegattaInformation;
use App\Http\Requests\StoreRegattaInformationRequest;
use App\Http\Requests\UpdateRegattaInformationRequest;

class RegattaInformationController extends Controller
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
     * @param  \App\Http\Requests\StoreRegattaInformationRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRegattaInformationRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RegattaInformation  $regattaInformation
     * @return \Illuminate\Http\Response
     */
    public function show(RegattaInformation $regattaInformation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RegattaInformation  $regattaInformation
     * @return \Illuminate\Http\Response
     */
    public function edit(RegattaInformation $regattaInformation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateRegattaInformationRequest  $request
     * @param  \App\Models\RegattaInformation  $regattaInformation
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRegattaInformationRequest $request, RegattaInformation $regattaInformation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RegattaInformation  $regattaInformation
     * @return \Illuminate\Http\Response
     */
    public function destroy(RegattaInformation $regattaInformation)
    {
        //
    }
}
