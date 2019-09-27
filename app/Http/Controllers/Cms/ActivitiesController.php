<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Activity;
use App\ActivityCategory;
use App\ActivityCountry;


class ActivitiesController extends Controller
{
    public function index()
    {
        $rows = Activity::orderBy('ht_pos')->get();
        $activity_categories = [];
	    $activity_categories_db = ActivityCategory::get()->toArray();
	    foreach ($activity_categories_db as $single_activity_categories_db) $activity_categories[$single_activity_categories_db['id']] = $single_activity_categories_db;$activity_countries = [];
	    $activity_countries_db = ActivityCountry::get()->toArray();
	    foreach ($activity_countries_db as $single_activity_countries_db) $activity_countries[$single_activity_countries_db['id']] = $single_activity_countries_db;
        return view('cms/pages/activities/index', compact('rows', 'activity_categories', 'activity_countries'));
    }

    public function create()
    {
        $activity_categories = ActivityCategory::get()->toArray();$activity_countries = ActivityCountry::get()->toArray();
        return view('cms/pages/activities/create', compact('activity_categories', 'activity_countries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'activity_category_id' => 'required',
			'activity_country_id' => 'required',
			'slug' => 'required|unique:activities',
			'title_en' => 'required',
			'title_ar' => 'required',
			'text_en' => 'required',
			'text_ar' => 'required',
			'date' => 'required',
			'image' => 'required|image',
        ]);

        $row = new Activity;
        
		$row->activity_category_id = $request->activity_category_id;
		$row->activity_country_id = $request->activity_country_id;
		$row->slug = $request->slug;
		$row->title_en = $request->title_en;
		$row->title_ar = $request->title_ar;
		$row->text_en = $request->text_en;
		$row->text_ar = $request->text_ar;
		$row->date = $request->date;
		if ($request->image) {
            $image = time() . '_' . md5(rand()) . '.' . request()->image->getClientOriginalExtension();
            $request->image->move(storage_path('app/public/activities'), $image);
            $row->image = 'storage/activities/' . $image;
        }
        
        $row->save();

        return redirect(env('CMS_PREFIX', 'admin') . '/activities')->with('success', 'Record added successfully');
    }

    public function show($id)
    {
        $row = Activity::findOrFail($id);
        $activity_categories = [];
	    $activity_categories_db = ActivityCategory::get()->toArray();
	    foreach ($activity_categories_db as $single_activity_categories_db) $activity_categories[$single_activity_categories_db['id']] = $single_activity_categories_db;$activity_countries = [];
	    $activity_countries_db = ActivityCountry::get()->toArray();
	    foreach ($activity_countries_db as $single_activity_countries_db) $activity_countries[$single_activity_countries_db['id']] = $single_activity_countries_db;
        return view('cms/pages/activities/show', compact('row', 'activity_categories', 'activity_countries'));
    }

    public function edit($id)
    {
        $row = Activity::findOrFail($id);
        $activity_categories = ActivityCategory::get()->toArray();$activity_countries = ActivityCountry::get()->toArray();
        return view('cms/pages/activities/edit', compact('row', 'activity_categories', 'activity_countries'));
    }

    public function update(Request $request, $id)
    {
        $row = Activity::findOrFail($id);

        $request->validate([
            'activity_category_id' => 'required',
			'activity_country_id' => 'required',
			'slug' => 'required|unique:activities,slug,' . $row->id,
			'title_en' => 'required',
			'title_ar' => 'required',
			'text_en' => 'required',
			'text_ar' => 'required',
			'date' => 'required',
			'image' => 'required_with:remove_file_image|image',
        ]);

        $row->activity_category_id = $request->activity_category_id;
		$row->activity_country_id = $request->activity_country_id;
		$row->slug = $request->slug;
		$row->title_en = $request->title_en;
		$row->title_ar = $request->title_ar;
		$row->text_en = $request->text_en;
		$row->text_ar = $request->text_ar;
		$row->date = $request->date;
		if ($request->remove_file_image) {
			$row->image = '';
		} elseif ($request->image) {
            $image = time() . '_' . md5(rand()) . '.' . request()->image->getClientOriginalExtension();
            $request->image->move(storage_path('app/public/activities'), $image);
            $row->image = 'storage/activities/' . $image;
        }
        
        $row->save();

        return redirect(env('CMS_PREFIX', 'admin') . '/activities')->with('success', 'Record edited successfully');
    }

    public function destroy($id)
    {
        $array = explode(',', $id);
        foreach ($array as $id) Activity::destroy($id);
        return redirect(env('CMS_PREFIX', 'admin') . '/activities')->with('success', 'Record deleted successfully');
    }

    public function orderIndex()
    {
        $rows = Activity::orderBy('ht_pos')->get();
        return view('cms/pages/activities/order', compact('rows'));
    }

    public function orderSubmit(Request $request)
    {
        foreach ($request->id as $key => $id) {
            $row = Activity::findOrFail($id);
            $row->ht_pos = $request->ht_pos[$key];
            $row->save();
        }

        return redirect(env('CMS_PREFIX', 'admin') . '/activities')->with('success', 'Records ordered successfully');
    }
}