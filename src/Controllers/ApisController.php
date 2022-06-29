<?php

namespace Hellotreedigital\Cms\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hellotreedigital\Cms\Models\CmsPage;
use Hellotreedigital\Cms\Models\Language;
use Illuminate\Support\Facades\Storage;
use App;

class ApisController extends Controller
{
    public function index(Request $request, $route)
    {
        $request->validate([
            'custom_validation' => 'array',
            'custom_validation.*.constraint' => 'required',
            'custom_validation.*.value' => 'required',
        ]);

        if ($request['locale']) App::setLocale($request['locale']);

        $page = CmsPage::where('route', $route)->firstOrFail();
        $translatable_fields = json_decode($page['translatable_fields']);

        $model = 'App\\' . $page['model_name'];

        $order_by = null;
        if ($request['order_by']) $order_by = $request['order_by'];
        elseif ($page['order_display']) $order_by = 'ht_pos';

        $order_dir = $request['order_dir'] ? $request['order_dir'] : 'asc';

        $rows = $model::select('*')
            ->when($order_by, function ($query) use ($order_by, $order_dir) {
                return $query->orderBy($order_by, $order_dir);
            })
            ->when($request['custom_validation'], function ($query) use ($request) {
                foreach ($request['custom_validation'] as $validation) {
                    if (!in_array($validation['constraint'], [
                        'where',
                        'orWhere',
                        'whereIn',
                        'whereNotIn',
                        'has',
                        'doesntHave',
                        'with',
                        'whereTranslationLike',
                        'orderByTranslation',
                    ])) abort(403, $validation['constraint'] . ' not supported');
                    $query = call_user_func_array([$query, $validation['constraint']], $validation['value']);
                }
                return $query;
            })
            ->when($request['locale'] && count($translatable_fields), function ($query) use ($request) {
                return $query->withTranslation();
            })
            ->when($request['per_page'], function ($query) use ($request) {
                return $query->paginate($request['per_page']);
            }, function ($query) {
                return $query->get();
            });

        // Check for images or files in form fields
        foreach (json_decode($page['fields'], true) as $page_field) {
            if ($page_field['form_field'] == 'image' || $page_field['form_field'] == 'file') {
                foreach ($rows as $r => $row) {
                    $rows[$r][$page_field['name']] = $rows[$r][$page_field['name']] ? Storage::url($rows[$r][$page_field['name']]) : null;
                }
            }
        }

        // Check for multiple images in form fields
        foreach (json_decode($page['fields'], true) as $page_field) {
            if ($page_field['form_field'] == 'multiple images') {
                foreach ($rows as $r => $row) {
                    $new_array = json_decode($rows[$r][$page_field['name']]);
                    foreach($new_array as $i => $path) $new_array[$i] = Storage::url($path);
                    $rows[$r][$page_field['name']] = $new_array;
                }
            }
        }

        // Check for images or files in translatable form fields
        foreach (json_decode($page['translatable_fields'], true) as $page_field) {
            if ($page_field['form_field'] == 'image' || $page_field['form_field'] == 'file') {
                foreach ($rows as $r => $row) {
                    $row->getTranslation()[$page_field['name']] = $row->getTranslation()[$page_field['name']] ? Storage::url($row->getTranslation()[$page_field['name']]) : null;
                }
            }
        }

        // Check for multiple images in translatable form fields
        foreach (json_decode($page['translatable_fields'], true) as $page_field) {
            if ($page_field['form_field'] == 'multiple images') {
                foreach ($rows as $r => $row) {
                    $new_array = json_decode($rows[$r][$page_field['name']]);
                    foreach($new_array as $i => $path) $new_array[$i] = Storage::url($row->getTranslation()[$page_field['name']]);
                    $row->getTranslation()[$page_field['name']] = $new_array;
                }
            }
        }

        if ($request['pluck']) {
            return call_user_func_array([$rows, 'pluck'], $request['pluck']);
        } elseif ($request['key_by']) {
            return call_user_func_array([$rows, 'keyBy'], $request['key_by']);
        } else {
            return $rows;
        }
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
            ->when($request['custom_validation'], function ($query) use ($request) {
                foreach ($request['custom_validation'] as $validation) {
                    $query = $query->{$validation['constraint']}($validation['column'], $validation['value']);
                }
                return $query;
            })
            ->findOrFail($id);

        // Check for images in form fields
        foreach (json_decode($page['fields'], true) as $page_field) {
            if ($page_field['form_field'] == 'image' || $page_field['form_field'] == 'file') {
                $row[$page_field['name']] = $row[$page_field['name']] ? Storage::url($row[$page_field['name']]) : null;
            }
        }

        // Check for images in translatable form fields
        foreach (json_decode($page['translatable_fields'], true) as $page_field) {
            if ($page_field['form_field'] == 'image' || $page_field['form_field'] == 'file') {
                foreach (Language::get() as $language) {
                    $row->getTranslation()[$page_field['name']] = $row->getTranslation()[$page_field['name']] ? Storage::url($row->getTranslation()[$page_field['name']]) : null;
                }
            }
        }

        return $row;
    }
}
