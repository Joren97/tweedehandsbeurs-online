<?php

namespace App\Http\Controllers\Api;

use App\Filters\ProductListFilter;
use App\Http\Requests\PayProductlistRequest;
use App\Models\Productlist;
use App\Http\Requests\StoreProductlistRequest;
use App\Http\Requests\UpdateProductlistRequest;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MailController;
use App\Http\Controllers\PDFController;
use App\Http\Requests\ConfirmProductlistRequest;
use App\Http\Resources\ProductListCollection;
use App\Http\Resources\ProductListResource;
use App\Models\Edition;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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

        if ($request->query('isUserConfirmed')) {
            $productLists = $productLists->whereIn('is_user_confirmed', $request->query('isUserConfirmed'));
        }

        if ($request->query('isEmployeeValidated')) {
            $productLists = $productLists->whereIn('is_employee_validated', $request->query('isEmployeeValidated'));
        }

        if ($request->query('isPaidToUser')) {
            $productLists = $productLists->whereIn('is_paid_to_user', $request->query('isPaidToUser'));
        }

        if ($request->query('editionId')) {
            if ($request->query('editionId') == -1) {
                // If the editionId is -1, get all productlists that are linked to the active edition
                $productLists = $productLists->whereHas('edition', function ($query) {
                    $query->where('is_active', true);
                });
            } else {
                $productLists = $productLists->where('edition_id', $request->query('editionId'));
            }
        }

        if ($request->query('history')) {
            // Get only productlists that are linked to the editions where the year is between the year of the current edition and 4 years ago 
            // and include the edition and products with price ine the response
            // Excluse the current edition
            $productLists = $productLists->whereHas('edition', function ($query) {
                $query->where('year', '>=', Edition::where('is_active', true)->first()->year - 4)->where('id', '!=', Edition::where('is_active', true)->first()->id);
            })->with('edition')->with('products.price');
        }

        if ($request->query('current')) {
            // Get only productlists that are linked to the current edition and include the edition and products with price ine the response
            $productLists = $productLists->whereHas('edition', function ($query) {
                $query->where('is_active', true);
            })->with('edition')->with('products.price');
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
        // Get the active edition
        $edition = Edition::where('is_active', true)->first();

        // If no active edition exists, return error
        if (!$edition) {
            $errors = ['edition' => ['Geen actieve editie gevonden']];
            return $this->fieldErrorResponse($errors, 500);
        }

        // Set edition id equal to active edition
        $productlist['edition_id'] = $edition->id;
        // Set user id equal to logged in user
        $productlist['user_id'] = auth()->user()->id;
        // Set list number
        $productlist['list_number'] = $request->listNumber;
        // Set the member number if present
        if ($request->has('memberNumber')) {
            $productlist['member_number'] = $request->memberNumber;
        }

        // Check if list number already exists on current edition
        $existingProductList = ProductList::where('edition_id', $productlist['edition_id'])
            ->where('list_number', $productlist['list_number'])->first();

        if ($existingProductList) {
            $errors = ['listNumber' => ['Lijstnummer is reeds in gebruik.']];
            return $this->fieldErrorResponse($errors, 422);
        }

        // Get all lists with the same member number for the current edition, if the member number is present
        if ($productlist['member_number'] != null && $productlist['member_number'] != '') {
            $sameMemberNumberLists = ProductList::where('edition_id', $productlist['edition_id'])
                ->where('member_number', $productlist['member_number'])->get();

            // If there are 2 lists with the same member number, return error
            if ($sameMemberNumberLists->count() >= 2) {
                $errors = ['memberNumber' => ['Lidnummer is reeds gebruikt voor 2 lijsten. Gelieve een ander lidnummer in te vullen of dit veld leeg te laten.']];
                return $this->fieldErrorResponse($errors, 422);
            }
        }

        // All checks ok, create the productlist
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
     * Display the specified resource for the logged in user.
     *
     * @param  \App\Models\ProductList  $productList
     * @return \App\Http\Resources\ProductListResource
     */
    public function showForLoggedInUser(int $id)
    {
        // Get the productlist
        $productList = ProductList::where('id', $id)->first();

        // If productlist does not exist, return error
        if (!$productList) {
            return $this->errorResponse('Productlist not found.', 404);
        }

        // If Productlist does not belong to logged in user, return error
        if ($productList->user_id !== auth()->user()->id) {
            return $this->errorResponse('Productlist does not belong to logged in user.', 404);
        }

        $includeUser = request()->query('includeUser');

        if ($includeUser) {
            $productList = $productList->loadMissing('user');
        }

        $includeProducts = request()->query('includeProducts');

        if ($includeProducts) {
            $productList = $productList->loadMissing('products');
        }

        $includeEdition = request()->query('includeEdition');

        if ($includeEdition) {
            $productList = $productList->loadMissing('edition');
        }

        return new ProductListResource($productList);
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
     * Update the specified resource in storage for the logged in user.
     *
     * @param  \App\Http\Requests\UpdateProductListRequest  $request
     * @param  integer  $id
     * @return \Illuminate\Http\Response
     */
    public function confirmListForLoggedInUser($id)
    {
        $list = ProductList::findOrFail($id);

        // If productlist does not belong to logged in user, return error
        if ($list->user_id !== auth()->user()->id) {
            return $this->errorResponse('Productlist does not belong to logged in user.', 404);
        }

        // Get PDF of productlist
        $pdf = PDFController::generateProductlistPdf($id);

        // To email address
        $to = auth()->user()->email;

        if (env('APP_DEBUG')) {
            $to = env('MAIL_TO_ADDRESS');
        }

        // Send an email to the user
        $data["number"] = $list->list_number;

        MailController::sendMail(
            'emails.confirmList',
            $data,
            $to,
            'Lijst ' . $list->list_number . ' werd zojuist bevestigd',
            $pdf,
            'lijst-' . $list->list_number . '.pdf'
        );

        $list->is_user_confirmed = true;
        $list->save();

        return new ProductListResource($list);
    }

    /**
     * Update the specified resource in storage for the logged in user.
     *
     * @param  \App\Http\Requests\UpdateProductListRequest  $request
     * @param  integer  $id
     * @return \Illuminate\Http\Response
     */
    public function confirmList($id)
    {
        $list = ProductList::findOrFail($id);

        // Get PDF of productlist
        $pdf = PDFController::generateProductlistPdf($id);

        // Get the user of the productlist
        $user = User::findOrFail($list->user_id);

        // To email address
        $to = $user->email;

        if (env('APP_DEBUG')) {
            $to = env('MAIL_TO_ADDRESS');
        }

        // Send an email to the user
        $data["number"] = $list->list_number;

        MailController::sendMail(
            'emails.confirmList',
            $data,
            $to,
            'Lijst ' . $list->list_number . ' werd zojuist bevestigd',
            $pdf,
            'lijst-' . $list->list_number . '.pdf'
        );

        $list->is_user_confirmed = true;
        $list->save();

        return new ProductListResource($list);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProductListRequest  $request
     * @param  \App\Models\ProductList  $productList
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductListRequest $request, int $listId)
    {
        $list = ProductList::findOrFail($listId);
        $list->is_user_confirmed = $request->isUserConfirmed;
        $list->save();
        return new ProductListResource($list);
    }

    public function payProductlists(PayProductlistRequest $request)
    {
        // Get all ids from the $request
        $ids = $request->productlistIds;

        $lists = [];

        // Check every id if it exists, if so add it to the $lists
        foreach ($ids as $id) {
            $list = ProductList::where('id', $id)->first();
            if ($list == null) {
                return $this->errorResponse('Productlijst met id ' . $id . ' bestaat niet.', 404);
            }
            array_push($lists, $list);
        }

        // Set the is_paid_to_user to true for every list
        foreach ($lists as $list) {
            $list->is_paid_to_user = true;
            $list->save();
        }

        $message = count($ids) === 1 ? 'Productlijst is betaald.' : 'Productlijsten zijn betaald.';

        return $this->successResponse($message);
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

    /**
     * Remove the specified resource from storage for the logged in user.
     *
     * @param  integer  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyForLoggedInUser($id)
    {
        $list = Productlist::findOrFail($id);

        // If the productlist does not belong to the logged in user, return a 403
        if ($list->user_id !== auth()->user()->id) {
            return $this->errorResponse('Deze lijst behoort tot een andere gebruiker.', 403);
        }

        // If the productlist is is confirmed by the user, return a 403
        if ($list->is_user_confirmed) {
            return $this->errorResponse('Deze lijst is reeds bevestigd.', 403);
        }

        $list->delete();
        return $this->successResponse('Lijst verwijderd.', 200);
    }
}