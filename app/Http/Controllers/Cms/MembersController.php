<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\MemberType;
use App\MemberCategory;
use App\MemberCountry;
use App\Member;


class MembersController extends Controller
{
    public function index()
    {
        $member_types = $this->getMemberTypes();
        $member_categories = $this->getMemberCategories();
        $member_countries = $this->getMemberCountries();
        $rows = Member::get();
        return view('cms/pages/members/index', compact('rows', 'member_types', 'member_categories', 'member_countries'));
    }

    public function create()
    {
        $member_types = $this->getMemberTypes();
        $member_categories = $this->getMemberCategories();
        $member_countries = $this->getMemberCountries();
        return view('cms/pages/members/create', compact('member_types', 'member_categories', 'member_countries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_en' => 'required',
            'name_ar' => 'required',
			'title_en' => 'required',
            'title_ar' => 'required',
			'date_from' => 'required|numeric',
			'date_to' => 'required|numeric',
			'text_en' => 'required',
            'text_ar' => 'required',
			'member_type_id' => 'required',
            'image' => 'required|image',
        ]);

        if ($request['member_type_id'] != 1) {
            $request->validate([
                'member_category_id' => 'required',
                'member_country_id' => 'required',
            ]);
        } else {
            $request->member_category_id = null;
            $request->member_country_id = null;
        }

        $row = new Member;
        
		$row->name_en = $request->name_en;
        $row->name_ar = $request->name_ar;
		$row->title_en = $request->title_en;
        $row->title_ar = $request->title_ar;
		$row->date_from = $request->date_from;
		$row->date_to = $request->date_to;
		$row->text_en = $request->text_en;
        $row->text_ar = $request->text_ar;
        $row->twitter_link = $request->twitter_link;
		$row->member_type_id = $request->member_type_id;
		$row->member_category_id = $request->member_category_id;
		$row->member_country_id = $request->member_country_id;
        if ($request->image) {
            $image = time() . '_' . md5(rand()) . '.' . request()->image->getClientOriginalExtension();
            $request->image->move(storage_path('app/public/members'), $image);
            $row->image = 'storage/members/' . $image;
        }
		
        $row->save();

        return redirect(env('CMS_PREFIX', 'admin') . '/members')->with('success', 'Record added successfully');
    }

    public function show($id)
    {
        $member_types = $this->getMemberTypes();
        $member_categories = $this->getMemberCategories();
        $member_countries = $this->getMemberCountries();
        $row = Member::findOrFail($id);
        return view('cms/pages/members/show', compact('row', 'member_types', 'member_categories', 'member_countries'));
    }

    public function edit($id)
    {
        $member_types = $this->getMemberTypes();
        $member_categories = $this->getMemberCategories();
        $member_countries = $this->getMemberCountries();
        $row = Member::findOrFail($id);
        return view('cms/pages/members/edit', compact('row', 'member_types', 'member_categories', 'member_countries'));
    }

    public function update(Request $request, $id)
    {
        $row = Member::findOrFail($id);

        $request->validate([
            'name_en' => 'required',
            'name_ar' => 'required',
			'title_en' => 'required',
            'title_ar' => 'required',
			'date_from' => 'required',
			'date_to' => 'required',
			'text_en' => 'required',
            'text_ar' => 'required',
			'member_type_id' => 'required',
            'image' => 'image',
        ]);

        if ($request['member_type_id'] != 1) {
            $request->validate([
                'member_category_id' => 'required',
                'member_country_id' => 'required',
            ]);
        } else {
            $request->member_category_id = null;
            $request->member_country_id = null;
        }

        $row->name_en = $request->name_en;
        $row->name_ar = $request->name_ar;
		$row->title_en = $request->title_en;
        $row->title_ar = $request->title_ar;
		$row->date_from = $request->date_from;
		$row->date_to = $request->date_to;
		$row->text_en = $request->text_en;
        $row->text_ar = $request->text_ar;
        $row->twitter_link = $request->twitter_link;
		$row->member_type_id = $request->member_type_id;
		$row->member_category_id = $request->member_category_id;
		$row->member_country_id = $request->member_country_id;
        if ($request->image) {
            $image = time() . '_' . md5(rand()) . '.' . request()->image->getClientOriginalExtension();
            $request->image->move(storage_path('app/public/members'), $image);
            $row->image = 'storage/members/' . $image;
        }
		
        $row->save();

        return redirect(env('CMS_PREFIX', 'admin') . '/members')->with('success', 'Record edited successfully');
    }

    public function destroy($id)
    {
        $array = explode(',', $id);
        foreach ($array as $id) Member::destroy($id);
        return redirect(env('CMS_PREFIX', 'admin') . '/members')->with('success', 'Record deleted successfully');
    }

    public function getMemberTypes()
    {
        $member_types = [];
        $member_types_db = MemberType::get()->toArray();
        foreach ($member_types_db as $member_type_db) $member_types[$member_type_db['id']] = $member_type_db;
        return $member_types;
    }

    public function getMemberCategories()
    {
        $member_categories = [];
        $member_categories_db = MemberCategory::get()->toArray();
        foreach ($member_categories_db as $member_category_db) $member_categories[$member_category_db['id']] = $member_category_db;
        return $member_categories;
    }

    public function getMemberCountries()
    {
        $member_countries = [];
        $member_countries_db = MemberCountry::get()->toArray();
        foreach ($member_countries_db as $member_country_db) $member_countries[$member_country_db['id']] = $member_country_db;
        return $member_countries;
    }

    public function orderIndex()
    {
        $rows = Member::orderBy('ht_pos')->get();
        return view('cms/pages/members/order', compact('rows'));
    }

    public function orderSubmit(Request $request)
    {
        foreach ($request->id as $key => $id) {
            $row = Member::findOrFail($id);
            $row->ht_pos = $request->ht_pos[$key];
            $row->save();
        }

        return redirect(env('CMS_PREFIX', 'admin') . '/members')->with('success', 'Records ordered successfully');
    }
}