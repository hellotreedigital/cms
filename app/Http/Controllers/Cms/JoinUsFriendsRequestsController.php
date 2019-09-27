<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\JoinUsFriendsRequest;


class JoinUsFriendsRequestsController extends Controller
{
    public function index()
    {
        $rows = JoinUsFriendsRequest::get();
        
        return view('cms/pages/join-us-friends-requests/index', compact('rows'));
    }

    public function create()
    {
        
        return view('cms/pages/join-us-friends-requests/create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
			'contact_info' => 'required',
			'country' => 'required',
			'gender' => 'required',
			'age_group' => 'required',
			'attachments' => 'required',
        ]);

        $row = new JoinUsFriendsRequest;
        
		$row->name = $request->name;
		$row->contact_info = $request->contact_info;
		$row->country = $request->country;
		$row->gender = $request->gender;
		$row->age_group = $request->age_group;
		$row->attachments = $request->attachments;
		
        $row->save();

        return redirect(env('CMS_PREFIX', 'admin') . '/join-us-friends-requests')->with('success', 'Record added successfully');
    }

    public function show($id)
    {
        $row = JoinUsFriendsRequest::findOrFail($id);
        
        return view('cms/pages/join-us-friends-requests/show', compact('row'));
    }

    public function edit($id)
    {
        $row = JoinUsFriendsRequest::findOrFail($id);
        
        return view('cms/pages/join-us-friends-requests/edit', compact('row'));
    }

    public function update(Request $request, $id)
    {
        $row = JoinUsFriendsRequest::findOrFail($id);

        $request->validate([
            'name' => 'required',
			'contact_info' => 'required',
			'country' => 'required',
			'gender' => 'required',
			'age_group' => 'required',
			'attachments' => 'required',
        ]);

        $row->name = $request->name;
		$row->contact_info = $request->contact_info;
		$row->country = $request->country;
		$row->gender = $request->gender;
		$row->age_group = $request->age_group;
		$row->attachments = $request->attachments;
		
        $row->save();

        return redirect(env('CMS_PREFIX', 'admin') . '/join-us-friends-requests')->with('success', 'Record edited successfully');
    }

    public function destroy($id)
    {
        $array = explode(',', $id);
        foreach ($array as $id) JoinUsFriendsRequest::destroy($id);
        return redirect(env('CMS_PREFIX', 'admin') . '/join-us-friends-requests')->with('success', 'Record deleted successfully');
    }

    
}