<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productlists', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Edition::class , 'edition_id');
            $table->foreignIdFor(\App\Models\User::class , 'user_id');
            $table->string("list_number");
            $table->string("member_number")->nullable()->default(null);
            $table->boolean("is_user_confirmed")->default(false);
            $table->boolean("is_employee_validated")->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('productlists');
    }
};