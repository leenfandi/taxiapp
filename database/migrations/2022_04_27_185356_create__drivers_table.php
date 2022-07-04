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
        Schema::create('_drivers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('admin_id');
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->string('gender');
            $table->string('typeofcar');
            $table->integer('number');
            $table->foreign('admin_id')
            ->references('id')
             ->on('admins')
             ->onDelete('cascade');

           $table->rememberToken();
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
        Schema::dropIfExists('_drivers');
    }
};


