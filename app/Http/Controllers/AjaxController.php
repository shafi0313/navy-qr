<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Traits\ApplicationTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AjaxController extends Controller
{
    use ApplicationTrait;

    public function select2(Request $request)
    {
        if ($request->ajax()) {
            switch ($request->type) {
                case 'getApplicant':
                    $response = Application::select('id', 'name', 'serial_no')
                        ->where('name', 'like', "%{$request->q}%")
                        ->orWhere('serial_no', 'like', "%{$request->q}%")
                        ->orderBy('serial_no')
                        ->limit(100)
                        ->get()->map(function ($data) {
                            return [
                                'id' => $data->id,
                                'text' => $data->name.' ('.$data->serial_no.')',
                            ];
                        })->toArray();
                    break;
                case 'getDistricts':
                    $response = Application::selectRaw('MIN(id) as id, eligible_district')
                        ->where('eligible_district', 'like', "%{$request->q}%")
                        ->groupBy('eligible_district')
                        ->orderBy('eligible_district')
                        ->limit(20)
                        ->get()
                        ->map(function ($data) {
                            return [
                                'id' => $data->eligible_district, // Using the first (min) id for each district
                                'text' => Str::ucfirst($data->eligible_district),
                            ];
                        })
                        ->toArray();
                    break;
                case 'getSSCGroups':
                    $response = Application::leftJoin('exam_marks', 'applications.id', '=', 'exam_marks.application_id')
                        ->selectRaw('MIN(applications.id) as id, applications.ssc_group')
                        ->where('applications.ssc_group', 'like', "%{$request->q}%")
                        ->where('exam_marks.viva', '>=', 0)
                        ->where('is_final_pass', 1)
                        ->groupBy('applications.ssc_group')
                        ->orderBy('applications.ssc_group')
                        ->get()
                        ->map(function ($data) {
                            return [
                                'id' => $data->ssc_group, // Using the first (min) id for each district
                                'text' => Str::ucfirst($data->ssc_group),
                            ];
                        })
                        ->toArray();
                    break;
                case 'getBranch':
                    $response = Application::leftJoin('exam_marks', 'applications.id', '=', 'exam_marks.application_id')
                        ->selectRaw('MIN(applications.id) as id, applications.candidate_designation')
                        ->where('applications.candidate_designation', 'like', "%{$request->q}%")
                        ->where('exam_marks.viva', '>=', 0)
                        ->where('is_final_pass', 1)
                        ->groupBy('applications.candidate_designation')
                        ->orderBy('applications.candidate_designation')
                        ->get()
                        ->map(function ($data) {
                            return [
                                'id' => $data->candidate_designation, // Using the first (min) id for each district
                                'text' => Str::ucfirst($data->candidate_designation),
                            ];
                        })
                        ->toArray();
                    break;
                case 'getBob':
                    $response = Application::leftJoin('exam_marks', 'applications.id', '=', 'exam_marks.application_id')
                        ->selectRaw('MIN(applications.id) as id, applications.dob')
                        ->where('applications.dob', 'like', "%{$request->q}%")
                        ->where('exam_marks.viva', '>=', 0)
                        ->where('is_final_pass', 1)
                        ->groupBy('applications.dob')
                        ->orderBy('applications.dob')
                        ->get()
                        ->map(function ($data) {
                            return [
                                'id' => $data->dob, // Using the first (min) id for each district
                                'text' => bdDate($data->dob),
                            ];
                        })
                        ->toArray();
                    break;

                case 'getGpa':
                    $response = Application::leftJoin('exam_marks', 'applications.id', '=', 'exam_marks.application_id')
                        ->selectRaw('MIN(applications.id) as id, applications.ssc_gpa')
                        ->where('applications.ssc_gpa', 'like', "%{$request->q}%")
                        ->where('exam_marks.viva', '>=', 0)
                        ->where('is_final_pass', 1)
                        ->groupBy('applications.ssc_gpa')
                        ->orderBy('applications.ssc_gpa', 'desc')
                        ->get()
                        ->map(function ($data) {
                            return [
                                'id' => $data->ssc_gpa,
                                'text' => $data->ssc_gpa,
                            ];
                        })
                        ->toArray();
                    break;
                case 'getHeight':
                    $response = Application::leftJoin('exam_marks', 'applications.id', '=', 'exam_marks.application_id')
                        ->selectRaw('MIN(applications.id) as id, applications.height')
                        ->where('applications.height', 'like', "%{$request->q}%")
                        ->where('exam_marks.viva', '>=', 0)
                        ->where('is_final_pass', 1)
                        ->groupBy('applications.height')
                        ->orderBy('applications.height')
                        ->get()
                        ->map(function ($data) {
                            return [
                                'id' => $data->height,
                                'text' => $data->height,
                            ];
                        })
                        ->toArray();
                    break;
                case 'getExamDates':
                    $response = Application::selectRaw('MIN(id) as id, exam_date')
                        ->where('exam_date', 'like', "%{$request->q}%")
                        ->groupBy('exam_date')
                        ->orderBy('exam_date', 'desc')
                        // ->limit(20)
                        ->get()
                        ->map(function ($data) {
                            return [
                                'id' => $data->exam_date, // Using the first (min) id for each district
                                'text' => bdDate($data->exam_date),
                            ];
                        })
                        ->toArray();
                    break;
                default:
                    $response = [];
                    break;
            }
            $name = preg_split('/(?=[A-Z])/', str_replace('get', '', $request->type), -1, PREG_SPLIT_NO_EMPTY);
            $name = implode(' ', $name);
            // array_unshift($response, ['id' => ' ', 'text' => 'All '.$name]);

            return $response;
        }

        return abort(404);
    }
}
