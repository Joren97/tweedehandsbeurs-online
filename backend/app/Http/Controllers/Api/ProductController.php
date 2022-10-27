<?php

namespace App\Http\Controllers\Api;

use App\Filters\ProductFilter;
use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Productlist;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \App\Http\Resources\ProductCollection
     */
    public function index(Request $request)
    {
        $filter = new ProductFilter();
        $filterItems = $filter->transform($request);

        $products = Product::where($filterItems);

        return new ProductCollection($products->paginate()->appends($request->query()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProductRequest  $request
     * @return \App\Http\Resources\ProductResource
     */
    public function store(StoreProductRequest $request)
    {
        return new ProductResource(Product::create($request->all()));
    }

    /**
     * Store a newly created resource in storage for the logged in user.
     *
     * @param  \App\Http\Requests\StoreProductRequest  $request
     * @return \App\Http\Resources\ProductResource
     */
    public function storeForLoggedInUser(StoreProductRequest $request)
    {
        // Find the productlist 
        $productlist = Productlist::findOrFail($request->productlistId);
        // If the productlist does not belong to the logged in user, return a 403
        if ($productlist->user_id !== auth()->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $product['description'] = $request->description;
        $product['price_id'] = $request->priceId;
        $product['productlist_id'] = $request->productlistId;

        return new ProductResource(Product::create($product));

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \App\Http\Resources\ProductResource
     */
    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProductRequest  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {

    }
}