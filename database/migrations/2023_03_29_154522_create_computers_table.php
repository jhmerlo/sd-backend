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
            $table->integer('current_step');
            $table->string('operational_system')->nullable();
            $table->boolean('hdmi_input')->nullable();
            $table->boolean('vga_input')->nullable();
            $table->boolean('dvi_input')->nullable();
            $table->boolean('local_network_adapter')->nullable();
            $table->boolean('wireless_network_adapter')->nullable();
            $table->boolean('audio_input_and_output')->nullable();
            $table->boolean('cd_rom')->nullable();
            $table->foreignId('current_step_responsible_id')->references('institutional_id')->on('users')->onUpdate('cascade');
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
