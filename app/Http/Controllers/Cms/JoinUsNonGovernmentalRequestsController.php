<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\JoinUsNonGovernmentalRequest;


class JoinUsNonGovernmentalRequestsController extends Controller
{
    public function index()
    {
        $rows = JoinUsNonGovernmentalRequest::get();
        
        return view('cms/pages/join-us-non-governmental-requests/index', compact('rows'));
    }

    public function create()
    {
        
        return view('cms/pages/join-us-non-governmental-requests/create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'entity_name' => 'required',
			'entity_president_name' => 'required',
			'entity_focal_point_name' => 'required',
			'entity_contact_info' => 'required',
			'independent' => 'required',
			'commitment' => 'required',
			'activities' => 'required',
			'reputation' => 'required',
			'legal_status' => 'required',
			'attachments' => 'required',
        ]);

        $row = new JoinUsNonGovernmentalRequest;
        
		$row->entity_name = $request->entity_name;
		$row->entity_president_name = $request->entity_president_name;
		$row->entity_focal_point_name = $request->entity_focal_point_name;
		$row->entity_contact_info = $request->entity_contact_info;
		$row->independent = ($request->independent) ? 1 : 0;
		$row->commitment = ($request->commitment) ? 1 : 0;
		$row->activities = ($request->activities) ? 1 : 0;
		$row->reputation = ($request->reputation) ? 1 : 0;
		$row->legal_status = ($request->legal_status) ? 1 : 0;
		$row->attachments = $request->attachments;
		
        $row->save();

        return redirect(env('CMS_PREFIX', 'admin') . '/join-us-non-governmental-requests')->with('success', 'Record added successfully');
    }

    public function show($id)
    {
        $row = JoinUsNonGovernmentalRequest::findOrFail($id);
        
        return view('cms/pages/join-us-non-governmental-requests/show', compact('row'));
    }

    public function edit($id)
    {
        $row = JoinUsNonGovernmentalRequest::findOrFail($id);
        
        return view('cms/pages/join-us-non-governmental-requests/edit', compact('row'));
    }

    public function update(Request $request, $id)
    {
        $row = JoinUsNonGovernmentalRequest::findOrFail($id);

        $request->validate([
            'entity_name' => 'required',
			'entity_president_name' => 'required',
			'entity_focal_point_name' => 'required',
			'entity_contact_info' => 'required',
			'independent' => 'required',
			'commitment' => 'required',
			'activities' => 'required',
			'reputation' => 'required',
			'legal_status' => 'required',
			'attachments' => 'required',
        ]);

        $row->entity_name = $request->entity_name;
		$row->entity_president_name = $request->entity_president_name;
		$row->entity_focal_point_name = $request->entity_focal_point_name;
		$row->entity_contact_info = $request->entity_contact_info;
		$row->independent = ($request->independent) ? 1 : 0;
		$row->commitment = ($request->commitment) ? 1 : 0;
		$row->activities = ($request->activities) ? 1 : 0;
		$row->reputation = ($request->reputation) ? 1 : 0;
		$row->legal_status = ($request->legal_status) ? 1 : 0;
		$row->attachments = $request->attachments;
		
        $row->save();

        return redirect(env('CMS_PREFIX', 'admin') . '/join-us-non-governmental-requests')->with('success', 'Record edited successfully');
    }

    public function destroy($id)
    {
        $array = explode(',', $id);
        foreach ($array as $id) JoinUsNonGovernmentalRequest::destroy($id);
        return redirect(env('CMS_PREFIX', 'admin') . '/join-us-non-governmental-requests')->with('success', 'Record deleted successfully');
    }

    
}