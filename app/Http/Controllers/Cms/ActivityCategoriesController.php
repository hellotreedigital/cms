<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ActivityCategory;


class ActivityCategoriesController extends Controller
{
    public function index()
    {
        $rows = ActivityCategory::orderBy('ht_pos')->get();
        
        return view('cms/pages/activity-categories/index', compact('rows'));
    }

    public function create()
    {
        
        return view('cms/pages/activity-categories/create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title_en' => 'required',
			'title_ar' => 'required',
        ]);

        $row = new ActivityCategory;
        
		$row->title_en = $request->title_en;
		$row->title_ar = $request->title_ar;
		
        $row->save();

        return redirect(env('CMS_PREFIX', 'admin') . '/activity-categories')->with('success', 'Record added successfully');
    }

    public function show($id)
    {
        $row = ActivityCategory::findOrFail($id);
        return view('cms/pages/activity-categories/show', compact('row'));
    }

    public function edit($id)
    {
        $row = ActivityCategory::findOrFail($id);
        
        return view('cms/pages/activity-categories/edit', compact('row'));
    }

    public function update(Request $request, $id)
    {
        $row = ActivityCategory::findOrFail($id);

        $request->validate([
            'title_en' => '',
			'title_ar' => '',
        ]);

        $row->title_en = $request->title_en;
		$row->title_ar = $request->title_ar;
		
        $row->save();

        return redirect(env('CMS_PREFIX', 'admin') . '/activity-categories')->with('success', 'Record edited successfully');
    }

    public function destroy($id)
    {
        $array = explode(',', $id);
        foreach ($array as $id) ActivityCategory::destroy($id);
        return redirect(env('CMS_PREFIX', 'admin') . '/activity-categories')->with('success', 'Record deleted successfully');
    }

    public function orderIndex()
    {
        $rows = ActivityCategory::orderBy('ht_pos')->get();
        return view('cms/pages/activity-categories/order', compact('rows'));
    }

    public function orderSubmit(Request $request)
    {
        foreach ($request->id as $key => $id) {
            $row = ActivityCategory::findOrFail($id);
            $row->ht_pos = $request->ht_pos[$key];
            $row->save();
        }

        return redirect(env('CMS_PREFIX', 'admin') . '/activity-categories')->with('success', 'Records ordered successfully');
    }
}