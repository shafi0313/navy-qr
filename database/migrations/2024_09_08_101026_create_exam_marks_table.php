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
        Schema::create('exam_marks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('applications')->cascadeOnDelete();
            $table->unsignedTinyInteger('bangla')->default(0);
            $table->unsignedTinyInteger('english')->default(0);
            $table->unsignedTinyInteger('math')->default(0);
            $table->unsignedTinyInteger('science')->default(0);
            $table->unsignedTinyInteger('general_knowledge')->default(0);
            $table->unsignedTinyInteger('viva')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_marks');
    }
};
