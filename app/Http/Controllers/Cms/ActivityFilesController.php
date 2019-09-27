<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ActivityFile;
use App\Activity;


class ActivityFilesController extends Controller
{
    public function index()
    {
        $rows = ActivityFile::orderBy('ht_pos')->get();
        $activities = [];
	    $activities_db = Activity::get()->toArray();
	    foreach ($activities_db as $single_activities_db) $activities[$single_activities_db['id']] = $single_activities_db;
        return view('cms/pages/activity-files/index', compact('rows', 'activities'));
    }

    public function create()
    {
        $activities = Activity::get()->toArray();
        return view('cms/pages/activity-files/create', compact('activities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'activity_id' => 'required',
			'image' => 'image',
			'title_en' => 'required',
			'title_ar' => 'required',
			'text_en' => '',
			'text_ar' => 'required',
			'file_en' => 'required',
			'file_ar' => 'required',
        ]);

        $row = new ActivityFile;
        
		$row->activity_id = $request->activity_id;
		if ($request->image) {
            $image = time() . '_' . md5(rand()) . '.' . request()->image->getClientOriginalExtension();
            $request->image->move(storage_path('app/public/activity-files'), $image);
            $row->image = 'storage/activity-files/' . $image;
        }
        $row->title_en = $request->title_en;
		$row->title_ar = $request->title_ar;
		$row->text_en = $request->text_en;
		$row->text_ar = $request->text_ar;
		if ($request->file_en) {
            $image = time() . '_' . md5(rand()) . '.' . request()->file_en->getClientOriginalExtension();
            $request->file_en->move(storage_path('app/public/activity-files'), $image);
            $row->file_en = 'storage/activity-files/' . $image;
        }
        if ($request->file_ar) {
            $image = time() . '_' . md5(rand()) . '.' . request()->file_ar->getClientOriginalExtension();
            $request->file_ar->move(storage_path('app/public/activity-files'), $image);
            $row->file_ar = 'storage/activity-files/' . $image;
        }
        
        $row->save();

        return redirect(env('CMS_PREFIX', 'admin') . '/activity-files')->with('success', 'Record added successfully');
    }

    public function show($id)
    {
        $row = ActivityFile::findOrFail($id);
        return view('cms/pages/activity-files/show', compact('row'));
    }

    public function edit($id)
    {
        $row = ActivityFile::findOrFail($id);
        $activities = Activity::get()->toArray();
        return view('cms/pages/activity-files/edit', compact('row', 'activities'));
    }

    public function update(Request $request, $id)
    {
        $row = ActivityFile::findOrFail($id);

        $request->validate([
            'activity_id' => '',
			'image' => 'image',
			'title_en' => '',
			'title_ar' => '',
			'text_en' => '',
			'text_ar' => '',
			'file_en' => 'required_with:remove_file_file_en',
			'file_ar' => 'required_with:remove_file_file_ar',
        ]);

        $row->activity_id = $request->activity_id;
		if ($request->remove_file_image) {
			$row->image = '';
		} elseif ($request->image) {
            $image = time() . '_' . md5(rand()) . '.' . request()->image->getClientOriginalExtension();
            $request->image->move(storage_path('app/public/activity-files'), $image);
            $row->image = 'storage/activity-files/' . $image;
        }
        $row->title_en = $request->title_en;
		$row->title_ar = $request->title_ar;
		$row->text_en = $request->text_en;
		$row->text_ar = $request->text_ar;
		if ($request->remove_file_file_en) {
			$row->file_en = '';
		} elseif ($request->file_en) {
            $image = time() . '_' . md5(rand()) . '.' . request()->file_en->getClientOriginalExtension();
            $request->file_en->move(storage_path('app/public/activity-files'), $image);
            $row->file_en = 'storage/activity-files/' . $image;
        }
        if ($request->remove_file_file_ar) {
			$row->file_ar = '';
		} elseif ($request->file_ar) {
            $image = time() . '_' . md5(rand()) . '.' . request()->file_ar->getClientOriginalExtension();
            $request->file_ar->move(storage_path('app/public/activity-files'), $image);
            $row->file_ar = 'storage/activity-files/' . $image;
        }
        
        $row->save();

        return redirect(env('CMS_PREFIX', 'admin') . '/activity-files')->with('success', 'Record edited successfully');
    }

    public function destroy($id)
    {
        $array = explode(',', $id);
        foreach ($array as $id) ActivityFile::destroy($id);
        return redirect(env('CMS_PREFIX', 'admin') . '/activity-files')->with('success', 'Record deleted successfully');
    }

    public function orderIndex()
    {
        $rows = ActivityFile::orderBy('ht_pos')->get();
        return view('cms/pages/activity-files/order', compact('rows'));
    }

    public function orderSubmit(Request $request)
    {
        foreach ($request->id as $key => $id) {
            $row = ActivityFile::findOrFail($id);
            $row->ht_pos = $request->ht_pos[$key];
            $row->save();
        }

        return redirect(env('CMS_PREFIX', 'admin') . '/activity-files')->with('success', 'Records ordered successfully');
    }
}