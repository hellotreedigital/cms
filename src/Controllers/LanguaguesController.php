<?php

namespace Hellotreedigital\Cms\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hellotreedigital\Cms\Models\AdminRolePermission;
use Hellotreedigital\Cms\Models\AdminRole;
use Hellotreedigital\Cms\Models\CmsPage;
use Hellotreedigital\Cms\Models\Language;


class LanguaguesController extends Controller
{
    public function index()
    {
        $rows = Language::get();
        return view('cms::pages/languages/index', compact('rows'));
    }

    public function create()
    {
        $cms_pages = [];
        return view('cms::pages/languages/form', compact(
            'cms_pages'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'slug' => 'required',
            'title' => 'required',
            'direction' => 'required',
        ]);

        $row = new Language();
        $row->slug = $request->slug;
        $row->title = $request->title;
        $row->direction = $request->direction;
        $row->save();

        $request->session()->flash('success', 'Record added successfully');
        return url(config('hellotree.cms_route_prefix') . '/languages');
    }

    public function show($id)
    {
        $row = Language::findOrFail($id);
        return view('cms::pages/languages/show', compact('row'));
    }

    public function edit($id)
    {
        $row = Language::findOrFail($id);
        return view('cms::pages/languages/form', compact(
            'row'
        ));
    }

    public function update(Request $request, $id)
    {
        $row = Language::findOrFail($id);

        $request->validate([
            'slug' => 'required',
            'title' => 'required',
            'direction' => 'required',
        ]);

        $row->slug = $request->slug;
        $row->title = $request->title;
        $row->direction = $request->direction;
        $row->save();

        $request->session()->flash('success', 'Record edited successfully');
        return url(config('hellotree.cms_route_prefix') . '/languages');
    }

    public function destroy($id)
    {
        $array = explode(',', $id);
        if (count($array) == Language::count()) return redirect(config('hellotree.cms_route_prefix') . '/languages')->with('error', 'Record deleted successfully');
        foreach ($array as $id) Language::destroy($id);
        return redirect(config('hellotree.cms_route_prefix') . '/languages')->with('success', 'Record deleted successfully');
    }
}
