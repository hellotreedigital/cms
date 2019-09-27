<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ActivitySlide;
use App\Activity;


class ActivitySlidesController extends Controller
{
    public function index()
    {
        $rows = ActivitySlide::orderBy('ht_pos')->get();
        $activities = [];
	    $activities_db = Activity::get()->toArray();
	    foreach ($activities_db as $single_activities_db) $activities[$single_activities_db['id']] = $single_activities_db;
        return view('cms/pages/activity-slides/index', compact('rows', 'activities'));
    }

    public function create()
    {
        $activities = Activity::get()->toArray();
        return view('cms/pages/activity-slides/create', compact('activities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'activity_id' => 'required',
			'image' => 'required|image',
        ]);

        $row = new ActivitySlide;
        
		$row->activity_id = $request->activity_id;
		if ($request->image) {
            $image = time() . '_' . md5(rand()) . '.' . request()->image->getClientOriginalExtension();
            $request->image->move(storage_path('app/public/activity-slides'), $image);
            $row->image = 'storage/activity-slides/' . $image;
        }
        
        $row->save();

        return redirect(env('CMS_PREFIX', 'admin') . '/activity-slides')->with('success', 'Record added successfully');
    }

    public function show($id)
    {
        $row = ActivitySlide::findOrFail($id);
        $activities = [];
	    $activities_db = Activity::get()->toArray();
	    foreach ($activities_db as $single_activities_db) $activities[$single_activities_db['id']] = $single_activities_db;
        return view('cms/pages/activity-slides/show', compact('row', 'activities'));
    }

    public function edit($id)
    {
        $row = ActivitySlide::findOrFail($id);
        $activities = Activity::get()->toArray();
        return view('cms/pages/activity-slides/edit', compact('row', 'activities'));
    }

    public function update(Request $request, $id)
    {
        $row = ActivitySlide::findOrFail($id);

        $request->validate([
            'activity_id' => 'required',
			'image' => 'required_with:remove_file_image|image',
        ]);

        $row->activity_id = $request->activity_id;
		if ($request->remove_file_image) {
			$row->image = '';
		} elseif ($request->image) {
            $image = time() . '_' . md5(rand()) . '.' . request()->image->getClientOriginalExtension();
            $request->image->move(storage_path('app/public/activity-slides'), $image);
            $row->image = 'storage/activity-slides/' . $image;
        }
        
        $row->save();

        return redirect(env('CMS_PREFIX', 'admin') . '/activity-slides')->with('success', 'Record edited successfully');
    }

    public function destroy($id)
    {
        $array = explode(',', $id);
        foreach ($array as $id) ActivitySlide::destroy($id);
        return redirect(env('CMS_PREFIX', 'admin') . '/activity-slides')->with('success', 'Record deleted successfully');
    }

    public function orderIndex()
    {
        $rows = ActivitySlide::orderBy('ht_pos')->get();
        return view('cms/pages/activity-slides/order', compact('rows'));
    }

    public function orderSubmit(Request $request)
    {
        foreach ($request->id as $key => $id) {
            $row = ActivitySlide::findOrFail($id);
            $row->ht_pos = $request->ht_pos[$key];
            $row->save();
        }

        return redirect(env('CMS_PREFIX', 'admin') . '/activity-slides')->with('success', 'Records ordered successfully');
    }
}