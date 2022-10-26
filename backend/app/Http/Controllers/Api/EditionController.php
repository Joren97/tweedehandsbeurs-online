<?php

namespace App\Http\Controllers\Api;

use App\Filters\EditionFilter;
use App\Http\Resources\EditionResource;
use App\Models\Edition;
use App\Http\Requests\StoreEditionRequest;
use App\Http\Requests\UpdateEditionRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\EditionCollection;
use Illuminate\Http\Request;

class EditionController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \App\Http\Resources\EditionCollection
     */
    public function index(Request $request)
    {
        $filter = new EditionFilter();
        $filterItems = $filter->transform($request);

        $editions = Edition::where($filterItems);

        return new EditionCollection($editions->paginate()->appends($request->query()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreEditionRequest  $request
     * @return \App\Http\Resources\EditionResource
     */
    public function store(StoreEditionRequest $request)
    {
        return new EditionResource(Edition::create($request->all()));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Edition  $edition
     * @return \App\Http\Resources\EditionResource
     */
    public function show(Edition $edition)
    {
        return new EditionResource(Edition::create($edition));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateEditionRequest  $request
     * @param  \App\Models\Edition  $edition
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateEditionRequest $request, Edition $edition)
    {
        $input = $request->all();

        $edition->year = $input['year'];
        $edition->name = $input['name'];
        $edition->is_active = $input['isActive'];
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
        $edition->delete();
        return $this->successResponse($edition, "Edition deleted successfully", 200);
    }
}