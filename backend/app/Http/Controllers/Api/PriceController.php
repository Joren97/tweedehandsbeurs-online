<?php

namespace App\Http\Controllers\Api;

use App\Filters\PriceFilter;
use App\Models\Price;
use App\Http\Requests\StorePriceRequest;
use App\Http\Requests\UpdatePriceRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\PriceCollection;
use App\Http\Resources\PriceResource;
use Illuminate\Http\Request;

class PriceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \App\Http\Resources\PriceCollection
     */
    public function index(Request $request)
    {
        $filter = new PriceFilter();
        $filterItems = $filter->transform($request);

        $prices = Price::where($filterItems);

        return new PriceCollection($prices->paginate()->appends($request->query()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePriceRequest  $request
     * @return \App\Http\Resources\PriceResource
     */
    public function store(StorePriceRequest $request)
    {
        return new PriceResource(Price::create($request->all()));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Price  $price
     * @return \App\Http\Resources\PriceResource
     */
    public function show(Price $price)
    {
        return new PriceResource($price);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePriceRequest  $request
     * @param  \App\Models\Price  $price
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePriceRequest $request, Price $price)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Price  $price
     * @return \Illuminate\Http\Response
     */
    public function destroy(Price $price)
    {

    }
}