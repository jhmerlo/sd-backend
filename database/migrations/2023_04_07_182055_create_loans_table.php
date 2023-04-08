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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->dateTime('return_date')->nullable();
            $table->foreignId('responsible_id')->references('institutional_id')->on('users')->onUpdate('cascade');
            $table->foreignId('borrower_id')->references('institutional_id')->on('borrowers')->onUpdate('cascade');
            $table->morphs('loanable');
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
        Schema::dropIfExists('loans');
    }
};
