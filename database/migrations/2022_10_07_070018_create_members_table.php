<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('first_name',30); // lowercase with _ all variables 
            $table->string('last_name',30);
            $table->string('email')->unique();
            $table->boolean('is_verfied')->default('0');
            $table->string('password');
            $table->string('reset_token')->nullable();
            $table->string('verification_token')->nullable();
            $table->dateTime('token_expiry')->nullable();
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
        Schema::dropIfExists('members');
    }
};
