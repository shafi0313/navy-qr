<?php

namespace App\Http\Controllers\Api\V1;

use GuzzleHttp\Client;
use App\Models\Application;
use Illuminate\Http\Request;
use App\Models\ApplicationUrl;
use Symfony\Component\DomCrawler\Crawler;
use App\Http\Resources\ApplicationUrlResource;
use App\Http\Requests\StoreApplicationUrlRequest;
use App\Http\Controllers\Api\V1\BaseController as BaseController;

class ApplicationUrlController extends BaseController
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreApplicationUrlRequest $request)
    {
        $data = $request->validated();
        // $checkDuplicate = ApplicationUrl::whereUrl($request->url)->first();
        // if ($checkDuplicate) {
        //     return $this->sendError('Duplicate Error.', 'Applicant already exists.');
        // }
        $data['role_id'] = 1;
        // $data['role_id'] = user()->role_id;


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
                $data[] = $node->text();
            });
            // return $data;

            $batch = explode(":", $data[2]);
            $roll = explode(":", $data[3]);
            // return$roll[1];
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
                'dob'                => sqlDate($data[12]),
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

            if ($data[1] == 'Education Branch') {
                $applicationData2 = [
                    'honors'            => $data[65] ?? null,
                    'honors_subject'    => $data[66] ?? null,
                    'honors_year'       => $data[67] ?? null,
                    'honors_gpa'        => $data[68] ?? null,
                    'honors_ins'        => $data[69] ?? null,
                    'masters'           => $data[70] ?? null,
                    'masters_subject'   => $data[71] ?? null,
                    'masters_year'      => $data[72] ?? null,
                    'masters_gpa'       => $data[73] ?? null,
                    'masters_ins'       => $data[74] ?? null,
                    'phd'               => $data[75] ?? null,
                    'phd_subject'       => $data[76] ?? null,
                    'phd_year'          => $data[77] ?? null,
                    'phd_gpa'           => $data[78] ?? null,
                    'phd_ins'           => $data[79] ?? null,
                    'hobby'             => $data[80] ?? null,
                    'games'             => $data[81] ?? null,
                    'inter_board'       => $data[82] ?? null,
                    'inter_date'        => sqlDate($data[83] ?? null),
                    'inter_result'      => $data[84] ?? null,
                    'inter2_board'      => $data[85] ?? null,
                    'inter2_date'       => sqlDate($data[86] ?? null),
                    'inter2_result'     => $data[87] ?? null,
                    'criminal'          => $data[88] ?? null,
                    'freedom_fighter'   => $data[89] ?? null,
                ];
            } else {
                $applicationData2 = [
                    'hobby'             => $data[65] ?? null,
                    'games'             => $data[66] ?? null,
                    'inter_board'       => $data[67] ?? null,
                    'inter_date'        => sqlDate($data[68] ?? null),
                    'inter_result'      => $data[69] ?? null,
                    'inter2_board'      => $data[70] ?? null,
                    'inter2_date'       => sqlDate($data[71] ?? null),
                    'inter2_result'     => $data[72] ?? null,
                    'criminal'          => $data[73] ?? null,
                    'freedom_fighter'   => $data[74] ?? null,
                ];
            }

            $applicationCreate = array_merge($applicationData, $applicationData2);
            $application = Application::create($applicationCreate);
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
