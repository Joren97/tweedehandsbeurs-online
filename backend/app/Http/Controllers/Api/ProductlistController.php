<?php

namespace App\Http\Controllers\Api;

use App\Filters\ProductListFilter;
use App\Models\Productlist;
use App\Http\Requests\StoreProductlistRequest;
use App\Http\Requests\UpdateProductlistRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductListCollection;
use App\Http\Resources\ProductListResource;
use App\Models\Edition;
use Illuminate\Http\Request;

class ProductlistController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \App\Http\Resources\ProductListCollection
     */
    public function index(Request $request)
    {
        $filter = new ProductListFilter();
        $filterItems = $filter->transform($request);

        $includeProducts = $request->query('includeProducts');

        $productLists = ProductList::where($filterItems);

        if ($includeProducts) {
            $productLists = $productLists->with('products');
        }

        return new ProductListCollection($productLists->paginate()->appends($request->query()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProductListRequest  $request
     * @return \App\Http\Resources\ProductListResource
     */
    public function store(StoreProductListRequest $request)
    {
        return new ProductListResource(ProductList::create($request->all()));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProductList  $productList
     * @return \App\Http\Resources\ProductListResource
     */
    public function show(ProductList $productList)
    {
        return new ProductListResource($productList);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProductListRequest  $request
     * @param  \App\Models\ProductList  $productList
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductListRequest $request, ProductList $productList)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductList  $productList
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductList $productList)
    {

    }
}