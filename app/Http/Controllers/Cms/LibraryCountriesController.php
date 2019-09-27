<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\LibraryCountry;


class LibraryCountriesController extends Controller
{
    public function index()
    {
        $rows = LibraryCountry::orderBy('ht_pos')->get();
        
        return view('cms/pages/library-countries/index', compact('rows'));
    }

    public function create()
    {
        
        return view('cms/pages/library-countries/create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title_en' => 'required',
			'title_ar' => 'required',
        ]);

        $row = new LibraryCountry;
        
		$row->title_en = $request->title_en;
		$row->title_ar = $request->title_ar;
		
        $row->save();

        return redirect(env('CMS_PREFIX', 'admin') . '/library-countries')->with('success', 'Record added successfully');
    }

    public function show($id)
    {
        $row = LibraryCountry::findOrFail($id);
        
        return view('cms/pages/library-countries/show', compact('row'));
    }

    public function edit($id)
    {
        $row = LibraryCountry::findOrFail($id);
        
        return view('cms/pages/library-countries/edit', compact('row'));
    }

    public function update(Request $request, $id)
    {
        $row = LibraryCountry::findOrFail($id);

        $request->validate([
            'title_en' => 'required',
			'title_ar' => 'required',
        ]);

        $row->title_en = $request->title_en;
		$row->title_ar = $request->title_ar;
		
        $row->save();

        return redirect(env('CMS_PREFIX', 'admin') . '/library-countries')->with('success', 'Record edited successfully');
    }

    public function destroy($id)
    {
        $array = explode(',', $id);
        foreach ($array as $id) LibraryCountry::destroy($id);
        return redirect(env('CMS_PREFIX', 'admin') . '/library-countries')->with('success', 'Record deleted successfully');
    }

    public function orderIndex()
    {
        $rows = LibraryCountry::orderBy('ht_pos')->get();
        return view('cms/pages/library-countries/order', compact('rows'));
    }

    public function orderSubmit(Request $request)
    {
        foreach ($request->id as $key => $id) {
            $row = LibraryCountry::findOrFail($id);
            $row->ht_pos = $request->ht_pos[$key];
            $row->save();
        }

        return redirect(env('CMS_PREFIX', 'admin') . '/library-countries')->with('success', 'Records ordered successfully');
    }
}