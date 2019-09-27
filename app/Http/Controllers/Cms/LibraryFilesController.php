<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\LibraryFile;
use App\LibraryCategory;
use App\LibraryCountry;


class LibraryFilesController extends Controller
{
    public function index()
    {
        $rows = LibraryFile::orderBy('ht_pos')->get();
        $library_categories = [];
	    $library_categories_db = LibraryCategory::get()->toArray();
	    foreach ($library_categories_db as $single_library_categories_db) $library_categories[$single_library_categories_db['id']] = $single_library_categories_db;$library_countries = [];
	    $library_countries_db = LibraryCountry::get()->toArray();
	    foreach ($library_countries_db as $single_library_countries_db) $library_countries[$single_library_countries_db['id']] = $single_library_countries_db;
        return view('cms/pages/library-files/index', compact('rows', 'library_categories', 'library_countries'));
    }

    public function create()
    {
        $library_categories = LibraryCategory::get()->toArray();$library_countries = LibraryCountry::get()->toArray();
        return view('cms/pages/library-files/create', compact('library_categories', 'library_countries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'library_category_id' => 'required',
			'library_country_id' => 'required',
			'title_en' => 'required',
			'title_ar' => 'required',
			'text_en' => 'required',
			'text_ar' => 'required',
			'icon' => 'required|image',
			'file_en' => 'required',
			'file_ar' => 'required',
			'show_home' => 'required',
        ]);

        $row = new LibraryFile;
        
		$row->library_category_id = $request->library_category_id;
		$row->library_country_id = $request->library_country_id;
		$row->title_en = $request->title_en;
		$row->title_ar = $request->title_ar;
		$row->text_en = $request->text_en;
		$row->text_ar = $request->text_ar;
		if ($request->icon) {
            $image = time() . '_' . md5(rand()) . '.' . request()->icon->getClientOriginalExtension();
            $request->icon->move(storage_path('app/public/library-files'), $image);
            $row->icon = 'storage/library-files/' . $image;
        }
        if ($request->file_en) {
            $image = time() . '_' . md5(rand()) . '.' . request()->file_en->getClientOriginalExtension();
            $request->file_en->move(storage_path('app/public/library-files'), $image);
            $row->file_en = 'storage/library-files/' . $image;
        }
        if ($request->file_ar) {
            $image = time() . '_' . md5(rand()) . '.' . request()->file_ar->getClientOriginalExtension();
            $request->file_ar->move(storage_path('app/public/library-files'), $image);
            $row->file_ar = 'storage/library-files/' . $image;
        }
        $row->show_home = ($request->show_home) ? 1 : 0;
		
        $row->save();

        return redirect(env('CMS_PREFIX', 'admin') . '/library-files')->with('success', 'Record added successfully');
    }

    public function show($id)
    {
        $row = LibraryFile::findOrFail($id);
        $library_categories = [];
	    $library_categories_db = LibraryCategory::get()->toArray();
	    foreach ($library_categories_db as $single_library_categories_db) $library_categories[$single_library_categories_db['id']] = $single_library_categories_db;$library_countries = [];
	    $library_countries_db = LibraryCountry::get()->toArray();
	    foreach ($library_countries_db as $single_library_countries_db) $library_countries[$single_library_countries_db['id']] = $single_library_countries_db;
        return view('cms/pages/library-files/show', compact('row', 'library_categories', 'library_countries'));
    }

    public function edit($id)
    {
        $row = LibraryFile::findOrFail($id);
        $library_categories = LibraryCategory::get()->toArray();$library_countries = LibraryCountry::get()->toArray();
        return view('cms/pages/library-files/edit', compact('row', 'library_categories', 'library_countries'));
    }

    public function update(Request $request, $id)
    {
        $row = LibraryFile::findOrFail($id);

        $request->validate([
            'library_category_id' => 'required',
			'library_country_id' => 'required',
			'title_en' => 'required',
			'title_ar' => 'required',
			'text_en' => 'required',
			'text_ar' => 'required',
			'icon' => 'required_with:remove_file_icon|image',
			'file_en' => 'required_with:remove_file_file_en',
			'file_ar' => 'required_with:remove_file_file_ar',
			'show_home' => 'required',
        ]);

        $row->library_category_id = $request->library_category_id;
		$row->library_country_id = $request->library_country_id;
		$row->title_en = $request->title_en;
		$row->title_ar = $request->title_ar;
		$row->text_en = $request->text_en;
		$row->text_ar = $request->text_ar;
		if ($request->remove_file_icon) {
			$row->icon = '';
		} elseif ($request->icon) {
            $image = time() . '_' . md5(rand()) . '.' . request()->icon->getClientOriginalExtension();
            $request->icon->move(storage_path('app/public/library-files'), $image);
            $row->icon = 'storage/library-files/' . $image;
        }
        if ($request->remove_file_file_en) {
			$row->file_en = '';
		} elseif ($request->file_en) {
            $image = time() . '_' . md5(rand()) . '.' . request()->file_en->getClientOriginalExtension();
            $request->file_en->move(storage_path('app/public/library-files'), $image);
            $row->file_en = 'storage/library-files/' . $image;
        }
        if ($request->remove_file_file_ar) {
			$row->file_ar = '';
		} elseif ($request->file_ar) {
            $image = time() . '_' . md5(rand()) . '.' . request()->file_ar->getClientOriginalExtension();
            $request->file_ar->move(storage_path('app/public/library-files'), $image);
            $row->file_ar = 'storage/library-files/' . $image;
        }
        $row->show_home = ($request->show_home) ? 1 : 0;
		
        $row->save();

        return redirect(env('CMS_PREFIX', 'admin') . '/library-files')->with('success', 'Record edited successfully');
    }

    public function destroy($id)
    {
        $array = explode(',', $id);
        foreach ($array as $id) LibraryFile::destroy($id);
        return redirect(env('CMS_PREFIX', 'admin') . '/library-files')->with('success', 'Record deleted successfully');
    }

    public function orderIndex()
    {
        $rows = LibraryFile::orderBy('ht_pos')->get();
        return view('cms/pages/library-files/order', compact('rows'));
    }

    public function orderSubmit(Request $request)
    {
        foreach ($request->id as $key => $id) {
            $row = LibraryFile::findOrFail($id);
            $row->ht_pos = $request->ht_pos[$key];
            $row->save();
        }

        return redirect(env('CMS_PREFIX', 'admin') . '/library-files')->with('success', 'Records ordered successfully');
    }
}