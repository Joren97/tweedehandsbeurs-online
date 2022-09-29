<?php

namespace App\Http\Controllers;

use App\Models\Edition;
use App\Http\Requests\StoreEditionRequest;
use App\Http\Requests\UpdateEditionRequest;

class EditionController extends Controller
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
     * @param  \App\Http\Requests\StoreEditionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEditionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Edition  $edition
     * @return \Illuminate\Http\Response
     */
    public function show(Edition $edition)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Edition  $edition
     * @return \Illuminate\Http\Response
     */
    public function edit(Edition $edition)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateEditionRequest  $request
     * @param  \App\Models\Edition  $edition
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEditionRequest $request, Edition $edition)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Edition  $edition
     * @return \Illuminate\Http\Response
     */
    public function destroy(Edition $edition)
    {
        //
    }
}
