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
        Schema::create('_comments', function (Blueprint $table) {
            $table->id();
            $table->text('message');
            $table->integer('rate')->nullable();
            $table->unsignedInteger('user_id');
            $table->unsignedBigInteger('driver_id');
            $table->foreign('user_id')
            ->references('id')
            ->on('users')
            ->onDelete('cascade');
        $table->foreign('driver_id')
            ->references('id')
            ->on('_drivers')
            ->onDelete('cascade');
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
        Schema::dropIfExists('_comments');
    }
};
