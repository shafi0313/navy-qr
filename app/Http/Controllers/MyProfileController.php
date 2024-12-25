<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class MyProfileController extends Controller
{
    public function index()
    {
        $applicationUrl = 'https://joinnavyofficer.org/candidate-verify/verify/SFLINFHNLEK';
        $client = new Client;
        if ($this->processURL($applicationUrl)) {
            $response = $client->get($applicationUrl);
            $htmlContent = $response->getBody()->getContents();
            // Parse the HTML using DomCrawler
            $crawler = new Crawler($htmlContent);
            $elements = $crawler->filter(' .preview__header h5, .data_roll,.pv_field__text,.pv-table td');

            $data = [];
            $elements->each(function (Crawler $node) use (&$data) {
                $data[] = $node->text();
            });

            // $dataKeys = [];
            // foreach ($data as $key => $value) {
            //     $dataKeys[] =  "$key, $value\n";  // Print both the key and corresponding value
            // }
            // return $dataKeys;
            $batch = explode(':', $data[2]);
            $roll = explode(':', $data[3]);
            // return$roll[1];
            $applicationData = [
                'post' => $data[1],
                'batch' => $batch[1],
                'roll' => $roll[1],
                'name' => $data[4],
                'present_address' => $data[5],
                'present_post' => $data[6],
                'mobile' => $data[7],
                'permanent_address' => $data[9],
                'permanent_post' => $data[10],
                'parent_mobile' => $data[11],
                'dob' => sqlDate($data[12]),
                'birth_place' => $data[13],
                'nationality' => $data[14],
                'f_nationality' => $data[16],
                'm_nationality' => $data[17],
                'religion' => $data[18],
                'marital_status' => $data[19],
                'gender' => $data[20],
                'height' => $data[21],
                'weight' => $data[22],
                'chest_normal' => $data[23],
                'chest_extended' => $data[24],
                'visible_marks' => $data[25],
                'father_name' => $data[26],
                'father_education' => $data[27],
                'mother_name' => $data[28],
                'mother_education' => $data[29],
                'parents_address' => $data[30],
                'father_occupation' => $data[31],
                'mother_occupation' => $data[32],
                'income_source' => $data[33],
                'income' => $data[34],
                'g_name' => $data[35],
                'g_relation' => $data[36],
                'g_mobile' => $data[37],
                'g_income_source' => $data[38],
                'g_income' => $data[39],
                'g_address' => $data[40],
                'psc' => $data[41],
                'psc_ins_name' => $data[42],
                'psc_year' => $data[43],
                'psc_remark' => $data[44],
                'jsc' => $data[45],
                'jsc_ins_name' => $data[46],
                'jsc_year' => $data[47],
                'jsc_remark' => $data[48],
                'ssc' => $data[49],
                'ssc_group' => $data[50],
                'ssc_year' => $data[51],
                'ssc_gpa' => $data[52],
                'ssc_ins_name' => $data[53],
                'ssc_roll' => $data[54],
                'ssc_reg_no' => $data[55],
                'ssc_board' => $data[56],
                'hsc' => $data[57],
                'hsc_group' => $data[58],
                'hsc_year' => $data[59],
                'hsc_gpa' => $data[60],
                'hsc_ins_name' => $data[61],
                'hsc_roll' => $data[62],
                'hsc_reg_no' => $data[63],
                'hsc_board' => $data[64],
            ];

            if ($data[1] == 'Education Branch') {
                $applicationData2 = [
                    'honors' => $data[65],
                    'honors_subject' => $data[66],
                    'honors_year' => $data[67],
                    'honors_gpa' => $data[68],
                    'honors_ins' => $data[69],
                    'masters' => $data[70],
                    'masters_subject' => $data[71],
                    'masters_year' => $data[72],
                    'masters_gpa' => $data[73],
                    'masters_ins' => $data[74],
                    'phd' => $data[75],
                    'phd_subject' => $data[76],
                    'phd_year' => $data[77],
                    'phd_gpa' => $data[78],
                    'phd_ins' => $data[79],
                    'hobby' => $data[80],
                    'games' => $data[81],
                    'inter_board' => $data[82],
                    'inter_date' => sqlDate($data[83]),
                    'inter_result' => $data[84],
                    'inter2_board' => $data[85],
                    'inter2_date' => sqlDate($data[86]),
                    'inter2_result' => $data[87],
                    'criminal' => $data[88],
                    'freedom_fighter' => $data[89],
                ];
            } else {
                $applicationData2 = [
                    'hobby' => $data[65],
                    'games' => $data[66],
                    'inter_board' => $data[67],
                    'inter_date' => sqlDate($data[68]),
                    'inter_result' => $data[69],
                    'inter2_board' => $data[70],
                    'inter2_date' => sqlDate($data[71]),
                    'inter2_result' => $data[72],
                    'criminal' => $data[73],
                    'freedom_fighter' => $data[74],
                ];
            }

            return $applicationCreate = array_merge($applicationData, $applicationData2);

            // $application = Application::create($applicationData);
            // Define the desired alt text
            $desiredAlt = 'Image';
            $imageSrc = $crawler->filter('img')->reduce(function (Crawler $node) use ($desiredAlt) {
                return $node->attr('alt') === $desiredAlt;
            })->first()->attr('src');
            // Download and save the image if found
            if ($imageSrc) {
                $imageSrc = strpos($imageSrc, 'http') === 0 ? $imageSrc : $applicationUrl.$imageSrc;
                $imgName = uniqueId().'.jpg';
                $imagePath = 'uploads/images/application/'.$imgName;
                // Download the image
                $client->get($imageSrc, ['sink' => $imagePath]);
                $application->update(['photo' => $imgName]);
            }
            // $applicationUrl->update(['is_info_taken' => 1]);
        }

        return view('admin.user.my-profile.index');
    }

    public function edit()
    {
        $myProfile = user();
        $genders = config('var.genders');

        return view('admin.user.my-profile.edit', compact('myProfile', 'genders'));
    }

    public function processURL($url)
    {
        try {
            file_get_contents($url);
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }
}
