<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\RequestAssistanceExperty;


class RequestAssistanceExpertiesController extends Controller
{
    public function index()
    {
        $rows = RequestAssistanceExperty::orderBy('ht_pos')->get();
        
        return view('cms/pages/request-assistance-experties/index', compact('rows'));
    }

    public function create()
    {
        
        return view('cms/pages/request-assistance-experties/create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title_en' => 'required',
			'title_ar' => 'required',
        ]);

        $row = new RequestAssistanceExperty;
        
		$row->title_en = $request->title_en;
		$row->title_ar = $request->title_ar;
		
        $row->save();

        return redirect(env('CMS_PREFIX', 'admin') . '/request-assistance-experties')->with('success', 'Record added successfully');
    }

    public function show($id)
    {
        $row = RequestAssistanceExperty::findOrFail($id);
        
        return view('cms/pages/request-assistance-experties/show', compact('row'));
    }

    public function edit($id)
    {
        $row = RequestAssistanceExperty::findOrFail($id);
        
        return view('cms/pages/request-assistance-experties/edit', compact('row'));
    }

    public function update(Request $request, $id)
    {
        $row = RequestAssistanceExperty::findOrFail($id);

        $request->validate([
            'title_en' => 'required',
			'title_ar' => 'required',
        ]);

        $row->title_en = $request->title_en;
		$row->title_ar = $request->title_ar;
		
        $row->save();

        return redirect(env('CMS_PREFIX', 'admin') . '/request-assistance-experties')->with('success', 'Record edited successfully');
    }

    public function destroy($id)
    {
        $array = explode(',', $id);
        foreach ($array as $id) RequestAssistanceExperty::destroy($id);
        return redirect(env('CMS_PREFIX', 'admin') . '/request-assistance-experties')->with('success', 'Record deleted successfully');
    }

    public function orderIndex()
    {
        $rows = RequestAssistanceExperty::orderBy('ht_pos')->get();
        return view('cms/pages/request-assistance-experties/order', compact('rows'));
    }

    public function orderSubmit(Request $request)
    {
        foreach ($request->id as $key => $id) {
            $row = RequestAssistanceExperty::findOrFail($id);
            $row->ht_pos = $request->ht_pos[$key];
            $row->save();
        }

        return redirect(env('CMS_PREFIX', 'admin') . '/request-assistance-experties')->with('success', 'Records ordered successfully');
    }
}