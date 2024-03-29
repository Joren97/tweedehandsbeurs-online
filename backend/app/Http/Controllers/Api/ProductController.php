<?php

namespace App\Http\Controllers\Api;

use App\Filters\ProductFilter;
use App\Models\Edition;
use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Productlist;
use Illuminate\Http\Request;

class ProductController extends ApiController
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

        if ($request->query('editionId')) {
            if ($request->query('editionId') == -1) {
                // Get all products that are linked to the active edition
                $products = $products->whereRelation('productList', 'edition_id', '=', Edition::where('is_active', true)->first()->id);
            } else {
                // Get all products that are linked to the given edition
                $products = $products->whereRelation('productList', 'edition_id', '=', $request->query('editionId'));
            }
        }

        if ($request->query('listNumber')) {
            $listNumber = $request->query('listNumber');
            $productList = Productlist::where('list_number', $listNumber)->first();
            if ($productList == null) {
                return $this->errorResponse("Product list with number " . $request->query('listNumber')['eq'] . " was not found", 200);
            }
            $products = $products->whereRelation('productList', 'id', '=', $productList->id);
        }

        if ($request->query('includeProductlist')) {
            $products = $products->with('productList');
        }

        return new ProductCollection($products->paginate($filter->perPage($request))->appends($request->query()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProductRequest  $request
     * @return \App\Http\Resources\ProductResource
     */
    public function store(StoreProductRequest $request)
    {
        // Find the productlist 
        $productlist = Productlist::findOrFail($request->productlistId);

        // If the productlist is is confirmed by the user, return a 403
        if ($productlist->is_user_confirmed) {
            return $this->errorResponse('Deze lijst is reeds bevestigd.', 400);
        }

        // If the productlist contains 20 products, return a 403
        if ($productlist->products()->count() >= 20) {
            return $this->errorResponse('Deze lijst bevat reeds 20 producten.', 400);
        }

        if ($productlist->products()->count() === 0) {
            $product['product_number'] = 1;
        } else {
            $usedNumbers = $productlist->products()->pluck('product_number')->toArray();
            $allNumbers = range(1, 20);
            $remainingNumbers = array_diff($allNumbers, $usedNumbers);
            // Get the first number of the remaining numbers
            $product['product_number'] = reset($remainingNumbers);
        }

        $product['description'] = $request->description;
        $product['price_id'] = $request->priceId;
        $product['productlist_id'] = $request->productlistId;

        return new ProductResource(Product::create($product));
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

        // If the productlist is is confirmed by the user, return a 403
        if ($productlist->is_user_confirmed) {
            return $this->errorResponse('Deze lijst is reeds bevestigd.', 403);
        }

        // If the productlist contains 20 products, return a 403
        if ($productlist->products()->count() >= 20) {
            return $this->errorResponse('Deze lijst bevat reeds 20 producten.', 403);
        }

        if ($productlist->products()->count() === 0) {
            $product['product_number'] = 1;
        } else {
            $usedNumbers = $productlist->products()->pluck('product_number')->toArray();
            $allNumbers = range(1, 20);
            $remainingNumbers = array_diff($allNumbers, $usedNumbers);
            // Get the first number of the remaining numbers
            $product['product_number'] = reset($remainingNumbers);
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
     * Update the specified resource in storage for the logged in user.
     *
     * @param  \App\Http\Requests\UpdateProductRequest  $request
     * @param  integer  $id
     * @return \Illuminate\Http\Response
     */
    public function updateForLoggedInUser($id)
    {
        $product = Product::with('productList')->findOrFail($id);

        // If the productlist does not belong to the logged in user, return a 403
        if ($product->productlist->user_id !== auth()->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        // If the productlist is is confirmed by the user, return a 403
        if ($product->productlist->is_user_confirmed) {
            return $this->errorResponse('Deze lijst is reeds bevestigd.', 403);
        }

        $product['price_id'] = request()->priceId;
        $product['description'] = request()->description;

        $product->save();

        return response()->json(['message' => 'Product updated successfully.']);
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
        $input = $request->all();

        $product->price_id = $input['priceId'];
        $product->productlist_id = $input['productlistId'];
        $product->description = $input['description'];
        $product->is_sold = $input['isSold'];
        $product->product_number = $input['productNumber'];
        $product->save();

        return $this->successResponse(new ProductResource($product), "Product updated successfully", 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product = Product::findOrFail($product->id);

        // If no product is found, return a 400
        if (!$product) {
            return $this->errorResponse('Product werd niet gevonden.', 400);
        }

        $product->delete();

        return $this->successResponse(null, 'Product verwijderd.', 200);
    }

    /**
     * Remove the specified resource from storage for the logged in user.
     *
     * @param  integer  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyForLoggedInUser($id)
    {
        $product = Product::with('productList')->findOrFail($id);

        // If the productlist does not belong to the logged in user, return a 403
        if ($product->productList->user_id !== auth()->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        // If the productlist is is confirmed by the user, return a 403
        if ($product->productList->is_user_confirmed) {
            return $this->errorResponse('Deze lijst is reeds bevestigd.', 403);
        }

        $product->delete();

        return response()->json(['message' => 'Product verwijderd.'], 200);
    }
}