<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('team_f_data', function (Blueprint $table) {
            $table->id();
            $table->string('serial_no')->nullable()->comment('Roll Number')->index();
            // $table->string('name')->nullable();
            // $table->string('district')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_f_data');
    }
};
