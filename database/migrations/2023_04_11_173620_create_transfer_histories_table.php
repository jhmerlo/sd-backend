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
        Schema::create('transfer_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('source_id')->nullable();
            $table->unsignedInteger('target_id')->nullable();
            $table->foreign('source_id')->references('id')->on('computers');
            $table->foreign('target_id')->references('id')->on('computers');
            $table->foreignId('responsible_id')->references('institutional_id')->on('users')->onUpdate('cascade');
            $table->morphs('transferable');
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
        Schema::dropIfExists('transfer_histories');
    }
};
