<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\MyMail;
use App\Models\Edition;
use App\Models\Price;
use App\Models\Product;
use App\Models\Productlist;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SeedController extends ApiController
{
    public function seedPrices(Request $request)
    {
        $path = $request->file('csv')->getRealPath();
        $records = array_map('str_getcsv', file($path));

        if (!count($records) > 0) {
            return 'Error...';
        }

        // Remove the header column
        array_shift($records);

        $prices = [];

        // For each record
        foreach ($records as $record) {
            $price = Price::factory()->create([
                'asking_price' => $record[0],
                'selling_price' => $record[1],
            ]);

            // Add price to array
            array_push($prices, $price);
        }
    }

    public function seed(Request $request)
    {
        $path = $request->file('csv')->getRealPath();
        $records = array_map('str_getcsv', file($path));

        if (!count($records) > 0) {
            return 'Error...';
        }

        // Remove the header column
        array_shift($records);

        $prices = [];

        // For each record
        foreach ($records as $record) {
            $price = Price::factory()->create([
                'asking_price' => $record[0],
                'selling_price' => $record[1],
            ]);

            // Add price to array
            array_push($prices, $price);
        }

        // Create 20 editions with a random year and name
        $editions = Edition::factory()->count(20)->create();

        // Create 50 random users
        $users = User::factory()->count(50)->create();

        // For each user, create a productlist with a random edition and 20 products
        foreach ($users as $user) {
            $productlist = Productlist::factory()->create([
                'user_id' => $user->id,
                'edition_id' => $editions->random()->id
            ]);

            Product::factory()->count(20)->create([
                'productlist_id' => $productlist->id,
            ]);
        }
    }

    public function clear()
    {
        Productlist::truncate();
        Product::truncate();
        Price::truncate();
        Edition::truncate();
        User::where('id', '>', 4)->delete();
    }

    public function testMail()
    {
        $mailData = [
            'title' => 'Mail from ItSolutionStuff.com',
            'body' => 'This is for testing email using smtp.'
        ];

        Mail::to('synaevejoren@gmail.com')->send(new MyMail($mailData));

        return $this->successResponse("Email is sent.");
    }
}