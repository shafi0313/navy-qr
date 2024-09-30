<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ApplicationUrl;

class AjaxController extends Controller
{
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
                                'text' => $data->name . ' (' . $data->serial_no . ')',
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
                case 'getExamDates':
                    $response = Application::selectRaw('MIN(id) as id, exam_date')
                        ->where('exam_date', 'like', "%{$request->q}%")
                        ->groupBy('exam_date')
                        ->orderBy('exam_date', 'desc')
                        // ->limit(20)
                        ->get()
                        ->map(function ($data) {
                            return [
                                'id' => $data->id, // Using the first (min) id for each district
                                'text' => $data->exam_date,
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
