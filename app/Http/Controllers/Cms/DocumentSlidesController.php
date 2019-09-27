<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DocumentSlide;
use App\Document;


class DocumentSlidesController extends Controller
{
    public function index()
    {
        $rows = DocumentSlide::orderBy('ht_pos')->get();
        $documents = [];
	    $documents_db = Document::get()->toArray();
	    foreach ($documents_db as $single_documents_db) $documents[$single_documents_db['id']] = $single_documents_db;
        return view('cms/pages/document-slides/index', compact('rows', 'documents'));
    }

    public function create()
    {
        $documents = Document::get()->toArray();
        return view('cms/pages/document-slides/create', compact('documents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'document_id' => 'required',
			'image' => 'required|image',
        ]);

        $row = new DocumentSlide;
        
		$row->document_id = $request->document_id;
		if ($request->image) {
            $image = time() . '_' . md5(rand()) . '.' . request()->image->getClientOriginalExtension();
            $request->image->move(storage_path('app/public/document-slides'), $image);
            $row->image = 'storage/document-slides/' . $image;
        }
        
        $row->save();

        return redirect(env('CMS_PREFIX', 'admin') . '/document-slides')->with('success', 'Record added successfully');
    }

    public function show($id)
    {
        $row = DocumentSlide::findOrFail($id);
        return view('cms/pages/document-slides/show', compact('row'));
    }

    public function edit($id)
    {
        $row = DocumentSlide::findOrFail($id);
        $documents = Document::get()->toArray();
        return view('cms/pages/document-slides/edit', compact('row', 'documents'));
    }

    public function update(Request $request, $id)
    {
        $row = DocumentSlide::findOrFail($id);

        $request->validate([
            'document_id' => 'required',
			'image' => 'image',
        ]);

        $row->document_id = $request->document_id;
		if ($request->remove_file_image) {
				$row->image = '';
			} elseif ($request->image) {
            $image = time() . '_' . md5(rand()) . '.' . request()->image->getClientOriginalExtension();
            $request->image->move(storage_path('app/public/document-slides'), $image);
            $row->image = 'storage/document-slides/' . $image;
        }
        
        $row->save();

        return redirect(env('CMS_PREFIX', 'admin') . '/document-slides')->with('success', 'Record edited successfully');
    }

    public function destroy($id)
    {
        $array = explode(',', $id);
        foreach ($array as $id) DocumentSlide::destroy($id);
        return redirect(env('CMS_PREFIX', 'admin') . '/document-slides')->with('success', 'Record deleted successfully');
    }

    public function orderIndex()
    {
        $rows = DocumentSlide::orderBy('ht_pos')->get();
        return view('cms/pages/document-slides/order', compact('rows'));
    }

    public function orderSubmit(Request $request)
    {
        foreach ($request->id as $key => $id) {
            $row = DocumentSlide::findOrFail($id);
            $row->ht_pos = $request->ht_pos[$key];
            $row->save();
        }

        return redirect(env('CMS_PREFIX', 'admin') . '/document-slides')->with('success', 'Records ordered successfully');
    }
}