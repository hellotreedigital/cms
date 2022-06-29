@extends('cms::layouts/dashboard')

@section('breadcrumb')
    <ul class="breadcrumbs list-inline font-weight-bold text-uppercase m-0">
        <li><a href="{{ url(config('hellotree.cms_route_prefix') . '/languages') }}">Languages</a></li>
        <li><a href="{{ url(config('hellotree.cms_route_prefix') . '/languages/' . $row['id']) }}">{{ $row['id'] }}</a></li>
        <li>Show</li>
    </ul>
@endsection

@section('dashboard-content')
    <div class="card p-4 mx-2 mx-sm-5">

        <div class="row">
            <div class="col-lg-6">
                <p class="font-weight-bold text-uppercase mb-4">Show Language #{{ $row['id'] }}</p>
            </div>
            <div class="col-lg-6 text-right">
                <div class="actions p-0">
                    @if (request()->get('admin')['cms_pages']['languages']['permissions']['edit'])
                        <a href="{{ url(config('hellotree.cms_route_prefix') . '/languages/' . $row['id'] . '/edit') }}" class="btn btn-sm btn-primary">Edit</a>
                    @endif
                    @if (request()->get('admin')['cms_pages']['languages']['permissions']['delete'])
                        <form class="d-inline" onsubmit="return confirm('Are you sure?')" method="post" action="{{ url(config('hellotree.cms_route_prefix') . '/languages/' . $row['id']) }}">
                            @csrf
                            <input type="hidden" name="_method" value="DELETE">
                            <button class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        @include('cms::/components/show-fields/text', [
            'label' => 'Slug',
            'text' => $row->slug,
        ])

        @include('cms::/components/show-fields/text', [
            'label' => 'Title',
            'text' => $row->title,
        ])

        @include('cms::/components/show-fields/text', [
            'label' => 'Direction',
            'text' => $row->direction,
        ])

    </div>
@endsection
