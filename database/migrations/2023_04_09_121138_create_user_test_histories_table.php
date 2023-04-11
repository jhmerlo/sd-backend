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
        Schema::create('user_test_histories', function (Blueprint $table) {
            $table->id();
            $table->boolean('auto_boot')->nullable();
            $table->boolean('initialization')->nullable();
            $table->boolean('shortcuts')->nullable();
            $table->boolean('correct_date')->nullable();
            $table->string('gsuite_performance')->nullable();
            $table->string('wine_performance')->nullable();
            $table->string('youtube_performance')->nullable();
            $table->foreignId('responsible_id')->references('institutional_id')->on('users')->onUpdate('cascade');
            $table->foreignId('computer_id')->references('id')->on('computers');
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
        Schema::dropIfExists('user_test_histories');
    }
};
