<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\MemberCategory;


class MemberCategoriesController extends Controller
{
    public function index()
    {
        $rows = MemberCategory::orderBy('ht_pos')->get();
        
        return view('cms/pages/member-categories/index', compact('rows'));
    }

    public function create()
    {
        
        return view('cms/pages/member-categories/create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title_en' => 'required',
			'title_ar' => 'required',
        ]);

        $row = new MemberCategory;
        
		$row->title_en = $request->title_en;
		$row->title_ar = $request->title_ar;
		
        $row->save();

        return redirect(env('CMS_PREFIX', 'admin') . '/member-categories')->with('success', 'Record added successfully');
    }

    public function show($id)
    {
        $row = MemberCategory::findOrFail($id);
        return view('cms/pages/member-categories/show', compact('row'));
    }

    public function edit($id)
    {
        $row = MemberCategory::findOrFail($id);
        
        return view('cms/pages/member-categories/edit', compact('row'));
    }

    public function update(Request $request, $id)
    {
        $row = MemberCategory::findOrFail($id);

        $request->validate([
            'title_en' => '',
			'title_ar' => '',
        ]);

        $row->title_en = $request->title_en;
		$row->title_ar = $request->title_ar;
		
        $row->save();

        return redirect(env('CMS_PREFIX', 'admin') . '/member-categories')->with('success', 'Record edited successfully');
    }

    public function destroy($id)
    {
        $array = explode(',', $id);
        foreach ($array as $id) MemberCategory::destroy($id);
        return redirect(env('CMS_PREFIX', 'admin') . '/member-categories')->with('success', 'Record deleted successfully');
    }

    public function orderIndex()
    {
        $rows = MemberCategory::orderBy('ht_pos')->get();
        return view('cms/pages/member-categories/order', compact('rows'));
    }

    public function orderSubmit(Request $request)
    {
        foreach ($request->id as $key => $id) {
            $row = MemberCategory::findOrFail($id);
            $row->ht_pos = $request->ht_pos[$key];
            $row->save();
        }

        return redirect(env('CMS_PREFIX', 'admin') . '/member-categories')->with('success', 'Records ordered successfully');
    }
}