<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Document;
use App\DocumentSlide;


class DocumentsController extends Controller
{
    public function index()
    {
        $rows = Document::orderBy('ht_pos')->get();
        return view('cms/pages/documents/index', compact('rows'));
    }

    public function create()
    {
        return view('cms/pages/documents/create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'slug' => 'required|unique:documents',
			'title_en' => 'required',
			'title_ar' => 'required',
			'text_en' => 'required',
			'text_ar' => 'required',
			'date' => 'required',
			'icon' => 'required|image',

        ]);

        $row = new Document;
        
		$row->slug = $request->slug;
		$row->title_en = $request->title_en;
		$row->title_ar = $request->title_ar;
		$row->text_en = $request->text_en;
		$row->text_ar = $request->text_ar;
		$row->date = $request->date;
		if ($request->icon) {
            $image = time() . '_' . md5(rand()) . '.' . request()->icon->getClientOriginalExtension();
            $request->icon->move(storage_path('app/public/documents'), $image);
            $row->icon = 'storage/documents/' . $image;
        }
        
        $row->save();

        return redirect(env('CMS_PREFIX', 'admin') . '/documents')->with('success', 'Record added successfully');
    }

    public function show($id)
    {
        $row = Document::findOrFail($id);
        return view('cms/pages/documents/show', compact('row'));
    }

    public function edit($id)
    {
        $row = Document::findOrFail($id);
        $images = DocumentSlide::where('document_id', $id)->get();

        return view('cms/pages/documents/edit', compact('row', 'images'));
    }

    public function update(Request $request, $id)
    {
        $row = Document::findOrFail($id);

        $request->validate([
            'slug' => 'required|unique:documents,slug,' . $row->id,
			'title_en' => 'required',
			'title_ar' => 'required',
			'text_en' => 'required',
			'text_ar' => 'required',
			'date' => 'required',
			'icon' => 'image',

        ]);

        $row->slug = $request->slug;
		$row->title_en = $request->title_en;
		$row->title_ar = $request->title_ar;
		$row->text_en = $request->text_en;
		$row->text_ar = $request->text_ar;
		$row->date = $request->date;
		if ($request->remove_file_icon) {
				$row->icon = '';
			} elseif ($request->icon) {
            $image = time() . '_' . md5(rand()) . '.' . request()->icon->getClientOriginalExtension();
            $request->icon->move(storage_path('app/public/documents'), $image);
            $row->icon = 'storage/documents/' . $image;
        }
        
        $row->save();

        return redirect(env('CMS_PREFIX', 'admin') . '/documents')->with('success', 'Record edited successfully');
    }

    public function destroy($id)
    {
        $array = explode(',', $id);
        foreach ($array as $id) Document::destroy($id);
        return redirect(env('CMS_PREFIX', 'admin') . '/documents')->with('success', 'Record deleted successfully');
    }

    public function orderIndex()
    {
        $rows = Document::orderBy('ht_pos')->get();
        return view('cms/pages/documents/order', compact('rows'));
    }

    public function orderSubmit(Request $request)
    {
        foreach ($request->id as $key => $id) {
            $row = Document::findOrFail($id);
            $row->ht_pos = $request->ht_pos[$key];
            $row->save();
        }

        return redirect(env('CMS_PREFIX', 'admin') . '/documents')->with('success', 'Records ordered successfully');
    }
}