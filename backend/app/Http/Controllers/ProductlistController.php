<?php

namespace App\Http\Controllers;

use App\Models\Productlist;
use App\Http\Requests\StoreProductlistRequest;
use App\Http\Requests\UpdateProductlistRequest;

class ProductlistController extends Controller
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
     * @param  \App\Http\Requests\StoreProductlistRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductlistRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Productlist  $productlist
     * @return \Illuminate\Http\Response
     */
    public function show(Productlist $productlist)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Productlist  $productlist
     * @return \Illuminate\Http\Response
     */
    public function edit(Productlist $productlist)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProductlistRequest  $request
     * @param  \App\Models\Productlist  $productlist
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductlistRequest $request, Productlist $productlist)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Productlist  $productlist
     * @return \Illuminate\Http\Response
     */
    public function destroy(Productlist $productlist)
    {
        //
    }
}
