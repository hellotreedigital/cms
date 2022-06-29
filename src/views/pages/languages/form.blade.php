@extends('cms::layouts/dashboard')

@section('breadcrumb')
    <ul class="breadcrumbs list-inline font-weight-bold text-uppercase m-0">
        <li><a href="{{ url(config('hellotree.cms_route_prefix') . '/languages') }}">Languages</a></li>
        @if (isset($row))
            <li>{{ $row['id'] }}</li>
            <li>Edit</li>
        @else
            <li>Create</li>
        @endif
    </ul>
@endsection

@section('dashboard-content')

    <form method="post" enctype="multipart/form-data" action="{{ isset($row) ? url(config('hellotree.cms_route_prefix') . '/languages/' . $row['id']) : url(config('hellotree.cms_route_prefix') . '/languages') }}" ajax>

        <div class="card p-4 mx-2 mx-sm-5">
            <p class="font-weight-bold text-uppercase mb-4">{{ isset($row) ? 'Edit Language #' . $row['id'] : 'Add Language' }}</p>

            @if (isset($row))
                @method('put')
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <p class="m-0">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            @include('cms::/components/form-fields/input', [
                'label' => 'Slug',
                'name' => 'slug',
                'type' => 'text',
                'value' => isset($row) ? $row->slug : '',
                'required' => true,
                'description' => '',
                'locale' => '',
            ])

            @include('cms::/components/form-fields/input', [
                'label' => 'Title',
                'name' => 'title',
                'type' => 'text',
                'value' => isset($row) ? $row->title : '',
                'required' => true,
                'description' => '',
                'locale' => '',
            ])

            @include('cms::/components/form-fields/select', [
                'label' => 'Direction',
                'name' => 'direction',
                'options' => [
                    [
                        'title' => 'Left To Right',
                        'value' => 'ltr',
                    ],
                    [
                        'title' => 'Right To Left',
                        'value' => 'rtl',
                    ],
                ],
                'store_column' => 'value',
                'display_column' => 'title',
                'value' => isset($row) ? $row->direction : '',
                'required' => true,
                'description' => '',
                'locale' => '',
            ])

            @csrf

            <div class="form-buttons-wrapper text-right">
                <input type="hidden" name="ht_preview_mode" value="0">
                <button type="submit" class="btn btn-sm btn-primary">Submit</button>
            </div>
        </div>

    </form>

@endsection
