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
        Schema::create('monitors', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('manufacturer');
            $table->string('model');
            $table->boolean('functional');
            $table->float('size');
            $table->string('panel');
            $table->string('connections');
            $table->unsignedInteger('computer_id')->nullable();
            $table->foreign('computer_id')->references('id')->on('computers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('monitors');
    }
};
