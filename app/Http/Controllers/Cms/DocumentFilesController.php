<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DocumentFile;
use App\Document;


class DocumentFilesController extends Controller
{
    public function index()
    {
        $rows = DocumentFile::orderBy('ht_pos')->get();
        $documents = [];
	    $documents_db = Document::get()->toArray();
	    foreach ($documents_db as $single_documents_db) $documents[$single_documents_db['id']] = $single_documents_db;
        return view('cms/pages/document-files/index', compact('rows', 'documents'));
    }

    public function create()
    {
        $documents = Document::get()->toArray();
        return view('cms/pages/document-files/create', compact('documents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'document_id' => 'required',
			'title_en' => 'required',
			'text_ar' => 'required',
			'file_en' => 'required',
			'file_ar' => 'required',
			'image' => 'image',
        ]);

        $row = new DocumentFile;
        
		$row->document_id = $request->document_id;
		$row->title_en = $request->title_en;
		$row->text_ar = $request->text_ar;
		if ($request->file_en) {
            $image = time() . '_' . md5(rand()) . '.' . request()->file_en->getClientOriginalExtension();
            $request->file_en->move(storage_path('app/public/document-files'), $image);
            $row->file_en = 'storage/document-files/' . $image;
        }
        if ($request->file_ar) {
            $image = time() . '_' . md5(rand()) . '.' . request()->file_ar->getClientOriginalExtension();
            $request->file_ar->move(storage_path('app/public/document-files'), $image);
            $row->file_ar = 'storage/document-files/' . $image;
        }
        if ($request->image) {
            $image = time() . '_' . md5(rand()) . '.' . request()->image->getClientOriginalExtension();
            $request->image->move(storage_path('app/public/document-files'), $image);
            $row->image = 'storage/document-files/' . $image;
        }
        
        $row->save();

        return redirect(env('CMS_PREFIX', 'admin') . '/document-files')->with('success', 'Record added successfully');
    }

    public function show($id)
    {
        $row = DocumentFile::findOrFail($id);
        return view('cms/pages/document-files/show', compact('row'));
    }

    public function edit($id)
    {
        $row = DocumentFile::findOrFail($id);
        $documents = Document::get()->toArray();
        return view('cms/pages/document-files/edit', compact('row', 'documents'));
    }

    public function update(Request $request, $id)
    {
        $row = DocumentFile::findOrFail($id);

        $request->validate([
            'document_id' => '',
			'title_en' => '',
			'text_ar' => '',
			'file_en' => 'required_with:remove_file_file_en',
			'file_ar' => 'required_with:remove_file_file_ar',
			'image' => 'image',
        ]);

        $row->document_id = $request->document_id;
		$row->title_en = $request->title_en;
		$row->text_ar = $request->text_ar;
		if ($request->remove_file_file_en) {
			$row->file_en = '';
		} elseif ($request->file_en) {
            $image = time() . '_' . md5(rand()) . '.' . request()->file_en->getClientOriginalExtension();
            $request->file_en->move(storage_path('app/public/document-files'), $image);
            $row->file_en = 'storage/document-files/' . $image;
        }
        if ($request->remove_file_file_ar) {
			$row->file_ar = '';
		} elseif ($request->file_ar) {
            $image = time() . '_' . md5(rand()) . '.' . request()->file_ar->getClientOriginalExtension();
            $request->file_ar->move(storage_path('app/public/document-files'), $image);
            $row->file_ar = 'storage/document-files/' . $image;
        }
        if ($request->remove_file_image) {
			$row->image = '';
		} elseif ($request->image) {
            $image = time() . '_' . md5(rand()) . '.' . request()->image->getClientOriginalExtension();
            $request->image->move(storage_path('app/public/document-files'), $image);
            $row->image = 'storage/document-files/' . $image;
        }
        
        $row->save();

        return redirect(env('CMS_PREFIX', 'admin') . '/document-files')->with('success', 'Record edited successfully');
    }

    public function destroy($id)
    {
        $array = explode(',', $id);
        foreach ($array as $id) DocumentFile::destroy($id);
        return redirect(env('CMS_PREFIX', 'admin') . '/document-files')->with('success', 'Record deleted successfully');
    }

    public function orderIndex()
    {
        $rows = DocumentFile::orderBy('ht_pos')->get();
        return view('cms/pages/document-files/order', compact('rows'));
    }

    public function orderSubmit(Request $request)
    {
        foreach ($request->id as $key => $id) {
            $row = DocumentFile::findOrFail($id);
            $row->ht_pos = $request->ht_pos[$key];
            $row->save();
        }

        return redirect(env('CMS_PREFIX', 'admin') . '/document-files')->with('success', 'Records ordered successfully');
    }
}