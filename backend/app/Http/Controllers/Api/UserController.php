<?php

namespace App\Http\Controllers\Api;

use App\Filters\UserFilter;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\Edition;
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
        $editions = Edition::whereBetween('year', [$currentEdition->year - 5, $currentEdition->year])->get();

        if ($request->query('includeList')) {

            // Load all productlist for this user for the current edition and include the products and the prices for each product
            $user->load([
                'productlists' => function ($query) use ($currentEdition) {
                    $query->where('edition_id', $currentEdition->id);
                },
                'productlists.products',
                'productlists.products.price',
                'productlists.edition'
            ]);
        }

        if ($request->query('includeListHistory')) {
            // Get all the productlists for this user for the last 5 years
            $user->load([
                'listHistory' => function ($query) use ($editions) {
                    $query->whereIn('edition_id', $editions->pluck('id'));
                },
                'listHistory.products',
                'listHistory.products.price',
                'listHistory.edition'
            ]);
        }

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