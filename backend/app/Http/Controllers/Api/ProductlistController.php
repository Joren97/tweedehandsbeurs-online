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

        $productLists = ProductList::where($filterItems);

        $includeProducts = $request->query('includeProducts');

        if ($includeProducts) {
            $productLists = $productLists->with('products');
        }

        $includeUser = $request->query('includeUser');

        if ($includeUser) {
            $productLists = $productLists->with('user');
        }

        $search = $request->query('search');

        if ($search) {
            $productLists = $productLists->orWhere('list_number', 'like', '%' . $search . '%')->orWhereHas('user', function ($query) use ($search) {
                $query->where('firstname', 'like', '%' . $search . '%')
                    ->orWhere('lastname', 'like', '%' . $search . '%');
            });
        }

        return new ProductListCollection($productLists->paginate()->appends($request->query()));
    }

    /**
     * Display a listing of resources associated with the user.
     *
     * @return \App\Http\Resources\ProductListCollection
     */
    public function getListsForLoggedInUser(Request $request)
    {
        $filter = new ProductListFilter();
        $filterItems = $filter->transform($request);

        // Get productlists associated with the user
        $productLists = ProductList::where('user_id', auth()->user()->id)->where($filterItems);

        $includeProducts = $request->query('includeProducts');

        if ($includeProducts) {
            $productLists = $productLists->with('products');
        }

        $includeEdition = $request->query('includeEdition');

        if ($includeEdition) {
            $productLists = $productLists->with('edition');
        }

        $includeUser = $request->query('includeUser');

        if ($includeUser) {
            $productLists = $productLists->with('user');
        }

        return new ProductListCollection($productLists->paginate()->appends($request->query()));
    }

    /**
     * Store a newly created resource in storage for the logged in user.
     *
     * @param  \App\Http\Requests\StoreProductListRequest  $request
     * @return \App\Http\Resources\ProductListResource
     */
    public function storeForLoggedInUser(StoreProductListRequest $request)
    {
        // Set edition id equal to active edition
        $productlist['edition_id'] = Edition::where('is_active', true)->first()->id;
        // Set user id equal to logged in user
        $productlist['user_id'] = auth()->user()->id;
        $productlist['list_number'] = $request->listNumber;
        if ($request->has('memberNumber')) {
            $productlist['member_number'] = $request->memberNumber;
        }

        // Check if list number already exists on current edition
        $existingProductList = ProductList::where('edition_id', $productlist['edition_id'])
            ->where('list_number', $productlist['list_number'])->first();

        if ($existingProductList) {
            $errors = ['listNumber' => ['Lijstnummer is reeds in gebruik.']];
            return $this->fieldErrorResponse($errors, 404);
        }

        // Check if list number already exists on current edition for current user
        $existingProductList = ProductList::where('edition_id', $productlist['edition_id'])
            ->where('user_id', $productlist['user_id'])
            ->where('list_number', $productlist['list_number'])->first();

        if ($existingProductList) {
            $errors = ['listNumber' => ['Lijstnummer is reeds in gebruik.']];
            return $this->fieldErrorResponse($errors, 404);
        }

        // Create the productlist
        return new ProductListResource(ProductList::create($productlist));
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
    public function show(int $id)
    {
        $productList = ProductList::findOrFail($id);

        $includeUser = request()->query('includeUser');

        if ($includeUser) {
            $productList = $productList->loadMissing('user');
        }

        $includeProducts = request()->query('includeProducts');

        if ($includeProducts) {
            $productList = $productList->loadMissing('products');
        }

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