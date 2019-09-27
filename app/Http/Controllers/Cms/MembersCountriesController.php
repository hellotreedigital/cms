<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\MemberCountry;


class MembersCountriesController extends Controller
{
    public function index()
    {
        $rows = MemberCountry::get();
        return view('cms/pages/member-countries/index', compact('rows'));
    }

    public function create()
    {
        return view('cms/pages/member-countries/create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title_en' => 'required',
			'title_ar' => 'required',

        ]);

        $row = new MemberCountry;
        
		$row->title_en = $request->title_en;
		$row->title_ar = $request->title_ar;
		
        $row->save();

        return redirect(env('CMS_PREFIX', 'admin') . '/member-countries')->with('success', 'Record added successfully');
    }

    public function show($id)
    {
        $row = MemberCountry::findOrFail($id);
        return view('cms/pages/member-countries/show', compact('row'));
    }

    public function edit($id)
    {
        $row = MemberCountry::findOrFail($id);
        return view('cms/pages/member-countries/edit', compact('row'));
    }

    public function update(Request $request, $id)
    {
        $row = MemberCountry::findOrFail($id);

        $request->validate([
            'title_en' => 'required',
			'title_ar' => 'required',

        ]);

        $row->title_en = $request->title_en;
		$row->title_ar = $request->title_ar;
		
        $row->save();

        return redirect(env('CMS_PREFIX', 'admin') . '/member-countries')->with('success', 'Record edited successfully');
    }

    public function destroy($id)
    {
        $array = explode(',', $id);
        foreach ($array as $id) MemberCountry::destroy($id);
        return redirect(env('CMS_PREFIX', 'admin') . '/member-countries')->with('success', 'Record deleted successfully');
    }
}