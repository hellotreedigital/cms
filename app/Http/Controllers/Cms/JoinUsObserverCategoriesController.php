<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\JoinUsObserverCategory;


class JoinUsObserverCategoriesController extends Controller
{
    public function index()
    {
        $rows = JoinUsObserverCategory::orderBy('ht_pos')->get();
        
        return view('cms/pages/join-us-observer-categories/index', compact('rows'));
    }

    public function create()
    {
        
        return view('cms/pages/join-us-observer-categories/create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title_en' => 'required',
			'title_ar' => 'required',
        ]);

        $row = new JoinUsObserverCategory;
        
		$row->title_en = $request->title_en;
		$row->title_ar = $request->title_ar;
		
        $row->save();

        return redirect(env('CMS_PREFIX', 'admin') . '/join-us-observer-categories')->with('success', 'Record added successfully');
    }

    public function show($id)
    {
        $row = JoinUsObserverCategory::findOrFail($id);
        
        return view('cms/pages/join-us-observer-categories/show', compact('row'));
    }

    public function edit($id)
    {
        $row = JoinUsObserverCategory::findOrFail($id);
        
        return view('cms/pages/join-us-observer-categories/edit', compact('row'));
    }

    public function update(Request $request, $id)
    {
        $row = JoinUsObserverCategory::findOrFail($id);

        $request->validate([
            'title_en' => 'required',
			'title_ar' => 'required',
        ]);

        $row->title_en = $request->title_en;
		$row->title_ar = $request->title_ar;
		
        $row->save();

        return redirect(env('CMS_PREFIX', 'admin') . '/join-us-observer-categories')->with('success', 'Record edited successfully');
    }

    public function destroy($id)
    {
        $array = explode(',', $id);
        foreach ($array as $id) JoinUsObserverCategory::destroy($id);
        return redirect(env('CMS_PREFIX', 'admin') . '/join-us-observer-categories')->with('success', 'Record deleted successfully');
    }

    public function orderIndex()
    {
        $rows = JoinUsObserverCategory::orderBy('ht_pos')->get();
        return view('cms/pages/join-us-observer-categories/order', compact('rows'));
    }

    public function orderSubmit(Request $request)
    {
        foreach ($request->id as $key => $id) {
            $row = JoinUsObserverCategory::findOrFail($id);
            $row->ht_pos = $request->ht_pos[$key];
            $row->save();
        }

        return redirect(env('CMS_PREFIX', 'admin') . '/join-us-observer-categories')->with('success', 'Records ordered successfully');
    }
}