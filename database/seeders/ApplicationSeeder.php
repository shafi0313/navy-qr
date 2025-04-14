<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ApplicationSeeder extends Seeder
{
    // public function run()
    // {
    //     // Define the directory where your JSON files are stored
    //     $directoryPath = storage_path('sailor'); // Adjust this to the correct directory

    //     // Check if the directory exists
    //     if (! File::exists($directoryPath)) {
    //         $this->command->error('Directory not found at: ' . $directoryPath);

    //         return;
    //     }

    //     // Get all JSON files in the directory
    //     $files = File::files($directoryPath);

    //     if (empty($files)) {
    //         $this->command->error('No JSON files found in directory: ' . $directoryPath);

    //         return;
    //     }

    //     // Set the batch size
    //     $batchSize = 300;
    //     $batchData = [];

    //     foreach ($files as $file) {
    //         // Check if the file is a JSON file
    //         if ($file->getExtension() !== 'json') {
    //             continue;
    //         }

    //         // Read the file contents
    //         $jsonContent = File::get($file->getPathname());
    //         $applications = json_decode($jsonContent, true); // Convert JSON into an associative array

    //         if (is_null($applications)) {
    //             $this->command->error('Invalid JSON format or file is empty: ' . $file->getFilename());

    //             continue;
    //         }

    //         // Process each application in the current file
    //         foreach ($applications as $application) {
    //             $batchData[] = [
    //                 'candidate_designation' => $application['candidate_designation'],
    //                 'exam_date' => $application['exam_date'],
    //                 'serial_no' => $application['serial_no'],
    //                 'eligible_district' => $application['eligible_district'],
    //                 'district' => $application['district'],
    //                 'name' => $application['name'],
    //                 'father_name' => $application['father_name'],
    //                 'father_nid' => $application['father_nid'],
    //                 'father_occupation' => $application['father_occupation'],
    //                 'father_income' => $application['father_income'],
    //                 'mother_name' => $application['mother_name'],
    //                 'mother_occupation' => $application['mother_occupation'],
    //                 'current_village' => $application['current_village'],
    //                 'current_word_no' => $application['current_word_no'],
    //                 'current_union' => $application['current_union'],
    //                 'current_post_office' => $application['current_post_office'],
    //                 'current_thana' => $application['current_thana'],
    //                 'current_post_code' => $application['current_post_code'],
    //                 'current_district' => $application['current_district'],
    //                 'current_phone' => $application['current_phone'],
    //                 'permanent_village' => $application['permanent_village'],
    //                 'permanent_union' => $application['permanent_union'],
    //                 'permanent_word_no' => $application['permanent_word_no'],
    //                 'permanent_post_office' => $application['permanent_post_office'],
    //                 'permanent_thana' => $application['permanent_thana'],
    //                 'permanent_district' => $application['permanent_district'],
    //                 'permanent_post_code' => $application['permanent_post_code'],
    //                 'permanent_phone' => $application['permanent_phone'],
    //                 'guardian_name' => $application['guardian_name'],
    //                 'guardian_relation' => $application['guardian_relation'],
    //                 'guardian_occupation' => $application['guardian_occupation'],
    //                 'guardian_address' => $application['guardian_address'],
    //                 'dob' => $application['dob'],
    //                 'age_according_to_circular' => $application['age_according_to_circular'],
    //                 'religion' => $application['religion'],
    //                 'gender' => $application['gender'],
    //                 'marital_status' => $application['marital_status'],
    //                 'nationality' => $application['nationality'],
    //                 'photo' => $application['photo'],
    //                 'jsc_reg_no' => $application['jsc_reg_no'],
    //                 'jsc_institute_name' => $application['jsc_institute_name'],
    //                 'jsc_passing_year' => $application['jsc_passing_year'],
    //                 'jsc_gpa' => $application['jsc_gpa'],
    //                 'ac_type_ssc' => $application['ac_type_ssc'],
    //                 'ssc_institute' => $application['ssc_institute'],
    //                 'ssc_group' => $application['ssc_group'],
    //                 'ssc_edu_board' => $application['ssc_edu_board'],
    //                 'ssc_reg_no' => $application['ssc_reg_no'],
    //                 'ssc_roll_no' => $application['ssc_roll_no'],
    //                 'ssc_passing_year' => $application['ssc_passing_year'],
    //                 'ssc_gpa' => $application['ssc_gpa'],


    //                 'ssc_bangla' => $application['ssc_result_with_subject']['bangla'] ?? null,
    //                 'ssc_english' => $application['ssc_result_with_subject']['english'] ?? null,
    //                 'ssc_math' => $application['ssc_result_with_subject']['math'] ?? null,
    //                 'ssc_physics' => $application['ssc_result_with_subject']['physics'] ?? null,
    //                 'ssc_biology' => $application['ssc_result_with_subject']['biology'] ?? null,


    //                 'hsc_dip_institute' => $application['hsc_dip_institute'],
    //                 'hsc_dip_group' => $application['hsc_dip_group'],
    //                 'hsc_dip_board' => $application['hsc_dip_board'],
    //                 'hsc_dip_reg_no' => $application['hsc_dip_reg_no'],
    //                 'hsc_dip_roll_no' => $application['hsc_dip_roll_no'],
    //                 'hsc_dip_passing_year' => $application['hsc_dip_passing_year'],
    //                 'is_freedom_fighter' => $application['is_freedom_fighter'],
    //                 'freedom_fighter_relation' => $application['freedom_fighter_relation'],
    //                 'is_child_of_naval_officer' => $application['is_child_of_naval_officer'],
    //                 'naval_father_name' => $application['naval_father_name'],
    //                 'is_departmental_candidate' => $application['is_departmental_candidate'],
    //                 'naval_office_no' => $application['naval_office_no'],
    //                 'naval_rank' => $application['naval_rank'],
    //                 'is_anser_vdp' => $application['is_anser_vdp'],
    //                 'anser_vdp_rank' => $application['anser_vdp_rank'],
    //                 'anser_vdp_office_no' => $application['anser_vdp_office_no'],
    //                 'is_khudro_jati_gosti' => $application['is_khudro_jati_gosti'],
    //                 'batch' => $application['batch'],
    //                 'center' => $application['center'],
    //                 // Add more fields if necessary
    //             ];

    //             // Insert the batch if the size is reached
    //             if (count($batchData) >= $batchSize) {
    //                 DB::table('applications')->insert($batchData);
    //                 $batchData = []; // Reset the batch
    //             }
    //         }
    //     }

    //     // Insert any remaining data
    //     if (! empty($batchData)) {
    //         DB::table('applications')->insert($batchData);
    //     }

    //     // Output success message
    //     $this->command->info('Data import from multiple files completed successfully.');
    // }

    public function run()
    {
        // Define the path to the JSON file
        $filePath = storage_path('sailor_data_ls.json'); // Adjust this path to where your JSON file is stored

        // Check if the file exists
        if (!File::exists($filePath)) {
            $this->command->error("File not found at: " . $filePath);
            return;
        }

        // Read the file contents
        $jsonContent = File::get($filePath);
        $applications = json_decode($jsonContent, true); // Convert JSON into an associative array

        if (is_null($applications)) {
            $this->command->error("Invalid JSON format or file is empty.");
            return;
        }

        // Set the batch size
        $batchSize = 500;
        $batchData = [];

        foreach ($applications as $application) {
            $batchData[] = [
                'candidate_designation' => $application['candidate_designation'],
                'exam_date' => $application['exam_date'],
                'serial_no' => $application['serial_no'],
                'eligible_district' => $application['eligible_district'],
                'district' => $application['district'],
                'name' => $application['name'],
                'father_name' => $application['father_name'],
                'father_nid' => $application['father_nid'],
                'father_occupation' => $application['father_occupation'],
                'father_income' => $application['father_income'],
                'mother_name' => $application['mother_name'],
                'mother_occupation' => $application['mother_occupation'],
                'current_village' => $application['current_village'],
                'current_word_no' => $application['current_word_no'],
                'current_union' => $application['current_union'],
                'current_post_office' => $application['current_post_office'],
                'current_thana' => $application['current_thana'],
                'current_post_code' => $application['current_post_code'],
                'current_district' => $application['current_district'],
                'current_phone' => $application['current_phone'],
                'permanent_village' => $application['permanent_village'],
                'permanent_union' => $application['permanent_union'],
                'permanent_word_no' => $application['permanent_word_no'],
                'permanent_post_office' => $application['permanent_post_office'],
                'permanent_thana' => $application['permanent_thana'],
                'permanent_district' => $application['permanent_district'],
                'permanent_post_code' => $application['permanent_post_code'],
                'permanent_phone' => $application['permanent_phone'],
                'guardian_name' => $application['guardian_name'],
                'guardian_relation' => $application['guardian_relation'],
                'guardian_occupation' => $application['guardian_occupation'],
                'guardian_address' => $application['guardian_address'],
                'dob' => $application['dob'],
                'age_according_to_circular' => $application['age_according_to_circular'],
                'religion' => $application['religion'],
                'gender' => $application['gender'],
                'marital_status' => $application['marital_status'],
                'nationality' => $application['nationality'],
                'photo' => $application['photo'],
                'jsc_reg_no' => $application['jsc_reg_no'],
                'jsc_institute_name' => $application['jsc_institute_name'],
                'jsc_passing_year' => $application['jsc_passing_year'],
                'jsc_gpa' => $application['jsc_gpa'],
                'ac_type_ssc' => $application['ac_type_ssc'],
                'ssc_institute' => $application['ssc_institute'],
                'ssc_group' => $application['ssc_group'],
                'ssc_edu_board' => $application['ssc_edu_board'],
                'ssc_reg_no' => $application['ssc_reg_no'],
                'ssc_roll_no' => $application['ssc_roll_no'],
                'ssc_passing_year' => $application['ssc_passing_year'],
                'ssc_gpa' => $application['ssc_gpa'],

                'ssc_bangla' => $application['ssc_result_with_subject']['bangla'] ?? null,
                'ssc_english' => $application['ssc_result_with_subject']['english'] ?? null,
                'ssc_math' => $application['ssc_result_with_subject']['math'] ?? null,
                'ssc_physics' => $application['ssc_result_with_subject']['physics'] ?? null,
                'ssc_biology' => $application['ssc_result_with_subject']['biology'] ?? null,

                'hsc_dip_institute' => $application['hsc_dip_institute'],
                'hsc_dip_group' => $application['hsc_dip_group'],
                'hsc_dip_board' => $application['hsc_dip_board'],
                'hsc_dip_reg_no' => $application['hsc_dip_reg_no'],
                'hsc_dip_roll_no' => $application['hsc_dip_roll_no'],
                'hsc_dip_passing_year' => $application['hsc_dip_passing_year'],
                'is_freedom_fighter' => $application['is_freedom_fighter'],
                'freedom_fighter_relation' => $application['freedom_fighter_relation'],
                'is_child_of_naval_officer' => $application['is_child_of_naval_officer'],
                'naval_father_name' => $application['naval_father_name'],
                'is_departmental_candidate' => $application['is_departmental_candidate'],
                'naval_office_no' => $application['naval_office_no'],
                'naval_rank' => $application['naval_rank'],
                'is_anser_vdp' => $application['is_anser_vdp'],
                'anser_vdp_rank' => $application['anser_vdp_rank'],
                'anser_vdp_office_no' => $application['anser_vdp_office_no'],
                'is_khudro_jati_gosti' => $application['is_khudro_jati_gosti'],
                'batch' => $application['batch'],
                'center' => $application['center'],
                // Add more fields if necessary
            ];

            // Insert the batch if the size is reached
            if (count($batchData) >= $batchSize) {
                DB::table('applications')->insert($batchData);
                $batchData = []; // Reset the batch
            }
        }

        // Insert any remaining data
        if (!empty($batchData)) {
            DB::table('applications')->insert($batchData);
        }

        // Output success message
        $this->command->info("Data import completed successfully.");
    }
}
