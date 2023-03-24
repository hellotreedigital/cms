@extends('cms::layouts/dashboard')

@section('breadcrumb')
    <ul class="breadcrumbs list-inline font-weight-bold text-uppercase m-0">
        <li><a href="{{ url(config('hellotree.cms_route_prefix') . '/' . $page['route'] . '') }}">{{ $page['display_name_plural'] }}</a></li>
        @if (isset($row))
            <li>{{ $row['id'] }}</li>
            <li>Edit</li>
        @else
            <li>Create</li>
        @endif
    </ul>
@endsection

@section('dashboard-content')

    <div class="row no-gutters">
        <div class="col-lg-6 mb-lg-4">
            <div class="pr-lg-3">
                <form method="post" enctype="multipart/form-data" action="{{ isset($row) ? url(config('hellotree.cms_route_prefix') . '/' . $page['route'] . '/' . $row['id'] . $appends_to_query) : url(config('hellotree.cms_route_prefix') . '/' . $page['route'] . '') }}" ajax>
                    <div class="card p-4 mx-2 mx-sm-5 mr-lg-0">
                        <p class="font-weight-bold text-uppercase mb-4">{{ isset($row) ? 'Edit ' . $page['display_name'] . ' #' . $row['id'] : 'Add ' . $page['display_name'] }}</p>

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

                        @foreach ($page_fields as $field)
                            @include('cms::pages/cms-page/form-fields', ['locale' => null])
                        @endforeach

                        @foreach (\Hellotreedigital\Cms\Models\Language::get() as $language)
                            <div class="form-group">
                                <label>{{ $language->title }}</label>
                                <div class="pl-3">
                                    @foreach ($page_translatable_fields as $field)
                                        @include('cms::pages/cms-page/form-fields', ['locale' => $language->slug])
                                    @endforeach
                                </div>
                            </div>
                        @endforeach

                        <div class="text-right">
                            @csrf
                            <input type="hidden" name="ht_preview_mode" value="0">
                            @if ($page['preview_path'])
                                <button type="button" class="btn btn-sm btn-secondary ht-preview-mode">Preview</button>
                            @endif
                            <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-6 d-none d-lg-block">
            <div class="card p-4 mx-2 mx-sm-5 ml-lg-0">
                <div>
                    <p class="font-weight-bold mb-3">GOOGLE</p>
                    <p class="seo-title mb-0" style="font-family: Arial; font-size: 18px; color: #1a0dab; line-height: 1.2; cursor: pointer; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"></p>
                    <p style="margin-bottom: 3px; font-family: Arial; font-size: 14px; color: #006621; line-height: 1.2; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ env('APP_URL') }}</p>
                    <p class="seo-google-description mb-0" style="font-family: Arial; font-size: 13px; color: #545454; line-height: 1.4; word-wrap: break-word;"></p>
                </div>
                <hr class="my-4">
                <div>
                    <p class="font-weight-bold mb-3">FACEBOOK</p>
                    <div style="width: 500px; max-width: 100%; cursor: pointer; border: 1px solid #dadde1;">
                        <img class="seo-image" style="width: 100%; height: 261px; object-fit: cover; {{ isset($row) && $row->image ? '' : 'opacity: 0' }}" src="{{ isset($row) ? Storage::url($row->image) : '' }}">
                        <div style="border-top: 1px solid #dadde1; padding: 10px 12px; background-color: #f2f3f5; font-family: Helvetica, Arial, sans-serif;">
                            <p class="text-uppercase mb-0" style="color: #606770; font-size: 12px;">{{ request()->getHost() }}</p>
                            <p class="seo-title mb-0" style="color: #606770; font-size: 16px; color: #1d2129; font-weight: 600;">{{ request()->getHost() }}</p>
                            <p class="seo-description mb-0" style="color: #606770; font-size: 14px; display: -webkit-box; text-overflow: ellipsis; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden;"></p>
                        </div>
                    </div>
                </div>
                <hr class="my-4">
                <div>
                    <p class="font-weight-bold mb-3">TWITTER</p>
                    <div style="width: 500px; max-width: 100%; cursor: pointer; border: 1px solid #E1E8ED; border-radius: 6px;">
                        <img class="seo-image" style="width: 100%; height: 252px; object-fit: cover; {{ isset($row) && $row->image ? '' : 'opacity: 0' }}" src="{{ isset($row) ? Storage::url($row->image) : '' }}">
                        <div style="border-top: 1px solid #dadde1; padding: 10.5px 14px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Ubuntu, 'Helvetica Neue', sans-serif;">
                            <p class="seo-title mb-0" style="color: #18283E; font-size: 14px; font-weight: bold"></p>
                            <p class="seo-description mb-1" style="color: #18283E; font-size: 14px; display: -webkit-box; overflow: hidden; -webkit-line-clamp: 2; -webkit-box-orient: vertical; text-overflow: ellipsis;"></p>
                            <p class="mb-0" style="color: #8899A6; font-size: 14px; display: -webkit-box; overflow: hidden; -webkit-line-clamp: 2; -webkit-box-orient: vertical; text-overflow: ellipsis;">{{ request()->getHost() }}</p>
                        </div>
                    </div>
                </div>
                @if (!auth('admin')->user()->admin_role_id)
                    <div class="text-right">
                        <hr class="mt-4">
                        <div>
                            <textarea id="code-to-copy" class="d-block" style="height: 1px; padding: 0px; margin: 0px; opacity: 0; border-width: 0px;">{{ '<!-- Primary Meta Tags --><title>%{%{ $seo_page->title }%}%</title>
                                                        <meta name="title" content="%{%{ $seo_page->title }%}%">
                                                        <meta name="description" content="%{%{ $seo_page->description }%}%">

                                                        <!-- Open Graph / Facebook --><meta property="og:type" content="website">
                                                        <meta property="og:url" content="' .
                                env('APP_URL') .
                                '">
                                                        <meta property="og:title" content="%{%{ $seo_page->title }%}%">
                                                        <meta property="og:description" content="%{%{ $seo_page->description }%}%">
                                                        <meta property="og:image" content="%{%{ Storage::url($seo_page->image) }%}%">

                                                        <!-- Twitter --><meta property="twitter:card" content="summary_large_image">
                                                        <meta property="twitter:url" content="' .
                                env('APP_URL') .
                                '">
                                                        <meta property="twitter:title" content="%{%{ $seo_page->title }%}%">
                                                        <meta property="twitter:description" content="%{%{ $seo_page->description }%}%">
                                                        <meta property="twitter:image" content="%{%{ Storage::url($seo_page->image) }%}%">' }}</textarea>
                        </div>
                        <button class="btn btn-sm btn-primary copy-code">Copy Code</button>
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $('[name*=title]').on('keyup', function() {
            $('.seo-title').text($(this).val());
        }).keyup();
        $('[name*=description]').on('keyup', function() {
            var description = $(this).val();
            var googleDescription = description.substring(0, 160);
            $('.seo-google-description').text(description.length > 160 ? (googleDescription + '...') : description);
            $('.seo-description').text(description);
        }).keyup();
        $('[name="image"]').on('change', function() {
            var file = $(this)[0].files[0];
            $('.seo-image').css('opacity', 0);
            if (file) {
                $('.seo-image').attr('src', URL.createObjectURL(file));
                $('.seo-image').css('opacity', 1);
            }
        });
        $('.copy-code').on('click', function() {
            var textarea = $('#code-to-copy');
            textarea.val(
                textarea
                .val()
                .split('%{')
                .join('{')
                .split('}%')
                .join('}')
            )
            textarea[0].select();
            document.execCommand('copy');
            alert('Code copied')
        });
    </script>
@endsection
