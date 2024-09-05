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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_url_id')->nullable()->constrained()->onDelete('set null');
            $table->string('post')->nullable();
            $table->string('batch', 32)->index()->nullable();
            $table->string('roll', 32)->index()->nullable();
            $table->string('name')->index()->nullable();
            $table->string('present_address', 255)->nullable();
            $table->string('present_post', 12)->nullable();
            $table->string('mobile', 32)->index()->nullable();
            $table->string('permanent_address', 255)->nullable();
            $table->string('permanent_post', 12)->nullable();
            $table->string('parent_mobile', 32)->nullable();
            $table->date('dob')->nullable();
            $table->string('birth_place')->nullable();
            $table->string('nationality', 64)->nullable();
            $table->string('f_nationality', 64)->nullable();
            $table->string('m_nationality', 64)->nullable();
            $table->string('religion', 64)->nullable();
            $table->string('marital_status', 64)->nullable();
            $table->string('gender', 64)->index()->nullable();
            $table->string('height', 12)->nullable();
            $table->string('weight', 12)->nullable();
            $table->string('chest_normal', 12)->nullable();
            $table->string('chest_extended', 12)->nullable();
            $table->string('visible_marks', 255)->nullable();
            $table->string('father_name')->nullable();
            $table->string('father_education')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('mother_education')->nullable();
            $table->string('parents_address', 255)->nullable();
            $table->string('father_occupation')->nullable();
            $table->string('mother_occupation')->nullable();
            $table->string('income_source', 64)->nullable();
            $table->string('income', 12)->nullable();
            $table->string('g_name')->nullable();
            $table->string('g_relation', 64)->nullable();
            $table->string('g_mobile', 32)->nullable();
            $table->string('g_income_source', 64)->nullable();
            $table->string('g_income', 12)->nullable();
            $table->string('g_address', 255)->nullable();
            $table->string('psc', 64)->nullable();
            $table->string('psc_ins_name')->nullable();
            $table->string('psc_year', 8)->nullable();
            $table->string('psc_remark')->nullable();
            $table->string('jsc', 64)->nullable();
            $table->string('jsc_ins_name')->nullable();
            $table->string('jsc_year', 8)->nullable();
            $table->string('jsc_remark')->nullable();
            $table->string('ssc', 64)->nullable();
            $table->string('ssc_group')->nullable();
            $table->string('ssc_year', 8)->nullable();
            $table->string('ssc_gpa', 8)->nullable();
            $table->string('ssc_ins_name')->nullable();
            $table->string('ssc_roll', 32)->nullable();
            $table->string('ssc_reg_no', 32)->nullable();
            $table->string('ssc_board', 64)->nullable();
            $table->string('hsc', 64)->nullable();
            $table->string('hsc_group', 64)->nullable();
            $table->string('hsc_year', 8)->nullable();
            $table->string('hsc_gpa', 8)->nullable();
            $table->string('hsc_ins_name')->nullable();
            $table->string('hsc_roll', 64)->nullable();
            $table->string('hsc_reg_no', 64)->nullable();
            $table->string('hsc_board', 64)->nullable();
            $table->string('honors')->nullable();
            $table->string('honors_subject')->nullable();
            $table->string('honors_year', 32)->nullable();
            $table->string('honors_gpa', 32)->nullable();
            $table->string('honors_ins')->nullable();
            $table->string('masters', 64)->nullable();
            $table->string('masters_subject')->nullable();
            $table->string('masters_year', 32)->nullable();
            $table->string('masters_gpa', 32)->nullable();
            $table->string('masters_ins')->nullable();
            $table->string('phd', 64)->nullable();
            $table->string('phd_subject', 64)->nullable();
            $table->string('phd_year', 32)->nullable();
            $table->string('phd_gpa', 64)->nullable();
            $table->string('phd_ins')->nullable();
            $table->string('hobby')->nullable();
            $table->string('games')->nullable();
            $table->string('inter_board', 64)->nullable();
            $table->date('inter_date')->nullable();
            $table->string('inter_result', 64)->nullable();
            $table->string('inter2_board', 64)->nullable();
            $table->date('inter2_date')->nullable();
            $table->string('inter2_result', 64)->nullable();
            $table->string('criminal', 64)->nullable();
            $table->string('freedom_fighter', 64)->nullable();
            $table->string('photo', 64)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
