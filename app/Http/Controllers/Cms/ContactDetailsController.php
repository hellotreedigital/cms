<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ContactDetail;


class ContactDetailsController extends Controller
{
    public function index()
    {
        $single_record = ContactDetail::first();
        if (!$single_record) return 'No record found';
        return redirect(env('CMS_PREFIX', 'admin') . '/contact-details/' . $single_record->id);
        $rows = ContactDetail::get();
        
        return view('cms/pages/contact-details/index', compact('rows'));
    }

    public function create()
    {
        
        return view('cms/pages/contact-details/create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'address_en' => 'required',
			'address_ar' => 'required',
			'email' => 'required',
			'phone' => 'required',
			'fax' => 'required',
			'twitter' => 'required',
			'coordinates' => 'required',
        ]);

        $row = new ContactDetail;
        
		$row->address_en = $request->address_en;
		$row->address_ar = $request->address_ar;
		$row->email = $request->email;
		$row->phone = $request->phone;
		$row->fax = $request->fax;
		$row->twitter = $request->twitter;
		$row->coordinates = $request->coordinates;
		
        $row->save();

        return redirect(env('CMS_PREFIX', 'admin') . '/contact-details')->with('success', 'Record added successfully');
    }

    public function show($id)
    {
        $row = ContactDetail::findOrFail($id);
        
        return view('cms/pages/contact-details/show', compact('row'));
    }

    public function edit($id)
    {
        $row = ContactDetail::findOrFail($id);
        
        return view('cms/pages/contact-details/edit', compact('row'));
    }

    public function update(Request $request, $id)
    {
        $row = ContactDetail::findOrFail($id);

        $request->validate([
            'address_en' => 'required',
			'address_ar' => 'required',
			'email' => 'required',
			'phone' => 'required',
			'fax' => 'required',
			'twitter' => 'required',
			'coordinates' => 'required',
        ]);

        $row->address_en = $request->address_en;
		$row->address_ar = $request->address_ar;
		$row->email = $request->email;
		$row->phone = $request->phone;
		$row->fax = $request->fax;
		$row->twitter = $request->twitter;
		$row->coordinates = $request->coordinates;
		
        $row->save();

        return redirect(env('CMS_PREFIX', 'admin') . '/contact-details')->with('success', 'Record edited successfully');
    }

    public function destroy($id)
    {
        $array = explode(',', $id);
        foreach ($array as $id) ContactDetail::destroy($id);
        return redirect(env('CMS_PREFIX', 'admin') . '/contact-details')->with('success', 'Record deleted successfully');
    }

    
}