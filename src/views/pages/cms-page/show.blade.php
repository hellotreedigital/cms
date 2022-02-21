@extends('cms::layouts/dashboard')

@section('breadcrumb')
    <ul class="breadcrumbs list-inline font-weight-bold text-uppercase m-0">
        <li><a href="{{ url(config('hellotree.cms_route_prefix') . '/' . $page['route']) }}">{{ $page['display_name_plural'] }}</a></li>
        <li><a href="{{ url(config('hellotree.cms_route_prefix') . '/' . $page['route'] . '/' . $row['id']) }}">{{ $row['id'] }}</a></li>
        <li>Show</li>
    </ul>
@endsection

@section('dashboard-content')

    <div class="card p-4 mx-2 mx-sm-5">

        <div class="row">
            <div class="col-lg-6">
                <p class="font-weight-bold text-uppercase mb-4">Show {{ $page['display_name'] }} #{{ $row['id'] }}</p>
            </div>
            <div class="col-lg-6 text-right">
                <div class="actions p-0">
                    @if ($page['edit'])
                        @if (request()->get('admin')['cms_pages'][$page['route']]['permissions']['edit'])
                            <a href="{{ url(config('hellotree.cms_route_prefix') . '/' . $page['route'] . '/' . $row['id'] . '/edit') }}" class="btn btn-sm btn-primary">Edit</a>
                        @endif
                    @endif
                    @if ($page['delete'])
                        @if (request()->get('admin')['cms_pages'][$page['route']]['permissions']['delete'])
                            <form class="d-inline" onsubmit="return confirm('Are you sure?')" method="post" action="{{ url(config('hellotree.cms_route_prefix') . '/' . $page['route'] . '/' . $row['id']) }}">
                                @csrf
                                <input type="hidden" name="_method" value="DELETE">
                                <button class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        @foreach ($page_fields as $field)
            @include('cms::pages/cms-page/show-fields', ['locale'=>null])
        @endforeach

        @if (count($translatable_fields))
            @foreach (config('translatable.locales') as $locale)
                @if (is_array($locale))
                    @continue
                @endif
                <div class="form-group">
                    <label>{{ ucfirst($locale) }}</label>
                    <div class="pl-3">
                        @foreach ($translatable_fields as $field)
                            @include('cms::pages/cms-page/show-fields', ['locale'=>$locale])
                        @endforeach
                    </div>
                </div>
            @endforeach
        @endif

    </div>

@endsection
