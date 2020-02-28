<?php

namespace Hellotreedigital\Cms\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hellotreedigital\Cms\Models\CmsPage;

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
    	->when($request['per_page'], function($query) use($request){
            return $query->paginate($request['per_page']);
        }, function($query) {
	        return $query->get();
        });

        // Check for images in form fields
        foreach (json_decode($page['fields'], true) as $page_field) {
            if ($page_field['form_field'] == 'image') {
                foreach ($rows as $r => $row) {
                    $rows[$r][$page_field['name']] = asset($rows[$r][$page_field['name']]);
                }
            }
        }

        // Check for images in translatable form fields
        foreach (json_decode($page['translatable_fields'], true) as $page_field) {
            if ($page_field['form_field'] == 'image') {
                foreach ($rows as $r => $row) {
                    foreach (config('translatable.locales') as $locale) {
                        $row->translate($locale)[$page_field['name']] = asset($row->translate($locale)[$page_field['name']]);
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
            if ($page_field['form_field'] == 'image') {
                $row[$page_field['name']] = asset($row[$page_field['name']]);
            }
        }

        // Check for images in translatable form fields
        foreach (json_decode($page['translatable_fields'], true) as $page_field) {
            if ($page_field['form_field'] == 'image') {
                foreach (config('translatable.locales') as $locale) {
                    $row->translate($locale)[$page_field['name']] = asset($row->translate($locale)[$page_field['name']]);
                }
            }
        }

    	return $row;
    }
}
