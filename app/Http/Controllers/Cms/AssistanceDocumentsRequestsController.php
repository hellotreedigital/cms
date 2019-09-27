<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\AssistanceDocumentsRequest;


class AssistanceDocumentsRequestsController extends Controller
{
    public function index()
    {
        $rows = AssistanceDocumentsRequest::get();
        
        return view('cms/pages/assistance-documents-requests/index', compact('rows'));
    }

    public function create()
    {
        
        return view('cms/pages/assistance-documents-requests/create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
			'title' => 'required',
			'contact_info' => 'required',
			'request_details' => 'required',
			'additional_info' => 'required',
        ]);

        $row = new AssistanceDocumentsRequest;
        
		$row->name = $request->name;
		$row->title = $request->title;
		$row->contact_info = $request->contact_info;
		$row->request_details = $request->request_details;
		$row->additional_info = $request->additional_info;
		
        $row->save();

        return redirect(env('CMS_PREFIX', 'admin') . '/assistance-documents-requests')->with('success', 'Record added successfully');
    }

    public function show($id)
    {
        $row = AssistanceDocumentsRequest::findOrFail($id);
        
        return view('cms/pages/assistance-documents-requests/show', compact('row'));
    }

    public function edit($id)
    {
        $row = AssistanceDocumentsRequest::findOrFail($id);
        
        return view('cms/pages/assistance-documents-requests/edit', compact('row'));
    }

    public function update(Request $request, $id)
    {
        $row = AssistanceDocumentsRequest::findOrFail($id);

        $request->validate([
            'name' => 'required',
			'title' => 'required',
			'contact_info' => 'required',
			'request_details' => 'required',
			'additional_info' => 'required',
        ]);

        $row->name = $request->name;
		$row->title = $request->title;
		$row->contact_info = $request->contact_info;
		$row->request_details = $request->request_details;
		$row->additional_info = $request->additional_info;
		
        $row->save();

        return redirect(env('CMS_PREFIX', 'admin') . '/assistance-documents-requests')->with('success', 'Record edited successfully');
    }

    public function destroy($id)
    {
        $array = explode(',', $id);
        foreach ($array as $id) AssistanceDocumentsRequest::destroy($id);
        return redirect(env('CMS_PREFIX', 'admin') . '/assistance-documents-requests')->with('success', 'Record deleted successfully');
    }

    
}