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
        Schema::create('computers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->enum('type', ['desktop', 'notebook']);
            $table->string('description');
            $table->string('patrimony')->nullable();
            $table->string('manufacturer');
            $table->boolean('sanitized');
            $table->boolean('functional');
            $table->integer('currentStep');
            $table->string('operationalSystem')->nullable();
            $table->boolean('hdmiInput')->nullable();
            $table->boolean('vgaInput')->nullable();
            $table->boolean('dviInput')->nullable();
            $table->boolean('localNetworkAdapter')->nullable();
            $table->boolean('wirelessNetworkAdapter')->nullable();
            $table->boolean('audioInputAndOutput')->nullable();
            $table->boolean('cdRom')->nullable();
            $table->foreignId('currentStepResponsibleId')->references('institutionalId')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('computers');
    }
};
