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
        Schema::create('_profiles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('driver_id');
            $table->string('name');
            $table->string('email');
            $table->string('gender');
            $table->string('typeofcar');
            $table->integer('number');
            $table->string('image');
            $table->foreign('driver_id')
           ->references('id')
            ->on('_drivers')
            ->onDelete('cascade');


            $table->timestamps();
        });
    }

    /**h
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('_profiles');
    }
};
