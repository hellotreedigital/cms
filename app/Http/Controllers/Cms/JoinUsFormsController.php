<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\JoinUsForm;


class JoinUsFormsController extends Controller
{
    public function index()
    {
        $rows = JoinUsForm::orderBy('ht_pos')->get();
        
        return view('cms/pages/join-us-forms/index', compact('rows'));
    }

    public function create()
    {
        abort(404);
        return view('cms/pages/join-us-forms/create');
    }

    public function store(Request $request)
    {
        abort(404);
        $request->validate([
            'title_en' => 'required',
			'title_ar' => 'required',
			'text_en' => 'required',
			'text_ar' => 'required',
			'form_title_en' => 'required',
			'form_title_ar' => 'required',
			'icon' => 'required|image',

        ]);

        $row = new JoinUsForm;
        
		$row->title_en = $request->title_en;
		$row->title_ar = $request->title_ar;
		$row->text_en = $request->text_en;
		$row->text_ar = $request->text_ar;
		$row->form_title_en = $request->form_title_en;
		$row->form_title_ar = $request->form_title_ar;
		if ($request->icon) {
            $image = time() . '_' . md5(rand()) . '.' . request()->icon->getClientOriginalExtension();
            $request->icon->move(storage_path('app/public/join-us-forms'), $image);
            $row->icon = 'storage/join-us-forms/' . $image;
        }
        
        $row->save();

        return redirect(env('CMS_PREFIX', 'admin') . '/join-us-forms')->with('success', 'Record added successfully');
    }

    public function show($id)
    {
        $row = JoinUsForm::findOrFail($id);
        
        return view('cms/pages/join-us-forms/show', compact('row'));
    }

    public function edit($id)
    {
        $row = JoinUsForm::findOrFail($id);
        
        return view('cms/pages/join-us-forms/edit', compact('row'));
    }

    public function update(Request $request, $id)
    {
        $row = JoinUsForm::findOrFail($id);

        $request->validate([
            'title_en' => 'required',
			'title_ar' => 'required',
			'text_en' => 'required',
			'text_ar' => 'required',
			'form_title_en' => 'required',
			'form_title_ar' => 'required',
			'icon' => 'required_with:remove_file_icon|image',

        ]);

        $row->title_en = $request->title_en;
		$row->title_ar = $request->title_ar;
		$row->text_en = $request->text_en;
		$row->text_ar = $request->text_ar;
		$row->form_title_en = $request->form_title_en;
		$row->form_title_ar = $request->form_title_ar;
		if ($request->remove_file_icon) {
			$row->icon = '';
		} elseif ($request->icon) {
            $image = time() . '_' . md5(rand()) . '.' . request()->icon->getClientOriginalExtension();
            $request->icon->move(storage_path('app/public/join-us-forms'), $image);
            $row->icon = 'storage/join-us-forms/' . $image;
        }
        
        $row->save();

        return redirect(env('CMS_PREFIX', 'admin') . '/join-us-forms')->with('success', 'Record edited successfully');
    }

    public function destroy($id)
    {
        abort(404);
        $array = explode(',', $id);
        foreach ($array as $id) JoinUsForm::destroy($id);
        return redirect(env('CMS_PREFIX', 'admin') . '/join-us-forms')->with('success', 'Record deleted successfully');
    }

    public function orderIndex()
    {
        $rows = JoinUsForm::orderBy('ht_pos')->get();
        return view('cms/pages/join-us-forms/order', compact('rows'));
    }

    public function orderSubmit(Request $request)
    {
        foreach ($request->id as $key => $id) {
            $row = JoinUsForm::findOrFail($id);
            $row->ht_pos = $request->ht_pos[$key];
            $row->save();
        }

        return redirect(env('CMS_PREFIX', 'admin') . '/join-us-forms')->with('success', 'Records ordered successfully');
    }
}