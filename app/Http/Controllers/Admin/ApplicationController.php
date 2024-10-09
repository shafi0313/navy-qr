<?php

namespace App\Http\Controllers\Admin;

use App\Models\Application;
use Illuminate\Http\Request;
use App\Traits\ApplicationTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Yajra\DataTables\Facades\DataTables;

class ApplicationController extends Controller
{
    use ApplicationTrait;

    public function index(Request $request)
    {

        // return sendOtpViaSms($mobileNumber, $otp);

        $otp = rand(1000, 9999); // Generate a 6-digit OTP
        $mobileNumber = '+8801725848515';
        // return sendOtpViaSms($mobileNumber, $otp);

        Http::get("http://smpp.revesms.com:7788/sendtext?apikey=943faf062f3d7241&secretkey=dfd0b83b&callerID=1&toUser=+8801725848515&messageContent=MESSAGE");




        // $apiKey = '943faf062f3d7241';
        // $secretKey = 'dfd0b83b';
        // $senderId = 1;
        // $message = "Your OTP code is $otp";

        // $response = Http::get('http://smpp.revesms.com:7788/sendtext', [
        //     'apikey' => $apiKey,
        //     'secretkey' => $secretKey,
        //     'callerID' => $senderId,
        //     'toUser' => $mobileNumber,
        //     'messageContent' => $message,
        // ]);

        // // Check the response to make sure the message was sent successfully
        // if ($response->successful()) {
        //     return true;
        // }

        // return false;





        // $query = Application::query();
        // return $query
        //     ->leftJoin('users', 'applications.user_id', '=', 'users.id')
        //     ->leftJoin('exam_marks', 'applications.id', '=', 'exam_marks.application_id')
        //     ->select(
        //         array_merge($this->userColumns(), $this->applicationColumns(), $this->examColumns())
        //     )
        //     ->selectRaw(
        //         $this->examSumColumns()
        //     )
        //     ->where(function ($query) {
        //         $query->where('bangla', '>=', 8)
        //             ->where('english', '>=', 8)
        //             ->where('math', '>=', 8)
        //             ->where('science', '>=', 8)
        //             ->where('general_knowledge', '>=', 8);
        //     })
        //     ->where('team', user()->team)
        //     ->orderBy('total_viva', 'desc')
        //     ->orderBy('total_marks', 'desc')->get();

        // return$query->select('id', 'candidate_designation', 'serial_no', 'name', 'eligible_district', 'remark')->get();

        if ($request->ajax()) {
            $roleId = user()->role_id;
            $query = Application::query();

            switch ($roleId) {
                case 1: // Supper Admin
                    $query->leftJoin('users', 'applications.user_id', '=', 'users.id')
                        ->leftJoin('exam_marks', 'applications.id', '=', 'exam_marks.application_id')
                        ->select(
                            array_merge($this->userColumns(), $this->applicationColumns(), $this->examColumns())
                        )
                        ->selectRaw(
                            $this->examSumColumns()
                        )
                        ->orderBy('total_marks', 'desc');
                    break;
                case 2: // Admin
                    $query->leftJoin('users', 'applications.user_id', '=', 'users.id')
                        ->leftJoin('exam_marks', 'applications.id', '=', 'exam_marks.application_id')
                        ->select(
                            array_merge($this->userColumns(), $this->applicationColumns(), $this->examColumns())
                        )
                        ->selectRaw(
                            $this->examSumColumns()
                        )
                        ->where('team', user()->team)
                        ->orderBy('total_marks', 'desc');
                    break;
                case 3: // Viva / Final Selection
                    $query->leftJoin('users', 'applications.user_id', '=', 'users.id')
                        ->leftJoin('exam_marks', 'applications.id', '=', 'exam_marks.application_id')
                        ->select(
                            array_merge($this->userColumns(), $this->applicationColumns(), $this->examColumns())
                        )
                        ->selectRaw(
                            $this->examSumColumns()
                        )
                        ->where(function ($query) {
                            $query->where('bangla', '>=', 8)
                                ->where('english', '>=', 8)
                                ->where('math', '>=', 8)
                                ->where('science', '>=', 8)
                                ->where('general_knowledge', '>=', 8);
                        })
                        ->where('team', user()->team)
                        ->orderBy('total_viva', 'desc')
                        ->orderBy('total_marks', 'desc');
                    break;
                case 4: // Final Medical
                    $query->leftJoin('users', 'applications.user_id', '=', 'users.id')
                        ->leftJoin('exam_marks', 'applications.id', '=', 'exam_marks.application_id')
                        ->select(
                            array_merge($this->userColumns(), $this->applicationColumns(), $this->examColumns())
                        )
                        ->selectRaw(
                            $this->examSumColumns()
                        )
                        ->where(function ($query) {
                            $query->where('bangla', '>=', 8)
                                ->where('english', '>=', 8)
                                ->where('math', '>=', 8)
                                ->where('science', '>=', 8)
                                ->where('general_knowledge', '>=', 8);
                        })
                        ->where('team', user()->team)
                        ->orderBy('total_marks', 'desc');
                    break;
                case 5: // Written
                    $query->leftJoin('users', 'applications.user_id', '=', 'users.id')
                        ->leftJoin('exam_marks', 'applications.id', '=', 'exam_marks.application_id')
                        ->select(
                            array_merge($this->userColumns(), $this->applicationColumns(), $this->examColumns())
                        )
                        ->selectRaw(
                            $this->examSumColumns()
                        )
                        ->where('team', user()->team)
                        ->orderBy('total_marks', 'desc');
                    break;

                case 6: // Primary Medical
                    $query->leftJoin('users', 'applications.user_id', '=', 'users.id')
                        ->select(
                            array_merge($this->userColumns(), $this->applicationColumns())
                        )
                        ->where('users.team', user()->team);
                    break;
                case 7: // Normal User
                    $query->select('id', 'candidate_designation', 'serial_no', 'name', 'eligible_district', 'photo', 'remark');
                    break;
            }
            $applications = $query;

            return DataTables::eloquent($applications)
                ->addIndexColumn()
                ->addColumn('exam_date', function ($row) {
                    return bdDate($row->exam_date);
                })
                ->addColumn('eligible_district', function ($row) {
                    return ucfirst($row->eligible_district);
                })
                ->addColumn('medical', function ($row) use ($roleId) {
                    return $this->primaryMedical($roleId, $row);
                })
                ->addColumn('written', function ($row) use ($roleId) {
                    return $this->written($roleId, $row);
                })
                ->addColumn('final', function ($row) use ($roleId) {
                    return $this->finalMedical($roleId, $row);
                })
                ->addColumn('total_viva', function ($row) use ($roleId) {
                    return $this->viva($roleId, $row);
                })
                ->filter(function ($query) use ($request) {
                    if ($request->filled('district')) {
                        $query->where('applications.eligible_district', $request->district);
                    }
                    if ($request->filled('exam_date')) {
                        $query->where('applications.exam_date', $request->exam_date);
                    }
                    if ($search = $request->get('search')['value']) {
                        $query->search($search);
                    }
                })
                ->rawColumns(['medical', 'written', 'final', 'viva', 'action'])
                ->make(true);
        }

        return view('admin.application.index');
    }
}
