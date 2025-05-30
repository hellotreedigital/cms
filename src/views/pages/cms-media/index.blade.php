@extends('cms::layouts/dashboard')

@section('breadcrumb')
	<ul class="breadcrumbs list-inline font-weight-bold text-uppercase m-0">
		<li>CMS Media Library</li>
	</ul>
@endsection

@section('dashboard-content')

	<div class="card py-4 mx-2 mx-lg-5">
        <div class="actions">
            <form id="upload-form" class="d-block d-md-inline-block">
                <!-- Hidden file input -->
                <input type="file" name="file" id="file-upload" style="display: none;">
                <meta name="csrf-token" content="{{ csrf_token() }}">
                <!-- Button that triggers file input -->
                <button type="button" class="btn btn-primary btn-sm" 
                        onclick="document.getElementById('file-upload').click()">
                    Upload file
                </button>
            </form>
        </div>
        <div class="view-toggle d-flex justify-content-end mb-3 px-3">
           <a style="{{ request()->get('view') === 'grid' ? '' : 'background-color: white;' }}" href="{{ request()->fullUrlWithQuery(['view' => 'list']) }}"
               class="btn btn-sm btn-primary">
                List View
            </a>
            <a style="{{ request()->get('view') === 'grid' ? 'background-color: white;' : '' }}" href="{{ request()->fullUrlWithQuery(['view' => 'grid']) }}"
               class="btn btn-sm ml-2 btn-primary">
                Grid View
            </a>
        </div>
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
					</form>
				</div>
			</div>
			<div class="col-md-auto px-md-0">
				<form class="server-search-wrapper mr-3">
					<label>
						Search:
                        @foreach(request()->query() as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
						<input type="search" name="custom_search" value="{{ request('custom_search') }}">
					</label>
				</form>
			</div>
		</div>
        <div class="row">
            <div class="col-12">
                @if(request('view', 'list') === 'grid')
                    <div class="row p-3">
                        @foreach($mediaItems as $item)
                            <div class="col-6 col-md-4 col-lg-3 custom-col mb-4">
                                <div class="card mb-3 h-100 toggle-card " style="cursor: pointer;">
                                    <div class="py-2"></div>
                                    @if (!empty($item['file_path']) && Str::endsWith(strtolower($item['file_path']), ['.jpeg', '.jpg','.gif', '.png', '.svg']))
                                        <img src="{{ Storage::url($item['file_path']) }}" class="card-img-top" style="max-height: 150px; object-fit: contain;">
                                    @elseif(!empty($item['file_path']) && Str::endsWith(strtolower($item['file_path']), ['.pdf']))
                                        <img src="{{ url(config('hellotree.cms_route_prefix')).'/vendor-image/pdf-logo.png' }}" class="card-img-top" style="max-height: 150px; object-fit: contain;">
                                    @elseif(!empty($item['file_path']) && Str::endsWith(strtolower($item['file_path']), ['.doc','.docx']))
                                        <img src="{{ url(config('hellotree.cms_route_prefix')).'/vendor-image/docx-logo.svg' }}" class="card-img-top" style="max-height: 150px; object-fit: contain;">
                                    @elseif(!empty($item['file_path']) && Str::endsWith(strtolower($item['file_path']), ['.xls','.xlsx']))
                                        <img src="{{ url(config('hellotree.cms_route_prefix')).'/vendor-image/xlsx-icon.svg' }}" class="card-img-top" style="max-height: 150px; object-fit: contain;">
                                    @else
                                        <img src="{{ url(config('hellotree.cms_route_prefix')).'/vendor-image/video.png' }}" class="card-img-top" style="max-height: 150px; object-fit: contain;">
                                    @endif
                            
                                    <div class="card-body card-details pt-0 d-none">
                                        <h6 class="pt-5" style="word-break: break-word; white-space: normal;" class="card-title mb-0 text-truncate">{{ $item['file_name'] }}</h6>
                                        <p class="card-text text-muted small mb-1">{{ $item['folder_name'] }}</p>
                                        <p class="card-text small mb-1">
                                            @if($item['size'] < 1000)
                                                {{ $item['size'] }} Bytes
                                            @elseif($item['size'] < 1048576)
                                                {{ number_format($item['size'] / 1024, 2) }} KB
                                            @elseif($item['size'] < 1073741824)
                                                {{ number_format($item['size'] / 1048576, 2) }} MB
                                            @else
                                                {{ number_format($item['size'] / 1073741824, 2) }} GB
                                            @endif
                                        </p>
                                        <p class="card-text small mb-2">Updated: {{ $item['updated_at'] }}</p>
                                        <a href="{{ Storage::url($item['file_path']) }}" class="btn btn-sm btn-outline-secondary" target="_blank">Open</a>
                                        <form class="d-inline float-right" method="POST"
                                              action="{{ url(config('hellotree.cms_route_prefix') . '/cms-media/destroy') }}"
                                              onsubmit="return confirm('This action cannot be undone. Are you sure you want to delete this?')">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="delete_path" value="{{ $item['file_path'] }}">
                                            @foreach(request()->query() as $key => $value)
                                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                            @endforeach
                                            <button class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
        		<div class="datatable-wrapper table-responsive ">
        			<table class="table no-export">
                        @php
                            $currentSort = request('sort_by');
                            $currentOrder = request('sort_order', 'asc');
                            function sortUrl($field) {
                                $order = request('sort_by') === $field && request('sort_order') === 'asc' ? 'desc' : 'asc';
                                return request()->fullUrlWithQuery(['sort_by' => $field, 'sort_order' => $order]);
                            }
                        @endphp
                        
                        <thead>
                            <tr>
                                <th></th>
                                <th>
                                    <a href="{{ sortUrl('file_name') }}">
                                        File Name
                                        @if($currentSort === 'file_name')
                                            <i class="fa fa-sort-{{ $currentOrder === 'asc' ? 'asc' : 'desc' }}"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ sortUrl('folder_name') }}">
                                        Folder Name
                                        @if($currentSort === 'folder_name')
                                            <i class="fa fa-sort-{{ $currentOrder === 'asc' ? 'asc' : 'desc' }}"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>Image Preview</th>
                                <th>Public Path</th>
                                <th>
                                    <a href="{{ sortUrl('size') }}">
                                        File Size
                                        @if($currentSort === 'size')
                                            <i class="fa fa-sort-{{ $currentOrder === 'asc' ? 'asc' : 'desc' }}"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ sortUrl('updated_at') }}">
                                        Last Modified
                                        @if($currentSort === 'updated_at')
                                            <i class="fa fa-sort-{{ $currentOrder === 'asc' ? 'asc' : 'desc' }}"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>Actions</th>
                            </tr>
                        </thead>
        				<tbody>
        					@foreach($mediaItems as $item)
        						<tr>
        						    <td></td>
        							<td>
        							    {{ $item['file_name'] }}
        							</td>
        							<td>{{$item['folder_name']}}</td>
        							<td>
									    @if (!empty($item['file_path']) && Str::endsWith(strtolower($item['file_path']), ['.jpeg', '.jpg','.gif', '.png', '.svg']))
                                            <img src="{{ Storage::url($item['file_path']) }}" class="img-thumbnail" style="max-width: 100px;">
                                        @elseif(!empty($item['file_path']) && Str::endsWith(strtolower($item['file_path']), ['.pdf']))
                                            <img src="{{ url(config('hellotree.cms_route_prefix')).'/vendor-image/pdf-logo.png' }}" class="img-thumbnail" style="max-width: 100px;">
                                        @elseif(!empty($item['file_path']) && Str::endsWith(strtolower($item['file_path']), ['.doc','.docx']))
                                            <img src="{{ url(config('hellotree.cms_route_prefix')).'/vendor-image/docx-logo.svg' }}" class="img-thumbnail" style="max-width: 100px;">
                                        @elseif(!empty($item['file_path']) && Str::endsWith(strtolower($item['file_path']), ['.xls','.xlsx']))
                                            <img src="{{ url(config('hellotree.cms_route_prefix')).'/vendor-image/xlsx-icon.svg' }}" class="img-thumbnail" style="max-width: 100px;">
                                        @else
                                            <img src="{{ url(config('hellotree.cms_route_prefix')).'/vendor-image/video.png' }}" class="img-thumbnail" style="max-width: 100px;">
                                        @endif
									</td>
        							<td><a target="_blank" href="{{Storage::url($item['file_path'])}}">Click Here</a></td>
                                    <td data-order="{{ $item['size'] }}">
                                        @if($item['size'] < 1000)
                                            {{ $item['size'] }} Bytes
                                        @elseif($item['size'] < 1048576)
                                            {{ number_format($item['size'] / 1024, 2) }} KB
                                        @elseif($item['size'] < 1073741824)
                                            {{ number_format($item['size'] / 1048576, 2) }} MB
                                        @else
                                            {{ number_format($item['size'] / 1073741824, 2) }} GB
                                        @endif
                                    </td>
                                    <td>{{$item['updated_at']}}</td>
        							<td class="actions-wrapper text-right">
    									<form class="d-inline" onsubmit="return confirm('This action cannot be undone. Are you sure you want to delete this?')" action="{{ url(config('hellotree.cms_route_prefix') . '/cms-media/destroy') }}" method="POST">
    										@csrf
    										<input type="hidden" name="delete_path" value="{{$item['file_path']}}"/>
                                            @foreach(request()->query() as $key => $value)
                                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                            @endforeach
    										@method('DELETE')
    										<button type="submit" class="mb-2 btn btn-danger btn-sm">Delete</button>
    									</form>
        							</td>
        						</tr>
        					@endforeach
        				</tbody>
        			</table>
        		</div>
        		@endif
                <div class="row no-gutters">
                    <div class="col-lg-6">
                        <div class="server-pagination-numbers">
                            @php
                                $last_item_in_page = $mediaItems->perPage() * $mediaItems->currentPage();
                                $first_item_in_page = $last_item_in_page - ($mediaItems->perPage() - 1);
                            @endphp
                            Showing {{ $first_item_in_page }} to {{ $last_item_in_page > $mediaItems->total() ? $mediaItems->total() : $last_item_in_page }} of {{ $mediaItems->total() }} entries
                        </div>
                    </div>
                    <div class="col-lg-6">
                        {{ $mediaItems->onEachSide(1)->appends($_GET)->links() }}
                    </div>
                </div>
            </div>
        </div>
	</div>
<style>
@media (min-width: 1400px) {
  .custom-col {
    flex: 0 0 20%;
    max-width: 20%;
  }
}
</style>
<script>
    // Handle toggle on card click
    document.querySelectorAll('.toggle-card').forEach(function (card) {
        card.addEventListener('click', function () {
            const currentDetails = this.querySelector('.card-details');
            const open = currentDetails.classList.contains('d-none');

            // Hide all card details
            document.querySelectorAll('.toggle-card .card-details').forEach(function (details) {
                details.classList.add('d-none');
            });

            // Toggle current card if it was previously closed
            if (open) {
                currentDetails.classList.remove('d-none');
            }
        });
    });

    // Prevent toggle when clicking buttons or forms inside the card
    document.querySelectorAll('.toggle-card .btn, .toggle-card form').forEach(function (el) {
        el.addEventListener('click', function (e) {
            e.stopPropagation();
        });
    });

    // Close all when clicking outside any .toggle-card
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.toggle-card')) {
            document.querySelectorAll('.toggle-card .card-details').forEach(function (details) {
                details.classList.add('d-none');
            });
        }
    });
</script>

<script>
    document.getElementById('file-upload').addEventListener('change', async function () {
        const loader = document.getElementById('loader');
        const fileInput = this;
        const file = fileInput.files[0];
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
        if (!file) return;
    
        const formData = new FormData();
        formData.append('_token', token);
        formData.append('file', file);
        const toast = document.querySelector('.toast.error');
    
        loader.style.display = 'block';
    
        try {
            const response = await fetch("{{ url(config('hellotree.cms_route_prefix') . '/cms-media/upload') }}", {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            });
    
    
            const data = await response.json();
    
            if (!response.ok) {
                loader.style.display = 'none';
                // Display error message
                const message = data?.errors?.file?.[0] || data?.message || 'Upload failed';
                document.querySelector('.toast.error').innerHTML = message;
                toast.classList.add('show');
                setTimeout(() => {
                    toast.classList.remove('show');
                    document.querySelector('.toast.error').innerHTML = '';
                }, 2000);
                fileInput.value = '';
                return;
            }
            // If successfull
            fileInput.value = '';
            window.location.reload();
                        
        } catch (error) {
            loader.style.display = 'none';
            document.querySelector('.toast.error').innerHTML = error.message || 'An unexpected error occurred';
            toast.classList.add('show');
            setTimeout(() => {
                toast.classList.remove('show');
                document.querySelector('.toast.error').innerHTML = '';
            }, 2000);
        }
    });
</script>
@endsection
