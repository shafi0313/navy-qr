<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApplicationUrl;

class AjaxController extends Controller
{
    public function select2(Request $request)
    {
        if ($request->ajax()) {
            switch ($request->type) {
                case 'getApplicant':
                    $response = ApplicationUrl::with([
                        'application' => function ($q) use ($request) {
                            $q->select('id', 'application_url_id', 'post', 'batch', 'roll', 'name')
                                ->where('name', 'like', "%{$request->q}%")
                                ->orWhere('roll', 'like', "%{$request->q}%");
                        }
                    ])->select('id', 'is_medical_pass')->where('is_medical_pass', 1)
                        ->limit(100)
                        ->get()->map(function ($data) {
                            return [
                                'id' => $data->application->id,
                                'text' => $data->application->name . ' (' . $data->application->roll . ')',
                            ];
                        })->toArray();
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
