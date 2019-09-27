<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\News;
use App\NewsCategory;


class NewsController extends Controller
{
    public function index()
    {
        $rows = News::get();
        $news_categories = [];
	    $news_categories_db = NewsCategory::get()->toArray();
	    foreach ($news_categories_db as $single_news_categories_db) $news_categories[$single_news_categories_db['id']] = $single_news_categories_db;
        return view('cms/pages/news/index', compact('rows', 'news_categories'));
    }

    public function create()
    {
        $news_categories = NewsCategory::get()->toArray();
        return view('cms/pages/news/create', compact('news_categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'slug' => 'required',
			'title_en' => 'required',
			'title_ar' => 'required',
			'date' => 'required',
			'text_en' => 'required',
			'text_ar' => 'required',
			'image' => 'required|image',
			'news_category_id' => 'required',
			'show_home' => '',
			'show_slider' => '',
			'pin' => '',
        ]);

        $row = new News;
        
		$row->slug = $request->slug;
		$row->title_en = $request->title_en;
		$row->title_ar = $request->title_ar;
		$row->date = $request->date;
		$row->text_en = $request->text_en;
		$row->text_ar = $request->text_ar;
		if ($request->image) {
            $image = time() . '_' . md5(rand()) . '.' . request()->image->getClientOriginalExtension();
            $request->image->move(storage_path('app/public/news'), $image);
            $row->image = 'storage/news/' . $image;
        }
        $row->news_category_id = $request->news_category_id;
		$row->show_home = ($request->show_home) ? 1 : 0;
		$row->show_slider = ($request->show_slider) ? 1 : 0;
		$row->pin = ($request->pin) ? 1 : 0;
		
        $row->save();

        return redirect(env('CMS_PREFIX', 'admin') . '/news')->with('success', 'Record added successfully');
    }

    public function show($id)
    {
        $row = News::findOrFail($id);
        $news_categories = [];
	    $news_categories_db = NewsCategory::get()->toArray();
	    foreach ($news_categories_db as $single_news_categories_db) $news_categories[$single_news_categories_db['id']] = $single_news_categories_db;
        return view('cms/pages/news/show', compact('row', 'news_categories'));
    }

    public function edit($id)
    {
        $row = News::findOrFail($id);
        $news_categories = NewsCategory::get()->toArray();
        return view('cms/pages/news/edit', compact('row', 'news_categories'));
    }

    public function update(Request $request, $id)
    {
        $row = News::findOrFail($id);

        $request->validate([
            'slug' => 'required',
			'title_en' => 'required',
			'title_ar' => 'required',
			'date' => 'required',
			'text_en' => 'required',
			'text_ar' => 'required',
			'image' => 'required_with:remove_file_image|image',
			'news_category_id' => 'required',
			'show_home' => '',
			'show_slider' => '',
			'pin' => '',
        ]);

        $row->slug = $request->slug;
		$row->title_en = $request->title_en;
		$row->title_ar = $request->title_ar;
		$row->date = $request->date;
		$row->text_en = $request->text_en;
		$row->text_ar = $request->text_ar;
		if ($request->remove_file_image) {
			$row->image = '';
		} elseif ($request->image) {
            $image = time() . '_' . md5(rand()) . '.' . request()->image->getClientOriginalExtension();
            $request->image->move(storage_path('app/public/news'), $image);
            $row->image = 'storage/news/' . $image;
        }
        $row->news_category_id = $request->news_category_id;
		$row->show_home = ($request->show_home) ? 1 : 0;
		$row->show_slider = ($request->show_slider) ? 1 : 0;
		$row->pin = ($request->pin) ? 1 : 0;
		
        $row->save();

        return redirect(env('CMS_PREFIX', 'admin') . '/news')->with('success', 'Record edited successfully');
    }

    public function destroy($id)
    {
        $array = explode(',', $id);
        foreach ($array as $id) News::destroy($id);
        return redirect(env('CMS_PREFIX', 'admin') . '/news')->with('success', 'Record deleted successfully');
    }

    
}