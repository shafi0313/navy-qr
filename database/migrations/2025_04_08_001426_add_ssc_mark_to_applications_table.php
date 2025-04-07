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
        Schema::table('applications', function (Blueprint $table) {
            $table->string('ssc_bangla')->nullable()->after('ssc_gpa');
            $table->string('ssc_english')->nullable()->after('ssc_gpa');
            $table->string('ssc_math')->nullable()->after('ssc_gpa');
            $table->string('ssc_physics')->nullable()->after('ssc_gpa');
            $table->string('ssc_biology')->nullable()->after('ssc_gpa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn('ssc_bangla');
            $table->dropColumn('ssc_english');
            $table->dropColumn('ssc_math');
            $table->dropColumn('ssc_physics');
            $table->dropColumn('ssc_biology');
            //
        });
    }
};
