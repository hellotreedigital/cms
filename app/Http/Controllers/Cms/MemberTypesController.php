<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\MemberType;


class MemberTypesController extends Controller
{
    public function index()
    {
        $rows = MemberType::get();
        return view('cms/pages/member-types/index', compact('rows'));
    }

    public function create()
    {
        return view('cms/pages/member-types/create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'slug' => 'required',
			'title_en' => 'required',
			'title_ar' => 'required',
			'image' => 'image',

        ]);

        $row = new MemberType;
        
		$row->slug = $request->slug;
		$row->title_en = $request->title_en;
		$row->title_ar = $request->title_ar;
		$row->text_en = $request->text_en;
		$row->text_ar = $request->text_ar;
		if ($request->image) {
            $image = time() . '_' . md5(rand()) . '.' . request()->image->getClientOriginalExtension();
            $request->image->move(storage_path('app/public/member-types'), $image);
            $row->image = 'storage/member-types/' . $image;
        }
        
        $row->save();

        return redirect(env('CMS_PREFIX', 'admin') . '/member-types')->with('success', 'Record added successfully');
    }

    public function show($id)
    {
        $row = MemberType::findOrFail($id);
        return view('cms/pages/member-types/show', compact('row'));
    }

    public function edit($id)
    {
        $row = MemberType::findOrFail($id);
        return view('cms/pages/member-types/edit', compact('row'));
    }

    public function update(Request $request, $id)
    {
        $row = MemberType::findOrFail($id);

        $request->validate([
            'slug' => 'required',
			'title_en' => 'required',
			'title_ar' => 'required',
			'image' => 'image',
            'banner_image' => 'image',

        ]);

        $row->slug = $request->slug;
		$row->title_en = $request->title_en;
		$row->title_ar = $request->title_ar;
		$row->text_en = $request->text_en;
		$row->text_ar = $request->text_ar;
		if ($request->remove_file_image) {
            $row->image = '';
        } elseif ($request->image) {
            $image = time() . '_' . md5(rand()) . '.' . request()->image->getClientOriginalExtension();
            $request->image->move(storage_path('app/public/member-types'), $image);
            $row->image = 'storage/member-types/' . $image;
        }
        if ($request->remove_file_banner_image) {
            $row->banner_image = '';
        } elseif ($request->banner_image) {
            $banner_image = time() . '_' . md5(rand()) . '.' . request()->banner_image->getClientOriginalExtension();
            $request->banner_image->move(storage_path('app/public/member-types'), $banner_image);
            $row->banner_image = 'storage/member-types/' . $banner_image;
        }
        
        $row->save();

        return redirect(env('CMS_PREFIX', 'admin') . '/member-types')->with('success', 'Record edited successfully');
    }

    public function destroy($id)
    {
        $array = explode(',', $id);
        foreach ($array as $id) if ($id != 1) MemberType::destroy($id);
        return redirect(env('CMS_PREFIX', 'admin') . '/member-types')->with('success', 'Record deleted successfully');
    }

    public function orderIndex()
    {
        $rows = MemberType::orderBy('ht_pos')->get();
        return view('cms/pages/member-types/order', compact('rows'));
    }

    public function orderSubmit(Request $request)
    {
        foreach ($request->id as $key => $id) {
            $row = MemberType::findOrFail($id);
            $row->ht_pos = $request->ht_pos[$key];
            $row->save();
        }

        return redirect(env('CMS_PREFIX', 'admin') . '/member-types')->with('success', 'Records ordered successfully');
    }
}