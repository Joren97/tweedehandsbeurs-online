<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Edition;
use App\Models\Product;
use App\Models\Productlist;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $editions = Edition::factory(4)->create();

        $users = User::factory(300)->create()->each(function ($user) use ($editions) {
            Productlist::factory(rand(1, 4))->create([
                'user_id' => $user->id,
                'edition_id' => $editions->random()->id,
            ])->each(
                    function ($list) {
                        Product::factory(rand(1, 20))->create([
                            'productlist_id' => $list->id,
                        ]);
                    }
                );
        });



        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}