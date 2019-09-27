<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\NewsSlide;
use App\News;


class NewsSlidesController extends Controller
{
    public function index()
    {
        $rows = NewsSlide::orderBy('ht_pos')->get();
        $news = [];
	    $news_db = News::get()->toArray();
	    foreach ($news_db as $single_news_db) $news[$single_news_db['id']] = $single_news_db;
        return view('cms/pages/news-slides/index', compact('rows', 'news'));
    }

    public function create()
    {
        $news = News::get()->toArray();
        return view('cms/pages/news-slides/create', compact('news'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image',
			'news_id' => 'required',
        ]);

        $row = new NewsSlide;
        
		if ($request->image) {
            $image = time() . '_' . md5(rand()) . '.' . request()->image->getClientOriginalExtension();
            $request->image->move(storage_path('app/public/news-slides'), $image);
            $row->image = 'storage/news-slides/' . $image;
        }
        $row->news_id = $request->news_id;
		
        $row->save();

        return redirect(env('CMS_PREFIX', 'admin') . '/news-slides')->with('success', 'Record added successfully');
    }

    public function show($id)
    {
        $row = NewsSlide::findOrFail($id);
        $news = [];
	    $news_db = News::get()->toArray();
	    foreach ($news_db as $single_news_db) $news[$single_news_db['id']] = $single_news_db;
        return view('cms/pages/news-slides/show', compact('row', 'news'));
    }

    public function edit($id)
    {
        $row = NewsSlide::findOrFail($id);
        $news = News::get()->toArray();
        return view('cms/pages/news-slides/edit', compact('row', 'news'));
    }

    public function update(Request $request, $id)
    {
        $row = NewsSlide::findOrFail($id);

        $request->validate([
            'image' => 'required_with:remove_file_image|image',
			'news_id' => 'required',
        ]);

        if ($request->remove_file_image) {
			$row->image = '';
		} elseif ($request->image) {
            $image = time() . '_' . md5(rand()) . '.' . request()->image->getClientOriginalExtension();
            $request->image->move(storage_path('app/public/news-slides'), $image);
            $row->image = 'storage/news-slides/' . $image;
        }
        $row->news_id = $request->news_id;
		
        $row->save();

        return redirect(env('CMS_PREFIX', 'admin') . '/news-slides')->with('success', 'Record edited successfully');
    }

    public function destroy($id)
    {
        $array = explode(',', $id);
        foreach ($array as $id) NewsSlide::destroy($id);
        return redirect(env('CMS_PREFIX', 'admin') . '/news-slides')->with('success', 'Record deleted successfully');
    }

    public function orderIndex()
    {
        $rows = NewsSlide::orderBy('ht_pos')->get();
        return view('cms/pages/news-slides/order', compact('rows'));
    }

    public function orderSubmit(Request $request)
    {
        foreach ($request->id as $key => $id) {
            $row = NewsSlide::findOrFail($id);
            $row->ht_pos = $request->ht_pos[$key];
            $row->save();
        }

        return redirect(env('CMS_PREFIX', 'admin') . '/news-slides')->with('success', 'Records ordered successfully');
    }
}