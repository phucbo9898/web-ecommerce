<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Slide\StoreRequest;
use App\Http\Requests\Slide\UpdateRequest;
use App\Models\Slide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SlideController extends Controller
{
    public function index()
    {
        $slides = Slide::paginate(10);

        return view('backend.slide.index', compact('slides'));
    }

    public function create()
    {
        return view('backend.slide.create');
    }

    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();
            if (!empty($request->file('image'))) {
                $checkExtensionImage = checkExtensionImage($request->file('image'));
                if (!$checkExtensionImage) {
                    return redirect()->back()->withInput()->with('error', __('Only PNG, JPEG and JPG files can be uploaded.'));
                }
            }

            $data = $request->all();
            if ($request->hasFile('image')) {     // image
                $imageUpload = $request->file('image');
                //get name file
                $name = $imageUpload->getClientOriginalName();
                //random name file for save database
                $nameImage = Str::random(3) . "_" . $name;
                // save file
                $imageUpload->move(public_path('/upload/slide/image/'), $nameImage);
                $data['image'] = '/upload/slide/image/' . $nameImage;
            }
            $handleData = $this->prepareSlide($data);
            Slide::create($handleData);

            DB::commit();
            return redirect()->route('admin.slide.index')->with('success', 'Đã thêm 1 Slide!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::debug($e);
            return redirect()->back()->withInput()->with('error', 'Thêm Slide không thành công');
        }
    }

    public function edit($id)
    {
        $slide = Slide::where('id', $id)->first();
        if (!$slide) {
            return back()->with('error', __('The requested resource is not available'));
        }

        return view('backend.slide.edit', compact('slide'));
    }

    public function update(UpdateRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $slide = Slide::where('id', $id)->first();
            if (!$slide) {
                return redirect()->route('admin.slide.index')->with('error', __('The requested resource is not available'));
            }

            if (!empty($request->file('image'))) {
                $checkExtensionImage = checkExtensionImage($request->file('image'));
                if (!$checkExtensionImage) {
                    return redirect()->back()->withInput()->with('error', __('Only PNG, JPEG and JPG files can be uploaded.'));
                }
            }

            $data = $request->all();
            if ($request->hasFile('image')) {     // image
                $imageUpload = $request->file('image');
                //get name file
                $name = $imageUpload->getClientOriginalName();
                //random name file for save database
                $nameImage = Str::random(3) . "_" . $name;
                // save file
                $imageUpload->move(public_path('/upload/slide/image/'), $nameImage);
                $data['image'] = '/upload/slide/image/' . $nameImage;
            } else {
                $data['image'] = $slide->image;
            }
            $handleData = $this->prepareSlide($data);
            $slide->update($handleData);

            DB::commit();
            return redirect()->route('admin.slide.index')->with('success', 'Đã sửa thành công slide id số ' . $slide->id . '!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::debug($e);
            return redirect()->back()->with('error', 'Sửa không thành công slide id số ' . $slide->id . '!');
        }
    }

    public function handle(Request $request, $action, $id)
    {
        try {
            $slide = Slide::where('id', $id)->first();
            switch ($action) {
                case 'delete':
                    $slide->delete();
                    $request->session()->flash('success', 'Đã xóa thành công slide mang ID số ' . $id . '!');
                    break;

                default:
                    dd("Lỗi r");
                    break;
            }
            return redirect()->route('admin.slide.index');
        } catch (\Exception $exception) {
            Log::debug($exception->getMessage());
        }
    }

    private function prepareSlide(array $data)
    {
        $slide = [
            'name' => $data['name'] ?? '',
            'image' => $data['image'] ?? ''
        ];

        return $slide;
    }
}
