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
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            // Basic Candidate Info
            $table->string('candidate_designation', 64);
            $table->date('exam_date');
            $table->string('serial_no', 64)->index();
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
            $table->boolean('is_important')->default(0)->nullable();
            $table->boolean('is_medical_pass')->index()->nullable();
            $table->boolean('is_final_pass')->index()->nullable();

            $table->string('p_m_remark', 160)->nullable();
            $table->string('f_m_remark', 160)->nullable();
            $table->string('remark')->nullable();
            $table->dateTime('scanned_at')->nullable();
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
