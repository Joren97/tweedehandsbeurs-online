<?php

namespace App\Http\Controllers\Api;

use App\Filters\DashboardFilter;
use App\Http\Resources\DashboardResource;
use App\Models\Dashboard;
use App\Http\Requests\StoreDashboardRequest;
use App\Http\Requests\UpdateDashboardRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\DashboardCollection;
use App\Models\Edition;
use Illuminate\Http\Request;

class DashboardController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \App\Http\Resources\DashboardCollection
     */
    public function index(Request $request)
    {
        $totalProfit = 0;
        $totalLists = 0;
        $totalProducts = 0;
        $percentageSold = 0;
        $totalProfitPrevious = 0;
        $totalListsPrevious = 0;
        $totalProductsPrevious = 0;
        $percentageSoldPrevious = 0;

        // Get the active edition
        $edition = Edition::where('is_active', true)->first();

        // If no active edition is found, return an error
        if ($edition == null) {
            return $this->errorResponse("No active edition was found", 200);
        }

        // Get all the lists with products (with prices) for the active edition
        $productLists = $edition->productlists()->with('products.price')->get();

        // Get the total number of products
        $totalProducts = $productLists->sum(function ($list) {
            return $list->products->count();
        });

        // Get the total number of products sold
        $totalProductsSold = $productLists->sum(function ($list) {
            return $list->products->where('is_sold', true)->count();
        });

        // Get the sum of all 'selling_price' of all products sold
        $totalSellingPrice = $productLists->sum(function ($list) {
            return $list->products->where('is_sold', true)->sum('price.selling_price');
        });

        // Get the sum of all 'asking_price' of all products sold
        $totalAskingPrice = $productLists->sum(function ($list) {
            return $list->products->where('is_sold', true)->sum('price.asking_price');
        });

        // Get all the lists where the 'member_number' is not filled in
        $productListsNoMemberNumber = $productLists->where('member_number', null)->count();

        // Calculate the total profit
        $totalProfit = $totalSellingPrice - $totalAskingPrice + ($productListsNoMemberNumber * 5);

        // Get the percentage sold, rounded to 2 decimals
        $percentageSold = round($totalProductsSold / $totalProducts * 100, 2);

        // Get the previous edition
        $previousEdition = $this->getPreviousEdition($edition);

        // If a previous edition is found, get all the data for that edition
        if ($previousEdition != null) {
            // Get all the lists with products (with prices) for the previous edition
            $productListsPrevious = $previousEdition->productlists()->with('products.price')->get();

            // Get the total number of products
            $totalProductsPrevious = $productListsPrevious->sum(function ($list) {
                return $list->products->count();
            });

            // Get the total number of products sold
            $totalProductsSoldPrevious = $productListsPrevious->sum(function ($list) {
                return $list->products->where('is_sold', true)->count();
            });

            // Get the sum of all 'selling_price' of all products sold
            $totalSellingPricePrevious = $productListsPrevious->sum(function ($list) {
                return $list->products->where('is_sold', true)->sum('price.selling_price');
            });

            // Get the sum of all 'asking_price' of all products sold
            $totalAskingPricePrevious = $productListsPrevious->sum(function ($list) {
                return $list->products->where('is_sold', true)->sum('price.asking_price');
            });

            // Get all the lists where the 'member_number' is not filled in
            $productListsNoMemberNumberPrevious = $productListsPrevious->where('member_number', null)->count();

            // Calculate the total profit
            $totalProfitPrevious = $totalSellingPricePrevious - $totalAskingPricePrevious + ($productListsNoMemberNumberPrevious * 5);

            // Get the percentage sold, rounded to 2 decimals
            if ($totalProductsPrevious != 0)
                $percentageSoldPrevious = round($totalProductsSoldPrevious / $totalProductsPrevious * 100, 2);
        }

        $data = [
            'profit' => ['amount' => $totalProfit, 'previousDiffPercentage' => $totalProfitPrevious != 0 ? round(($totalProfit - $totalProfitPrevious) / $totalProfitPrevious * 100, 2) : 0],
            'lists' => ['amount' => $productLists->count(), 'previousDiffPercentage' => $productListsPrevious->count() != 0 ? round(($productLists->count() - $productListsPrevious->count()) / $productListsPrevious->count() * 100, 2) : 0],
            'products' => ['amount' => $totalProducts, 'previousDiffPercentage' => $totalProductsPrevious != 0 ? round(($totalProducts - $totalProductsPrevious) / $totalProductsPrevious * 100, 2) : 0],
            'percentageSold' => ['amount' => $percentageSold, 'previousDiffPercentage' => $percentageSoldPrevious != 0 ? round(($percentageSold - $percentageSoldPrevious) / $percentageSoldPrevious * 100, 2) : 0],
        ];

        return $this->successResponse($data, 200);
    }

    private function getPreviousEdition(Edition $currentEdition)
    {
        $monthsOrder = ['Januari', 'Februari', 'Maart', 'April', 'Mei', 'Juni', 'Juli', 'Augustus', 'September', 'Oktober', 'November', 'December'];

        $previousEdition = Edition::where('year', '<=', $currentEdition->year)->where('id', '!=', $currentEdition->id)->get();

        // If only one edition is found, return it
        if ($previousEdition->count() == 1) {
            return $previousEdition->first();
        }

        // If no edition is found, return null
        if ($previousEdition->count() == 0) {
            return null;
        }

        // If there is more than one edition, sort them by year descending and return the first one whichs month is lower than the current edition
        $previousEdition = $previousEdition->sortByDesc('year')->first(function ($edition) use ($monthsOrder, $currentEdition) {
            return array_search($edition->name, $monthsOrder) < array_search($currentEdition->name, $monthsOrder);
        });

        return $previousEdition;
    }
}