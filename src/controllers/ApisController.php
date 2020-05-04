<?php

namespace Hellotreedigital\Cms\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hellotreedigital\Cms\Models\CmsPage;
use App;

class ApisController extends Controller
{
    public function index(Request $request, $route)
    {
    	$request->validate([
    		'custom_validation' => 'array',
    		'custom_validation.*.column' => 'required',
    		'custom_validation.*.constraint' => 'required',
    		'custom_validation.*.value' => 'required',
    	]);
	    
    	if ($request['locale']) App::setLocale($request['locale']);

    	$page = CmsPage::where('route', $route)->firstOrFail();

    	$model = 'App\\' . $page['model_name'];

    	$rows = $model::select('*')
    	->when($page['order_display'], function($query) use($page){
            return $query->orderBy('ht_pos');
        })
    	->when($request['custom_validation'], function($query) use($request){
    		foreach ($request['custom_validation'] as $validation) {
    			$query = $query->{$validation['constraint']}($validation['column'], $validation['value']);
    		}
    		return $query;
        })
    	->when($request['locale'], function($query) use($request){
    		return $query->withTranslation();
        })
    	->when($request['per_page'], function($query) use($request){
            return $query->paginate($request['per_page']);
        }, function($query) {
	        return $query->get();
        });

        // Check for images or files in form fields
        foreach (json_decode($page['fields'], true) as $page_field) {
            if ($page_field['form_field'] == 'image' || $page_field['form_field'] == 'file') {
                foreach ($rows as $r => $row) {
                    $rows[$r][$page_field['name']] = $rows[$r][$page_field['name']] ? asset($rows[$r][$page_field['name']]) : null;
                }
            }
        }

        // Check for images or files in translatable form fields
        foreach (json_decode($page['translatable_fields'], true) as $page_field) {
            if ($page_field['form_field'] == 'image' || $page_field['form_field'] == 'file') {
                foreach ($rows as $r => $row) {
                    foreach (config('translatable.locales') as $locale) {
                        $row->translate($locale)[$page_field['name']] = $row->translate($locale)[$page_field['name']] ? asset($row->translate($locale)[$page_field['name']]) : null;
                    }
                }
            }
        }

        return $rows;
    }

    public function single(Request $request, $id, $route)
    {
        $request->validate([
            'custom_validation' => 'array',
            'custom_validation.*.column' => 'required',
            'custom_validation.*.constraint' => 'required',
            'custom_validation.*.value' => 'required',
        ]);

    	$page = CmsPage::where('route', $route)->firstOrFail();

    	$model = 'App\\' . $page['model_name'];

    	$row = $model::select('*')
    	->when($request['custom_validation'], function($query) use($request){
    		foreach ($request['custom_validation'] as $validation) {
    			$query = $query->{$validation['constraint']}($validation['column'], $validation['value']);
    		}
    		return $query;
        })
		->findOrFail($id);

        // Check for images in form fields
        foreach (json_decode($page['fields'], true) as $page_field) {
            if ($page_field['form_field'] == 'image' || $page_field['form_field'] == 'file') {
                $row[$page_field['name']] = $row[$page_field['name']] ? asset($row[$page_field['name']]) : null;
            }
        }

        // Check for images in translatable form fields
        foreach (json_decode($page['translatable_fields'], true) as $page_field) {
            if ($page_field['form_field'] == 'image' || $page_field['form_field'] == 'file') {
                foreach (config('translatable.locales') as $locale) {
                    $row->translate($locale)[$page_field['name']] = $row->translate($locale)[$page_field['name']] ? asset($row->translate($locale)[$page_field['name']]) : null;
                }
            }
        }

    	return $row;
    }
}
