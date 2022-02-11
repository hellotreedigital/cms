@php
	$filters = [];
	foreach($page_fields as $field) {
		if ($field['form_field'] == 'select' || $field['form_field'] == 'select multiple') {
			$filters[] = $field;
		}
	}
@endphp

@extends('cms::layouts/dashboard')

@section('breadcrumb')
	<ul class="breadcrumbs list-inline font-weight-bold text-uppercase m-0">
		<li>{{ $page['display_name_plural'] }}</li>
	</ul>
@endsection

@section('dashboard-content')

	<div class="card py-4 mx-2 mx-lg-5">
		<div class="actions">
			@if ($page['add'] || !request()->get('admin')['admin_role_id'])
				@if (request()->get('admin')['cms_pages'][$page['route']]['permissions']['add'])
					<a href="{{ url(config('hellotree.cms_route_prefix') . '/' . $page['route'] . '/create') }}" class="btn btn-primary btn-sm {{ $page['add'] ? '' : 'opacity-half' }}">Add</a>
				@endif
			@endif
			@if ($page['order_display'])
				<a href="{{ url(config('hellotree.cms_route_prefix') . '/' . $page['route'] . '/order') }}" class="btn btn-secondary btn-sm">Order</a>
			@endif
			@if ($page['delete'] || !request()->get('admin')['admin_role_id'])
				@if (request()->get('admin')['cms_pages'][$page['route']]['permissions']['delete'])
					<form method="post" action="{{ url(config('hellotree.cms_route_prefix') . '/' . $page['route'] . '/') }}" class="d-block d-md-inline-block bulk-delete {{ $page['delete'] ? '' : 'opacity-half' }}" onsubmit="return confirm('Are you sure?')">
						@csrf
						<input type="hidden" name="_method" value="DELETE">
						<button type="submit" class="btn btn-danger btn-sm w-100">Bulk Delete</button>
					</form>
				@endif
			@endif
        </div>
		@if ($page['server_side_pagination'])
			<div class="row no-gutters">
				<div class="col-md">
					<div class="server-showing-number-wrapper">
						<form>
							@if (request('custom_validation'))
								@foreach(request('custom_validation') as $i => $validation)
									@if (isset($validation['value'][1]) && $validation['value'][1])
									<input type="hidden" name="custom_validation[{{ $i }}][constraint]" value="{{ $validation['constraint'] }}">
									<input type="hidden" name="custom_validation[{{ $i }}][value][0]" value="{{ $validation['value'][0] }}">
										@foreach($validation['value'][1] as $value)
										<input type="hidden" name="custom_validation[{{ $i }}][value][1][]" value="{{ $value }}">
										@endforeach
									@endif
								@endforeach
							@endif
							<label>
								Show
								<select name="per_page" class="select2-width-auto px-4">
									<option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
									<option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
									<option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
									<option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
								</select>
								entries
							</label>
						</form>
					</div>
				</div>
				<div class="col-md-auto px-md-0">
					<form class="server-search-wrapper">
						<label>
							Search:
							<input type="search" name="custom_search" value="{{ request('custom_search') }}">
						</label>
					</form>
				</div>
				<div class="col-md-auto">
					<label class="filter-wrapper">
						@if (count($filters))
						<i class="fa fa-filter ml-3"></i>
						@endif
					</label>
				</div>
			</div>
		@endif
		<div class="datatable-wrapper {{ $page['server_side_pagination'] ? 'table-responsive' : '' }} {{ count($filters) ? 'has-filters' : '' }}">
			<table class="{{ $page['server_side_pagination'] ? 'table' : 'datatable' }} {{ $page['with_export'] ? '' : 'no-export' }}">
				<thead>
					<tr>
						<th></th>
						<th>#</th>
						@foreach($page_fields as $field)
							@if ($field['form_field'] == 'password' || $field['form_field'] == 'password with confirmation' || (isset($field['hide_index']) && $field['hide_index'])) @continue
							@else
								@php
								$appends_to_sort_query = '?';

								if (request('per_page')) $appends_to_sort_query .= 'per_page=' . request('per_page') . '&';
								if (request('custom_search')) $appends_to_sort_query .= 'custom_search=' . request('custom_search') . '&';

								$appends_to_sort_query .= 'sort_by=' . $field['name'] . '&';

								if (request('sort_by') == $field['name']) {
									$appends_to_sort_query .= 'sort_by_direction=' . (request('sort_by_direction') == 'asc' ? 'desc' : 'asc') . '&';
								} else {
									$appends_to_sort_query .= 'sort_by_direction=asc&';
								}
								@endphp
								<th>
									<a {!! $page['server_side_pagination'] ? 'href="' . url(config('hellotree.cms_route_prefix') . '/' . $page['route'] . $appends_to_sort_query) . '"' : '' !!}>{{ str_replace(['_id', '_'], ['', ' '], $field['name']) }}</a>
									@if ($page['server_side_pagination'])
									<div class="sort-arrows position-relative d-inline {{ request('sort_by') == $field['name'] ? request('sort_by_direction') : '' }}"></div>
									@endif
								</th>
							@endif
						@endforeach
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					@foreach($rows as $row)
						<tr>
							<td>
								<label class="checkbox-wrapper delete-checkbox">
									<input type="checkbox" value="{{ $row['id'] }}">
									<div></div>
								</label>
							</td>
							<td>
								{{ $row['id'] }}
							</td>
							@foreach($page_fields as $field)
								@if ($field['form_field'] == 'password' || $field['form_field'] == 'password with confirmation' || (isset($field['hide_index']) && $field['hide_index'])) @continue

								@elseif ($field['form_field'] == 'image')
									<td>
										@if ($row[$field['name']])
											<img src="{{ Storage::url($row[$field['name']]) }}" class="img-thumbnail">
										@endif
									</td>
								@elseif ($field['form_field'] == 'multiple images')
									<td>
                                        @if ($row[$field['name']])
                                            @php
                                                $files = json_decode($row[$field['name']]);
                                            @endphp
                                            @foreach ($files as $file)
											    <img src="{{ Storage::url($file) }}" class="img-thumbnail multiple-image">
                                            @endforeach
										@endif
									</td>
								@elseif ($field['form_field'] == 'file')
									<td>
										@if ($row[$field['name']])
											<a href="{{ Storage::url($row[$field['name']]) }}" target="_blank"><i class="fa fa-file" aria-hidden="true"></i></a>
											<p style="font-size: 0;">{{ Storage::url($row[$field['name']]) }}</p>
										@endif
									</td>
								@elseif ($field['form_field'] == 'textarea')
									<td>
										<div class="max-lines">{{ $row[$field['name']] }}</div>
									</td>
								@elseif ($field['form_field'] == 'rich-textbox')
									<td>
										<div class="max-lines">{{ strip_tags($row[$field['name']]) }}</div>
									</td>
								@elseif ($field['form_field'] == 'select')
									<td>
										@if ($row[str_replace('_id', '', $field['name'])])
											<a href="{{ url(config('hellotree.cms_route_prefix') . '/' . (str_replace('_', '-', $field['form_field_additionals_1'])) . '/' . $row[$field['name']]) }}">
												{{ strip_tags($row[str_replace('_id', '', $field['name'])][$field['form_field_additionals_2']]) }}
											</a>
										@endif
									</td>
								@elseif ($field['form_field'] == 'select multiple')
									<td>
										@foreach($row[str_replace('_id', '', $field['name'])] as $i => $pivot)
											{{ $i ? ', ' : '' }}
											<a href="{{ url(config('hellotree.cms_route_prefix') . '/' . (str_replace('_', '-', $field['form_field_additionals_1'])) . '/' . $pivot->id) }}">
												{{ $pivot[$field['form_field_additionals_2']] }}
											</a>
										@endforeach
									</td>
								@elseif ($field['form_field'] == 'checkbox')
									<td>
										@if ($row[$field['name']])
											<i class="fa fa-check" aria-hidden="true"></i>
										@else
											<i class="fa fa-times" aria-hidden="true"></i>
										@endif
									</td>
								@elseif ($field['form_field'] == 'time')
									<td>
										{{ date('h:i A', strtotime($row[$field['name']])) }}
									</td>
								@elseif ($field['form_field'] == 'map coordinates')
									<td>
										@if ($row[$field['name']])
											<a target="_blank" href="https://www.google.com/maps/search/?api=1&query={{ $row[$field['name']] }}"><i class="fa fa-map-marker" aria-hidden="true"></i></a>
										@endif
									</td>
								@else
									<td>{{ $row[$field['name']] }}</td>
								@endif
							@endforeach
							<td class="actions-wrapper text-right">
								@if ($page['show'] || !request()->get('admin')['admin_role_id'])
									@if (request()->get('admin')['cms_pages'][$page['route']]['permissions']['read'])
										<a href="{{ url(config('hellotree.cms_route_prefix') . '/' . $page['route'] . '/' . $row['id']) }}" class="mb-2 btn btn-secondary btn-sm {{ $page['show'] ? '' : 'opacity-half' }}">View</a>
									@endif
								@endif
								@if ($page['edit'] || !request()->get('admin')['admin_role_id'])
									@if (request()->get('admin')['cms_pages'][$page['route']]['permissions']['edit'])
										<a href="{{ url(config('hellotree.cms_route_prefix') . '/' . $page['route'] . '/' . $row['id'] . '/edit' . $appends_to_query) }}" class="mb-2 btn btn-primary btn-sm {{ $page['edit'] ? '' : 'opacity-half' }}">Edit</a>
									@endif
								@endif
								@if ($page['delete'] || !request()->get('admin')['admin_role_id'])
									@if (request()->get('admin')['cms_pages'][$page['route']]['permissions']['delete'])
										<form class="row-delete d-inline-block {{ $page['delete'] ? '' : 'opacity-half' }}" method="post" action="{{ url(config('hellotree.cms_route_prefix') . '/' . $page['route'] .  '/' . $row['id'] . $appends_to_query) }}" onsubmit="return confirm('Are you sure?')">
											@csrf
											<input type="hidden" name="_method" value="DELETE">
											<button class="mb-2 btn btn-danger btn-sm">Delete</button>
										</form>
									@endif
								@endif
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>

            @if ($page['server_side_pagination'])
            <div class="row no-gutters">
                <div class="col-lg-6">
                    <div class="server-pagination-numbers">
                        @php
                            $last_item_in_page = $rows->perPage() * $rows->currentPage();
                            $first_item_in_page = $last_item_in_page - ($rows->perPage() - 1);
                        @endphp
                        Showing {{ $first_item_in_page }} to {{ $last_item_in_page > $rows->total() ? $rows->total() : $last_item_in_page }} of {{ $rows->total() }} entries
                    </div>
                    {{-- @dd($rows) --}}
                </div>
                <div class="col-lg-6">
                    {{ $rows->onEachSide(1)->appends($_GET)->links() }}
                </div>
            </div>
            @endif

		</div>
	</div>

	<div class="popup filter-popup">
		<div class="container h-100">
			<div class="row justify-content-center align-items-center h-100">
				<div class="col-lg-6">
					<div class="card py-3 px-4 my-0">
						<div class="d-flex mb-3">
							<p class="font-weight-bold flex-grow-1">FILTER:</p>
							<i class="fa fa-times close-popup"></i>
						</div>
						<form>
							<input type="hidden" name="per_page" value="{{ request('per_page') }}">
							@foreach($filters as $i => $filter)
								<input type="hidden" name="custom_validation[{{ $i }}][constraint]" value="{{ $filter['form_field'] == 'select multiple' ? 'whereHas' : 'whereIn' }}">
								<input type="hidden" name="custom_validation[{{ $i }}][value][0]" value="{{ $filter['name'] }}">
								@include('cms::/components/form-fields/select-multiple', [
									'label' => ucwords(str_replace('_', ' ', $filter['form_field_additionals_1'])),
									'name' => 'custom_validation[' . $i . '][value][1]',
									'options' => $extra_variables[$filter['form_field_additionals_1']],
									'store_column' => 'id',
									'display_column' => $filter['form_field_additionals_2'],
									'value' => isset(request('custom_validation')[$i]['value'][1]) && request('custom_validation')[$i]['value'][1] ? request('custom_validation')[$i]['value'][1] : '',
									'required' => false,
									'description' => '',
									'locale' => 'en',
								])
							@endforeach
							<div class="text-right">
								<a href="{{ url(config('hellotree.cms_route_prefix') . '/' . $page['route']) }}" class="btn btn-secondary btn-sm">Clear</a>
								<button class="btn btn-primary btn-sm">Submit</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

@endsection
