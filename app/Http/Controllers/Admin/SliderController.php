<?php

namespace App\Http\Controllers\Admin;

use App\Models\Slider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\StoreSliderRequest;
use App\Http\Requests\UpdateSliderRequest;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($error = $this->authorize('slider-manage')) {
            return $error;
        }

        if ($request->ajax()) {
            $sliders = Slider::query();
            return DataTables::of($sliders)
                ->addIndexColumn()
                ->addColumn('content', function ($row) {
                    return '<textarea class="form-control>' . $row->content . '</textarea>';
                })
                ->addColumn('image', function ($row) {
                    $path = imagePath('slider', $row->image);
                    return '<img src="' . $path . '" width="70px" alt="image">';
                })
                ->addColumn('is_active', function ($row) {
                    if (userCan('slider-edit')) {
                        return view('button', ['type' => 'is_active', 'route' => route('admin.sliders.is_active', $row->id), 'row' => $row->is_active]);
                    }
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    if (userCan('slider-edit')) {
                        $btn .= view('button', ['type' => 'ajax-edit', 'route' => route('admin.sliders.edit', $row->id), 'row' => $row]);
                    }
                    if (userCan('slider-delete')) {
                        $btn .= view('button', ['type' => 'ajax-delete', 'route' => route('admin.sliders.destroy', $row->id), 'row' => $row, 'src' => 'dt']);
                    }
                    return $btn;
                })
                ->rawColumns(['content', 'image', 'is_active', 'action'])
                ->make(true);
        }
        return view('admin.slider.index');
    }

    function status(Slider $slider)
    {
        if ($error = $this->authorize('slider-edit')) {
            return $error;
        }
        $slider->is_active = $slider->is_active  == 1 ? 0 : 1;
        try {
            $slider->save();
            return response()->json(['message' => 'The status has been updated'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Oops something went wrong, Please try again.'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSliderRequest $request)
    {
        if ($error = $this->authorize('slider-add')) {
            return $error;
        }
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = imgWebpStore($request->image, 'slider', [1920, 1080]);
        }

        try {
            Slider::create($data);
            return response()->json(['message' => 'The information has been inserted'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Oops something went wrong, Please try again.'], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Slider $slider)
    {
        if ($error = $this->authorize('slider-edit')) {
            return $error;
        }
        if ($request->ajax()) {
            $modal = view('admin.slider.edit')->with(['slider' => $slider])->render();
            return response()->json(['modal' => $modal], 200);
        }
        return abort(500);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSliderRequest $request, Slider $slider)
    {
        if ($error = $this->authorize('slider-add')) {
            return $error;
        }
        $data = $slider->validated();
        $image = $slider->image;
        if ($request->hasFile('image')) {
            $data['image'] = imgWebpUpdate($request->image, 'user', [1920, 1080], $image);
        }
        try {
            $slider->update($data);
            return response()->json(['message' => 'The information has been updated'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Oops something went wrong, Please try again'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Slider $slider)
    {
        if ($error = $this->authorize('slider-delete')) {
            return $error;
        }
        try {
            imgUnlink('slider', $slider->image);
            $slider->delete();
            return response()->json(['message' => 'The information has been deleted'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Oops something went wrong, Please try again'], 500);
        }
    }
}
