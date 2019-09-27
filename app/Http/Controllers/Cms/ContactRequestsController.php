<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ContactRequest;


class ContactRequestsController extends Controller
{
    public function index()
    {
        $rows = ContactRequest::get();
        
        return view('cms/pages/contact-requests/index', compact('rows'));
    }

    public function create()
    {
        
        return view('cms/pages/contact-requests/create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
			'last_name' => 'required',
			'email' => 'required',
			'subject' => 'required',
			'additional_info' => 'required',
        ]);

        $row = new ContactRequest;
        
		$row->first_name = $request->first_name;
		$row->last_name = $request->last_name;
		$row->email = $request->email;
		$row->subject = $request->subject;
		$row->additional_info = $request->additional_info;
		
        $row->save();

        return redirect(env('CMS_PREFIX', 'admin') . '/contact-requests')->with('success', 'Record added successfully');
    }

    public function show($id)
    {
        $row = ContactRequest::findOrFail($id);
        
        return view('cms/pages/contact-requests/show', compact('row'));
    }

    public function edit($id)
    {
        $row = ContactRequest::findOrFail($id);
        
        return view('cms/pages/contact-requests/edit', compact('row'));
    }

    public function update(Request $request, $id)
    {
        $row = ContactRequest::findOrFail($id);

        $request->validate([
            'first_name' => 'required',
			'last_name' => 'required',
			'email' => 'required',
			'subject' => 'required',
			'additional_info' => 'required',
        ]);

        $row->first_name = $request->first_name;
		$row->last_name = $request->last_name;
		$row->email = $request->email;
		$row->subject = $request->subject;
		$row->additional_info = $request->additional_info;
		
        $row->save();

        return redirect(env('CMS_PREFIX', 'admin') . '/contact-requests')->with('success', 'Record edited successfully');
    }

    public function destroy($id)
    {
        $array = explode(',', $id);
        foreach ($array as $id) ContactRequest::destroy($id);
        return redirect(env('CMS_PREFIX', 'admin') . '/contact-requests')->with('success', 'Record deleted successfully');
    }

    
}