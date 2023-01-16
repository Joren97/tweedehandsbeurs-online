<?php

namespace App\Http\Controllers\Api;

use App\Filters\UserFilter;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\Edition;
use App\Models\Productlist;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \App\Http\Resources\UserCollection
     */
    public function index(Request $request)
    {
        $filter = new UserFilter();
        $filterItems = $filter->transform($request);

        $users = User::where($filterItems);

        if ($request->has('search')) {
            $users = $users->orWhere('firstname', 'like', '%' . $request->search . '%')
                ->orWhere('lastname', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%')
                ->orWhere('member_number', 'like', '%' . $request->search . '%')
                ->orWhere('phone_number', 'like', '%' . $request->search . '%');
        }

        return new UserCollection($users->orderBy('lastname')->paginate()->appends($request->query()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Get the current edition
        $currentEdition = Edition::where('is_active', true)->first();
        // Get all the editions for the last 5 years
        $previousEditions = Edition::whereBetween('year', [$currentEdition->year - 5, $currentEdition->year - 1])->get();

        if ($request->query('includeCurrentEdition')) {
            // Set the current edition including all lists for the user, with products, with price
            $user->currentEdition = $currentEdition;
            $user->currentEdition->productlists = Productlist::where('edition_id', $currentEdition->id)
                ->where('user_id', $user->id)
                ->with('products', 'products.price')
                ->get();
        }

        // return $user;

        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}