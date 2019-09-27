<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\AboutSlide;


class AboutSlidesController extends Controller
{
    public function index()
    {
        $rows = AboutSlide::get();
        return view('cms/pages/about-slides/index', compact('rows'));
    }

    public function create()
    {
        return view('cms/pages/about-slides/create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image',

        ]);

        $row = new AboutSlide;
        
		if ($request->image) {
            $image = time() . '_' . md5(rand()) . '.' . request()->image->getClientOriginalExtension();
            $request->image->move(storage_path('app/public/about-slides'), $image);
            $row->image = 'storage/about-slides/' . $image;
        }
        
        $row->save();

        return redirect(env('CMS_PREFIX', 'admin') . '/about-slides')->with('success', 'Record added successfully');
    }

    public function show($id)
    {
        $row = AboutSlide::findOrFail($id);
        return view('cms/pages/about-slides/show', compact('row'));
    }

    public function edit($id)
    {
        $row = AboutSlide::findOrFail($id);
        return view('cms/pages/about-slides/edit', compact('row'));
    }

    public function update(Request $request, $id)
    {
        $row = AboutSlide::findOrFail($id);

        $request->validate([
            'image' => 'image',

        ]);

        if ($request->remove_file_image) {
            $row->image = '';
        } elseif ($request->image) {
            $image = time() . '_' . md5(rand()) . '.' . request()->image->getClientOriginalExtension();
            $request->image->move(storage_path('app/public/about-slides'), $image);
            $row->image = 'storage/about-slides/' . $image;
        }
        
        $row->save();

        return redirect(env('CMS_PREFIX', 'admin') . '/about-slides')->with('success', 'Record edited successfully');
    }

    public function destroy($id)
    {
        $array = explode(',', $id);
        foreach ($array as $id) AboutSlide::destroy($id);
        return redirect(env('CMS_PREFIX', 'admin') . '/about-slides')->with('success', 'Record deleted successfully');
    }

    public function orderIndex()
    {
        $rows = AboutSlide::orderBy('ht_pos')->get();
        return view('cms/pages/about-slides/order', compact('rows'));
    }

    public function orderSubmit(Request $request)
    {
        foreach ($request->id as $key => $id) {
            $row = AboutSlide::findOrFail($id);
            $row->ht_pos = $request->ht_pos[$key];
            $row->save();
        }

        return redirect(env('CMS_PREFIX', 'admin') . '/about-slides')->with('success', 'Records ordered successfully');
    }
}