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

            // Basic Candidate Info
            $table->string('candidate_designation', 64);
            $table->date('exam_date');
            $table->string('serial_no', 64);
            $table->string('eligible_district', 128)->nullable();
            $table->string('district', 128)->nullable();
            $table->string('name', 128);
            $table->string('father_name', 128)->nullable();
            $table->string('father_nid', 20)->nullable();
            $table->string('father_occupation', 64)->nullable();
            $table->string('father_income', 64)->nullable();
            $table->string('mother_name', 128)->nullable();
            $table->string('mother_occupation', 64)->nullable();

            // Address Information (Current & Permanent)
            $table->string('current_village', 128)->nullable();
            $table->string('current_word_no', 32)->nullable();
            $table->string('current_union', 128)->nullable();
            $table->string('current_post_office', 128)->nullable();
            $table->string('current_thana', 128)->nullable();
            $table->string('current_post_code', 10)->nullable();
            $table->string('current_district', 128)->nullable();
            $table->string('current_phone', 20)->nullable();

            $table->string('permanent_village', 128)->nullable();
            $table->string('permanent_union', 128)->nullable();
            $table->string('permanent_word_no', 32)->nullable();
            $table->string('permanent_post_office', 128)->nullable();
            $table->string('permanent_thana', 128)->nullable();
            $table->string('permanent_district', 128)->nullable();
            $table->string('permanent_post_code', 10)->nullable();
            $table->string('permanent_phone', 20)->nullable();

            // Guardian Info
            $table->string('guardian_name', 128)->nullable();
            $table->string('guardian_relation', 64)->nullable();
            $table->string('guardian_occupation', 64)->nullable();
            $table->string('guardian_address', 255)->nullable(); // Increased address length

            // Personal Details
            $table->date('dob')->nullable();
            $table->string('age_according_to_circular', 64)->nullable();
            $table->string('religion', 64)->nullable();
            $table->string('gender', 16)->nullable();
            $table->string('marital_status', 16)->nullable();
            $table->string('nationality', 64)->nullable();
            $table->string('photo', 255)->nullable(); // Path for the photo (longer string for file path)

            // Education (JSC, SSC, HSC)
            $table->string('jsc_reg_no', 20)->nullable();
            $table->string('jsc_institute_name', 191)->nullable();
            $table->string('jsc_passing_year', 20)->nullable(); // Use 'year' for year data
            $table->string('jsc_gpa', 32)->nullable();

            $table->string('ac_type_ssc', 64)->nullable();
            $table->string('ssc_institute', 191)->nullable();
            $table->string('ssc_group', 64)->nullable();
            $table->string('ssc_edu_board', 64)->nullable();
            $table->string('ssc_reg_no', 20)->nullable();
            $table->string('ssc_roll_no', 20)->nullable();
            $table->string('ssc_passing_year', 20)->nullable();
            $table->string('ssc_gpa', 8)->nullable();

            $table->string('hsc_dip_institute', 191)->nullable();
            $table->string('hsc_dip_group', 64)->nullable();
            $table->string('hsc_dip_board', 64)->nullable();
            $table->string('hsc_dip_reg_no', 20)->nullable();
            $table->string('hsc_dip_roll_no', 20)->nullable();
            $table->string('hsc_dip_passing_year', 20)->nullable();

            // Special Categories
            $table->string('is_freedom_fighter', 32)->nullable();
            $table->string('freedom_fighter_relation', 64)->nullable();
            $table->string('is_child_of_naval_officer', 32)->nullable();
            $table->string('naval_father_name', 128)->nullable();
            $table->string('is_departmental_candidate', 64)->nullable();
            $table->string('naval_office_no', 64)->nullable();
            $table->string('naval_rank', 64)->nullable();
            $table->string('is_anser_vdp', 8)->nullable();
            $table->string('anser_vdp_rank', 64)->nullable();
            $table->string('anser_vdp_office_no', 64)->nullable();
            $table->string('is_khudro_jati_gosti', 8)->nullable();

            // Exam Info
            $table->string('batch', 64)->nullable();
            $table->string('center', 128)->nullable();

            // Pass Info
            $table->boolean('is_medical_pass')->index()->nullable();
            $table->boolean('is_final_pass')->index()->nullable();

            $table->string('remark')->nullable();

            $table->dateTime('normal_scanned_at')->nullable();
            $table->dateTime('primary_scanned_at')->nullable();
            $table->dateTime('written_scanned_at')->nullable();
            $table->dateTime('final_scanned_at')->nullable();
            $table->dateTime('viva_scanned_at')->nullable();
            // Timestamps
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


// $table->foreignId('application_url_id')->nullable()->constrained()->onDelete('set null');
            // $table->string('post')->nullable();
            // $table->string('batch', 32)->index()->nullable();
            // $table->string('roll', 32)->index()->nullable();
            // $table->string('name')->index()->nullable();
            // $table->string('present_address', 255)->nullable();
            // $table->string('present_post', 12)->nullable();
            // $table->string('mobile', 32)->index()->nullable();
            // $table->string('permanent_address', 255)->nullable();
            // $table->string('permanent_post', 12)->nullable();
            // $table->string('parent_mobile', 32)->nullable();
            // $table->string('dob', 32)->nullable();
            // $table->string('birth_place')->nullable();
            // $table->string('nationality', 64)->nullable();
            // $table->string('f_nationality', 64)->nullable();
            // $table->string('m_nationality', 64)->nullable();
            // $table->string('religion', 64)->nullable();
            // $table->string('marital_status', 64)->nullable();
            // $table->string('gender', 64)->index()->nullable();
            // $table->string('height', 12)->nullable();
            // $table->string('weight', 12)->nullable();
            // $table->string('chest_normal', 12)->nullable();
            // $table->string('chest_extended', 12)->nullable();
            // $table->string('visible_marks', 255)->nullable();
            // $table->string('father_name')->nullable();
            // $table->string('father_education')->nullable();
            // $table->string('mother_name')->nullable();
            // $table->string('mother_education')->nullable();
            // $table->string('parents_address', 255)->nullable();
            // $table->string('father_occupation')->nullable();
            // $table->string('mother_occupation')->nullable();
            // $table->string('income_source', 64)->nullable();
            // $table->string('income', 12)->nullable();
            // $table->string('g_name')->nullable();
            // $table->string('g_relation', 64)->nullable();
            // $table->string('g_mobile', 32)->nullable();
            // $table->string('g_income_source', 64)->nullable();
            // $table->string('g_income', 12)->nullable();
            // $table->string('g_address', 255)->nullable();
            // $table->string('psc', 64)->nullable();
            // $table->string('psc_ins_name')->nullable();
            // $table->string('psc_year', 8)->nullable();
            // $table->string('psc_remark')->nullable();
            // $table->string('jsc', 64)->nullable();
            // $table->string('jsc_ins_name')->nullable();
            // $table->string('jsc_year', 8)->nullable();
            // $table->string('jsc_remark')->nullable();
            // $table->string('ssc', 64)->nullable();
            // $table->string('ssc_group')->nullable();
            // $table->string('ssc_year', 8)->nullable();
            // $table->string('ssc_gpa', 8)->nullable();
            // $table->string('ssc_ins_name')->nullable();
            // $table->string('ssc_roll', 32)->nullable();
            // $table->string('ssc_reg_no', 32)->nullable();
            // $table->string('ssc_board', 64)->nullable();
            // $table->string('hsc', 64)->nullable();
            // $table->string('hsc_group', 64)->nullable();
            // $table->string('hsc_year', 8)->nullable();
            // $table->string('hsc_gpa', 8)->nullable();
            // $table->string('hsc_ins_name')->nullable();
            // $table->string('hsc_roll', 64)->nullable();
            // $table->string('hsc_reg_no', 64)->nullable();
            // $table->string('hsc_board', 64)->nullable();
            // $table->string('honors')->nullable();
            // $table->string('honors_subject')->nullable();
            // $table->string('honors_year', 32)->nullable();
            // $table->string('honors_gpa', 32)->nullable();
            // $table->string('honors_ins')->nullable();
            // $table->string('masters', 64)->nullable();
            // $table->string('masters_subject')->nullable();
            // $table->string('masters_year', 32)->nullable();
            // $table->string('masters_gpa', 32)->nullable();
            // $table->string('masters_ins')->nullable();
            // $table->string('phd', 64)->nullable();
            // $table->string('phd_subject', 64)->nullable();
            // $table->string('phd_year', 32)->nullable();
            // $table->string('phd_gpa', 64)->nullable();
            // $table->string('phd_ins')->nullable();
            // $table->string('hobby')->nullable();
            // $table->string('games')->nullable();
            // $table->string('inter_board', 64)->nullable();
            // $table->string('inter_date',32)->nullable();
            // $table->string('inter_result', 64)->nullable();
            // $table->string('inter2_board', 64)->nullable();
            // $table->string('inter2_date',32)->nullable();
            // $table->string('inter2_result', 64)->nullable();
            // $table->string('criminal', 64)->nullable();
            // $table->string('freedom_fighter', 64)->nullable();
            // $table->string('photo', 64)->nullable();
