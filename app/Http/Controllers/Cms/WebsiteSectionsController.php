<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\WebsiteSection;


class WebsiteSectionsController extends Controller
{
    public function index()
    {
        $rows = WebsiteSection::get();
        return view('cms/pages/website-sections/index', compact('rows'));
    }

    public function create()
    {
        return view('cms/pages/website-sections/create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'slug' => 'required',
            'image' => 'image'
        ]);

        $row = new WebsiteSection;
        
		$row->slug = $request->slug;
		$row->title_en = $request->title_en;
		$row->title_ar = $request->title_ar;
		$row->text_en = $request->text_en;
		$row->text_ar = $request->text_ar;
        if ($request->image) {
            $image = time() . '_' . md5(rand()) . '.' . request()->image->getClientOriginalExtension();
            $request->image->move(storage_path('app/public/website-sections'), $image);
            $row->image = 'storage/website-sections/' . $image;
        }
		
        $row->save();

        return redirect(env('CMS_PREFIX', 'admin') . '/website-sections')->with('success', 'Record added successfully');
    }

    public function show($id)
    {
        $row = WebsiteSection::findOrFail($id);
        return view('cms/pages/website-sections/show', compact('row'));
    }

    public function edit($id)
    {
        $row = WebsiteSection::findOrFail($id);
        return view('cms/pages/website-sections/edit', compact('row'));
    }

    public function update(Request $request, $id)
    {
        $row = WebsiteSection::findOrFail($id);

        $request->validate([
            'image' => 'image'
        ]);

		$row->title_en = $request->title_en;
		$row->title_ar = $request->title_ar;
		$row->text_en = $request->text_en;
		$row->text_ar = $request->text_ar;

        if ($request->remove_file_image) {
            $row->image = '';
        } elseif ($request->image) {
            $image = time() . '_' . md5(rand()) . '.' . request()->image->getClientOriginalExtension();
            $request->image->move(storage_path('app/public/website-sections'), $image);
            $row->image = 'storage/website-sections/' . $image;
        }
		
        $row->save();

        return redirect(env('CMS_PREFIX', 'admin') . '/website-sections')->with('success', 'Record edited successfully');
    }

    public function destroy($id)
    {
        $array = explode(',', $id);
        foreach ($array as $id) WebsiteSection::destroy($id);
        return redirect(env('CMS_PREFIX', 'admin') . '/website-sections')->with('success', 'Record deleted successfully');
    }
}