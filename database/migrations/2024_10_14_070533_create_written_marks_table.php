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
        Schema::create('written_marks', function (Blueprint $table) {
            $table->id();
            $table->string('serial_no', 64);
            $table->float('bangla');
            $table->float('english');
            $table->float('math');
            $table->float('science');
            $table->float('general_knowledge');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('written_marks');
    }
};
