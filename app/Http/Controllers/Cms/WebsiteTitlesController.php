<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\WebsiteTitle;


class WebsiteTitlesController extends Controller
{
    public function index()
    {
        $rows = WebsiteTitle::get();
        return view('cms/pages/website-titles/index', compact('rows'));
    }

    public function create()
    {
        return view('cms/pages/website-titles/create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'slug' => 'required',
			'title_en' => 'required',
			'title_ar' => 'required',

        ]);

        $row = new WebsiteTitle;
        
		$row->slug = $request->slug;
		$row->title_en = $request->title_en;
		$row->title_ar = $request->title_ar;
		
        $row->save();

        return redirect(env('CMS_PREFIX', 'admin') . '/website-titles')->with('success', 'Record added successfully');
    }

    public function show($id)
    {
        $row = WebsiteTitle::findOrFail($id);
        return view('cms/pages/website-titles/show', compact('row'));
    }

    public function edit($id)
    {
        $row = WebsiteTitle::findOrFail($id);
        return view('cms/pages/website-titles/edit', compact('row'));
    }

    public function update(Request $request, $id)
    {
        $row = WebsiteTitle::findOrFail($id);

        $request->validate([
			'title_en' => 'required',
			'title_ar' => 'required',

        ]);

		$row->title_en = $request->title_en;
		$row->title_ar = $request->title_ar;
		
        $row->save();

        return redirect(env('CMS_PREFIX', 'admin') . '/website-titles')->with('success', 'Record edited successfully');
    }

    public function destroy($id)
    {
        $array = explode(',', $id);
        foreach ($array as $id) WebsiteTitle::destroy($id);
        return redirect(env('CMS_PREFIX', 'admin') . '/website-titles')->with('success', 'Record deleted successfully');
    }
}