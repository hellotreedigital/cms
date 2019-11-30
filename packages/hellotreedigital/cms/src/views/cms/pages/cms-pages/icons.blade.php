@extends('cms::cms/layouts/dashboard')

@section('breadcrumb')
	<ul class="breadcrumbs list-inline font-weight-bold text-uppercase m-0">
		<li>Icons</li>
	</ul>
@endsection

@section('dashboard-content')

	<div class="card py-4 px-3 mx-2 mx-lg-5">
		<div class="row">
			@foreach($icons as $icon)
				<div class="col-2 text-center mb-3">
					<i class="fa {{ $icon }}" aria-hidden="true"></i>
					<p>{{ $icon }}</p>
				</div>
			@endforeach
		</div>
	</div>

@endsection