<?php

namespace App\Http\Controllers\Api;

use App\Models\Productlist;
use App\Http\Requests\StoreProductlistRequest;
use App\Http\Requests\UpdateProductlistRequest;
use App\Http\Controllers\Controller;
use App\Models\Edition;

class ProductlistController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // If the user is an admin, return all productlists
        if ($this->role() == "admin") {
            $productlists = Productlist::all();
            return $this->successResponse($productlists);
        }

        // If the user is a customer, return only the productlists for the logged in user
        $productlists = Productlist::where('user_id', auth()->user()->id)->get();

        $productlist = Productlist::all();
        return $this->successResponse($productlist);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProductlistRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreProductlistRequest $request)
    {
        $input = $request->all();

        // Get the user ID from the request
        $user_id = auth()->user()->id;

        // Get the active edition
        $edition = Edition::where('is_active', 1)->first();

        $input['user_id'] = $user_id;
        $input['edition_id'] = $edition->id;

        $created = Productlist::create($input);
        return $this->successResponse($created, "Productlist created successfully", 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Productlist  $productlist
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Productlist $productlist)
    {
        // If the user is an admin or employee, return the productlist
        if ($this->role() == "admin" || $this->role() == "employee") {
            return $this->successResponse($productlist);
        }

        // If the user is a customer, return the productlist if it belongs to the logged in user
        if ($this->role() == "customer") {
            if ($productlist->user_id == auth()->user()->id) {
                return $this->successResponse($productlist);
            }
        }

        return $this->errorResponse("You are not authorized to view this productlist", 403);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProductlistRequest  $request
     * @param  \App\Models\Productlist  $productlist
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateProductlistRequest $request, Productlist $productlist)
    {
        // Get the role from the user
        $role = auth()->user()->role;

        // If the user is not an admin or employee, check if the user is the owner of the productlist
        if ($role != "admin" && $role != "employee") {
            if ($productlist->user_id != auth()->user()->id) {
                return $this->errorResponse("You are not authorized to update this productlist", 403);
            }
        }

        // If list is confirmed by user, only admin or employee can update
        if ($productlist->is_user_confirmed && $role != "admin" && $role != "employee") {
            return $this->errorResponse("You are not authorized to update this productlist", 403);
        }

        // If list is confirmed by user and validated by employee, only admin can update
        if ($productlist->is_user_confirmed && $productlist->is_employee_validated && $role != "admin") {
            return $this->errorResponse("You are not authorized to update this productlist", 403);
        }

        // Update the list
        $input = $request->all();
        $productlist->fill($input);
        $productlist->save();
        return $this->successResponse($productlist);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Productlist  $productlist
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Productlist $productlist)
    {
        // Get the role from the user
        $role = auth()->user()->role;

        // If the user is not an admin or employee, check if the user is the owner of the productlist
        if ($role != "admin" && $role != "employee") {
            if ($productlist->user_id != auth()->user()->id) {
                return $this->errorResponse("You are not authorized to delete this productlist", 403);
            }
        }

        // If list is confirmed by user, only admin or employee can delete
        if ($productlist->is_user_confirmed && $role != "admin" && $role != "employee") {
            return $this->errorResponse("You are not authorized to delete this productlist", 403);
        }

        // If list is confirmed by user and validated by employee, only admin can delete
        if ($productlist->is_user_confirmed && $productlist->is_employee_validated && $role != "admin") {
            return $this->errorResponse("You are not authorized to delete this productlist", 403);
        }

        // Delete the list
        $productlist->delete();
        return $this->successResponse($productlist);
    }
}
