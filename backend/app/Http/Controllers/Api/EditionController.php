<?php

namespace App\Http\Controllers\Api;

use App\Models\Edition;
use App\Http\Requests\StoreEditionRequest;
use App\Http\Requests\UpdateEditionRequest;
use App\Http\Controllers\Controller;

class EditionController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $edition = Edition::all();
        return $this->successResponse($edition);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreEditionRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreEditionRequest $request)
    {
        $input = $request->all();
        $edition = Edition::create($input);
        return $this->successResponse($edition, "Edition created successfully", 201);
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
        $input = $request->all();

        $edition->year = $input['year'];
        $edition->name = $input['name'];
        $edition->is_active = $input['is_active'];
        $edition->save();

        return $this->successResponse($edition, "Edition updated successfully", 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Edition  $edition
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Edition $edition)
    {
        if (auth()->user()->role == "admin") {
            $edition->delete();
            return $this->successResponse($edition, "Edition deleted successfully", 200);
        }
        else {
            return $this->unauthorizedResponse();
        }
    }
}