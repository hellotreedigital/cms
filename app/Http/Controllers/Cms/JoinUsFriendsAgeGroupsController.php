<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\JoinUsFriendsAgeGroup;


class JoinUsFriendsAgeGroupsController extends Controller
{
    public function index()
    {
        $rows = JoinUsFriendsAgeGroup::orderBy('ht_pos')->get();
        
        return view('cms/pages/join-us-friends-age-groups/index', compact('rows'));
    }

    public function create()
    {
        
        return view('cms/pages/join-us-friends-age-groups/create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title_en' => 'required',
			'title_ar' => 'required',
        ]);

        $row = new JoinUsFriendsAgeGroup;
        
		$row->title_en = $request->title_en;
		$row->title_ar = $request->title_ar;
		
        $row->save();

        return redirect(env('CMS_PREFIX', 'admin') . '/join-us-friends-age-groups')->with('success', 'Record added successfully');
    }

    public function show($id)
    {
        $row = JoinUsFriendsAgeGroup::findOrFail($id);
        
        return view('cms/pages/join-us-friends-age-groups/show', compact('row'));
    }

    public function edit($id)
    {
        $row = JoinUsFriendsAgeGroup::findOrFail($id);
        
        return view('cms/pages/join-us-friends-age-groups/edit', compact('row'));
    }

    public function update(Request $request, $id)
    {
        $row = JoinUsFriendsAgeGroup::findOrFail($id);

        $request->validate([
            'title_en' => 'required',
			'title_ar' => 'required',
        ]);

        $row->title_en = $request->title_en;
		$row->title_ar = $request->title_ar;
		
        $row->save();

        return redirect(env('CMS_PREFIX', 'admin') . '/join-us-friends-age-groups')->with('success', 'Record edited successfully');
    }

    public function destroy($id)
    {
        $array = explode(',', $id);
        foreach ($array as $id) JoinUsFriendsAgeGroup::destroy($id);
        return redirect(env('CMS_PREFIX', 'admin') . '/join-us-friends-age-groups')->with('success', 'Record deleted successfully');
    }

    public function orderIndex()
    {
        $rows = JoinUsFriendsAgeGroup::orderBy('ht_pos')->get();
        return view('cms/pages/join-us-friends-age-groups/order', compact('rows'));
    }

    public function orderSubmit(Request $request)
    {
        foreach ($request->id as $key => $id) {
            $row = JoinUsFriendsAgeGroup::findOrFail($id);
            $row->ht_pos = $request->ht_pos[$key];
            $row->save();
        }

        return redirect(env('CMS_PREFIX', 'admin') . '/join-us-friends-age-groups')->with('success', 'Records ordered successfully');
    }
}