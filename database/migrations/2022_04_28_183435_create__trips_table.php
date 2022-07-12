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
        Schema::create('_trips', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id');
            $table->unsignedBigInteger('driver_id');
            $table->foreign('user_id') ->references('id') ->on('users') ->onDelete('cascade');
            $table->foreign('driver_id')  ->references('id')  ->on('_drivers')  ->onDelete('cascade');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('first_location');
            $table->string('end_location');
            $table->string('note');
            $table->integer('status')->default(0);
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
        Schema::dropIfExists('_trips');
    }
};
