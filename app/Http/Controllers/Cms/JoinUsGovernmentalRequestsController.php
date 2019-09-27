<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\JoinUsGovernmentalRequest;


class JoinUsGovernmentalRequestsController extends Controller
{
    public function index()
    {
        $rows = JoinUsGovernmentalRequest::get();
        
        return view('cms/pages/join-us-governmental-requests/index', compact('rows'));
    }

    public function create()
    {
        
        return view('cms/pages/join-us-governmental-requests/create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'entity_name' => 'required',
			'entity_president_name' => 'required',
			'entity_focal_point_name' => 'required',
			'entity_contact_info' => 'required',
			'attachments' => 'required',
        ]);

        $row = new JoinUsGovernmentalRequest;
        
		$row->entity_name = $request->entity_name;
		$row->entity_president_name = $request->entity_president_name;
		$row->entity_focal_point_name = $request->entity_focal_point_name;
		$row->entity_contact_info = $request->entity_contact_info;
		$row->attachments = $request->attachments;
		
        $row->save();

        return redirect(env('CMS_PREFIX', 'admin') . '/join-us-governmental-requests')->with('success', 'Record added successfully');
    }

    public function show($id)
    {
        $row = JoinUsGovernmentalRequest::findOrFail($id);
        
        return view('cms/pages/join-us-governmental-requests/show', compact('row'));
    }

    public function edit($id)
    {
        $row = JoinUsGovernmentalRequest::findOrFail($id);
        
        return view('cms/pages/join-us-governmental-requests/edit', compact('row'));
    }

    public function update(Request $request, $id)
    {
        $row = JoinUsGovernmentalRequest::findOrFail($id);

        $request->validate([
            'entity_name' => 'required',
			'entity_president_name' => 'required',
			'entity_focal_point_name' => 'required',
			'entity_contact_info' => 'required',
			'attachments' => 'required',
        ]);

        $row->entity_name = $request->entity_name;
		$row->entity_president_name = $request->entity_president_name;
		$row->entity_focal_point_name = $request->entity_focal_point_name;
		$row->entity_contact_info = $request->entity_contact_info;
		$row->attachments = $request->attachments;
		
        $row->save();

        return redirect(env('CMS_PREFIX', 'admin') . '/join-us-governmental-requests')->with('success', 'Record edited successfully');
    }

    public function destroy($id)
    {
        $array = explode(',', $id);
        foreach ($array as $id) JoinUsGovernmentalRequest::destroy($id);
        return redirect(env('CMS_PREFIX', 'admin') . '/join-us-governmental-requests')->with('success', 'Record deleted successfully');
    }

    
}