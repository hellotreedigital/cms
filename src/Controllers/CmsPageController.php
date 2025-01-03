<?php

namespace Hellotreedigital\Cms\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hellotreedigital\Cms\Models\CmsPage;
use Hellotreedigital\Cms\Models\Language;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Hash;

class CmsPageController extends Controller
{
    public $appends_to_query;

    public function __construct()
    {
        $this->appends_to_query = '';
        if (
            request('page') ||
            request('per_page') ||
            request('custom_search') ||
            request('sort_by') ||
            request('sort_by_direction')
        ) $this->appends_to_query .= '?';
        if (request('page')) $this->appends_to_query .= 'page=' . request('page') . '&';
        if (request('per_page')) $this->appends_to_query .= 'per_page=' . request('per_page') . '&';
        if (request('custom_search')) $this->appends_to_query .= 'custom_search=' . request('custom_search') . '&';
        if (request('sort_by')) $this->appends_to_query .= 'sort_by=' . request('sort_by') . '&';
        if (request('sort_by_direction')) $this->appends_to_query .= 'sort_by_direction=' . request('sort_by_direction') . '&';
    }

    public function index($route)
    {
        $page = CmsPage::where('route', $route)->firstOrFail();
        $page_fields = json_decode($page['fields'], true);
        $extra_variables = $this->getPageExtraVariables($page_fields);

        $model = 'App\\' . $page['model_name'];
        if ($page['single_record']) {
            $row = $model::withoutGlobalScope('cms_draft_flag')->first();
            if (!$row) abort(403, "Single record page has no record");
            return redirect(config('hellotree.cms_route_prefix') . '/' . $route . '/' . $row['id']);
        }

        // Default order
        $order_by = 'id';
        $order_direction = 'desc';
        $order_by_column_relationship = null;

        if (request('sort_by')) {
            foreach ($page_fields as $page_field) {
                if ($page_field['name'] == request('sort_by')) {
                    if ($page_field['form_field'] == 'select' || $page_field['form_field'] == 'select multiple') {
                        $order_by_column_relationship = $page_field;
                    }
                }
            }
            if (!$order_by_column_relationship) {
                $order_by = request('sort_by');
                $order_direction = request('sort_by_direction');
            }
        } else {
            if ($page['sort_by']) {
                $order_by = $page['sort_by'];
                $order_direction = $page['sort_by_direction'];
            } elseif ($page['order_display']) {
                $order_by = 'ht_pos';
                $order_direction = 'asc';
            }
        }

        $rows = $model::withoutGlobalScope('cms_draft_flag')->select($page->database_table . '.*')
            ->when($page['order_display'], function ($query) use ($page) {
                return $query->orderBy($page->database_table . '.' . 'ht_pos');
            })
            ->when(request('custom_validation'), function ($query) use ($page) {
                foreach (request('custom_validation') as $validation) {
                    if ($validation['constraint'] == 'whereHas' && isset($validation['value'][1]) && count($validation['value'][1])) {
                        // Didn't use whereHas because it is making issues on same table relationship
                        $pivot_table = Str::singular($validation['value'][0]) . '_' . Str::singular($page->database_table);
                        $column_name = $validation['table'] == $page->database_table ? 'other_' . Str::singular($validation['table']) . '_id' : Str::singular($validation['table']) . '_id';
                        $second_table = uniqid();
                        $second_table = str_replace('e','a',$second_table);

                        $query
                            ->join($pivot_table, $pivot_table . '.' . Str::singular($page->database_table) . '_id', $page->database_table . '.id')
                            ->join($validation['table'] . ' as ' . $second_table, $pivot_table . '.' . $column_name, $second_table . '.id')
                            ->whereRaw($second_table . '.id in (' . implode(',', $validation['value'][1]) . ')');
                    } else {
                        if (isset($validation['value'][1]) && $validation['value'][1]) {
                            $query = call_user_func_array([$query, $validation['constraint']], $validation['value']);
                        }
                    }
                }
                return $query;
            })
            ->when(request('custom_search'), function ($query) use ($page_fields) {
                foreach ($page_fields as $field) {
                    if (
                        $field['form_field'] == 'password' ||
                        $field['form_field'] == 'password with confirmation' ||
                        $field['form_field'] == 'select' ||
                        $field['form_field'] == 'select multiple' ||
                        $field['form_field'] == 'checkbox' ||
                        $field['form_field'] == 'image' ||
                        $field['form_field'] == 'multiple images' ||
                        $field['form_field'] == 'file' ||
                        $field['form_field'] == 'map coordinates'
                    ) continue;
                    $query->orWhere($field['name'], 'like', '%' . request('custom_search') . '%');
                }
                return $query;
            })
            ->when(
                $order_by_column_relationship,
                function ($query) use ($order_by_column_relationship, $page) {
                    $query->when(
                        $order_by_column_relationship['form_field'] == 'select',
                        function ($query) use ($order_by_column_relationship, $page) {
                            $query
                                ->leftJoin($order_by_column_relationship['form_field_additionals_1'], $order_by_column_relationship['form_field_additionals_1'] . '.id', '=', $page['database_table'] . '.' . $order_by_column_relationship['name'])
                                ->orderBy($order_by_column_relationship['form_field_additionals_1'] . '.' . $order_by_column_relationship['form_field_additionals_2'], request('sort_by_direction'));
                        },
                        function ($query) {
                        }
                    );
                },
                function ($query) use ($page, $order_by, $order_direction) {
                    $query->orderBy($page->database_table . '.' . $order_by, $order_direction);
                }
            )
            ->when($page['server_side_pagination'], function ($query) {
                return $query->paginate(request('per_page') ? request('per_page') : 10);
            }, function ($query) {
                return $query->get();
            });

        $appends_to_query = $this->appends_to_query;

        $view = view()->exists('cms::pages/' . $route . '/index') ? 'cms::pages/' . $route . '/index' : 'cms::pages/cms-page/index';
        return view($view, compact('page', 'page_fields', 'rows', 'extra_variables', 'appends_to_query'));
    }

    public function getPageExtraVariables($page_fields)
    {
        $extra_variables = [];
        foreach ($page_fields as $field) {
            if ($field['form_field'] == 'select' || $field['form_field'] == 'select multiple') {
                // Get model name from database table
                $extra_page = CmsPage::where('database_table', $field['form_field_additionals_1'])->first();
                if (!$extra_page) abort(403, 'Cms page not found for `database_table` ' . $field['form_field_additionals_1']);
                $extra_model = 'App\\' . $extra_page['model_name'];
                $extra_variables[$field['form_field_additionals_1']] = $extra_model::get();
            }
        }
        return $extra_variables;
    }

    public function create($route)
    {
        $page = CmsPage::where('route', $route)->when(request()->get('admin')['admin_role_id'], function ($query) {
            $query->where('add', 1);
        })->firstOrFail();
        $page_fields = json_decode($page['fields'], true);
        $page_translatable_fields = json_decode($page['translatable_fields'], true);
        $extra_variables = $this->getPageExtraVariables($page_fields);

        $view = view()->exists('cms::pages/' . $route . '/form') ? 'cms::pages/' . $route . '/form' : 'cms::pages/cms-page/form';
        return view($view, compact('page', 'page_fields', 'page_translatable_fields', 'extra_variables'));
    }

    public function storeValidation($page_fields, $page)
    {
        $validation_rules = [];
        foreach ($page_fields as $field) {
            if (isset($field['hide_create']) && $field['hide_create']) continue;
            if ($field['form_field'] == 'slug' && !$field['form_field_additionals_2'] && !$field['unique']) continue;

            $validation_rules[$field['name']] = '';

            if (!$field['nullable']) $validation_rules[$field['name']] .= 'required|';
            if (isset($field['unique']) && $field['unique']) $validation_rules[$field['name']] .= 'unique:' . $page['database_table'] . '|';
            if ($field['form_field'] == 'image') $validation_rules[$field['name']] .= 'image|';
            if ($field['form_field'] == 'multiple images') $validation_rules[$field['name']] .= 'array|';
            if ($field['form_field'] == 'password with confirmation') $validation_rules[$field['name']] .= 'confirmed|';
            if ($field['form_field'] == 'number') $validation_rules[$field['name']] .= 'numeric|';
            if ($field['form_field'] == 'number' && $field['nullable']) $validation_rules[$field['name']] .= 'nullable|';
            if ($field['migration_type'] == 'string' && ($field['form_field'] != 'number' && $field['form_field'] != 'image' && $field['form_field'] != 'file')) $validation_rules[$field['name']] .= 'max:191|';

            if (strlen($validation_rules[$field['name']]) > 0) $validation_rules[$field['name']] = substr($validation_rules[$field['name']], 0, -1);
        }
        return $validation_rules;
    }

    public function translateOrNew($translatable_fields, $request, $row)
    {
        // Translatable insert query
        if (count($translatable_fields)) {
            foreach (Language::get() as $language) {
                foreach ($translatable_fields as $field) {
                    if ($field['form_field'] == 'select multiple') continue;
                    elseif ($field['form_field'] == 'password' || $field['form_field'] == 'password with confirmation') {
                        $row->translateOrNew($language->slug)->{$field['name']} = Hash::make($request[$language->slug][$field['name']]);
                    } elseif ($field['form_field'] == 'checkbox') {
                        $row->translateOrNew($language->slug)->{$field['name']} = isset($request[$language->slug][$field['name']]) ? 1 : 0;
                    } elseif ($field['form_field'] == 'time') {
                        $row->translateOrNew($language->slug)->{$field['name']} = date('H:i', strtotime($request[$language->slug][$field['name']]));
                    } elseif ($field['form_field'] == 'image' || $field['form_field'] == 'file') {
                        if (isset($request[$language->slug][$field['name']]) && $request[$language->slug][$field['name']]) {
                            $row->translateOrNew($language->slug)->{$field['name']} = $this->uploadFile($request->file($language->slug . '.' . $field['name']), $request['route']);
                        } elseif (isset($request[$language->slug]['remove_file_' . $field['name']]) && $request[$language->slug]['remove_file_' . $field['name']]) {
                            $row->translateOrNew($language->slug)->{$field['name']} = null;
                        }
                    } else {
                        $row->translateOrNew($language->slug)->{$field['name']} = isset($request[$language->slug][$field['name']]) ? $request[$language->slug][$field['name']] : null;
                    }
                }
            }
            $row->save();
        }
    }

    public function store(Request $request, $route)
    {
        $page = CmsPage::where('route', $route)->when(request()->get('admin')['admin_role_id'], function ($query) {
            $query->where('add', 1);
        })->firstOrFail();
        $page_fields = json_decode($page['fields'], true);
        $translatable_fields = json_decode($page['translatable_fields'], true);
        
        $field_validation_rules = [];
        $translatable_field_validation_rules = [];
        // Request validation
        if(!isset($request->draft_cms_field) || (isset($request->draft_cms_field) && $request->draft_cms_field != 1)){
            $field_validation_rules = $this->storeValidation($page_fields, $page);
            $translatable_field_validation_rules = $this->storeValidation($translatable_fields, $page);
        }

        $translatable_field_validation_rules_languages = [];
        if(!isset($request->draft_cms_field) || (isset($request->draft_cms_field) && $request->draft_cms_field != 1)){
            foreach ($translatable_field_validation_rules as $translatable_field => $translatable_rule) {
                foreach (Language::get() as $language) {
                    $translatable_field_validation_rules_languages[$language->slug . '.' . $translatable_field] = $translatable_rule;
                }
            }
        }

        $validation_rules = array_merge($field_validation_rules, $translatable_field_validation_rules_languages);
        $request->validate($validation_rules);

        // Check if preview mode
        if ($request->ht_preview_mode) return $this->previewMode($page, $request);

        // Insert query
        $query = [];
        foreach ($page_fields as $field) {
            if ($field['form_field'] == 'select multiple' || isset($field['hide_create']) && $field['hide_create']) continue;
            elseif ($field['form_field'] == 'password' || $field['form_field'] == 'password with confirmation') {
                $query[$field['name']] = Hash::make($request[$field['name']]);
            } elseif ($field['form_field'] == 'checkbox') {
                $query[$field['name']] = isset($request[$field['name']]) ? 1 : 0;
            } elseif ($field['form_field'] == 'time') {
                $query[$field['name']] = date('H:i', strtotime($request[$field['name']]));
            } elseif ($field['form_field'] == 'image' || $field['form_field'] == 'file') {
                if ($request[$field['name']]) {
                    $query[$field['name']] = $this->uploadFile($request->file($field['name']), $route);
                }
            } elseif ($field['form_field'] == 'multiple images') {
                $query[$field['name']] = $request[$field['name']] ? json_encode($request[$field['name']]) : '[]';
            } else {
                $query[$field['name']] = $request[$field['name']];
            }
        }

        // Model
        $model = 'App\\' . $page['model_name'];

        // Get ht_pos
        if ($page['order_display']) $query['ht_pos'] = $model::withoutGlobalScope('cms_draft_flag')->min('ht_pos') - 1;

        // Create
        $row = $model::create($query);

        // Select multiple insert query
        foreach ($page_fields as $field) {
            if ($field['form_field'] == 'select multiple') {
                // No need for ht_pos here because it's being saved by order
                $row->{str_replace('_id', '', $field['name'])}()->sync($request[$field['name']]);
            }
        }
        if(isset($request->draft_cms_field) && $request->draft_cms_field == 1){
            $row->cms_draft_flag = 1;
            $row->save();
        }else{
            $row->cms_draft_flag = 0;
            $row->save();
        }
        
        $this->translateOrNew($translatable_fields, $request, $row);

        $request->session()->flash('success', 'Record added successfully');
        return url(config('hellotree.cms_route_prefix') . '/' . $route);
    }

    public function show($id, $route)
    {
        $page = CmsPage::where('route', $route)->where('show', 1)->firstOrFail();
        $page_fields = json_decode($page['fields'], true);
        $translatable_fields = json_decode($page['translatable_fields'], true);

        $model = 'App\\' . $page['model_name'];
        $row = $model::withoutGlobalScope('cms_draft_flag')->findOrFail($id);

        $view = view()->exists('cms::pages/' . $route . '/show') ? 'cms::pages/' . $route . '/show' : 'cms::pages/cms-page/show';
        return view($view, compact('page', 'page_fields', 'translatable_fields', 'row'));
    }

    public function edit($id, $route)
    {
        $page = CmsPage::where('route', $route)->when(request()->get('admin')['admin_role_id'], function ($query) {
            $query->where('edit', 1);
        })->firstOrFail();
        $page_fields = json_decode($page['fields'], true);
        $page_translatable_fields = json_decode($page['translatable_fields'], true);
        $extra_variables = $this->getPageExtraVariables($page_fields);

        $model = 'App\\' . $page['model_name'];
        $row = $model::withoutGlobalScope('cms_draft_flag')->findOrFail($id);

        $appends_to_query = $this->appends_to_query;

        $view = view()->exists('cms::pages/' . $route . '/form') ? 'cms::pages/' . $route . '/form' : 'cms::pages/cms-page/form';
        return view($view, compact('page', 'page_fields', 'page_translatable_fields', 'row', 'extra_variables', 'appends_to_query'));
    }

    public function updateValiation($page_fields, $database_table, $id, $row)
    {
        $validation_rules = [];
        foreach ($page_fields as $field) {
            if ((isset($field['hide_edit']) && $field['hide_edit'])) continue;
            if ($field['form_field'] == 'slug' && $field['form_field_additionals_2'] == 0 && !$field['unique']) continue;

            $validation_rules[$field['name']] = '';
            if (!$field['nullable'] && ($field['form_field'] != 'image' && $field['form_field'] != 'file' && $field['form_field'] != 'password with confirmation')) $validation_rules[$field['name']] .= 'required|';
            if (!$field['nullable'] && ($field['form_field'] == 'image' || $field['form_field'] == 'file')) $validation_rules[$field['name']] .= 'required_with:remove_file_' . $field['name'] . '|';
            if (isset($field['unique']) && $field['unique']) $validation_rules[$field['name']] .= 'unique:' . $database_table . ',' . $field['name'] . ',' . $id . '|';
            if ($field['form_field'] == 'image') $validation_rules[$field['name']] .= 'image|';
            if ($field['form_field'] == 'password with confirmation') $validation_rules[$field['name']] .= 'confirmed|';
            if ($field['form_field'] == 'number') $validation_rules[$field['name']] .= 'numeric|';
            if ($field['form_field'] == 'number' && $field['nullable']) $validation_rules[$field['name']] .= 'nullable|';
            if ($field['form_field'] == 'multiple images') $validation_rules[$field['name']] .= 'array|';
            if ($field['migration_type'] == 'string' && ($field['form_field'] != 'number' && $field['form_field'] != 'image' && $field['form_field'] != 'file')) $validation_rules[$field['name']] .= 'max:191|';

            if (strlen($validation_rules[$field['name']]) > 0) $validation_rules[$field['name']] = substr($validation_rules[$field['name']], 0, -1);
        }
        return $validation_rules;
    }

    public function update(Request $request, $id, $route)
    {
        $page = CmsPage::where('route', $route)->when(request()->get('admin')['admin_role_id'], function ($query) {
            $query->where('edit', 1);
        })->firstOrFail();
        $page_fields = json_decode($page['fields'], true);
        $page_translatable_fields = json_decode($page['translatable_fields'], true);
        $translatable_fields = json_decode($page['translatable_fields'], true);

        // Get row
        $model = 'App\\' . $page['model_name'];
        $row = $model::withoutGlobalScope('cms_draft_flag')->findOrFail($id);

        // Request validations
        
        $field_validation_rules = [];
        $translatable_field_validation_rules = [];
        
        if(!isset($request->draft_cms_field) || (isset($request->draft_cms_field) && $request->draft_cms_field != 1)){
            $field_validation_rules = $this->updateValiation($page_fields, $page['database_table'], $id, $row);
            $translatable_field_validation_rules = $this->updateValiation($translatable_fields, $page['database_table'] . '_translations', $id, $row);
        }
        
        $translatable_field_validation_rules_languages = [];
        if(!isset($request->draft_cms_field) || (isset($request->draft_cms_field) && $request->draft_cms_field != 1)){
            foreach ($translatable_field_validation_rules as $translatable_field => $translatable_rule) {
                foreach (Language::get() as $language) {
                    $translatable_field_validation_rules_languages[$language->slug . '.' . $translatable_field] = $translatable_rule;
                }
            }
        }

        $validation_rules = array_merge($field_validation_rules, $translatable_field_validation_rules_languages);
        $request->validate($validation_rules);

        // Check if preview mode
        if ($request->ht_preview_mode) return $this->previewMode($page, $request);

        // Update query
        $query = [];
        foreach ($page_fields as $field) {
            if ((!isset($request->draft_cms_field) || (isset($request->draft_cms_field) && $request->draft_cms_field != 1)) && ($field['form_field'] == 'slug' && !$field['form_field_additionals_2']) || $field['form_field'] == 'select multiple' || (isset($field['hide_edit']) && $field['hide_edit'])) continue;

            if (($field['form_field'] == 'password' || $field['form_field'] == 'password with confirmation')) {
                if ($request[$field['name']]) {
                    $query[$field['name']] = Hash::make($request[$field['name']]);
                }
            } elseif ($field['form_field'] == 'checkbox') {
                $query[$field['name']] = isset($request[$field['name']]) ? 1 : 0;
            } elseif ($field['form_field'] == 'time') {
                $query[$field['name']] = date('H:i', strtotime($request[$field['name']]));
            } elseif ($field['form_field'] == 'image' || $field['form_field'] == 'file') {
                if ($request[$field['name']]) {
                    $query[$field['name']] = $this->uploadFile($request->file($field['name']), $route);
                } elseif ($request['remove_file_' . $field['name']]) {
                    $query[$field['name']] = null;
                }
            } elseif ($field['form_field'] == 'multiple images') {
                $query[$field['name']] = $request[$field['name']] ? json_encode($request[$field['name']]) : '[]';
            } else {
                $query[$field['name']] = $request[$field['name']];
            }
        }
        $row->update($query);

        // Select multiple update query
        foreach ($page_fields as $field) {
            if ($field['form_field'] == 'select multiple') {
                $sync_values = [];
                if ($request[$field['name']]) {
                    foreach ($request[$field['name']] as $sync_id) {
                        $sync_values[$sync_id] = ['ht_pos' => $request['ht_pos'][$field['name']][$sync_id]];
                    }
                    try {
                        $row->{str_replace('_id', '', $field['name'])}()->sync($sync_values);
                    } catch (\Throwable $th) {
                        $row->{str_replace('_id', '', $field['name'])}()->sync($request[$field['name']]);
                    }
                } else {
                    $row->{str_replace('_id', '', $field['name'])}()->sync([]);
                }
            }
        }
        
        if(isset($request->draft_cms_field) && $request->draft_cms_field == 1){
            $row->cms_draft_flag = 1;
            $row->save();
        }else{
            $row->cms_draft_flag = 0;
            $row->save();
        }
        
        // Translatable update query
        $this->translateOrNew($translatable_fields, $request, $row);

        $request->session()->flash('success', 'Record edited successfully');
        return url(config('hellotree.cms_route_prefix') . '/' . $route . $this->appends_to_query);
    }

    public function uploadImages(Request $request, $route)
    {
        $files = [];
        if ($request['images']) {
            foreach ($request['images'] as $file) {
                $file_path = $this->uploadFile($file, $route);
                $files[] = [
                    'path' => $file_path,
                    'url' => Storage::url($file_path),
                ];
            }
        }
        return $files;
    }

    public function destroy($id, $route)
    {
        $page = CmsPage::where('route', $route)->when(request()->get('admin')['admin_role_id'], function ($query) {
            $query->where('delete', 1);
        })->firstOrFail();
        $model = 'App\\' . $page['model_name'];

        $array = explode(',', $id);
        foreach ($array as $id) {
            $record = $model::withoutGlobalScope('cms_draft_flag')->find($id);
            if ($record) {
                $record->delete();
            }
        }

        $appends_to_query = $this->appends_to_query;

        return redirect(config('hellotree.cms_route_prefix') . '/' . $route . $appends_to_query)->with('success', 'Record deleted successfully');
    }

    public function order($route)
    {
        $page = CmsPage::where('route', $route)->whereNotNull('order_display')->firstOrFail();
        $page_fields = json_decode($page['fields'], true);
        $page_translatable_fields = json_decode($page['translatable_fields'], true);

        $model = 'App\\' . $page['model_name'];

        if (!$page['order_display']) abort(404);

        $rows = $model::withoutGlobalScope('cms_draft_flag')->orderBy('ht_pos')->get();

        $view = view()->exists('cms::pages/' . $route . '/order') ? 'cms::pages/' . $route . '/order' : 'cms::pages/cms-page/order';
        return view($view, compact('page', 'page_fields', 'page_translatable_fields', 'rows'));
    }

    public function changeOrder(Request $request, $route)
    {
        $page = CmsPage::where('route', $route)->firstOrFail();
        $model = 'App\\' . $page['model_name'];

        foreach ($request['ht_pos'] as $id => $pos) {
            $row = $model::withoutGlobalScope('cms_draft_flag')->findOrFail($id);
            $row->ht_pos = $pos;
            $row->save();
        }

        return redirect(config('hellotree.cms_route_prefix') . '/' . $route)->with('success', 'Records ordered successfully');
    }

    public function compressImage($path)
    {
        if (config('hellotree.tinify.key')) {
            try {
                \Tinify\setKey(config('hellotree.tinify.key'));
                $source = \Tinify\fromFile(Storage::path($path));
                $source->toFile(Storage::path($path));
            } catch (\Exception $e) {
            }
        }
    }

    public function uploadFile($file, $route)
    {
        $path = null;

        if (config('hellotree.use_original_name')) {
            $name = $file->getClientOriginalName();
            $path = $file->storeAs($route . '/' . Str::uuid(), $name);
        } else {
            $path = $file->store($route);
        }

        $this->compressImage($path);

        return $path;
    }

    public function uploadCkeditorImages(Request $request)
    {
        $request->validate([
            'upload' => 'required|image'
        ]);

        $image = $request->file('upload')->store('ht-ck-images');
        $url = Storage::url($image);

        $this->compressImage($image);

        echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($request->CKEditorFuncNum, '$url', '');</script>";
    }

    public function previewMode($page, $request)
    {
        $request_assoc = $request->toArray();
        $request_json = json_encode($request_assoc);
        $request_obj = json_decode($request_json);
        session(['ht-preview-mode-request' => $request_obj]);
        return response()->json(url($page['preview_path']) . '?ht_preview_mode=1', 303);
    }
}
