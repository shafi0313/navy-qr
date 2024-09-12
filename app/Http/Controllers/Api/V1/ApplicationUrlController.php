<?php

namespace App\Http\Controllers\Api\V1;

use GuzzleHttp\Client;
use App\Models\Application;
use Illuminate\Http\Request;
use App\Models\ApplicationUrl;
use Symfony\Component\DomCrawler\Crawler;
use App\Http\Resources\ApplicationUrlResource;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\StoreApplicationUrlRequest;
use App\Http\Controllers\Api\V1\BaseController as BaseController;

class ApplicationUrlController extends BaseController
{
    public function index()
    {
        $roleId = user()->role_id;
        $query = ApplicationUrl::query();

        switch ($roleId) {
            case 1: // Admin
                $query->with([
                    'application:id,application_url_id,post,batch,roll,name',
                    'application.examMark:id,application_id,bangla,english,math,science,general_knowledge,viva'
                ])->select('application_urls.id', 'is_medical_pass', 'is_final_pass')
                    ->selectRaw('
                    (COALESCE(exam_marks.bangla, 0) +
                    COALESCE(exam_marks.english, 0) +
                    COALESCE(exam_marks.math, 0) +
                    COALESCE(exam_marks.science, 0) +
                    COALESCE(exam_marks.general_knowledge, 0)) as total_marks
                ')
                    ->join('applications', 'applications.application_url_id', '=', 'application_urls.id')
                    ->join('exam_marks', 'exam_marks.application_id', '=', 'applications.id')
                    ->groupBy('application_urls.id', 'application_urls.is_medical_pass', 'application_urls.is_final_pass', 'exam_marks.bangla', 'exam_marks.english', 'exam_marks.math', 'exam_marks.science', 'exam_marks.general_knowledge')
                    ->orderBy('total_marks', 'desc');
                break;
            case 2: // Normal User
                $query->with([
                    'application:id,application_url_id,post,batch,roll,name',
                ])->select('id', 'url');
                break;
            case 3: // Primary Medical
                $query->with([
                    'application:id,application_url_id,post,batch,roll,name',
                    'application.examMark:id'
                ])->select('id', 'url', 'is_medical_pass');
                break;
            case 4: // Written
                $query->with([
                    'application:id,application_url_id,post,batch,roll,name',
                    'application.examMark:id,application_id,bangla,english,math,science,general_knowledge,viva'
                ])
                    ->select('application_urls.id', 'is_medical_pass', 'is_final_pass')
                    ->selectRaw('
                    (COALESCE(exam_marks.bangla, 0) +
                    COALESCE(exam_marks.english, 0) +
                    COALESCE(exam_marks.math, 0) +
                    COALESCE(exam_marks.science, 0) +
                    COALESCE(exam_marks.general_knowledge, 0)) as total_marks
                ')
                    ->join('applications', 'applications.application_url_id', '=', 'application_urls.id')
                    ->join('exam_marks', 'exam_marks.application_id', '=', 'applications.id')
                    ->groupBy('application_urls.id', 'application_urls.is_medical_pass', 'application_urls.is_final_pass', 'exam_marks.bangla', 'exam_marks.english', 'exam_marks.math', 'exam_marks.science', 'exam_marks.general_knowledge')
                    ->orderBy('total_marks', 'desc');
                break;
            case 5: // Final Medical
                $query->with([
                    'application:id,application_url_id,post,batch,roll,name',
                    'application.examMark:id,application_id,bangla,english,math,science,general_knowledge'
                ])
                    ->whereHas('application.examMark', function ($query) {
                        $query->where('bangla', '>=', 8)
                            ->where('english', '>=', 8)
                            ->where('math', '>=', 8)
                            ->where('science', '>=', 8)
                            ->where('general_knowledge', '>=', 8);
                    })
                    ->select('application_urls.id', 'is_medical_pass', 'is_final_pass')
                    ->selectRaw('
                    (COALESCE(exam_marks.bangla, 0) +
                    COALESCE(exam_marks.english, 0) +
                    COALESCE(exam_marks.math, 0) +
                    COALESCE(exam_marks.science, 0) +
                    COALESCE(exam_marks.general_knowledge, 0)) as total_marks
                ')
                    ->join('applications', 'applications.application_url_id', '=', 'application_urls.id')
                    ->join('exam_marks', 'exam_marks.application_id', '=', 'applications.id')
                    ->groupBy('application_urls.id', 'application_urls.is_medical_pass', 'application_urls.is_final_pass', 'exam_marks.bangla', 'exam_marks.english', 'exam_marks.math', 'exam_marks.science', 'exam_marks.general_knowledge')
                    ->orderBy('total_marks', 'desc');
                break;
            case 6: // Viva / Final Selection
                $query->with([
                    'application:id,application_url_id,post,batch,roll,name',
                    'application.examMark:id,application_id,bangla,english,math,science,general_knowledge,viva'
                ])
                    ->whereHas('application.examMark', function ($query) {
                        $query->where('bangla', '>=', 8)
                            ->where('english', '>=', 8)
                            ->where('math', '>=', 8)
                            ->where('science', '>=', 8)
                            ->where('general_knowledge', '>=', 8);
                    })
                    ->join('applications', 'applications.application_url_id', '=', 'application_urls.id')
                    ->join('exam_marks', 'exam_marks.application_id', '=', 'applications.id')
                    ->select(
                        'application_urls.id',
                        'is_medical_pass',
                        'is_final_pass',
                        'applications.id as application_id'
                    )
                    ->selectRaw('
                    (COALESCE(exam_marks.bangla, 0) +
                    COALESCE(exam_marks.english, 0) +
                    COALESCE(exam_marks.math, 0) +
                    COALESCE(exam_marks.science, 0) +
                    COALESCE(exam_marks.general_knowledge, 0)) as total_marks,
                    COALESCE(exam_marks.viva, 0) as total_viva
                ')
                    ->groupBy(
                        'application_urls.id',
                        'applications.id',
                        'is_medical_pass',
                        'is_final_pass',
                        'exam_marks.bangla',
                        'exam_marks.english',
                        'exam_marks.math',
                        'exam_marks.science',
                        'exam_marks.general_knowledge',
                        'exam_marks.viva'
                    )
                    ->orderBy('total_viva', 'desc');

                break;
        }

        $applicationUrls = $query->get();

        return $this->sendResponse(ApplicationUrlResource::collection($applicationUrls), 'Applicant list.');
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(StoreApplicationUrlRequest $request)
    // {
    //     $data = $request->validated();

    //     // Check for duplicate
    //     // $checkDuplicate = ApplicationUrl::whereUrl($request->url)->first();
    //     // if ($checkDuplicate) {
    //     //     return $this->sendError('Duplicate Error.', 'Applicant already exists.');
    //     // }

    //     // Create the application URL
    //     $applicationUrl = ApplicationUrl::create($data);

    //     // Initialize HTTP client
    //     $client = new Client();

    //     try {
    //         if ($this->processURL($applicationUrl->url)) {
    //             $response = $client->get($applicationUrl->url);
    //             $htmlContent = $response->getBody()->getContents();

    //             // Parse the HTML using DomCrawler
    //             $crawler = new Crawler($htmlContent);
    //             $elements = $crawler->filter('.preview__header h5, .data_roll,.pv_field__text,.pv-table td');

    //             $data = [];
    //             $elements->each(function (Crawler $node) use (&$data) {
    //                 $data[] = trim($node->text());
    //             });

    //             // Ensure array indexes exist before accessing them
    //             $batch = isset($data[2]) ? explode(":", $data[2]) : [null, null];
    //             $roll = isset($data[3]) ? explode(":", $data[3]) : [null, null];

    //             $applicationData = [
    //                 'application_url_id' => $applicationUrl->id,
    //                 'post'               => $data[1] ?? null,
    //                 'batch'              => $batch[1] ?? null,
    //                 'roll'               => $roll[1] ?? null,
    //                 'name'               => $data[4] ?? null,
    //                 'present_address'    => $data[5] ?? null,
    //                 'present_post'       => $data[6] ?? null,
    //                 'mobile'             => $data[7] ?? null,
    //                 'permanent_address'  => $data[9] ?? null,
    //                 'permanent_post'     => $data[10] ?? null,
    //                 'parent_mobile'      => $data[11] ?? null,
    //                 'dob'                => isset($data[12]) ? sqlDate($data[12]) : null,
    //                 'birth_place'        => $data[13] ?? null,
    //                 'nationality'        => $data[14] ?? null,
    //                 'f_nationality'      => $data[16] ?? null,
    //                 'm_nationality'      => $data[17] ?? null,
    //                 'religion'           => $data[18] ?? null,
    //                 'marital_status'     => $data[19] ?? null,
    //                 'gender'             => $data[20] ?? null,
    //                 'height'             => $data[21] ?? null,
    //                 'weight'             => $data[22] ?? null,
    //                 'chest_normal'       => $data[23] ?? null,
    //                 'chest_extended'     => $data[24] ?? null,
    //                 'visible_marks'      => $data[25] ?? null,
    //                 'father_name'        => $data[26] ?? null,
    //                 'father_education'   => $data[27] ?? null,
    //                 'mother_name'        => $data[28] ?? null,
    //                 'mother_education'   => $data[29] ?? null,
    //                 'parents_address'    => $data[30] ?? null,
    //                 'father_occupation'  => $data[31] ?? null,
    //                 'mother_occupation'  => $data[32] ?? null,
    //                 'income_source'      => $data[33] ?? null,
    //                 'income'             => $data[34] ?? null,
    //                 'g_name'             => $data[35] ?? null,
    //                 'g_relation'         => $data[36] ?? null,
    //                 'g_mobile'           => $data[37] ?? null,
    //                 'g_income_source'    => $data[38] ?? null,
    //                 'g_income'           => $data[39] ?? null,
    //                 'g_address'          => $data[40] ?? null,
    //                 'psc'                => $data[41] ?? null,
    //                 'psc_ins_name'       => $data[42] ?? null,
    //                 'psc_year'           => $data[43] ?? null,
    //                 'psc_remark'         => $data[44] ?? null,
    //                 'jsc'                => $data[45] ?? null,
    //                 'jsc_ins_name'       => $data[46] ?? null,
    //                 'jsc_year'           => $data[47] ?? null,
    //                 'jsc_remark'         => $data[48] ?? null,
    //                 'ssc'                => $data[49] ?? null,
    //                 'ssc_group'          => $data[50] ?? null,
    //                 'ssc_year'           => $data[51] ?? null,
    //                 'ssc_gpa'            => $data[52] ?? null,
    //                 'ssc_ins_name'       => $data[53] ?? null,
    //                 'ssc_roll'           => $data[54] ?? null,
    //                 'ssc_reg_no'         => $data[55] ?? null,
    //                 'ssc_board'          => $data[56] ?? null,
    //                 'hsc'                => $data[57] ?? null,
    //                 'hsc_group'          => $data[58] ?? null,
    //                 'hsc_year'           => $data[59] ?? null,
    //                 'hsc_gpa'            => $data[60] ?? null,
    //                 'hsc_ins_name'       => $data[61] ?? null,
    //                 'hsc_roll'           => $data[62] ?? null,
    //                 'hsc_reg_no'         => $data[63] ?? null,
    //                 'hsc_board'          => $data[64] ?? null,
    //             ];

    //             // Commented out the Application::create, so data won't be saved
    //             $application = Application::create($applicationData);

    //             // Process and save the image if available
    //             $desiredAlt = 'Image';
    //             $imageSrc = $crawler->filter('img')->reduce(function (Crawler $node) use ($desiredAlt) {
    //                 return $node->attr('alt') === $desiredAlt;
    //             })->first()->attr('src');

    //             if ($imageSrc) {
    //                 // Handle relative image URL
    //                 $imageSrc = strpos($imageSrc, 'http') === 0 ? $imageSrc : $applicationUrl->url . $imageSrc;
    //                 $imgName = uniqueId() . '.jpg';
    //                 $imagePath = 'uploads/images/application/' . $imgName;

    //                 // Download and save the image
    //                 $client->get($imageSrc, ['sink' => $imagePath]);
    //                 // Commented out the update to the Application model
    //                 // $application->update(['photo' => $imgName]);
    //             }

    //             // Mark the application URL as processed
    //             $applicationUrl->update(['is_info_taken' => 1]);

    //             return $this->sendResponse(new ApplicationUrlResource($applicationUrl), 'Applicant info successfully retrieved.');
    //         }
    //     } catch (\Exception $e) {
    //         return $this->sendError('Error occurred.', $e->getMessage());
    //     }
    // }



    public function store(StoreApplicationUrlRequest $request)
    {
        $data = $request->validated();
        $checkDuplicate = ApplicationUrl::whereUrl($request->url)->first();
        if ($checkDuplicate) {
            return $this->sendError('Duplicate Error.', 'Applicant already exists.');
        }
        $applicationUrl = ApplicationUrl::create($data);

        $client = new Client();
        if ($this->processURL($applicationUrl->url)) {
            $response = $client->get($applicationUrl->url);
            $htmlContent = $response->getBody()->getContents();
            // Parse the HTML using DomCrawler
            $crawler = new Crawler($htmlContent);
            $elements = $crawler->filter('.preview__header h5, .data_roll,.pv_field__text,.pv-table td');

            $data = [];
            $elements->each(function (Crawler $node) use (&$data) {
                $data[] = trim($node->text());
            });
            // return $data;

            $batch = explode(":", $data[2]);
            $roll = explode(":", $data[3]);
            $applicationData = [
                'application_url_id' => $applicationUrl->id,
                'post'               => $data[1],
                'batch'              => $batch[1],
                'roll'               => $roll[1],
                'name'               => $data[4],
                'present_address'    => $data[5],
                'present_post'       => $data[6],
                'mobile'             => $data[7],
                'permanent_address'  => $data[9],
                'permanent_post'     => $data[10],
                'parent_mobile'      => $data[11],
                'dob'                => sqlDate($data[12]) ?? null,
                'birth_place'        => $data[13],
                'nationality'        => $data[14],
                'f_nationality'      => $data[16],
                'm_nationality'      => $data[17],
                'religion'           => $data[18],
                'marital_status'     => $data[19],
                'gender'             => $data[20],
                'height'             => $data[21],
                'weight'             => $data[22],
                'chest_normal'       => $data[23],
                'chest_extended'     => $data[24],
                'visible_marks'      => $data[25],
                'father_name'        => $data[26],
                'father_education'   => $data[27],
                'mother_name'        => $data[28],
                'mother_education'   => $data[29],
                'parents_address'    => $data[30],
                'father_occupation'  => $data[31],
                'mother_occupation'  => $data[32] ?? null,
                'income_source'      => $data[33] ?? null,
                'income'             => $data[34] ?? null,
                'g_name'             => $data[35] ?? null,
                'g_relation'         => $data[36] ?? null,
                'g_mobile'           => $data[37] ?? null,
                'g_income_source'    => $data[38] ?? null,
                'g_income'           => $data[39] ?? null,
                'g_address'          => $data[40] ?? null,
                'psc'                => $data[41] ?? null,
                'psc_ins_name'       => $data[42] ?? null,
                'psc_year'           => $data[43] ?? null,
                'psc_remark'         => $data[44] ?? null,
                'jsc'                => $data[45] ?? null,
                'jsc_ins_name'       => $data[46] ?? null,
                'jsc_year'           => $data[47] ?? null,
                'jsc_remark'         => $data[48] ?? null,
                'ssc'                => $data[49] ?? null,
                'ssc_group'          => $data[50] ?? null,
                'ssc_year'           => $data[51] ?? null,
                'ssc_gpa'            => $data[52] ?? null,
                'ssc_ins_name'       => $data[53] ?? null,
                'ssc_roll'           => $data[54] ?? null,
                'ssc_reg_no'         => $data[55] ?? null,
                'ssc_board'          => $data[56] ?? null,
                'hsc'                => $data[57] ?? null,
                'hsc_group'          => $data[58] ?? null,
                'hsc_year'           => $data[59] ?? null,
                'hsc_gpa'            => $data[60] ?? null,
                'hsc_ins_name'       => $data[61] ?? null,
                'hsc_roll'           => $data[62] ?? null,
                'hsc_reg_no'         => $data[63] ?? null,
                'hsc_board'          => $data[64] ?? null,
            ];

            // if ($data[1] == 'Education Branch') {
            //     $applicationData2 = [
            //         'honors'            => $data[65] ?? null,
            //         'honors_subject'    => $data[66] ?? null,
            //         'honors_year'       => $data[67] ?? null,
            //         'honors_gpa'        => $data[68] ?? null,
            //         'honors_ins'        => $data[69] ?? null,
            //         'masters'           => $data[70] ?? null,
            //         'masters_subject'   => $data[71] ?? null,
            //         'masters_year'      => $data[72] ?? null,
            //         'masters_gpa'       => $data[73] ?? null,
            //         'masters_ins'       => $data[74] ?? null,
            //         'phd'               => $data[75] ?? null,
            //         'phd_subject'       => $data[76] ?? null,
            //         'phd_year'          => $data[77] ?? null,
            //         'phd_gpa'           => $data[78] ?? null,
            //         'phd_ins'           => $data[79] ?? null,
            //         'hobby'             => $data[80] ?? null,
            //         'games'             => $data[81] ?? null,
            //         'inter_board'       => $data[82] ?? null,
            //         'inter_date'        => sqlDate($data[83] ?? null),
            //         'inter_result'      => $data[84] ?? null,
            //         'inter2_board'      => $data[85] ?? null,
            //         'inter2_date'       => sqlDate($data[86] ?? null),
            //         'inter2_result'     => $data[87] ?? null,
            //         'criminal'          => $data[88] ?? null,
            //         'freedom_fighter'   => $data[89] ?? null,
            //     ];
            // } else {
            //     $applicationData2 = [
            //         'hobby'             => $data[65] ?? null,
            //         'games'             => $data[66] ?? null,
            //         'inter_board'       => $data[67] ?? null,
            //         'inter_date'        => sqlDate($data[68] ?? null),
            //         'inter_result'      => $data[69] ?? null,
            //         'inter2_board'      => $data[70] ?? null,
            //         'inter2_date'       => sqlDate($data[71] ?? null),
            //         'inter2_result'     => $data[72] ?? null,
            //         'criminal'          => $data[73] ?? null,
            //         'freedom_fighter'   => $data[74] ?? null,
            //     ];
            // }
            // return $applicationData;

            // $applicationCreate = array_merge($applicationData, $applicationData2);
            $application = Application::create($applicationData);
            // Define the desired alt text
            $desiredAlt = 'Image';
            $imageSrc = $crawler->filter('img')->reduce(function (Crawler $node) use ($desiredAlt) {
                return $node->attr('alt') === $desiredAlt;
            })->first()->attr('src');
            // Download and save the image if found
            if ($imageSrc) {
                $imageSrc = strpos($imageSrc, 'http') === 0 ? $imageSrc : $applicationUrl->url . $imageSrc;
                $imgName = uniqueId() . '.jpg';
                $imagePath = 'uploads/images/application/' . $imgName;
                // Download the image
                $client->get($imageSrc, ['sink' => $imagePath]);
                $application->update(['photo' => $imgName]);
            }
            $applicationUrl->update(['is_info_taken' => 1]);
        }
        try {

            return $this->sendResponse(new ApplicationUrlResource($applicationUrl), 'Applicant info successfully inserted.');
        } catch (\Exception $e) {
            return $this->sendError('Validation Error.', $data->errors());
        }
    }





    public function medicalPassStatus(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'id' => 'required|exists:application_urls,id',
            'is_medical_pass' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $applicationUrl = ApplicationUrl::with([
            'application:id,application_url_id,post,batch,roll,name'
        ])->select('id', 'url', 'is_medical_pass')->findOrFail($request->id);

        $applicationUrl->update(['is_medical_pass' => $request->is_medical_pass]);

        return $this->sendResponse(new ApplicationUrlResource($applicationUrl), 'Primary medical status updated.');
    }




    function processURL($url)
    {
        try {
            file_get_contents($url);
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }
}
