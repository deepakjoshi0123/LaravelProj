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
        Schema::create('proj__mems', function (Blueprint $table) {
           
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('member_id');
            $table->foreign('project_id')->references('id')->on('projects');
            $table->foreign('member_id')->references('id')->on('members');
            $table->timestamps();
        });
    }
    //no need for _ in modals / class name in camel case

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proj__mems');
    }
};
