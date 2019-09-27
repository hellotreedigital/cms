<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\AssistanceStudyTourRequest;


class AssistanceStudyTourRequestsController extends Controller
{
    public function index()
    {
        $rows = AssistanceStudyTourRequest::get();
        
        return view('cms/pages/assistance-study-tour-requests/index', compact('rows'));
    }

    public function create()
    {
        
        return view('cms/pages/assistance-study-tour-requests/create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
			'title' => 'required',
			'contact_info' => 'required',
			'subject' => 'required',
			'request_details' => 'required',
			'duration' => 'required',
			'funding' => 'required',
			'attachments' => 'required',
        ]);

        $row = new AssistanceStudyTourRequest;
        
		$row->name = $request->name;
		$row->title = $request->title;
		$row->contact_info = $request->contact_info;
		$row->subject = $request->subject;
		$row->request_details = $request->request_details;
		$row->duration = $request->duration;
		$row->funding = ($request->funding) ? 1 : 0;
		$row->attachments = $request->attachments;
		
        $row->save();

        return redirect(env('CMS_PREFIX', 'admin') . '/assistance-study-tour-requests')->with('success', 'Record added successfully');
    }

    public function show($id)
    {
        $row = AssistanceStudyTourRequest::findOrFail($id);
        
        return view('cms/pages/assistance-study-tour-requests/show', compact('row'));
    }

    public function edit($id)
    {
        $row = AssistanceStudyTourRequest::findOrFail($id);
        
        return view('cms/pages/assistance-study-tour-requests/edit', compact('row'));
    }

    public function update(Request $request, $id)
    {
        $row = AssistanceStudyTourRequest::findOrFail($id);

        $request->validate([
            'name' => 'required',
			'title' => 'required',
			'contact_info' => 'required',
			'subject' => 'required',
			'request_details' => 'required',
			'duration' => 'required',
			'funding' => 'required',
			'attachments' => 'required',
        ]);

        $row->name = $request->name;
		$row->title = $request->title;
		$row->contact_info = $request->contact_info;
		$row->subject = $request->subject;
		$row->request_details = $request->request_details;
		$row->duration = $request->duration;
		$row->funding = ($request->funding) ? 1 : 0;
		$row->attachments = $request->attachments;
		
        $row->save();

        return redirect(env('CMS_PREFIX', 'admin') . '/assistance-study-tour-requests')->with('success', 'Record edited successfully');
    }

    public function destroy($id)
    {
        $array = explode(',', $id);
        foreach ($array as $id) AssistanceStudyTourRequest::destroy($id);
        return redirect(env('CMS_PREFIX', 'admin') . '/assistance-study-tour-requests')->with('success', 'Record deleted successfully');
    }

    
}