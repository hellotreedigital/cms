<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CmsPage;
use Artisan;
use Str;
use Schema;

class CmsPagesController extends Controller
{
	public $migration_types = [
		'string',
		'date',
		'time',
		'text',
		'mediumText',
		'longText',
		'json',
		'integer',
		'bigInteger',
		'mediumInteger',
		'tinyInteger',
		'smallInteger',
		'boolean',
		'decimal',
		'double',
		'float',
	];

	public $form_fields = [
		'text',
		'slug',
		'textarea',
		'rich-textbox',
		'password',
		'password with confirmation',
		'email',
		'number',
		'date',
		'time',
		'select',
		'checkbox',
		'image',
		'file',
		'files',
		'map coordinates',
	];

	public function index()
	{
		$rows = CmsPage::all();
		return view('cms/pages/cms-pages/index', compact('rows'));
	}

	public function create()
	{
		return view('cms/pages/cms-pages/create', [
			'migration_types' => $this->migration_types,
			'form_fields' => $this->form_fields
		]);
	}

	public function store(Request $request, $edit=false)
	{
		$validation_rules = [
			'database_table' => 'required',
			'display_name' => 'required',
			'display_name_plural' => 'required',
			'model_name' => 'required',
			'controller_name' => 'required',
			'icon' => 'required_with:controller_name',
			'page_type' => 'required',
			'name' => 'required',
		];
		if (!$edit) {
			$validation_rules['database_table'] .= '|unique:cms_pages';
			$validation_rules['model_name'] .= '|unique:cms_pages';
			$validation_rules['controller_name'] .= '|unique:cms_pages';
		}
		$request->validate($validation_rules);

		$database_table = $request['database_table'];
		$route = Str::slug($database_table);
		$model_name = $request['model_name'];
		$controller_name = $request['controller_name'];
		$migration_name = date('Y_m_d_His') . '_create_' . $database_table . '_table.php';
		$display_name = $request['display_name'];
		$display_name_plural = $request['display_name_plural'];
		$model_name_plural = str_replace(' ', '', $display_name_plural);
		$order_column = $request['order_display'] ? 'ht_pos' : '';
		$order_display = $request['order_display'];
		$page_type = $request['page_type'];

		if ($edit) {
			$generate_migration = isset($request['edit_migration']) && $request['edit_migration'] ? true : false;
			$generate_model = isset($request['edit_model']) && $request['edit_model'] ? true : false;
			$generate_controller = isset($request['edit_controller']) && $request['edit_controller'] ? true : false;
			$generate_index_view = isset($request['edit_index_view']) && $request['edit_index_view'] ? true : false;
			$generate_create_view = isset($request['edit_create_view']) && $request['edit_create_view'] ? true : false;
			$generate_edit_view = isset($request['edit_edit_view']) && $request['edit_edit_view'] ? true : false;
			$generate_show_view = isset($request['edit_show_view']) && $request['edit_show_view'] ? true : false;
			$generate_order_view = $order_column ? (isset($request['edit_order_view']) && $request['edit_order_view'] ? true : false) : false;
			$generate_route = isset($request['edit_route']) && $request['edit_route'] ? true : false;
		} else {
			$generate_migration = true;
			$generate_model = true;
			$generate_controller = true;
			$generate_index_view = true;
			$generate_create_view = true;
			$generate_edit_view = true;
			$generate_show_view = true;
			$generate_order_view = true;
			$generate_route = true;
		}

		if ($page_type == 'show') {
			$generate_create_view = false;
			$generate_edit_view = false;
		}

		if ($page_type == 'fixed') {
			$generate_create_view = false;
		}

		if ($page_type == 'single') {
			$generate_index_view = false;
			$generate_create_view = false;
			$generate_order_view = false;
		}

		$fields = [];
		$additional_cms_pages = [];
		$order_display_form_field = '';
		for ($i=0; $i < count($request['name']); $i++) {
			// Check if field is unique
			foreach($fields as $field) if ($field['name'] == $request['name'][$i]) return redirect()->back()->withInput($request->toArray())->withErrors(['Column "' . $request['name'][$i] . '" already exists']);

			$fields[] = [
				'name' => $request['name'][$i],
				'migration_type' => $request['migration_type'][$i],
				'form_field' => $request['form_field'][$i],
				'form_field_additionals_1' => $request['form_field_additionals_1'][$i],
				'form_field_additionals_2' => $request['form_field_additionals_2'][$i],
				'nullable' => $request['nullable'][$i],
				'unique' => $request['unique'][$i],
			];
			if ($request['name'][$i] == $order_display) $order_display_form_field = $request['form_field'][$i];
			if ($request['form_field'][$i] == 'select') {
				$additional_cms_page = CmsPage::where('database_table', $request['form_field_additionals_1'][$i])->first();
				if (!$additional_cms_page) return redirect()->back()->withInput($request->toArray())->withErrors(['Database table not found in "' . $request['name'][$i] . '" field']);
				$additional_cms_pages[$additional_cms_page->database_table] = $additional_cms_page->toArray();
			}
		}
		if ($order_column && !$order_display_form_field) return redirect()->back()->withInput($request->toArray())->withErrors(['Order display column does not exist in the table fields']);

		if ($edit) {
			$old_page = CmsPage::where('database_table', $database_table)->first();
			if ($old_page) CmsPage::destroy($old_page->id);
		}

		if ($generate_migration) $this->generateMigration($database_table, $model_name_plural, $fields, $migration_name, $order_column);
		if ($generate_model) $this->generateModel($database_table, $model_name);
		if ($generate_controller) $this->generateController($model_name, $controller_name, $route, $fields, $database_table, $order_column, $additional_cms_pages, $page_type);

		if ($generate_index_view) $this->generateIndexView($route, $fields, $display_name_plural, $order_column, $page_type);
		elseif ($edit && ($page_type == 'single')) $this->deleteIndexView($old_page);

		if ($generate_create_view) $this->generateCreateView($route, $fields, $display_name_plural, $display_name, $order_column, $additional_cms_pages);
		elseif ($edit && ($page_type == 'show' || $page_type == 'fixed')) $this->deleteCreateView($old_page);

		if ($generate_edit_view) $this->generateEditView($route, $fields, $display_name_plural, $display_name);
		elseif ($edit && ($page_type == 'show')) $this->deleteEditView($old_page);

		if ($generate_show_view) $this->generateShowView($route, $fields, $display_name_plural, $display_name, $page_type);
		if ($generate_route) $this->generateRoute($route, $controller_name, $order_column);
		if ($generate_order_view) $this->generateOrderView($route, $display_name_plural, $order_display, $order_display_form_field);

		$cms_page = new CmsPage;
		$cms_page->id = isset($old_page) ? $old_page->id : null;
		$cms_page->icon = $request->icon;
		$cms_page->display_name = $display_name;
		$cms_page->display_name_plural = $display_name_plural;
		$cms_page->database_table = $database_table;
		$cms_page->route = $route;
		$cms_page->model_name = $model_name;
		$cms_page->controller_name = $controller_name;
		$cms_page->migration_name = $migration_name;
		$cms_page->order_display = $order_display;
		$cms_page->fields = json_encode($fields);
		$cms_page->page_type = $page_type;
		$cms_page->ht_pos = isset($old_page) ? $old_page['ht_pos'] : CmsPage::max('ht_pos') + 1;
		$cms_page->save();

		return redirect(env('CMS_PREFIX', 'admin') . '/' . $route)->with('success', 'Record added successfully');
	}

	public function edit($id)
	{
		$cms_page = CmsPage::findOrFail($id);
		$migration_types = $this->migration_types;
		$form_fields = $this->form_fields;

		return view('cms/pages/cms-pages/create', compact(
			'cms_page',
			'migration_types',
			'form_fields'
		));
	}

	public function update(Request $request)
	{
		return $this->store($request, true);
	}

	public function generateModel($database_table, $model_name)
	{
		file_put_contents(
			app_path('/' . $model_name . '.php'),
			str_replace(
				['%%database_table%%', '%%model_name%%'],
				[$database_table, $model_name],
				file_get_contents(resource_path('stubs/Model.stub'))
			)
		);
	}

	public function generateController($model_name, $controller_name, $route, $fields, $database_table, $order_column, $additional_cms_pages, $page_type)
	{
		// Used for first Controller generation
		if (!file_exists(app_path('Http/Controllers/Cms'))) mkdir(app_path('Http/Controllers/Cms'));

		$index_method_header = '';
		if ($page_type == 'single') {
			$index_method_header = '$single_record = ' . $model_name . '::first();
        if (!$single_record) return ' . "'" . 'No record found' . "'" . ';
        return redirect(env(' . "'" . 'CMS_PREFIX' . "'" . ', ' . "'" . 'admin' . "'" . ') . ' . "'" . '/' . $route . '/' . "'" . ' . $single_record->id);';
		}

		// Include main model
		$controller_header = 'use App\\' . $model_name . ';
';

		// Include Hash if password exists
		foreach ($fields as $field) {
			if ($field['form_field'] == 'password' || $field['form_field'] == 'password with confirmation') {
				$controller_header .= 'use Hash;
';
				break;
			}
		}

		// Include additional models
		foreach ($additional_cms_pages as $additional_cms_page) $controller_header .= 'use App\\' . $additional_cms_page['model_name'] . ';
';
		
		// Create view additional variables
		$create_additional_variables = '';
		$create_compact = '';
		foreach ($additional_cms_pages as $additional_cms_page) {
			$create_additional_variables .= '$' . $additional_cms_page['database_table'] . ' = ' . $additional_cms_page['model_name'] . '::get()->toArray();';

			if (!$create_compact) $create_compact = ', compact(';
			$create_compact .= "'" . $additional_cms_page['database_table'] . "', ";
		}
		if ($create_compact) $create_compact = substr($create_compact, 0, -2) . ')';
		
		// Edit view additional variables
		$edit_additional_variables = '';
		$edit_compact = '';
		foreach ($additional_cms_pages as $additional_cms_page) {
			$edit_additional_variables .= '$' . $additional_cms_page['database_table'] . ' = ' . $additional_cms_page['model_name'] . '::get()->toArray();';
			$edit_compact .= ", '" . $additional_cms_page['database_table'] . "'";
		}

		// Index view additional variables
		$index_compact = '';
		$index_additional_variables = '';
		foreach ($fields as $field) {
			if ($field['form_field'] == 'select') {
				$index_additional_variables .= '$' . $field['form_field_additionals_1'] . ' = [];
	    $' . $field['form_field_additionals_1'] . '_db = ' . $additional_cms_pages[$field['form_field_additionals_1']]['model_name'] . '::get()->toArray();
	    foreach ($' . $field['form_field_additionals_1'] . '_db as $single_' . $field['form_field_additionals_1'] . '_db) $' . $field['form_field_additionals_1'] . '[$single_' . $field['form_field_additionals_1'] . '_db[' . "'" . 'id' . "'" . ']] = $single_' . $field['form_field_additionals_1'] . '_db;';

	    		$index_compact .= ", '" . $field['form_field_additionals_1'] . "'";
			}
		}

		// Show view additional variables
		$show_compact = '';
		$show_additional_variables = '';
		foreach ($fields as $field) {
			if ($field['form_field'] == 'select') {
				$show_additional_variables .= '$' . $field['form_field_additionals_1'] . ' = [];
	    $' . $field['form_field_additionals_1'] . '_db = ' . $additional_cms_pages[$field['form_field_additionals_1']]['model_name'] . '::get()->toArray();
	    foreach ($' . $field['form_field_additionals_1'] . '_db as $single_' . $field['form_field_additionals_1'] . '_db) $' . $field['form_field_additionals_1'] . '[$single_' . $field['form_field_additionals_1'] . '_db[' . "'" . 'id' . "'" . ']] = $single_' . $field['form_field_additionals_1'] . '_db;';

	    		$show_compact .= ", '" . $field['form_field_additionals_1'] . "'";
			}
		}

		// Store validations
		$store_validation = '';
		foreach ($fields as $field) {
			$store_validation .= "'" . $field['name'] . "' => '";

			$validation_fields = '';
			if (!$field['nullable']) $validation_fields .= 'required|';
			if ($field['unique']) $validation_fields .= 'unique:' . $database_table . '|';
			if ($field['form_field'] == 'image') $validation_fields .= 'image|';
			if ($field['form_field'] == 'password with confirmation') $validation_fields .= 'confirmed|';
			$validation_fields = substr($validation_fields, 0, -1);

			$store_validation .= $validation_fields . "',
			";
		}
		$store_validation = substr($store_validation, 0, -4);

		// Store query
		$store_query = '
		';
		foreach ($fields as $field) {
			if ($field['form_field'] == 'password' || $field['form_field'] == 'password with confirmation') {
				$store_query .= '$row->' . $field['name'] . ' = Hash::make($request->' . $field['name'] . ');
		';
			} elseif ($field['form_field'] == 'checkbox') {
				$store_query .= '$row->' . $field['name'] . ' = ($request->' . $field['name'] . ') ? 1 : 0;
		';
			} elseif ($field['form_field'] == 'image' || $field['form_field'] == 'file') {
				$store_query .= 'if ($request->' . $field['name'] . ') {
            $image = time() . ' . "'" . '_' . "'" . ' . md5(rand()) . ' . "'" . '.' . "'" . ' . request()->' . $field['name'] . '->getClientOriginalExtension();
            $request->' . $field['name'] . '->move(storage_path(' . "'" . 'app/public/' . $route . "'" . '), $image);
            $row->' . $field['name'] . ' = ' . "'" . 'storage/' . $route . '/' . "'" . ' . $image;
        }
        ';
			} else {
				$store_query .= '$row->' . $field['name'] . ' = $request->' . $field['name'] . ';
		';
			}
		}

		// Update validations
		$update_validation = '';
		foreach ($fields as $key => $field) {
			$update_validation .= "'" . $field['name'] . "' => '";

			$validation_fields = '';
			if (!$field['nullable'] && ($field['form_field'] != 'image' && $field['form_field'] != 'file' && $field['form_field'] != 'password with confirmation')) $validation_fields .= 'required|';
			if (!$field['nullable'] && ($field['form_field'] == 'image' || $field['form_field'] == 'file')) $validation_fields .= 'required_with:remove_file_' . $field['name'] . '|';
			if ($field['unique']) $validation_fields .= 'unique:' . $database_table . ',' . $field['name'] . ',' . "' . " . '$row->id|';
			if ($field['form_field'] == 'image') $validation_fields .= 'image|';
			if ($field['form_field'] == 'password with confirmation') $validation_fields .= 'confirmed|';
			$validation_fields = substr($validation_fields, 0, -1);

			if (strpos($validation_fields, '$row->id') == false) $validation_fields .= "'";

			$update_validation .= $validation_fields . ",
			";
		}
		$update_validation = substr($update_validation, 0, -4);

		// Update query
		$update_query = '';
		foreach ($fields as $field) {
			if ($field['form_field'] == 'password' || $field['form_field'] == 'password with confirmation') {
				$update_query .= 'if ($request->' . $field['name'] . ') $row->' . $field['name'] . ' = Hash::make($request->' . $field['name'] . ');
		';
			} elseif ($field['form_field'] == 'checkbox') {
				$update_query .= '$row->' . $field['name'] . ' = ($request->' . $field['name'] . ') ? 1 : 0;
		';
			} elseif ($field['form_field'] == 'image' || $field['form_field'] == 'file') {
				$update_query .= 'if ($request->remove_file_' . $field['name'] . ') {
			$row->' . $field['name'] . ' = ' . "''" . ';
		} elseif ($request->' . $field['name'] . ') {
            $image = time() . ' . "'" . '_' . "'" . ' . md5(rand()) . ' . "'" . '.' . "'" . ' . request()->' . $field['name'] . '->getClientOriginalExtension();
            $request->' . $field['name'] . '->move(storage_path(' . "'" . 'app/public/' . $route . "'" . '), $image);
            $row->' . $field['name'] . ' = ' . "'" . 'storage/' . $route . '/' . "'" . ' . $image;
        }
        ';
			} else {
				$update_query .= '$row->' . $field['name'] . ' = $request->' . $field['name'] . ';
		';
			}
		}

		// Order methods
		$order_by = $order_column ? "orderBy('$order_column')->" : '';
		if ($order_column) {
			$order_methods = 'public function orderIndex()
    {
        $rows = ' . $model_name . '::orderBy(' . "'" . $order_column . "'" . ')->get();
        return view(' . "'" . 'cms/pages/' . $route . '/order' . "'" . ', compact(' . "'" . 'rows' . "'" . '));
    }

    public function orderSubmit(Request $request)
    {
        foreach ($request->id as $key => $id) {
            $row = ' . $model_name . '::findOrFail($id);
            $row->' . $order_column . ' = $request->' . $order_column . '[$key];
            $row->save();
        }

        return redirect(env(' . "'" . 'CMS_PREFIX' . "'" . ', ' . "'" . 'admin' . "'" . ') . ' . "'" . '/' . $route . "'" . ')->with(' . "'" . 'success' . "'" . ', ' . "'" . 'Records ordered successfully' . "'" . ');
    }';
		} else {
			$order_methods = '';
		}

		file_put_contents(
			app_path('Http/Controllers/Cms/' . $controller_name . '.php'),
			str_replace(
				[
					'%%controller_header%%',
					'%%model_name%%',
					'%%order_by%%',
					'%%order_methods%%',
					'%%controller_name%%',
					'%%route%%',
					'%%store_validation%%',
					'%%store_query%%',
					'%%update_validation%%',
					'%%update_query%%',
					'%%index_method_header%%',
					'%%index_additional_variables%%',
					'%%index_compact%%',
					'%%create_additional_variables%%',
					'%%create_compact%%',
					'%%edit_additional_variables%%',
					'%%edit_compact%%',
					'%%show_additional_variables%%',
					'%%show_compact%%',
				],
				[
					$controller_header,
					$model_name,
					$order_by,
					$order_methods,
					$controller_name,
					$route,
					$store_validation,
					$store_query,
					$update_validation,
					$update_query,
					$index_method_header,
					$index_additional_variables,
					$index_compact,
					$create_additional_variables,
					$create_compact,
					$edit_additional_variables,
					$edit_compact,
					$show_additional_variables,
					$show_compact,
				],
				file_get_contents(resource_path('stubs/Controller.stub'))
			)
		);
	}

	public function generateMigration($database_table, $model_name_plural, $fields, $migration_name, $order_column)
	{
		// Delete old migration
		foreach (scandir(database_path('migrations/')) as $key => $migration) {
			if (substr($migration, -(strlen($database_table) + strlen('_table.php'))) == ($database_table . '_table.php')) unlink(database_path('migrations/' . $migration));
		}

		if ($order_column) {
			$fields[] = [
				'name' => $order_column,
				'migration_type' => 'integer',
				'nullable' => 1,
				'unique' => 0,
			];
		}

		$columns = '';
		foreach ($fields as $key => $field) {
			$columns .= "$" . "table->" . $field['migration_type'] . "('" . $field['name'] . "')";
			if ($field['nullable']) $columns .= "->nullable()";
			if ($field['unique']) $columns .= "->unique()";
			$columns .= ";
			";
		}
		$columns = substr($columns, 0, -4);
		file_put_contents(
			database_path('migrations/' . $migration_name),
			str_replace(
				['%%database_table%%', '%%model_name_plural%%', '%%columns%%'],
				[$database_table, $model_name_plural, $columns],
				file_get_contents(resource_path('stubs/Migration.stub'))
			)
		);

		Schema::dropIfExists($database_table);

		Artisan::call('migrate');
	}

	public function generateIndexView($route, $fields, $display_name_plural, $order_column, $page_type)
	{
		if (!file_exists(resource_path('views/cms/pages/' . $route))) mkdir(resource_path('views/cms/pages/' . $route));

		$table_header = '';
		foreach ($fields as $key => $field) {
			if ($field['form_field'] == 'password' || $field['form_field'] == 'password with confirmation') continue;

			if ($field['form_field'] == 'select') {
				$table_header .= '<th>' . ucwords(str_replace(['_id', '_'], ['', ' '], $field['name'])) . '</th>
						';
			} else {
				$table_header .= '<th>' . ucwords(str_replace('_', ' ', $field['name'])) . '</th>
						';
			}
		}
		$table_header .= '<th>Actions</th>';

		$table_body = '';
		foreach ($fields as $key => $field) {
			if ($field['form_field'] == 'password' || $field['form_field'] == 'password with confirmation') continue;

			if ($field['form_field'] == 'image') {
				$table_body .= '<td>
								@if ($row->' . $field['name'] . ')
									<img src="{{ asset($row->' . $field['name'] . ') }}" class="img-thumbnail">
								@endif
							</td>
							';
			} elseif ($field['form_field'] == 'file') {
				$table_body .= '<td>
								@if ($row->' . $field['name'] . ')
									<a href="{{ asset($row->' . $field['name'] . ') }}" target="_blank"><i class="fa fa-file" aria-hidden="true"></i></a>
								@endif
							</td>
							';
			} elseif ($field['form_field'] == 'files') {
				$table_body .= '<td class="no-wrap">
								@foreach(json_decode($row->' . $field['name'] . ') as $attachment)
									<a href="{{ asset($attachment) }}" target="_blank"><i class="fa fa-file mr-2" aria-hidden="true"></i></a>
								@endforeach
							</td>
							';
			} elseif ($field['form_field'] == 'textarea') {
				$table_body .= '<td><div class="max-lines">{{ $row->' . $field['name'] . ' }}</div></td>
							';
			} elseif ($field['form_field'] == 'rich-textbox') {
				$table_body .= '<td><div class="max-lines">{{ strip_tags($row->' . $field['name'] . ') }}</div></td>
							';
			} elseif ($field['form_field'] == 'select') {
				$table_body .= '<td>{{ $' . $field['form_field_additionals_1'] . '[$row[' . "'" . $field['name'] . "'" . ']][' . "'" . $field['form_field_additionals_2'] . "'" . '] }}</td>
							';
			} elseif ($field['form_field'] == 'checkbox') {
				$table_body .= '<td>
								@if ($row[' . "'" . $field['name'] . "'" . '])
									<i class="fa fa-check" aria-hidden="true"></i>
								@else
									<i class="fa fa-times" aria-hidden="true"></i>
								@endif
							</td>
							';
			} elseif ($field['form_field'] == 'map coordinates') {
				$table_body .= '<td>
								<a target="_blank" href="https://www.google.com/maps/search/?api=1&query={{ $row[' . "'" . $field['name'] . "'" . '] }}"><i class="fa fa-map-marker" aria-hidden="true"></i></a>
							</td>
							';
			} else {
				$table_body .= '<td>{{ $row[' . "'" . $field['name'] . "'" . '] }}</td>
							';
			}
		}
		$table_body .= '<td class="actions-wrapper text-right">
								@if (session(' . "'" . 'admin' . "'" . ')[' . "'" . 'cms_pages' . "'" . '][' . "'" . $route . "'" . '][' . "'" . 'permissions' . "'" . '][' . "'" . 'read' . "'" . '])
									<a href="{{ url(env(' . "'" . 'CMS_PREFIX' . "'" . ', ' . "'" . 'admin' . "'" . ') . ' . "'" . '/' . $route . '/' . "'" . ' . $row[' . "'" . 'id' . "'" . ']) }}" class="mb-2 btn btn-secondary btn-sm">View</a>
								@endif';
		if ($page_type != 'show') {
			$table_body .= '
								@if (session(' . "'" . 'admin' . "'" . ')[' . "'" . 'cms_pages' . "'" . '][' . "'" . $route . "'" . '][' . "'" . 'permissions' . "'" . '][' . "'" . 'edit' . "'" . '])
									<a href="{{ url(env(' . "'" . 'CMS_PREFIX' . "'" . ', ' . "'" . 'admin' . "'" . ') . ' . "'" . '/' . $route . '/' . "'" . ' . $row[' . "'" . 'id' . "'" . '] . ' . "'" . '/edit' . "'" . ') }}" class="mb-2 btn btn-primary btn-sm">Edit</a>
								@endif';
		}
		if ($page_type != 'show' && $page_type != 'fixed') {
			$table_body .= '
								@if (session(' . "'" . 'admin' . "'" . ')[' . "'" . 'cms_pages' . "'" . '][' . "'" . $route . "'" . '][' . "'" . 'permissions' . "'" . '][' . "'" . 'delete' . "'" . '])
									<form class="row-delete" method="post" action="{{ url(env(' . "'" . 'CMS_PREFIX' . "'" . ', ' . "'" . 'admin' . "'" . ') . ' . "'" . '/' . $route .  '/' . "'" . ' . $row[' . "'" . 'id' . "'" . ']) }}" style="display: inline-block;">
										@csrf
										<input type="hidden" name="_method" value="DELETE">
										<button class="mb-2 btn btn-danger btn-sm">Delete</button>
									</form>
								@endif';
		}
		$table_body .= '								
							</td>';

		$add_button = '';
		if ($page_type != 'show' && $page_type != 'fixed') {
			$add_button = '@if (session(' . "'" . 'admin' . "'" . ')[' . "'" . 'cms_pages' . "'" . '][' . "'" . $route . "'" . '][' . "'" . 'permissions' . "'" . '][' . "'" . 'add' . "'" . '])
				<a href="{{ url(env(' . "'" . 'CMS_PREFIX' . "'" . ', ' . "'" . 'admin' . "'" . ') . ' . "'" . '/' . $route . '/create' . "'" . ') }}" class="btn btn-primary btn-sm">Add</a>
			@endif';
		}

		$bulk_delete_button = '';
		if ($page_type != 'show' && $page_type != 'fixed') {
			$bulk_delete_button = '
			@if (session(' . "'" . 'admin' . "'" . ')[' . "'" . 'cms_pages' . "'" . '][' . "'" . $route . "'" . '][' . "'" . 'permissions' . "'" . '][' . "'" . 'delete' . "'" . '])
				<form method="post" action="{{ url(env(' . "'" . 'CMS_PREFIX' . "'" . ', ' . "'" . 'admin' . "'" . ') . ' . "'" . '/' . $route . '/' . "'" . ') }}" class="d-inline-block bulk-delete" onsubmit="return confirm(' . "'" . 'Are you sure?' . "'" . ')">
					@csrf
					<input type="hidden" name="_method" value="DELETE">
					<button type="submit" class="btn btn-danger btn-sm">Bulk Delete</button>
				</form>
			@endif';
		}

		if ($order_column) {
			$order_button = '@if (session(' . "'" . 'admin' . "'" . ')[' . "'" . 'cms_pages' . "'" . '][' . "'" . $route . "'" . '][' . "'" . 'permissions' . "'" . '][' . "'" . 'edit' . "'" . '])
				<a href="{{ url(env(' . "'" . 'CMS_PREFIX' . "'" . ', ' . "'" . 'admin' . "'" . ') . ' . "'" . '/' . $route . '/order' . "'" . ') }}" class="btn btn-secondary btn-sm">Order</a>
			@endif';
		} else {
			$order_button = '';
		}

		file_put_contents(
			resource_path('views/cms/pages/' . $route . '/index.blade.php'),
			str_replace(
				['%%display_name_plural%%', '%%route%%', '%%add_button%%', '%%order_button%%', '%%bulk_delete_button%%', '%%table_header%%', '%%table_body%%'],
				[$display_name_plural, $route, $add_button, $order_button, $bulk_delete_button, $table_header, $table_body],
				file_get_contents(resource_path('stubs/views/index.stub'))
			)
		);
	}

	public function generateCreateView($route, $fields, $display_name_plural, $display_name, $order_column, $additional_cms_pages)
	{
		$create_form_inputs = '';

		$slug_available = 0;
		// Check if there is slug
		foreach ($fields as $key => $field) if ($field['form_field'] == 'slug') $slug_available = 1;

		foreach ($fields as $key => $field) {
			if ($field['form_field'] == 'textarea') {
				$create_form_inputs .= "@include('cms/components/form-fields/textarea', ['label' => '" . ucwords(str_replace('_', ' ', $field['name'])) . "', 'name' => '" . $field['name'] . "', 'value' => old('" . $field['name'] . "') ? old('" . $field['name'] . "') : '' ])
			";
			} elseif ($field['form_field'] == 'rich-textbox') {
				$create_form_inputs .= "@include('cms/components/form-fields/rich-textbox', ['label' => '" . ucwords(str_replace('_', ' ', $field['name'])) . "', 'name' => '" . $field['name'] . "', 'value' => old('" . $field['name'] . "') ? old('" . $field['name'] . "') : '' ])
			";
			} elseif ($field['form_field'] == 'select') {
				$create_form_inputs .= "@include('cms/components/form-fields/select', ['label' => '" . ucwords(str_replace(['_id', '_'], ['', ' '], $field['name'])) . "', 'name' => '" . $field['name'] . "', 'options' => $" . $field['form_field_additionals_1'] . ", 'store_column' => 'id' , 'display_column' => '" . $field['form_field_additionals_2'] . "' ])
			";
			} elseif ($field['form_field'] == 'file') {
				$create_form_inputs .= "@include('cms/components/form-fields/file', ['label' => '" . ucwords(str_replace('_', ' ', $field['name'])) . "', 'name' => '" . $field['name'] . "' ])
			";
			} elseif ($field['form_field'] == 'image') {
				$create_form_inputs .= "@include('cms/components/form-fields/image', ['label' => '" . ucwords(str_replace('_', ' ', $field['name'])) . "', 'name' => '" . $field['name'] . "' ])
			";
			} elseif ($field['form_field'] == 'slug') {
				$create_form_inputs .= "@include('cms/components/form-fields/input', ['label' => '" . ucwords(str_replace('_', ' ', $field['name'])) . "', 'name' => '" . $field['name'] . "', 'slug_origin' => '" . $field['form_field_additionals_1'] . "', 'type' => 'text', 'value' => old('" . $field['name'] . "') ? old('" . $field['name'] . "') : '' ])
			";
			} elseif ($field['form_field'] == 'date') {
				$create_form_inputs .= "@include('cms/components/form-fields/date', ['label' => '" . ucwords(str_replace('_', ' ', $field['name'])) . "', 'name' => '" . $field['name'] . "', 'value' => old('" . $field['name'] . "') ])
			";
			} elseif ($field['form_field'] == 'password') {
				$create_form_inputs .= "@include('cms/components/form-fields/input', ['label' => '" . ucwords(str_replace('_', ' ', $field['name'])) . "', 'name' => '" . $field['name'] . "', 'type' => 'password', 'value' => '' ])
			";
			} elseif ($field['form_field'] == 'password with confirmation') {
				$create_form_inputs .= "@include('cms/components/form-fields/password-with-confirmation', ['label' => '" . ucwords(str_replace('_', ' ', $field['name'])) . "', 'name' => '" . $field['name'] . "' ])
			";
			} elseif ($field['form_field'] == 'checkbox') {
				$create_form_inputs .= "@include('cms/components/form-fields/checkbox', ['label' => '" . ucwords(str_replace('_', ' ', $field['name'])) . "', 'name' => '" . $field['name'] . "' ])
			";
			} elseif ($field['form_field'] == 'map coordinates') {
				$create_form_inputs .= "@include('cms/components/form-fields/map', ['label' => '" . ucwords(str_replace('_', ' ', $field['name'])) . "', 'name' => '" . $field['name'] . "', 'value' => old('" . $field['name'] . "') ])
			";
			} else {
				$create_form_inputs .= "@include('cms/components/form-fields/input', ['label' => '" . ucwords(str_replace('_', ' ', $field['name'])) . "', 'name' => '" . $field['name'] . "', 'type' => 'text', 'value' => old('" . $field['name'] . "') ? old('" . $field['name'] . "') : '' ])
			";
			}
		}

		file_put_contents(
			resource_path('views/cms/pages/' . $route . '/create.blade.php'),
			str_replace(
				['%%display_name_plural%%', '%%display_name%%', '%%route%%', '%%create_form_inputs%%'],
				[strtolower($display_name_plural), strtolower($display_name), $route, $create_form_inputs],
				file_get_contents(resource_path('stubs/views/create.stub'))
			)
		);
	}

	public function generateEditView($route, $fields, $display_name_plural, $display_name)
	{
		$edit_form_inputs = '';
		foreach ($fields as $key => $field) {
			if ($field['form_field'] == 'textarea') {
				$edit_form_inputs .= "@include('cms/components/form-fields/textarea', ['label' => '" . ucwords(str_replace('_', ' ', $field['name'])) . "', 'name' => '" . $field['name'] . "', 'value' => old('" . $field['name'] . "') ? old('" . $field['name'] . "') : " . '$' . "row->" . $field['name'] . " ])
			";
			} elseif ($field['form_field'] == 'rich-textbox') {
				$edit_form_inputs .= "@include('cms/components/form-fields/rich-textbox', ['label' => '" . ucwords(str_replace('_', ' ', $field['name'])) . "', 'name' => '" . $field['name'] . "', 'value' => old('" . $field['name'] . "') ? old('" . $field['name'] . "') : " . '$' . "row->" . $field['name'] . " ])
			";
			} elseif ($field['form_field'] == 'select') {
				$edit_form_inputs .= "@include('cms/components/form-fields/select', ['label' => '" . ucwords(str_replace(['_id', '_'], ['', ' '], $field['name'])) . "', 'name' => '" . $field['name'] . "', 'options' => $" . $field['form_field_additionals_1'] . ", 'store_column' => 'id' , 'display_column' => '" . $field['form_field_additionals_2'] . "', 'value' => " . '$' . "row->" . $field['name'] . " ])
			";
			} elseif ($field['form_field'] == 'file') {
				$edit_form_inputs .= "@include('cms/components/form-fields/file', ['label' => '" . ucwords(str_replace('_', ' ', $field['name'])) . "', 'name' => '" . $field['name'] . "', 'value' => " . '$' . "row->" . $field['name'] . " ])
			";
			} elseif ($field['form_field'] == 'image') {
				$edit_form_inputs .= "@include('cms/components/form-fields/image', ['label' => '" . ucwords(str_replace('_', ' ', $field['name'])) . "', 'name' => '" . $field['name'] . "', 'value' => " . '$' . "row->" . $field['name'] . " ])
			";
			} elseif ($field['form_field'] == 'slug') {
				$edit_form_inputs .= "@include('cms/components/form-fields/input', ['label' => '" . ucwords(str_replace('_', ' ', $field['name'])) . "', 'name' => '" . $field['name'] . "', 'slug_origin' => '" . $field['form_field_additionals_1'] . "', 'type' => 'text', 'value' => old('" . $field['name'] . "') ? old('" . $field['name'] . "') : " . '$' . "row->" . $field['name'] . " ])
			";
			} elseif ($field['form_field'] == 'date') {
				$edit_form_inputs .= "@include('cms/components/form-fields/date', ['label' => '" . ucwords(str_replace('_', ' ', $field['name'])) . "', 'name' => '" . $field['name'] . "', 'value' => old('" . $field['name'] . "') ? old('" . $field['name'] . "') : " . '$' . "row->" . $field['name'] . " ])
			";
			} elseif ($field['form_field'] == 'password') {
				$edit_form_inputs .= "@include('cms/components/form-fields/input', ['label' => '" . ucwords(str_replace('_', ' ', $field['name'])) . "', 'name' => '" . $field['name'] . "', 'type' => 'password', 'value' => '' ])
			";
			} elseif ($field['form_field'] == 'password with confirmation') {
				$edit_form_inputs .= "@include('cms/components/form-fields/password-with-confirmation', ['label' => '" . ucwords(str_replace('_', ' ', $field['name'])) . "', 'name' => '" . $field['name'] . "' ])
			";
			} elseif ($field['form_field'] == 'checkbox') {
				$edit_form_inputs .= "@include('cms/components/form-fields/checkbox', ['label' => '" . ucwords(str_replace('_', ' ', $field['name'])) . "', 'name' => '" . $field['name'] . "', 'checked' => " . '$' . "row->" . $field['name'] . " ])
			";
			} elseif ($field['form_field'] == 'map coordinates') {
				$edit_form_inputs .= "@include('cms/components/form-fields/map', ['label' => '" . ucwords(str_replace('_', ' ', $field['name'])) . "', 'name' => '" . $field['name'] . "', 'value' => old('" . $field['name'] . "') ? old('" . $field['name'] . "') : " . '$' . "row['" . $field['name'] . "'] ])
			";
			} else {
				$edit_form_inputs .= "@include('cms/components/form-fields/input', ['label' => '" . ucwords(str_replace('_', ' ', $field['name'])) . "', 'name' => '" . $field['name'] . "', 'type' => 'text', 'value' => old('" . $field['name'] . "') ? old('" . $field['name'] . "') : " . '$' . "row->" . $field['name'] . " ])
			";
			}
		}

		if (!file_exists(resource_path('views/cms/pages/' . $route))) mkdir(resource_path('views/cms/pages/' . $route));

		file_put_contents(
			resource_path('views/cms/pages/' . $route . '/edit.blade.php'),
			str_replace(
				['%%display_name_plural%%', '%%display_name%%', '%%route%%', '%%edit_form_inputs%%'],
				[strtolower($display_name_plural), strtolower($display_name), $route, $edit_form_inputs],
				file_get_contents(resource_path('stubs/views/edit.stub'))
			)
		);
	}

	public function generateShowView($route, $fields, $display_name_plural, $display_name, $page_type)
	{
		$show_page_id_breadcrumb = '<li><a href="{{ url(env(' . "'" . 'CMS_PREFIX' . "'" . ', ' . "'" . 'admin' . "'" . ') . ' . "'" . '/' . $route . '/' . "'" . ' . $row[' . "'" . 'id' . "'" . ']) }}">{{ $row[' . "'" . 'id' . "'" . '] }}</a></li>';
		$delete_button_show_page = '@if (session(' . "'" . 'admin' . "'" . ')[' . "'" . 'cms_pages' . "'" . '][' . "'" . $route . "'" . '][' . "'" . 'permissions' . "'" . '][' . "'" . 'delete' . "'" . '])
						<form class="row-delete" method="post" action="{{ url(env(' . "'" . 'CMS_PREFIX' . "'" . ', ' . "'" . 'admin' . "'" . ') . ' . "'" . '/' . $route . '/' . "'" . ' . $row[' . "'" . 'id' . "'" . ']) }}" style="display: inline-block;">
							@csrf
							<input type="hidden" name="_method" value="DELETE">
							<button class="btn btn-danger btn-sm">Delete</button>
						</form>
					@endif';

		if ($page_type == 'show' || $page_type == 'fixed' || $page_type == 'single')  {
			$delete_button_show_page = '';
		}

		if ($page_type == 'single')  {
			$show_page_id_breadcrumb = '';
		}

		$show_fields = '';
		foreach ($fields as $field) {
			if ($field['form_field'] != 'password' && $field['form_field'] != 'password with confirmation') {
				if ($field['form_field'] == 'image') {
					$show_fields .= "@include('cms/components/show-fields/image', ['label' => '" . ucwords(str_replace('_', ' ', $field['name'])) . "', 'image' => " . '$' . "row" . '[' . "'" . $field['name'] . "'" . ']' . "] )
		";
				} elseif ($field['form_field'] == 'file') {
					$show_fields .= "@include('cms/components/show-fields/file', ['label' => '" . ucwords(str_replace('_', ' ', $field['name'])) . "', 'file' => " . '$' . "row" . '[' . "'" . $field['name'] . "'" . ']' . "] )
		";
				} elseif ($field['form_field'] == 'files') {
					$show_fields .= "@include('cms/components/show-fields/files', ['label' => '" . ucwords(str_replace('_', ' ', $field['name'])) . "', 'files' => " . '$' . "row" . '[' . "'" . $field['name'] . "'" . ']' . "] )
		";
				} elseif ($field['form_field'] == 'select') {
					$show_fields .= "@include('cms/components/show-fields/text', ['label' => '" . ucwords(str_replace(['_id', '_'], ['', ' '], $field['name'])) . "', 'text' => " . '$' . $field['form_field_additionals_1'] . '[$' . "row['" . $field['name'] . "'" . ']][' . "'" . $field['form_field_additionals_2'] . "']] )
		";
				} elseif ($field['form_field'] == 'checkbox') {
					$show_fields .= "@include('cms/components/show-fields/boolean', ['label' => '" . ucwords(str_replace(['_id', '_'], ['', ' '], $field['name'])) . "', 'value' => " . '$' . "row" . '[' . "'" . $field['name'] . "'" . ']' . "] )
		";
				} elseif ($field['form_field'] == 'map coordinates') {
					$show_fields .= "@include('cms/components/show-fields/map', ['label' => '" . ucwords(str_replace(['_id', '_'], ['', ' '], $field['name'])) . "', 'name' => '" . $field['name'] . "', 'value' => " . '$' . "row" . '[' . "'" . $field['name'] . "'" . ']' . "] )
		";
				} else {
					$show_fields .= "@include('cms/components/show-fields/text', ['label' => '" . ucwords(str_replace('_', ' ', $field['name'])) . "', 'text' => " . '$' . "row" . '[' . "'" . $field['name'] . "'" . ']' . "] )
		";
				}
			}
		}

		file_put_contents(
			resource_path('views/cms/pages/' . $route . '/show.blade.php'),
			str_replace(
				[
					'%%display_name_plural%%',
					'%%display_name%%',
					'%%route%%',
					'%%show_fields%%',
					'%%show_page_id_breadcrumb%%',
					'%%delete_button_show_page%%',
				],
				[
					strtolower($display_name_plural),
					strtolower($display_name),
					$route,
					$show_fields,
					$show_page_id_breadcrumb,
					$delete_button_show_page,
				],
				file_get_contents(resource_path('stubs/views/show.stub'))
			)
		);
	}

	public function generateOrderView($route, $display_name_plural, $order_display_column, $order_display_form_field)
	{
		if ($order_display_form_field == 'image') {
			$order_display = '<img class="img-thumbnail" src="{{ asset($row->' . $order_display_column . ') }}">';
		} elseif ($order_display_form_field == 'file') {
			$order_display = '<a href="{{ asset($row->' . $order_display_column . ') }}">{{ array_values(array_slice(explode("/", $row->' . $order_display_column . '), -1))[0] }}</a>';
		} else {
			$order_display = '{{ $row->' . $order_display_column . ' }}';
		}

		file_put_contents(
			resource_path('views/cms/pages/' . $route . '/order.blade.php'),
			str_replace(
				['%%route%%', '%%display_name_plural%%', '%%order_display%%'],
				[strtolower($route), strtolower($display_name_plural), $order_display],
				file_get_contents(resource_path('stubs/views/order.stub'))
			)
		);
	}

	public function generateRoute($route, $controller_name, $order_column)
	{
		if ($order_column) {
			$route_function = "Route::get('/" . $route . "/order', 'Cms\\" . $controller_name . "@orderIndex');Route::post('/" . $route . "/order', 'Cms\\" . $controller_name . "@orderSubmit');";
		} else {
			$route_function = "";
		}
		$route_function .= "Route::resource('/" . $route . "', 'Cms\\" . $controller_name . "');";

		// If route already exists, remove it
		$web_routes = file_get_contents(base_path('routes/web.php'));
		file_put_contents(
			base_path('routes/web.php'),
			str_replace(
				["
	Route::get('/" . $route . "/order', 'Cms\\" . $controller_name . "@orderIndex');Route::post('/" . $route . "/order', 'Cms\\" . $controller_name . "@orderSubmit');",
				"
	Route::resource('/" . $route . "', 'Cms\\" . $controller_name . "');"],
				["", ""],
				$web_routes
			)
		);

		// Create route
		$web_routes = file_get_contents(base_path('routes/web.php'));
		file_put_contents(
			base_path('routes/web.php'),
			str_replace(
				"/* End admin route group */",
				$route_function . "
	/* End admin route group */",
				$web_routes
			)
		);
	}

	public function destroy($id)
	{
		$array = explode(',', $id);
		if (!count($array)) return redirect(env('CMS_PREFIX', 'admin') . '/cms-pages')->with('error', 'No record selected');
		foreach ($array as $id) $this->deletePage($id);
		return redirect(env('CMS_PREFIX', 'admin') . '/cms-pages')->with('success', 'Record deleted successfully');
	}

	public function deletePage($id)
	{
		$cms_page = CmsPage::findorfail($id);

		$this->deleteRoute($cms_page);
		$this->deleteDatabase($cms_page);
		$this->deleteModel($cms_page);
		$this->deleteController($cms_page);
		$this->deleteMigration($cms_page);
		$this->deleteViews($cms_page);
		$this->deleteStorage($cms_page);
	
		CmsPage::destroy($id);
	}

	public function deleteRoute($cms_page)
	{
		$web_routes = file_get_contents(base_path('routes/web.php'));
		file_put_contents(
			base_path('routes/web.php'),
			str_replace(
				"Route::resource('/" . $cms_page->route . "', 'Cms\\" . $cms_page->controller_name . "');",
				"",
				$web_routes
			)
		);
	}

	public function deleteDatabase($cms_page)
	{
		Schema::dropIfExists($cms_page->database_table);
	}

	public function deleteModel($cms_page)
	{
		if (file_exists(app_path('/' . $cms_page->model_name . '.php'))) unlink(app_path('/' . $cms_page->model_name . '.php'));
	}

	public function deleteController($cms_page)
	{
		if (file_exists(app_path('Http/Controllers/Cms/' . $cms_page->controller_name . '.php'))) unlink(app_path('Http/Controllers/Cms/' . $cms_page->controller_name . '.php'));
	}

	public function deleteMigration($cms_page)
	{
		if (file_exists(database_path('migrations/' . $cms_page->migration_name))) unlink(database_path('migrations/' . $cms_page->migration_name));
	}

	public function deleteViews($cms_page)
	{
		if (file_exists(resource_path('views/cms/pages/' . $cms_page->route))) $this->deleteDirectory(resource_path('views/cms/pages/' . $cms_page->route));
	}

	public function deleteIndexView($cms_page)
	{
		if (file_exists(resource_path('views/cms/pages/' . $cms_page->route . '/index.blade.php'))) unlink(resource_path('views/cms/pages/' . $cms_page->route . '/index.blade.php'));
	}

	public function deleteCreateView($cms_page)
	{
		if (file_exists(resource_path('views/cms/pages/' . $cms_page->route . '/create.blade.php'))) unlink(resource_path('views/cms/pages/' . $cms_page->route . '/create.blade.php'));
	}

	public function deleteEditView($cms_page)
	{
		if (file_exists(resource_path('views/cms/pages/' . $cms_page->route . '/edit.blade.php'))) unlink(resource_path('views/cms/pages/' . $cms_page->route . '/edit.blade.php'));
	}

	public function deleteOrderView($cms_page)
	{
		if (file_exists(resource_path('views/cms/pages/' . $cms_page->route . '/order.blade.php'))) unlink(resource_path('views/cms/pages/' . $cms_page->route . '/order.blade.php'));
	}

	public function deleteShowView($cms_page)
	{
		if (file_exists(resource_path('views/cms/pages/' . $cms_page->route . '/show.blade.php'))) unlink(resource_path('views/cms/pages/' . $cms_page->route . '/show.blade.php'));
	}

	public function deleteStorage($cms_page)
	{
		if (file_exists(storage_path('app/public/' . $cms_page->route))) $this->deleteDirectory(storage_path('app/public/' . $cms_page->route));
	}

	public function deleteDirectory($dir_path) {
		if (!is_dir($dir_path)) {
			throw new InvalidArgumentException("$dir_path must be a directory");
		}
		if (substr($dir_path, strlen($dir_path) - 1, 1) != '/') {
			$dir_path .= '/';
		}
		$files = glob($dir_path . '*', GLOB_MARK);
		foreach ($files as $file) {
			if (is_dir($file)) {
				self::deleteDirectory($file);
			} else {
				unlink($file);
			}
		}
		rmdir($dir_path);
	}

    public function orderIndex()
    {
        $rows = CmsPage::orderBy('ht_pos')->get();

        return view('cms/pages/cms-pages/order', compact('rows'));
    }

    public function orderSubmit(Request $request)
    {
        foreach ($request->id as $key => $id) {
            $row = CmsPage::findOrFail($id);
            $row->ht_pos = $request->ht_pos[$key];
            $row->save();
        }

        return redirect(env('CMS_PREFIX', 'admin') . '/cms-pages')->with('success', 'Records ordered successfully');
    }
}
