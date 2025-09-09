<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Attribute\StoreRequest;
use App\Http\Requests\Attribute\UpdateRequest;
use App\Models\Attribute;
use App\Models\CategoryAttribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class AttributeController extends Controller
{
    public function index()
    {
        $attributes = Attribute::orderBy('id', 'desc')->get();

        return view('backend.attribute.index', compact('attributes'));
    }

    public function create()
    {
        return view('backend.attribute.create');
    }

    public function store(StoreRequest $request)
    {
        try {
            $data = $request->all();
            if ($data['value']) {
                $arrayAttributeValue = explode(';', $data['value']);
                $checkValueInArray = array_unique($arrayAttributeValue);
                if (count($checkValueInArray) < count($arrayAttributeValue)) {
                    return back()->withInput()->with('sameValue', 'The input attribute values ​​cannot be the same.');
                }
            }

            DB::beginTransaction();
            Attribute::create([
                'name' => $data['name'] ?? '',
                'slug' => Str::slug($data['name'] ?? ''),
                'type' => $data['type'] ?? '',
                'value' => $data['value'] ?? '',
            ]);

            DB::commit();
            return redirect()->route('admin.attribute.index')->with('success', 'Create success');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::debug($e);
            return redirect()->back()->with('error', 'Create failed');
        }
    }

    public function edit($id)
    {
        $attribute = Attribute::where('id', $id)->first();
        if (!$attribute) {
            return back()->with('error', __('The requested resource is not available'));
        }

        return view('backend.attribute.edit', compact('attribute'));
    }

    public function update(UpdateRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $attribute = Attribute::where('id', $id)->first();
            if (!$attribute) {
                return redirect()->route('admin.attribute.index')->with('error', __('The requested resource is not available'));
            }

            $data = $request->all();
            //Check same value
            if ($data['value']) {
                $arrayAttributeValue = explode(';', $data['value']);
                $checkValueInArray = array_unique($arrayAttributeValue);
                if (count($checkValueInArray) < count($arrayAttributeValue)) {
                    return back()->withInput()->with('sameValue', 'The input attribute values ​​cannot be the same.');
                }
            }

            $attribute->update([
                'name' => $data['name'] ?? '',
                'slug' => Str::slug($data['name'] ?? ''),
                'type' => $data['type'] ?? '',
                'value' => $data['value'] ?? '',
            ]);
            DB::commit();
            return redirect()->route('admin.attribute.index')->with('success', 'Update success!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::debug($e);
            return redirect()->back()->with('error', 'Update failed!');
        }
    }

    public function handle(Request $request, $action, $id)
    {
        //get Attribute
        $attribute = Attribute::where('id', $id)->first();
        if (!$attribute) {
            return redirect()->route('admin.attribute.index')->with('error', __('The requested resource is not available'));
        }
        try {
            DB::beginTransaction();
            switch ($action) {
                case 'delete':
                    CategoryAttribute::where('attribute_id', $id)->delete();
                    $attribute->delete();
                    DB::commit();
                    $request->session()->flash('success', 'Delete success!');
                    break;
                default:
                    dd("Lỗi rồi");
                    break;
            }
            return redirect()->route('admin.attribute.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::debug($e);
        }
    }
}
