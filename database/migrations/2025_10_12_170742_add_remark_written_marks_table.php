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
        Schema::table('written_marks', function (Blueprint $table) {
            $table->string('remark')->nullable()->after('serial_no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('written_marks', function (Blueprint $table) {
            $table->dropColumn('remark');
        });
    }
};
