@extends('cms::cms/layouts/dashboard')

@section('breadcrumb')
	<ul class="breadcrumbs list-inline font-weight-bold text-uppercase m-0">
		<li><a href="{{ url(env('CMS_PREFIX', 'admin') . '/cms-pages') }}">cms pages</a></li>
		<li>order</li>
	</ul>
@endsection

@section('dashboard-content')

	<div class="card p-4 mx-2 mx-sm-5">
		@if (count(request()->get('admin')['cms_pages_grouped']))
			<div class="mb-4">
				<form id="add-column">
					<label><b>Add Dropdown</b></label>
					<div class="row">
						<div class="col-6">
							<div class="mb-2">
								<label>Title</label>
								<input name="title" class="form-control">
							</div>
						</div>
						<div class="col-6">
							<div class="mb-2">
								<label>Icon</label>
								<input name="icon" class="form-control">
							</div>
						</div>
					</div>
					<div class="text-right">
						<button type="submit" class="btn btn-primary btn-sm px-3">Add</button>
					</div>
				</form>
			</div>
			<form method="post" id="order-form">
				@csrf
				<ul class="nested-sortable list-unstyled m-0">
					@foreach(request()->get('admin')['cms_pages_grouped'] as $group)
						@if (!$group['title'] && !$group['icon'])
							@foreach($group['pages'] as $page)
								<li class="nested-sortable-row bg-white border px-3 py-2 my-2">
									<input type="hidden" name="id[]" value="{{ $page['id'] }}">
									<input type="hidden" name="title[]" value="{{ $page['display_name_plural'] }}">
									<input type="hidden" name="icon[]" value="{{ $page['icon'] }}">
									<input type="hidden" name="parent_title[]" value="">
									<input type="hidden" name="parent_icon[]" value="">
									<div class="py-2">
										<i class="text-center mr-2 fa {{ $page['icon'] }}" aria-hidden="true"></i> {{ $page['display_name_plural'] }}
									</div>
								</li>
							@endforeach
						@else
							<li class="nested-sortable-row bg-white border px-3 py-2 my-2">
								<input type="hidden" name="id[]" value="">
								<input type="hidden" name="title[]" value="{{ $group['title'] }}">
								<input type="hidden" name="icon[]" value="{{ $group['icon'] }}">
								<input type="hidden" name="parent_title[]" value="">
								<input type="hidden" name="parent_icon[]" value="">
								<div class="py-2">
									<i class="text-center mr-2 fa {{ $group['icon'] }}" aria-hidden="true"></i> {{ $group['title'] }}
								</div>
								<ul>
									@foreach($group['pages'] as $page)
									<li class="nested-sortable-row bg-white border px-3 py-2 my-2">
										<input type="hidden" name="id[]" value="{{ $page['id'] }}">
										<input type="hidden" name="title[]" value="{{ $page['display_name_plural'] }}">
										<input type="hidden" name="icon[]" value="{{ $page['icon'] }}">
										<input type="hidden" name="parent_title[]" value="">
										<input type="hidden" name="parent_icon[]" value="">
										<div class="py-2">
											<i class="text-center mr-2 fa {{ $page['icon'] }}" aria-hidden="true"></i> {{ $page['display_name_plural'] }}
										</div>
									</li>
									@endforeach
								</ul>
							</li>
						@endif
					@endforeach
				</ul>
				<div class="text-right">
					<button class="btn btn-sm btn-primary">Submit</button>
				</div>
			</form>
		@else
			<h5 class="text-center m-0 py-4">No record found for sorting</h5>
		@endif
	</div>

@endsection

@section('scripts')

<script src="{{ asset('cms/js/jquery.mjs.nestedSortable.js') }}"></script>
<script>
	$(document).ready(function(){

		$('#add-column').on('submit', function(e){
			e.preventDefault();

			var form = $(this);
			var title = form.find('[name="title"]').val();
			var icon = form.find('[name="icon"]').val();

			$('.nested-sortable').append(' <li class="nested-sortable-row bg-white border px-3 py-2 my-2"> <input type="hidden" name="id[]" value=""> <input type="hidden" name="title[]" value="' + title + '"> <input type="hidden" name="icon[]" value="' + icon + '"> <input type="hidden" name="parent_title[]" value=""> <input type="hidden" name="parent_icon[]" value=""> <div class="py-2"> <i class="text-center mr-2 fa ' + icon + '" aria-hidden="true"></i> ' + title + ' </div> </li>');

			form[0].reset();
		});

		$('.nested-sortable').nestedSortable({
			listType: 'ul',
			handle: 'div',
			items: 'li',
			maxLevels: 2,
			toleranceElement: '> div',
		});

		$('#order-form').on('submit', function(e){
			$('.nested-sortable > li').each(function(){
				var parent = $(this);
				var parent_title = parent.find('[name="title[]"]').val();
				var parent_icon = parent.find('[name="icon[]"]').val();
				parent.find('ul li').each(function(){
					var child = $(this);
					child.find('[name="parent_title[]"]').val(parent_title);
					child.find('[name="parent_icon[]"]').val(parent_icon);
				});
			});
		});

	});
</script>

@endsection