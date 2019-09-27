<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\AssistanceExpertsRequest;


class AssistanceExpertsRequestsController extends Controller
{
    public function index()
    {
        $rows = AssistanceExpertsRequest::get();
        
        return view('cms/pages/assistance-experts-requests/index', compact('rows'));
    }

    public function create()
    {
        
        return view('cms/pages/assistance-experts-requests/create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
			'title' => 'required',
			'entity' => 'required',
			'contact_info' => 'required',
			'subject' => 'required',
			'requested_expert' => 'required',
			'duration' => 'required',
			'funding' => 'required',
			'additional_info' => 'required',
			'attachments' => 'required',
        ]);

        $row = new AssistanceExpertsRequest;
        
		$row->name = $request->name;
		$row->title = $request->title;
		$row->entity = $request->entity;
		$row->contact_info = $request->contact_info;
		$row->subject = $request->subject;
		$row->requested_expert = $request->requested_expert;
		$row->duration = $request->duration;
		$row->funding = $request->funding;
		$row->additional_info = $request->additional_info;
		$row->attachments = $request->attachments;
		
        $row->save();

        return redirect(env('CMS_PREFIX', 'admin') . '/assistance-experts-requests')->with('success', 'Record added successfully');
    }

    public function show($id)
    {
        $row = AssistanceExpertsRequest::findOrFail($id);
        
        return view('cms/pages/assistance-experts-requests/show', compact('row'));
    }

    public function edit($id)
    {
        $row = AssistanceExpertsRequest::findOrFail($id);
        
        return view('cms/pages/assistance-experts-requests/edit', compact('row'));
    }

    public function update(Request $request, $id)
    {
        $row = AssistanceExpertsRequest::findOrFail($id);

        $request->validate([
            'name' => 'required',
			'title' => 'required',
			'entity' => 'required',
			'contact_info' => 'required',
			'subject' => 'required',
			'requested_expert' => 'required',
			'duration' => 'required',
			'funding' => 'required',
			'additional_info' => 'required',
			'attachments' => 'required',
        ]);

        $row->name = $request->name;
		$row->title = $request->title;
		$row->entity = $request->entity;
		$row->contact_info = $request->contact_info;
		$row->subject = $request->subject;
		$row->requested_expert = $request->requested_expert;
		$row->duration = $request->duration;
		$row->funding = $request->funding;
		$row->additional_info = $request->additional_info;
		$row->attachments = $request->attachments;
		
        $row->save();

        return redirect(env('CMS_PREFIX', 'admin') . '/assistance-experts-requests')->with('success', 'Record edited successfully');
    }

    public function destroy($id)
    {
        $array = explode(',', $id);
        foreach ($array as $id) AssistanceExpertsRequest::destroy($id);
        return redirect(env('CMS_PREFIX', 'admin') . '/assistance-experts-requests')->with('success', 'Record deleted successfully');
    }

    
}