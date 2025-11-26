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
            $table->string('local_no', 191)->nullable()->after('is_team_f');
            $table->text('doc_submitted')->nullable()->after('is_team_f');
            $table->text('doc_submitted_to_bns')->nullable()->after('is_team_f');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn(['local_no', 'doc_submitted', 'doc_submitted_to_bns']);
        });
    }
};
