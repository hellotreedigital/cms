@extends('cms::layouts/dashboard')

@section('dashboard-content')

	<div class="card py-4 mx-2 mx-lg-5">
        <h4 class="font-weight-bold text-center my-4">{{ config('hellotree.home_title') }}</h4>
        @if (config('hellotree.home_content'))
            {!! config('hellotree.home_content') !!}
        @endif
	</div>

@endsection