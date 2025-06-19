<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\AppInstruction;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\StoreAppInstructionRequest;
use App\Http\Requests\UpdateAppInstructionRequest;

class AppInstructionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (! in_array(user()->role_id, [1])) {
            Alert::error('You are not authorized to perform this action');
        }
        if ($request->ajax()) {
            $appInstructions = AppInstruction::query();

            return DataTables::of($appInstructions)
                ->addIndexColumn()
                ->addColumn('menu_name', function ($row) {
                    return config('var.menuNames')[$row->menu_name];
                })
                ->addColumn('instruction', function ($row) {
                    return $row->instruction;
                })
                ->addColumn('is_active', function ($row) {
                    return view('button', ['type' => 'is_active', 'route' => route('admin.app_instructions.is_active', $row->id), 'row' => $row->is_active]);
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    $btn .= view('button', ['type' => 'ajax-edit', 'route' => route('admin.app-instructions.edit', $row->id), 'row' => $row]);
                    $btn .= view('button', ['type' => 'ajax-delete', 'route' => route('admin.app-instructions.destroy', $row->id), 'row' => $row, 'src' => 'dt']);

                    return $btn;
                })
                ->rawColumns(['instruction', 'is_active', 'action'])
                ->make(true);
        }

        return view('admin.app-instruction.index');
    }

    public function status(AppInstruction $appInstruction)
    {
        if (! in_array(user()->role_id, [1, 2])) {
            return response()->json(['message' => 'You can not edit'], 500);
        }
        $appInstruction->is_active = $appInstruction->is_active == 1 ? 0 : 1;
        try {
            $appInstruction->save();

            return response()->json(['message' => 'The status has been updated'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Oops something went wrong, Please try again.'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAppInstructionRequest $request)
    {
        $data = $request->validated();

        try {
            AppInstruction::updateOrCreate([
                'menu_name' => $data['menu_name']
            ], $data);

            return response()->json(['message' => 'The information has been inserted'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Oops something went wrong, Please try again.'], 500);
        }
    }

    public function show(AppInstruction $appInstruction)
    {
        $instruction = AppInstruction::findOrFail($appInstruction->id);
        return view('admin.app-instruction.show', compact('instruction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, AppInstruction $appInstruction)
    {
        if (! in_array(user()->role_id, [1, 2])) {
            return response()->json(['message' => 'You cannot edit'], 403);
        }

        if ($request->ajax()) {
            $modal = view('admin.app-instruction.edit', ['appInstruction' => $appInstruction])->render();
            return response()->json(['modal' => $modal], 200);
        }

        return abort(403, 'Unauthorized action.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAppInstructionRequest $request, AppInstruction $appInstruction)
    {
        if (! in_array(user()->role_id, [1, 2])) {
            return response()->json(['message' => 'You can not edit'], 500);
        }
        $data = $request->validated();
        try {
            $appInstruction->update($data);
            return response()->json(['message' => 'The information has been updated'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Oops, something went wrong. Please try again later.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AppInstruction $appInstruction)
    {
        if (! in_array(user()->role_id, [1, 2])) {
            return response()->json(['message' => 'You can not edit'], 500);
        }
        try {
            $appInstruction->delete();

            return response()->json(['message' => 'The information has been deleted'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Oops something went wrong, Please try again'], 500);
        }
    }
}
