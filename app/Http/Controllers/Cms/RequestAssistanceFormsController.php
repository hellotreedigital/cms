<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\RequestAssistanceForm;


class RequestAssistanceFormsController extends Controller
{
    public function index()
    {
        $rows = RequestAssistanceForm::orderBy('ht_pos')->get();
        
        return view('cms/pages/request-assistance-forms/index', compact('rows'));
    }

    public function create()
    {
        abort(404);
        return view('cms/pages/request-assistance-forms/create');
    }

    public function store(Request $request)
    {
        abort(404);
        $request->validate([
            'title_en' => 'required',
			'title_ar' => 'required',
			'icon' => 'required|image',
			'form_title_en' => 'required',
			'form_title_ar' => 'required',
			'text_en' => '',
			'text_ar' => '',

        ]);

        $row = new RequestAssistanceForm;
        
		$row->title_en = $request->title_en;
		$row->title_ar = $request->title_ar;
		if ($request->icon) {
            $image = time() . '_' . md5(rand()) . '.' . request()->icon->getClientOriginalExtension();
            $request->icon->move(storage_path('app/public/request-assistance-forms'), $image);
            $row->icon = 'storage/request-assistance-forms/' . $image;
        }
        $row->form_title_en = $request->form_title_en;
		$row->form_title_ar = $request->form_title_ar;
		$row->text_en = $request->text_en;
		$row->text_ar = $request->text_ar;
		
        $row->save();

        return redirect(env('CMS_PREFIX', 'admin') . '/request-assistance-forms')->with('success', 'Record added successfully');
    }

    public function show($id)
    {
        $row = RequestAssistanceForm::findOrFail($id);
        
        return view('cms/pages/request-assistance-forms/show', compact('row'));
    }

    public function edit($id)
    {
        $row = RequestAssistanceForm::findOrFail($id);
        
        return view('cms/pages/request-assistance-forms/edit', compact('row'));
    }

    public function update(Request $request, $id)
    {
        $row = RequestAssistanceForm::findOrFail($id);

        $request->validate([
            'title_en' => 'required',
			'title_ar' => 'required',
			'icon' => 'required_with:remove_file_icon|image',
			'form_title_en' => 'required',
			'form_title_ar' => 'required',
			'text_en' => '',
			'text_ar' => '',

        ]);

        $row->title_en = $request->title_en;
		$row->title_ar = $request->title_ar;
		if ($request->remove_file_icon) {
			$row->icon = '';
		} elseif ($request->icon) {
            $image = time() . '_' . md5(rand()) . '.' . request()->icon->getClientOriginalExtension();
            $request->icon->move(storage_path('app/public/request-assistance-forms'), $image);
            $row->icon = 'storage/request-assistance-forms/' . $image;
        }
        $row->form_title_en = $request->form_title_en;
		$row->form_title_ar = $request->form_title_ar;
		$row->text_en = $request->text_en;
		$row->text_ar = $request->text_ar;
		
        $row->save();

        return redirect(env('CMS_PREFIX', 'admin') . '/request-assistance-forms')->with('success', 'Record edited successfully');
    }

    public function destroy($id)
    {
        abort(404);
        $array = explode(',', $id);
        foreach ($array as $id) RequestAssistanceForm::destroy($id);
        return redirect(env('CMS_PREFIX', 'admin') . '/request-assistance-forms')->with('success', 'Record deleted successfully');
    }

    public function orderIndex()
    {
        $rows = RequestAssistanceForm::orderBy('ht_pos')->get();
        return view('cms/pages/request-assistance-forms/order', compact('rows'));
    }

    public function orderSubmit(Request $request)
    {
        foreach ($request->id as $key => $id) {
            $row = RequestAssistanceForm::findOrFail($id);
            $row->ht_pos = $request->ht_pos[$key];
            $row->save();
        }

        return redirect(env('CMS_PREFIX', 'admin') . '/request-assistance-forms')->with('success', 'Records ordered successfully');
    }
}