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
        Schema::create('maintenance_histories', function (Blueprint $table) {
            $table->id();
            $table->string('software_installation')->nullable();
            $table->string('operational_system_installation')->nullable();
            $table->string('formatting')->nullable();
            $table->string('battery_change')->nullable();
            $table->string('suction')->nullable();
            $table->string('other')->nullable();
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
        Schema::dropIfExists('maintenance_histories');
    }
};
