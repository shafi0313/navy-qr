<?php

namespace App\Http\Controllers\Admin;

use GuzzleHttp\Client;
use App\Models\Application;
use Illuminate\Http\Request;
use App\Models\ApplicationUrl;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Symfony\Component\DomCrawler\Crawler;
use App\Http\Requests\StoreApplicationUrlRequest;
use App\Http\Requests\UpdateApplicationUrlRequest;

class ApplicationUrlController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://joinnavyofficer.org/candidate-verify/verify/XSJRROMRL');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;


        $applicationUrl = 'https://joinnavyofficer.org/candidate-verify/verify/XSJRROMRL';

        // $client = new Client();
        $client = new Client([
            'cookies' => true,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36'
            ]
        ]);
        // if ($this->processURL($applicationUrl)) {
        $response = $client->get($applicationUrl);
        $htmlContent = $response->getBody()->getContents();
        // Parse the HTML using DomCrawler
        $crawler = new Crawler($htmlContent);
        $elements = $crawler->filter('.preview__header h5, .data_roll,.pv_field__text,.pv-table td');

        $data = [];
        $elements->each(function (Crawler $node) use (&$data) {
            $data[] = trim($node->text());
        });
        return $data;

        $batch = explode(":", $data[2]);
        $roll = explode(":", $data[3]);
        $applicationData = [
            'application_url_id' => 1,
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
        // $application = Application::create($applicationData);
        // }

        //     $query = ApplicationUrl::query();

        if ($request->ajax()) {
            $roleId = user()->role_id;
            $query = ApplicationUrl::query();

            switch ($roleId) {
                case 1: // Admin
                    $query->with([
                        'application:id,application_url_id,post,batch,roll,name',
                        'application.examMark:id,application_id,bangla,english,math,science,general_knowledge,viva'
                    ])->select('application_urls.id', 'is_medical_pass', 'is_final_pass')
                        ->leftJoin('applications', 'applications.application_url_id', '=', 'application_urls.id')
                        ->leftJoin('exam_marks', 'exam_marks.application_id', '=', 'applications.id')
                        ->selectRaw('
                        (COALESCE(exam_marks.bangla, 0) +
                        COALESCE(exam_marks.english, 0) +
                        COALESCE(exam_marks.math, 0) +
                        COALESCE(exam_marks.science, 0) +
                        COALESCE(exam_marks.general_knowledge, 0)) as total_marks
                    ')->groupBy('application_urls.id', 'application_urls.is_medical_pass', 'application_urls.is_final_pass', 'exam_marks.bangla', 'exam_marks.english', 'exam_marks.math', 'exam_marks.science', 'exam_marks.general_knowledge')
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
                    ])->select('application_urls.id', 'is_medical_pass', 'is_final_pass')
                        ->leftJoin('applications', 'applications.application_url_id', '=', 'application_urls.id')
                        ->leftJoin('exam_marks', 'exam_marks.application_id', '=', 'applications.id')
                        ->selectRaw('
                        (COALESCE(exam_marks.bangla, 0) +
                        COALESCE(exam_marks.english, 0) +
                        COALESCE(exam_marks.math, 0) +
                        COALESCE(exam_marks.science, 0) +
                        COALESCE(exam_marks.general_knowledge, 0)) as total_marks
                    ')->groupBy('application_urls.id', 'application_urls.is_medical_pass', 'application_urls.is_final_pass', 'exam_marks.bangla', 'exam_marks.english', 'exam_marks.math', 'exam_marks.science', 'exam_marks.general_knowledge')
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
                        ->leftJoin('applications', 'applications.application_url_id', '=', 'application_urls.id')
                        ->leftJoin('exam_marks', 'exam_marks.application_id', '=', 'applications.id')
                        ->selectRaw('
                        (COALESCE(exam_marks.bangla, 0) +
                        COALESCE(exam_marks.english, 0) +
                        COALESCE(exam_marks.math, 0) +
                        COALESCE(exam_marks.science, 0) +
                        COALESCE(exam_marks.general_knowledge, 0)) as total_marks
                    ')->groupBy('application_urls.id', 'application_urls.is_medical_pass', 'application_urls.is_final_pass', 'exam_marks.bangla', 'exam_marks.english', 'exam_marks.math', 'exam_marks.science', 'exam_marks.general_knowledge')
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
                        ->leftJoin('applications', 'applications.application_url_id', '=', 'application_urls.id')
                        ->leftJoin('exam_marks', 'exam_marks.application_id', '=', 'applications.id')
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
            $applications = $query;

            return DataTables::eloquent($applications)
                ->addIndexColumn()
                ->addColumn('roll', function ($row) {
                    return $row->application ? $row->application->roll : '';
                })
                ->addColumn('name', function ($row) {
                    return $row->application ? $row->application->name : '';
                })
                ->addColumn('url', function ($row) {
                    return "<a href='$row->url' target='_blank'>Form</a>";
                })
                ->addColumn('medical', function ($row) use ($roleId) {
                    if (in_array($roleId, [1, 3, 4, 5, 6])) {
                        return result($row->is_medical_pass);
                    } else {
                        return '';
                    }
                })
                ->addColumn('written', function ($row) use ($roleId) {
                    if (in_array($roleId, [1, 4, 5, 6]) && $row->application && $row->application->examMark) {
                        $written = $row->application->examMark;
                        $totalMark = $written->bangla + $written->english + $written->math + $written->science + $written->general_knowledge;
                        if ($written->bangla >= 8 && $written->english >= 8 && $written->math >= 8 && $written->science >= 8 && $written->general_knowledge >= 8) {
                            return '<span class="badge bg-success">Pass</span>' . ' ' . ($row->total_marks ? $row->total_marks : '');
                        } elseif ($totalMark > 2 && ($written->bangla <= 7 || $written->english <= 7 || $written->math <= 7 || $written->science <= 7 || $written->general_knowledge <= 7)) {
                            return '<span class="badge bg-danger">Fail</span>';
                        } else {
                            return '<span class="badge bg-warning">Pending</span>';
                        }
                    } else {
                        return '';
                    }
                })
                ->addColumn('final', function ($row) use ($roleId) {
                    if (in_array($roleId, [1, 5, 6])) {
                        return result($row->is_final_pass);
                    } else {
                        return '';
                    }
                })
                ->addColumn('viva', function ($row) use ($roleId) {
                    if (in_array($roleId, [1, 5, 6])) {
                        return $row->application->examMark->viva;
                    } else {
                        return '';
                    }
                })
                // ->addColumn('action', function ($row) {
                //     $btn = '';
                //     if (userCan('admin-edit')) {
                //         $btn .= view('button', ['type' => 'ajax-edit', 'route' => route('admin.admins.edit', $row->id), 'row' => $row]);
                //     }
                //     if (userCan('admin-delete')) {
                //         $btn .= view('button', ['type' => 'ajax-delete', 'route' => route('admin.admins.destroy', $row->id), 'row' => $row, 'src' => 'dt']);
                //     }
                //     return $btn;
                // })
                // ->filter(function ($query) use ($request) {
                //     if ($request->has('gender') && $request->gender != '') {
                //         $query->where('gender', $request->gender);
                //     }
                //     if ($search = $request->get('search')['value']) {
                //         $query->search($search);
                //     }
                // })
                ->rawColumns(['url', 'medical', 'written', 'final', 'viva', 'action'])
                ->make(true);
        }

        return view('admin.application-url.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function medicalPassStatus(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'id' => 'required|exists:application_urls,id',
            'is_medical_pass' => 'required|boolean',
        ]);

        $applicationUrl = ApplicationUrl::with([
            'application:id,application_url_id,post,batch,roll,name'
        ])->select('id', 'url', 'is_medical_pass')->findOrFail($request->id);

        try {
            $applicationUrl->update(['is_medical_pass' => $request->is_medical_pass]);
            return response()->json(['message' => 'The status has been updated'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Oops something went wrong, Please try again.'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreApplicationUrlRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ApplicationUrl $applicationUrl)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ApplicationUrl $applicationUrl)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateApplicationUrlRequest $request, ApplicationUrl $applicationUrl)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ApplicationUrl $applicationUrl)
    {
        //
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
