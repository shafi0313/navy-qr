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
        Schema::create('application_urls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->nullable()->constrained()->onDelete('set null');
            // $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('url')->index();
            $table->boolean('is_medical_pass')->index()->nullable();
            $table->boolean('is_written_pass')->index()->nullable();
            $table->boolean('is_final_pass')->index()->nullable();
            $table->boolean('is_viva_pass')->index()->nullable();
            $table->boolean('is_info_taken')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_urls');
    }
};
