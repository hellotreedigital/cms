<?php

namespace Hellotreedigital\Cms\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Hellotreedigital\Cms\Models\CmsPage;
use Artisan;
use Illuminate\Validation\ValidationException;
use Storage;
use Str;
use Schema;

class CmsPagesController extends Controller
{
    public $migration_types = [
        'string',
        'date',
        'time',
        'datetime',
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
        'select multiple',
        'checkbox',
        'image',
        'multiple images',
        'file',
        'map coordinates',
    ];

    public $icons = ["fa-500px", "fa-address-book", "fa-address-book-o", "fa-address-card", "fa-address-card-o", "fa-adjust", "fa-adn", "fa-align-center", "fa-align-justify", "fa-align-left", "fa-align-right", "fa-amazon", "fa-ambulance", "fa-american-sign-language-interpreting", "fa-anchor", "fa-android", "fa-angellist", "fa-angle-double-down", "fa-angle-double-left", "fa-angle-double-right", "fa-angle-double-up", "fa-angle-down", "fa-angle-left", "fa-angle-right", "fa-angle-up", "fa-apple", "fa-archive", "fa-area-chart", "fa-arrow-circle-down", "fa-arrow-circle-left", "fa-arrow-circle-o-down", "fa-arrow-circle-o-left", "fa-arrow-circle-o-right", "fa-arrow-circle-o-up", "fa-arrow-circle-right", "fa-arrow-circle-up", "fa-arrow-down", "fa-arrow-left", "fa-arrow-right", "fa-arrow-up", "fa-arrows", "fa-arrows-alt", "fa-arrows-h", "fa-arrows-v", "fa-asl-interpreting", "fa-assistive-listening-systems", "fa-asterisk", "fa-at", "fa-audio-description", "fa-automobile", "fa-backward", "fa-balance-scale", "fa-ban", "fa-bandcamp", "fa-bank", "fa-bar-chart", "fa-bar-chart-o", "fa-barcode", "fa-bars", "fa-bath", "fa-bathtub", "fa-battery", "fa-battery-0", "fa-battery-1", "fa-battery-2", "fa-battery-3", "fa-battery-4", "fa-battery-empty", "fa-battery-full", "fa-battery-half", "fa-battery-quarter", "fa-battery-three-quarters", "fa-bed", "fa-beer", "fa-behance", "fa-behance-square", "fa-bell", "fa-bell-o", "fa-bell-slash", "fa-bell-slash-o", "fa-bicycle", "fa-binoculars", "fa-birthday-cake", "fa-bitbucket", "fa-bitbucket-square", "fa-bitcoin", "fa-black-tie", "fa-blind", "fa-bluetooth", "fa-bluetooth-b", "fa-bold", "fa-bolt", "fa-bomb", "fa-book", "fa-bookmark", "fa-bookmark-o", "fa-braille", "fa-briefcase", "fa-btc", "fa-bug", "fa-building", "fa-building-o", "fa-bullhorn", "fa-bullseye", "fa-bus", "fa-buysellads", "fa-cab", "fa-calculator", "fa-calendar", "fa-calendar-check-o", "fa-calendar-minus-o", "fa-calendar-o", "fa-calendar-plus-o", "fa-calendar-times-o", "fa-camera", "fa-camera-retro", "fa-car", "fa-caret-down", "fa-caret-left", "fa-caret-right", "fa-caret-square-o-down", "fa-caret-square-o-left", "fa-caret-square-o-right", "fa-caret-square-o-up", "fa-caret-up", "fa-cart-arrow-down", "fa-cart-plus", "fa-cc", "fa-cc-amex", "fa-cc-diners-club", "fa-cc-discover", "fa-cc-jcb", "fa-cc-mastercard", "fa-cc-paypal", "fa-cc-stripe", "fa-cc-visa", "fa-certificate", "fa-chain", "fa-chain-broken", "fa-check", "fa-check-circle", "fa-check-circle-o", "fa-check-square", "fa-check-square-o", "fa-chevron-circle-down", "fa-chevron-circle-left", "fa-chevron-circle-right", "fa-chevron-circle-up", "fa-chevron-down", "fa-chevron-left", "fa-chevron-right", "fa-chevron-up", "fa-child", "fa-chrome", "fa-circle", "fa-circle-o", "fa-circle-o-notch", "fa-circle-thin", "fa-clipboard", "fa-clock-o", "fa-clone", "fa-close", "fa-cloud", "fa-cloud-download", "fa-cloud-upload", "fa-cny", "fa-code", "fa-code-fork", "fa-codepen", "fa-codiepie", "fa-coffee", "fa-cog", "fa-cogs", "fa-columns", "fa-comment", "fa-comment-o", "fa-commenting", "fa-commenting-o", "fa-comments", "fa-comments-o", "fa-compass", "fa-compress", "fa-connectdevelop", "fa-contao", "fa-copy", "fa-copyright", "fa-creative-commons", "fa-credit-card", "fa-credit-card-alt", "fa-crop", "fa-crosshairs", "fa-css3", "fa-cube", "fa-cubes", "fa-cut", "fa-cutlery", "fa-dashboard", "fa-dashcube", "fa-database", "fa-deaf", "fa-deafness", "fa-dedent", "fa-delicious", "fa-desktop", "fa-deviantart", "fa-diamond", "fa-digg", "fa-dollar", "fa-dot-circle-o", "fa-download", "fa-dribbble", "fa-drivers-license", "fa-drivers-license-o", "fa-dropbox", "fa-drupal", "fa-edge", "fa-edit", "fa-eercast", "fa-eject", "fa-ellipsis-h", "fa-ellipsis-v", "fa-empire", "fa-envelope", "fa-envelope-o", "fa-envelope-open", "fa-envelope-open-o", "fa-envelope-square", "fa-envira", "fa-eraser", "fa-etsy", "fa-eur", "fa-euro", "fa-exchange", "fa-exclamation", "fa-exclamation-circle", "fa-exclamation-triangle", "fa-expand", "fa-expeditedssl", "fa-external-link", "fa-external-link-square", "fa-eye", "fa-eye-slash", "fa-eyedropper", "fa-fa", "fa-facebook", "fa-facebook-f", "fa-facebook-official", "fa-facebook-square", "fa-fast-backward", "fa-fast-forward", "fa-fax", "fa-feed", "fa-female", "fa-fighter-jet", "fa-file", "fa-file-archive-o", "fa-file-audio-o", "fa-file-code-o", "fa-file-excel-o", "fa-file-image-o", "fa-file-movie-o", "fa-file-o", "fa-file-pdf-o", "fa-file-photo-o", "fa-file-picture-o", "fa-file-powerpoint-o", "fa-file-sound-o", "fa-file-text", "fa-file-text-o", "fa-file-video-o", "fa-file-word-o", "fa-file-zip-o", "fa-files-o", "fa-film", "fa-filter", "fa-fire", "fa-fire-extinguisher", "fa-firefox", "fa-first-order", "fa-flag", "fa-flag-checkered", "fa-flag-o", "fa-flash", "fa-flask", "fa-flickr", "fa-floppy-o", "fa-folder", "fa-folder-o", "fa-folder-open", "fa-folder-open-o", "fa-font", "fa-font-awesome", "fa-fonticons", "fa-fort-awesome", "fa-forumbee", "fa-forward", "fa-foursquare", "fa-free-code-camp", "fa-frown-o", "fa-futbol-o", "fa-gamepad", "fa-gavel", "fa-gbp", "fa-ge", "fa-gear", "fa-gears", "fa-genderless", "fa-get-pocket", "fa-gg", "fa-gg-circle", "fa-gift", "fa-git", "fa-git-square", "fa-github", "fa-github-alt", "fa-github-square", "fa-gitlab", "fa-gittip", "fa-glass", "fa-glide", "fa-glide-g", "fa-globe", "fa-google", "fa-google-plus", "fa-google-plus-circle", "fa-google-plus-official", "fa-google-plus-square", "fa-google-wallet", "fa-graduation-cap", "fa-gratipay", "fa-grav", "fa-group", "fa-h-square", "fa-hacker-news", "fa-hand-grab-o", "fa-hand-lizard-o", "fa-hand-o-down", "fa-hand-o-left", "fa-hand-o-right", "fa-hand-o-up", "fa-hand-paper-o", "fa-hand-peace-o", "fa-hand-pointer-o", "fa-hand-rock-o", "fa-hand-scissors-o", "fa-hand-spock-o", "fa-hand-stop-o", "fa-handshake-o", "fa-hard-of-hearing", "fa-hashtag", "fa-hdd-o", "fa-header", "fa-headphones", "fa-heart", "fa-heart-o", "fa-heartbeat", "fa-history", "fa-home", "fa-hospital-o", "fa-hotel", "fa-hourglass", "fa-hourglass-1", "fa-hourglass-2", "fa-hourglass-3", "fa-hourglass-end", "fa-hourglass-half", "fa-hourglass-o", "fa-hourglass-start", "fa-houzz", "fa-html5", "fa-i-cursor", "fa-id-badge", "fa-id-card", "fa-id-card-o", "fa-ils", "fa-image", "fa-imdb", "fa-inbox", "fa-indent", "fa-industry", "fa-info", "fa-info-circle", "fa-inr", "fa-instagram", "fa-institution", "fa-internet-explorer", "fa-intersex", "fa-ioxhost", "fa-italic", "fa-joomla", "fa-jpy", "fa-jsfiddle", "fa-key", "fa-keyboard-o", "fa-krw", "fa-language", "fa-laptop", "fa-lastfm", "fa-lastfm-square", "fa-leaf", "fa-leanpub", "fa-legal", "fa-lemon-o", "fa-level-down", "fa-level-up", "fa-life-bouy", "fa-life-buoy", "fa-life-ring", "fa-life-saver", "fa-lightbulb-o", "fa-line-chart", "fa-link", "fa-linkedin", "fa-linkedin-square", "fa-linode", "fa-linux", "fa-list", "fa-list-alt", "fa-list-ol", "fa-list-ul", "fa-location-arrow", "fa-lock", "fa-long-arrow-down", "fa-long-arrow-left", "fa-long-arrow-right", "fa-long-arrow-up", "fa-low-vision", "fa-magic", "fa-magnet", "fa-mail-forward", "fa-mail-reply", "fa-mail-reply-all", "fa-male", "fa-map", "fa-map-marker", "fa-map-o", "fa-map-pin", "fa-map-signs", "fa-mars", "fa-mars-double", "fa-mars-stroke", "fa-mars-stroke-h", "fa-mars-stroke-v", "fa-maxcdn", "fa-meanpath", "fa-medium", "fa-medkit", "fa-meetup", "fa-meh-o", "fa-mercury", "fa-microchip", "fa-microphone", "fa-microphone-slash", "fa-minus", "fa-minus-circle", "fa-minus-square", "fa-minus-square-o", "fa-mixcloud", "fa-mobile", "fa-mobile-phone", "fa-modx", "fa-money", "fa-moon-o", "fa-mortar-board", "fa-motorcycle", "fa-mouse-pointer", "fa-music", "fa-navicon", "fa-neuter", "fa-newspaper-o", "fa-object-group", "fa-object-ungroup", "fa-odnoklassniki", "fa-odnoklassniki-square", "fa-opencart", "fa-openid", "fa-opera", "fa-optin-monster", "fa-outdent", "fa-pagelines", "fa-paint-brush", "fa-paper-plane", "fa-paper-plane-o", "fa-paperclip", "fa-paragraph", "fa-paste", "fa-pause", "fa-pause-circle", "fa-pause-circle-o", "fa-paw", "fa-paypal", "fa-pencil", "fa-pencil-square", "fa-pencil-square-o", "fa-percent", "fa-phone", "fa-phone-square", "fa-photo", "fa-picture-o", "fa-pie-chart", "fa-pied-piper", "fa-pied-piper-alt", "fa-pied-piper-pp", "fa-pinterest", "fa-pinterest-p", "fa-pinterest-square", "fa-plane", "fa-play", "fa-play-circle", "fa-play-circle-o", "fa-plug", "fa-plus", "fa-plus-circle", "fa-plus-square", "fa-plus-square-o", "fa-podcast", "fa-power-off", "fa-print", "fa-product-hunt", "fa-puzzle-piece", "fa-qq", "fa-qrcode", "fa-question", "fa-question-circle", "fa-question-circle-o", "fa-quora", "fa-quote-left", "fa-quote-right", "fa-ra", "fa-random", "fa-ravelry", "fa-rebel", "fa-recycle", "fa-reddit", "fa-reddit-alien", "fa-reddit-square", "fa-refresh", "fa-registered", "fa-remove", "fa-renren", "fa-reorder", "fa-repeat", "fa-reply", "fa-reply-all", "fa-resistance", "fa-retweet", "fa-rmb", "fa-road", "fa-rocket", "fa-rotate-left", "fa-rotate-right", "fa-rouble", "fa-rss", "fa-rss-square", "fa-rub", "fa-ruble", "fa-rupee", "fa-s15", "fa-safari", "fa-save", "fa-scissors", "fa-scribd", "fa-search", "fa-search-minus", "fa-search-plus", "fa-sellsy", "fa-send", "fa-send-o", "fa-server", "fa-share", "fa-share-alt", "fa-share-alt-square", "fa-share-square", "fa-share-square-o", "fa-shekel", "fa-sheqel", "fa-shield", "fa-ship", "fa-shirtsinbulk", "fa-shopping-bag", "fa-shopping-basket", "fa-shopping-cart", "fa-shower", "fa-sign-in", "fa-sign-language", "fa-sign-out", "fa-signal", "fa-signing", "fa-simplybuilt", "fa-sitemap", "fa-skyatlas", "fa-skype", "fa-slack", "fa-sliders", "fa-slideshare", "fa-smile-o", "fa-snapchat", "fa-snapchat-ghost", "fa-snapchat-square", "fa-snowflake-o", "fa-soccer-ball-o", "fa-sort", "fa-sort-alpha-asc", "fa-sort-alpha-desc", "fa-sort-amount-asc", "fa-sort-amount-desc", "fa-sort-asc", "fa-sort-desc", "fa-sort-down", "fa-sort-numeric-asc", "fa-sort-numeric-desc", "fa-sort-up", "fa-soundcloud", "fa-space-shuttle", "fa-spinner", "fa-spoon", "fa-spotify", "fa-square", "fa-square-o", "fa-stack-exchange", "fa-stack-overflow", "fa-star", "fa-star-half", "fa-star-half-empty", "fa-star-half-full", "fa-star-half-o", "fa-star-o", "fa-steam", "fa-steam-square", "fa-step-backward", "fa-step-forward", "fa-stethoscope", "fa-sticky-note", "fa-sticky-note-o", "fa-stop", "fa-stop-circle", "fa-stop-circle-o", "fa-street-view", "fa-strikethrough", "fa-stumbleupon", "fa-stumbleupon-circle", "fa-subscript", "fa-subway", "fa-suitcase", "fa-sun-o", "fa-superpowers", "fa-superscript", "fa-support", "fa-table", "fa-tablet", "fa-tachometer", "fa-tag", "fa-tags", "fa-tasks", "fa-taxi", "fa-telegram", "fa-television", "fa-tencent-weibo", "fa-terminal", "fa-text-height", "fa-text-width", "fa-th", "fa-th-large", "fa-th-list", "fa-themeisle", "fa-thermometer", "fa-thermometer-0", "fa-thermometer-1", "fa-thermometer-2", "fa-thermometer-3", "fa-thermometer-4", "fa-thermometer-empty", "fa-thermometer-full", "fa-thermometer-half", "fa-thermometer-quarter", "fa-thermometer-three-quarters", "fa-thumb-tack", "fa-thumbs-down", "fa-thumbs-o-down", "fa-thumbs-o-up", "fa-thumbs-up", "fa-ticket", "fa-times", "fa-times-circle", "fa-times-circle-o", "fa-times-rectangle", "fa-times-rectangle-o", "fa-tint", "fa-toggle-down", "fa-toggle-left", "fa-toggle-off", "fa-toggle-on", "fa-toggle-right", "fa-toggle-up", "fa-trademark", "fa-train", "fa-transgender", "fa-transgender-alt", "fa-trash", "fa-trash-o", "fa-tree", "fa-trello", "fa-tripadvisor", "fa-trophy", "fa-truck", "fa-try", "fa-tty", "fa-tumblr", "fa-tumblr-square", "fa-turkish-lira", "fa-tv", "fa-twitch", "fa-twitter", "fa-twitter-square", "fa-umbrella", "fa-underline", "fa-undo", "fa-universal-access", "fa-university", "fa-unlink", "fa-unlock", "fa-unlock-alt", "fa-unsorted", "fa-upload", "fa-usb", "fa-usd", "fa-user", "fa-user-circle", "fa-user-circle-o", "fa-user-md", "fa-user-o", "fa-user-plus", "fa-user-secret", "fa-user-times", "fa-users", "fa-vcard", "fa-vcard-o", "fa-venus", "fa-venus-double", "fa-venus-mars", "fa-viacoin", "fa-viadeo", "fa-viadeo-square", "fa-video-camera", "fa-vimeo", "fa-vimeo-square", "fa-vine", "fa-vk", "fa-volume-control-phone", "fa-volume-down", "fa-volume-off", "fa-volume-up", "fa-warning", "fa-wechat", "fa-weibo", "fa-weixin", "fa-whatsapp", "fa-wheelchair", "fa-wheelchair-alt", "fa-wifi", "fa-wikipedia-w", "fa-window-close", "fa-window-close-o", "fa-window-maximize", "fa-window-minimize", "fa-window-restore", "fa-windows", "fa-won", "fa-wordpress", "fa-wpbeginner", "fa-wpexplorer", "fa-wpforms", "fa-wrench", "fa-xing", "fa-xing-square", "fa-y-combinator", "fa-y-combinator-square", "fa-yahoo", "fa-yc", "fa-yc-square", "fa-yelp", "fa-yen", "fa-yoast", "fa-youtube", "fa-youtube-play", "fa-youtube-square"];

    public function index()
    {
        $rows = CmsPage::all();
        return view('cms::pages/cms-pages/index', compact('rows'));
    }

    public function create()
    {
        return view('cms::pages/cms-pages/create', [
            'migration_types' => $this->migration_types,
            'form_fields' => $this->form_fields,
            'icons' => $this->icons,
        ]);
    }

    public function createCustom()
    {
        $icons = $this->icons;
        return view('cms::pages/cms-pages/create-custom', compact('icons'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'database_table' => 'required|unique:cms_pages',
            'model_name' => 'required|unique:cms_pages',
            'display_name' => 'required',
            'display_name_plural' => 'required',
            'name' => 'required|array',
            'name.*' => 'required',
            'old_name' => 'array',
            'migration_type' => 'array',
            'form_field' => 'required|array',
            'form_field.*' => 'required',
            'old_form_field_additionals_1' => 'required|array',
            'form_field_additionals_1' => 'required|array',
            'form_field_additionals_2' => 'required|array',
            'hide_index' => 'required|array',
            'hide_create' => 'required|array',
            'hide_edit' => 'required|array',
            'hide_show' => 'required|array',
            'nullable' => 'required|array',
            'unique' => 'required|array',
            'translatable_name' => 'array',
            'translatable_name.*' => 'required',
            'translatable_old_name' => 'array',
            'translatable_form_field' => 'array',
            'translatable_form_field.*' => 'required',
            'translatable_migration_type' => 'array',
            'translatable_migration_type.*' => 'required',
            'translatable_nullable' => 'array',
        ]);

        $fields = $this->beautifyFields($request);
        if (!is_array($fields)) return $fields;

        $translatable_fields = $this->beautifyTranslatableFields($request);
        if (!is_array($translatable_fields)) return $translatable_fields;

        $this->createDatabase($request);
        $this->createModel($request);

        $cms_page = new CmsPage;
        $cms_page->icon = $request->icon;
        $cms_page->display_name = $request->display_name;
        $cms_page->display_name_plural = $request->display_name_plural;
        $cms_page->database_table = $request->database_table;
        $cms_page->route = Str::slug($request->database_table);
        $cms_page->model_name = $request->model_name;
        $cms_page->order_display = $request->order_display;
        $cms_page->sort_by = $request->sort_by;
        $cms_page->sort_by_direction = $request->sort_by_direction;
        $cms_page->preview_path = $request->preview_path;
        $cms_page->fields = json_encode($fields);
        $cms_page->translatable_fields = json_encode($translatable_fields);
        $cms_page->add = isset($request->single_record) ? 0 : (isset($request->add) ? 1 : 0);
        $cms_page->edit = isset($request->edit) ? 1 : 0;
        $cms_page->delete = isset($request->single_record) ? 0 : (isset($request->delete) ? 1 : 0);
        $cms_page->show = isset($request->show) ? 1 : 0;
        $cms_page->single_record = isset($request->single_record) ? 1 : 0;
        $cms_page->apis = isset($request->apis) ? 1 : 0;
        $cms_page->server_side_pagination = isset($request->server_side_pagination) ? 1 : 0;
        $cms_page->with_export = isset($request->with_export) ? 1 : 0;
        $cms_page->hidden = isset($request->hidden) ? 1 : 0;
        $cms_page->ht_pos = CmsPage::max('ht_pos') + 1;
        $cms_page->save();

        $request->session()->flash('success', 'Page added successfully');
        return url(config('hellotree.cms_route_prefix') . '/' . $cms_page->route);
    }

    public function storeCustom(Request $request)
    {
        $request->validate([
            'route' => 'required|unique:cms_pages',
        ]);

        $cms_page = new CmsPage;
        $cms_page->icon = $request->icon;
        $cms_page->display_name_plural = $request->display_name_plural;
        $cms_page->route = $request->route;
        $cms_page->ht_pos = CmsPage::max('ht_pos') + 1;
        $cms_page->custom_page = 1;
        $cms_page->save();

        return redirect(config('hellotree.cms_route_prefix') . '/cms-pages')->with('success', 'Page added successfully');
    }

    public function edit($id)
    {
        $cms_page = CmsPage::where('custom_page', 0)->findOrFail($id);
        $migration_types = $this->migration_types;
        $form_fields = $this->form_fields;
        $icons = $this->icons;

        return view('cms::pages/cms-pages/create', compact(
            'cms_page',
            'migration_types',
            'form_fields',
            'icons'
        ));
    }

    public function editCustom($id)
    {
        $cms_page = CmsPage::where('custom_page', 1)->findOrFail($id);
        $icons = $this->icons;

        return view('cms::pages/cms-pages/create-custom', compact(
            'cms_page',
            'icons'
        ));
    }

    public function update(Request $request, $id)
    {
        $cms_page = CmsPage::where('custom_page', 0)->findOrFail($id);

        $request->validate([
            'database_table' => 'required|unique:cms_pages,database_table,' . $id,
            'model_name' => 'required|unique:cms_pages,model_name,' . $id,
            'display_name' => 'required',
            'display_name_plural' => 'required',
            'name' => 'required|array',
            'name.*' => 'required',
            'form_field' => 'required|array',
            'form_field.*' => 'required',
            'old_form_field_additionals_1' => 'required|array',
            'form_field_additionals_1' => 'required|array',
            'form_field_additionals_2' => 'required|array',
            'hide_index' => 'required|array',
            'hide_create' => 'required|array',
            'hide_edit' => 'required|array',
            'hide_show' => 'required|array',
            'nullable' => 'required|array',
            'unique' => 'required|array',
        ]);

        $fields = $this->beautifyFields($request);
        if (!is_array($fields)) return $fields;

        $translatable_fields = $this->beautifyTranslatableFields($request);
        if (!is_array($translatable_fields)) return $translatable_fields;

        if (count($request['old_name']) != count($request['name'])) abort(500);

        $r = $this->editDatabase($request, $cms_page);
        if (!is_null($r)) return $r;

        if ($cms_page->database_table != $request->database_table) $this->deleteModel($cms_page);
        if ($request->edit_model) $this->createModel($request);

        $cms_page->icon = $request->icon;
        $cms_page->display_name = $request->display_name;
        $cms_page->display_name_plural = $request->display_name_plural;
        $cms_page->database_table = $request->database_table;
        $cms_page->route = Str::slug($request->database_table);
        $cms_page->model_name = $request->model_name;
        $cms_page->order_display = $request->order_display;
        $cms_page->sort_by = $request->sort_by;
        $cms_page->sort_by_direction = $request->sort_by_direction;
        $cms_page->preview_path = $request->preview_path;
        $cms_page->fields = json_encode($fields);
        $cms_page->translatable_fields = json_encode($translatable_fields);
        $cms_page->add = isset($request->single_record) ? 0 : (isset($request->add) ? 1 : 0);
        $cms_page->edit = isset($request->edit) ? 1 : 0;
        $cms_page->delete = isset($request->single_record) ? 0 : (isset($request->delete) ? 1 : 0);
        $cms_page->show = isset($request->show) ? 1 : 0;
        $cms_page->single_record = isset($request->single_record) ? 1 : 0;
        $cms_page->apis = isset($request->apis) ? 1 : 0;
        $cms_page->server_side_pagination = isset($request->server_side_pagination) ? 1 : 0;
        $cms_page->with_export = isset($request->with_export) ? 1 : 0;
        $cms_page->hidden = isset($request->hidden) ? 1 : 0;
        $cms_page->save();

        $request->session()->flash('success', 'Page edited successfully');
        return url(config('hellotree.cms_route_prefix') . '/' . $cms_page->route);
    }

    public function updateCustom(Request $request, $id)
    {
        $cms_page = CmsPage::where('custom_page', 1)->findOrFail($id);

        $request->validate([
            'route' => 'required|unique:cms_pages,route,' . $id,
        ]);

        $cms_page->icon = $request->icon;
        $cms_page->display_name_plural = $request->display_name_plural;
        $cms_page->route = $request->route;
        if (!$request->display_name_plural) {
            $cms_page->parent_title = null;
            $cms_page->parent_icon = null;
        }
        $cms_page->save();

        return redirect(config('hellotree.cms_route_prefix') . '/cms-pages')->with('success', 'Page edited successfully');
    }

    public function createDatabase($request)
    {
        // Create table
        Schema::create($request['database_table'], function ($table) use ($request) {
            $table->increments('id');
            // Regular database columns
            foreach ($request->form_field as $f => $form_field) {
                if ($form_field == 'select multiple') continue;

                $table->{$request->migration_type[$f]}($request->name[$f])->{$form_field == 'select' ? 'unsigned' : ''}()->{$request->nullable[$f] ? 'nullable' : ''}();
                if ($form_field == 'select') {
                    $table->foreign($request->name[$f])->references('id')->on($request->form_field_additionals_1[$f])->onDelete('cascade');
                }
            }
            if ($request['order_display']) $table->integer('ht_pos')->nullable();
            $table->timestamps();
        });

        // Create pivot tables
        foreach ($request->form_field as $f => $form_field) {
            if ($form_field == 'select multiple') {
                $pivot_table = Str::singular($request->form_field_additionals_1[$f]) . '_' . Str::singular($request->database_table);
                $column_name = $request->form_field_additionals_1[$f] == $request->database_table ? 'other_' . Str::singular($request->form_field_additionals_1[$f]) . '_id' : Str::singular($request->form_field_additionals_1[$f]) . '_id';

                Schema::create($pivot_table, function ($table) use ($request, $f, $column_name) {
                    $table->increments('id');
                    $table->integer($column_name)->unsigned();
                    $table->integer(Str::singular($request->database_table) . '_id')->unsigned();
                    $table->integer('ht_pos')->unsigned()->nullable();
                    $table->timestamps();

                    $table->foreign($column_name)->references('id')->on($request->form_field_additionals_1[$f])->onDelete('cascade');
                    $table->foreign(Str::singular($request->database_table) . '_id')->references('id')->on($request->database_table)->onDelete('cascade');
                });
            }
        }

        // Create translations table
        if ($request->translatable_form_field) {
            Schema::create($request['database_table'] . '_translations', function ($table) use ($request) {
                $table->increments('id');
                $table->string('locale');
                $table->integer(Str::singular($request->database_table) . '_id')->unsigned();
                // Regular database columns
                foreach ($request->translatable_form_field as $f => $form_field) {
                    if ($form_field == 'select' || $form_field == 'select multiple') continue;

                    $table->{$request->translatable_migration_type[$f]}($request->translatable_name[$f])->{$form_field == 'select' ? 'unsigned' : ''}()->{$request->translatable_nullable[$f] ? 'nullable' : ''}();
                }
                $table->timestamps();

                $table->foreign(Str::singular($request->database_table) . '_id', 'translatable_id_' . time())->references('id')->on($request->database_table)->onDelete('cascade');
            });
        }
    }

    public function editDatabase($request, $old_page)
    {
        // Database table name changed
        if ($old_page['database_table'] != $request['database_table']) {
            // Check if old table have a pivot table
            foreach (json_decode($old_page['fields'], true) as $old_field) {
                if ($old_field['form_field'] == 'select multiple') {
                    throw ValidationException::withMessages(['Remove fields with pivot table (form field `select multiple`) before changing database table name']);
                }
            }

            if ($request['translatable_name']) Schema::rename($old_page['database_table'], $request['database_table'] . '_translations');
            Schema::rename($old_page['database_table'], $request['database_table']);
        }

        for ($i = 0; $i < count($request['name']); $i++) {
            // Skip select multiple fields
            if ($request['form_field'][$i] == 'select multiple') continue;

            // New field
            elseif (!$request['old_name'][$i]) {
                // Get previous column that isn't a select multiple field
                $after_column = 'id';
                if ($i > 0) {
                    for ($j = $i - 1; $j >= 0; $j--) {
                        if ($request->form_field[$j] != 'select multiple') {
                            $after_column = $request->name[$j];
                            break;
                        }
                    }
                }
                Schema::table($request['database_table'], function ($table) use ($request, $i, $after_column) {
                    $table->{$request->migration_type[$i]}($request->name[$i])->{$request->form_field[$i] == 'select' ? 'unsigned' : ''}()->{$request->nullable[$i] ? 'nullable' : ''}()->after($after_column);
                });
            }

            // Existing field
            elseif ($request['name'][$i] != $request['old_name'][$i]) {
                // Update name
                Schema::table($request['database_table'], function ($table) use ($request, $i) {
                    $table->renameColumn($request['old_name'][$i], $request['name'][$i]);
                });
            }
        }

        // Update migration type and nullable for all columns
        for ($i = 0; $i < count($request['name']); $i++) {
            // Skip select multiple fields
            if ($request['form_field'][$i] == 'select multiple') continue;
            if ($request['form_field'][$i] == 'select') continue;

            Schema::table($request['database_table'], function ($table) use ($request, $i) {
                $table->{$request->migration_type[$i]}($request->name[$i])->{$request->nullable[$i] ? 'nullable' : ''}()->change();
            });
        }

        // If table already have 'ht_pos' column
        if (Schema::hasColumn($request['database_table'], 'ht_pos')) {
            // If request have no 'order_display' remove the existing 'ht_pos'
            if (!$request['order_display']) {
                Schema::table($request['database_table'], function ($table) {
                    $table->dropColumn('ht_pos');
                });
            }
        }
        // If table already have 'ht_pos' column
        else {
            // If request have 'order_display', add 'ht_pos'
            if ($request['order_display']) {
                $last_column = $request['name'][count($request['name']) - 1];
                Schema::table($request['database_table'], function ($table) use ($last_column) {
                    $table->integer('ht_pos')->nullable()->after($last_column);
                });
            }
        }

        // Create pivot tables
        foreach ($request->form_field as $f => $form_field) {
            // Take only select multiple fields
            if ($form_field == 'select multiple') {
                // New field
                if (!$request->old_form_field_additionals_1[$f]) {
                    $pivot_table = Str::singular($request->form_field_additionals_1[$f]) . '_' . Str::singular($request->database_table);
                    $column_name = $request->form_field_additionals_1[$f] == $request->database_table ? 'other_' . Str::singular($request->form_field_additionals_1[$f]) . '_id' : Str::singular($request->form_field_additionals_1[$f]) . '_id';

                    Schema::create($pivot_table, function ($table) use ($request, $f, $column_name) {
                        $table->increments('id');
                        $table->integer($column_name)->unsigned();
                        $table->integer(Str::singular($request->database_table) . '_id')->unsigned();
                        $table->integer('ht_pos')->unsigned()->nullable();
                        $table->timestamps();

                        $table->foreign($column_name)->references('id')->on($request->form_field_additionals_1[$f])->onDelete('cascade');
                        $table->foreign(Str::singular($request->database_table) . '_id')->references('id')->on($request->database_table)->onDelete('cascade');
                    });
                }
                // If form_field_additionals_1 have changed
                elseif ($request->old_form_field_additionals_1[$f] != $request->form_field_additionals_1[$f]) {
                    $old_pivot_table = Str::singular($request->old_form_field_additionals_1[$f]) . '_' . Str::singular($request->database_table);
                    Schema::drop($old_pivot_table);

                    $pivot_table = Str::singular($request->form_field_additionals_1[$f]) . '_' . Str::singular($request->database_table);
                    $column_name = $request->form_field_additionals_1[$f] == $request->database_table ? 'other_' . Str::singular($request->form_field_additionals_1[$f]) . '_id' : Str::singular($request->form_field_additionals_1[$f]) . '_id';

                    Schema::create($pivot_table, function ($table) use ($request, $f, $column_name) {
                        $table->increments('id');
                        $table->integer($column_name)->unsigned();
                        $table->integer(Str::singular($request->database_table) . '_id')->unsigned();
                        $table->timestamps();

                        $table->foreign($column_name)->references('id')->on($request->form_field_additionals_1[$f])->onDelete('cascade');
                        $table->foreign(Str::singular($request->database_table) . '_id')->references('id')->on($request->database_table)->onDelete('cascade');
                    });
                }
            }
        }

        // Delete columns
        foreach (Schema::getColumnListing($request['database_table']) as $db_column) {
            if ($db_column == 'id' || $db_column == 'ht_pos' || $db_column == 'created_at' || $db_column == 'updated_at') continue;

            // Check if db column is in requested names
            $db_column_found = false;
            foreach ($request->name as $requested_name) {
                if ($requested_name == $db_column) {
                    $db_column_found = true;
                }
            }

            // Column not found delete it
            if (!$db_column_found) {
                Schema::table($request['database_table'], function ($table) use ($db_column) {
                    $table->dropColumn($db_column);
                });
            }
        }

        // Check pivot tables
        foreach (json_decode($old_page['fields'], true) as $old_field) {
            if ($old_field['form_field'] == 'select multiple') {
                $field_found = false;
                foreach ($request->form_field as $i => $form_field) {
                    if ($form_field == 'select multiple') {
                        if ($old_field['form_field_additionals_1'] == $request->form_field_additionals_1[$i]) {
                            $field_found = true;
                        }
                    }
                }
                if (!$field_found) {
                    $pivot_table = Str::singular($old_field['form_field_additionals_1']) . '_' . Str::singular($old_page->database_table);
                    Schema::drop($pivot_table);
                }
            }
        }

        // Edit translations table
        if ($request['translatable_name']) {

            // Create table if not exists
            if (!Schema::hasTable($request['database_table'] . '_translations')) {
                Schema::create($request['database_table'] . '_translations', function ($table) use ($request) {
                    $table->increments('id');
                    $table->string('locale');
                    $table->integer(Str::singular($request->database_table) . '_id')->unsigned();
                    $table->timestamps();

                    $table->foreign(Str::singular($request->database_table) . '_id')->references('id')->on($request->database_table)->onDelete('cascade');
                });
            }

            for ($i = 0; $i < count($request['translatable_name']); $i++) {
                // Skip select multiple fields
                if ($request['translatable_form_field'][$i] == 'select' || $request['translatable_form_field'][$i] == 'select multiple') continue;

                // New field
                elseif (!$request['translatable_old_name'][$i]) {
                    // Get previous column that isn't a select multiple field
                    $after_column = 'id';
                    if ($i > 0) {
                        for ($j = $i - 1; $j >= 0; $j--) {
                            if ($request->translatable_form_field[$j] != 'select multiple') {
                                $after_column = $request->translatable_name[$j];
                                break;
                            }
                        }
                    }
                    Schema::table($request['database_table'] . '_translations', function ($table) use ($request, $i, $after_column) {
                        $table->{$request->translatable_migration_type[$i]}($request->translatable_name[$i])->{$request->translatable_form_field[$i] == 'select' ? 'unsigned' : ''}()->{$request->translatable_nullable[$i] ? 'nullable' : ''}()->after($after_column);
                    });
                }

                // Existing field
                elseif ($request['translatable_name'][$i] != $request['translatable_old_name'][$i]) {
                    // Update name
                    Schema::table($request['database_table'] . '_translations', function ($table) use ($request, $i) {
                        $table->renameColumn($request['translatable_old_name'][$i], $request['translatable_name'][$i]);
                    });
                }
            }

            // Update migration type and nullable for all columns
            for ($i = 0; $i < count($request['translatable_name']); $i++) {
                // Skip select multiple fields
                if ($request['translatable_form_field'][$i] == 'select' || $request['translatable_form_field'][$i] == 'select multiple') continue;

                Schema::table($request['database_table'] . '_translations', function ($table) use ($request, $i) {
                    $table->{$request->translatable_migration_type[$i]}($request->translatable_name[$i])->{$request->translatable_nullable[$i] ? 'nullable' : ''}()->change();
                });
            }

            // Delete columns
            foreach (Schema::getColumnListing($request['database_table'] . '_translations') as $db_column) {
                if ($db_column == 'id' || $db_column == 'locale' || $db_column == (Str::singular($request->database_table) . '_id') || $db_column == 'created_at' || $db_column == 'updated_at') continue;

                // Check if db column is in requested names
                $db_column_found = false;
                foreach ($request->translatable_name as $requested_name) {
                    if ($requested_name == $db_column) {
                        $db_column_found = true;
                    }
                }

                // Column not found delete it
                if (!$db_column_found) {
                    Schema::table($request['database_table'] . '_translations', function ($table) use ($db_column) {
                        $table->dropColumn($db_column);
                    });
                }
            }
        } else {
            // Drop table table if exists
            Schema::dropIfExists($request['database_table'] . '_translations');
        }
    }

    public function createModel($request)
    {
        $head = '';
        $implements = '';
        $use = '';
        $translated_attributes = '';
        if ($request['translatable_name']) {
            $head = 'use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract; use Astrotomic\Translatable\Translatable;';
            $implements = ' implements TranslatableContract';
            $use = 'use Translatable;';
            $translated_attributes = 'public $translatedAttributes = ' . json_encode($request['translatable_name']) . ';';
        }


        $body = '';
        foreach ($request['form_field'] as $f => $form_field) {
            if ($form_field == 'select') {
                $second_database_table = $request->form_field_additionals_1[$f];
                $second_page = CmsPage::where('database_table', $second_database_table)->firstOrFail();
                $pivot_table = Str::singular($request->form_field_additionals_1[$f]) . '_' . Str::singular($request->database_table);
                $body .= 'public function ' . str_replace('_id', '', $request->name[$f]) . '() { return $this->belongsTo' . "('App\\" . $second_page['model_name'] . "')" . '; } ';
            } elseif ($form_field == 'select multiple') {
                $second_database_table = $request->form_field_additionals_1[$f];
                $second_page = CmsPage::where('database_table', $second_database_table)->firstOrFail();
                $pivot_table = Str::singular($request->form_field_additionals_1[$f]) . '_' . Str::singular($request->database_table);
                $column_name = $request->form_field_additionals_1[$f] == $request->database_table ? 'other_' . Str::singular($request->form_field_additionals_1[$f]) . '_id' : Str::singular($request->form_field_additionals_1[$f]) . '_id';

                $body .= 'public function ' . str_replace('_id', '', $request->name[$f]) . '() { return $this->belongsToMany' . "('App\\" . $second_page['model_name'] . "', '" . $pivot_table . "', '" . Str::singular($request->database_table) . '_id' . "', '" . $column_name . "')->orderBy('ht_pos')" . '; } ';
            }
        }

        file_put_contents(
            app_path('/' . $request['model_name'] . '.php'),
            str_replace(
                [
                    '%%head%%',
                    '%%model_name%%',
                    '%%implements%%',
                    '%%database_table%%',
                    '%%use%%',
                    '%%translated_attributes%%',
                    '%%body%%',
                ],
                [
                    $head,
                    $request['model_name'],
                    $implements,
                    $request['database_table'],
                    $use,
                    $translated_attributes,
                    $body,
                ],
                file_get_contents(__DIR__ . '/../stubs/model.stub')
            )
        );

        if ($request['translatable_name']) {
            $head = '';
            $implements = '';
            $use = '';
            $translated_attributes = '';
            $body = '';

            file_put_contents(
                app_path('/' . $request->model_name . 'Translation.php'),
                str_replace(
                    [
                        '%%head%%',
                        '%%model_name%%',
                        '%%implements%%',
                        '%%database_table%%',
                        '%%use%%',
                        '%%translated_attributes%%',
                        '%%body%%',
                    ],
                    [
                        $head,
                        $request->model_name . 'Translation',
                        $implements,
                        $request->database_table . '_translations',
                        $use,
                        $translated_attributes,
                        $body,
                    ],
                    file_get_contents(__DIR__ . '/../stubs/model.stub')
                )
            );
        }
    }

    public function destroy($id)
    {
        $array = explode(',', $id);
        if (!count($array)) return redirect(config('hellotree.cms_route_prefix') . '/cms-pages')->with('error', 'No record selected');
        foreach ($array as $id) $this->deletePage($id);
        return redirect(config('hellotree.cms_route_prefix') . '/cms-pages')->with('success', 'Record deleted successfully');
    }

    public function deletePage($id)
    {
        $cms_page = CmsPage::whereNotIn('id', [1, 2, 3, 4])->findorfail($id);

        $this->deleteDatabase($cms_page);
        $this->deleteModel($cms_page);

        CmsPage::destroy($id);
    }

    public function deleteDatabase($cms_page)
    {
        if ($cms_page->database_table) {
            foreach (json_decode($cms_page['fields'], true) as $field) {
                if ($field['form_field'] == 'select multiple') {
                    $pivot_table = Str::singular($field['form_field_additionals_1']) . '_' . Str::singular($cms_page->database_table);
                    Schema::drop($pivot_table);
                }
            }
            if (count(json_decode($cms_page['translatable_fields'], true))) {
                Schema::drop($cms_page->database_table . '_translations');
            }
            Schema::drop($cms_page->database_table);
        }
    }

    public function deleteModel($cms_page)
    {
        if (file_exists(app_path('/' . $cms_page->model_name . '.php')))
            unlink(app_path('/' . $cms_page->model_name . '.php'));
    }

    public function order()
    {
        return view('cms::pages/cms-pages/order');
    }

    public function changeOrder(Request $request)
    {
        $ht_pos = 1;
        foreach ($request->id as $key => $id) {
            if ($id) {
                $row = CmsPage::findOrFail($id);
                $row->parent_title = $request->parent_title[$key];
                $row->parent_icon = $request->parent_icon[$key];
                $row->ht_pos = $ht_pos;
                $row->save();
                $ht_pos++;
            }
        }

        return redirect(config('hellotree.cms_route_prefix') . '/cms-pages')->with('success', 'Records ordered successfully');
    }

    public function icons()
    {
        $icons = ["fa-500px", "fa-address-book", "fa-address-book-o", "fa-address-card", "fa-address-card-o", "fa-adjust", "fa-adn", "fa-align-center", "fa-align-justify", "fa-align-left", "fa-align-right", "fa-amazon", "fa-ambulance", "fa-american-sign-language-interpreting", "fa-anchor", "fa-android", "fa-angellist", "fa-angle-double-down", "fa-angle-double-left", "fa-angle-double-right", "fa-angle-double-up", "fa-angle-down", "fa-angle-left", "fa-angle-right", "fa-angle-up", "fa-apple", "fa-archive", "fa-area-chart", "fa-arrow-circle-down", "fa-arrow-circle-left", "fa-arrow-circle-o-down", "fa-arrow-circle-o-left", "fa-arrow-circle-o-right", "fa-arrow-circle-o-up", "fa-arrow-circle-right", "fa-arrow-circle-up", "fa-arrow-down", "fa-arrow-left", "fa-arrow-right", "fa-arrow-up", "fa-arrows", "fa-arrows-alt", "fa-arrows-h", "fa-arrows-v", "fa-asl-interpreting", "fa-assistive-listening-systems", "fa-asterisk", "fa-at", "fa-audio-description", "fa-automobile", "fa-backward", "fa-balance-scale", "fa-ban", "fa-bandcamp", "fa-bank", "fa-bar-chart", "fa-bar-chart-o", "fa-barcode", "fa-bars", "fa-bath", "fa-bathtub", "fa-battery", "fa-battery-0", "fa-battery-1", "fa-battery-2", "fa-battery-3", "fa-battery-4", "fa-battery-empty", "fa-battery-full", "fa-battery-half", "fa-battery-quarter", "fa-battery-three-quarters", "fa-bed", "fa-beer", "fa-behance", "fa-behance-square", "fa-bell", "fa-bell-o", "fa-bell-slash", "fa-bell-slash-o", "fa-bicycle", "fa-binoculars", "fa-birthday-cake", "fa-bitbucket", "fa-bitbucket-square", "fa-bitcoin", "fa-black-tie", "fa-blind", "fa-bluetooth", "fa-bluetooth-b", "fa-bold", "fa-bolt", "fa-bomb", "fa-book", "fa-bookmark", "fa-bookmark-o", "fa-braille", "fa-briefcase", "fa-btc", "fa-bug", "fa-building", "fa-building-o", "fa-bullhorn", "fa-bullseye", "fa-bus", "fa-buysellads", "fa-cab", "fa-calculator", "fa-calendar", "fa-calendar-check-o", "fa-calendar-minus-o", "fa-calendar-o", "fa-calendar-plus-o", "fa-calendar-times-o", "fa-camera", "fa-camera-retro", "fa-car", "fa-caret-down", "fa-caret-left", "fa-caret-right", "fa-caret-square-o-down", "fa-caret-square-o-left", "fa-caret-square-o-right", "fa-caret-square-o-up", "fa-caret-up", "fa-cart-arrow-down", "fa-cart-plus", "fa-cc", "fa-cc-amex", "fa-cc-diners-club", "fa-cc-discover", "fa-cc-jcb", "fa-cc-mastercard", "fa-cc-paypal", "fa-cc-stripe", "fa-cc-visa", "fa-certificate", "fa-chain", "fa-chain-broken", "fa-check", "fa-check-circle", "fa-check-circle-o", "fa-check-square", "fa-check-square-o", "fa-chevron-circle-down", "fa-chevron-circle-left", "fa-chevron-circle-right", "fa-chevron-circle-up", "fa-chevron-down", "fa-chevron-left", "fa-chevron-right", "fa-chevron-up", "fa-child", "fa-chrome", "fa-circle", "fa-circle-o", "fa-circle-o-notch", "fa-circle-thin", "fa-clipboard", "fa-clock-o", "fa-clone", "fa-close", "fa-cloud", "fa-cloud-download", "fa-cloud-upload", "fa-cny", "fa-code", "fa-code-fork", "fa-codepen", "fa-codiepie", "fa-coffee", "fa-cog", "fa-cogs", "fa-columns", "fa-comment", "fa-comment-o", "fa-commenting", "fa-commenting-o", "fa-comments", "fa-comments-o", "fa-compass", "fa-compress", "fa-connectdevelop", "fa-contao", "fa-copy", "fa-copyright", "fa-creative-commons", "fa-credit-card", "fa-credit-card-alt", "fa-crop", "fa-crosshairs", "fa-css3", "fa-cube", "fa-cubes", "fa-cut", "fa-cutlery", "fa-dashboard", "fa-dashcube", "fa-database", "fa-deaf", "fa-deafness", "fa-dedent", "fa-delicious", "fa-desktop", "fa-deviantart", "fa-diamond", "fa-digg", "fa-dollar", "fa-dot-circle-o", "fa-download", "fa-dribbble", "fa-drivers-license", "fa-drivers-license-o", "fa-dropbox", "fa-drupal", "fa-edge", "fa-edit", "fa-eercast", "fa-eject", "fa-ellipsis-h", "fa-ellipsis-v", "fa-empire", "fa-envelope", "fa-envelope-o", "fa-envelope-open", "fa-envelope-open-o", "fa-envelope-square", "fa-envira", "fa-eraser", "fa-etsy", "fa-eur", "fa-euro", "fa-exchange", "fa-exclamation", "fa-exclamation-circle", "fa-exclamation-triangle", "fa-expand", "fa-expeditedssl", "fa-external-link", "fa-external-link-square", "fa-eye", "fa-eye-slash", "fa-eyedropper", "fa-fa", "fa-facebook", "fa-facebook-f", "fa-facebook-official", "fa-facebook-square", "fa-fast-backward", "fa-fast-forward", "fa-fax", "fa-feed", "fa-female", "fa-fighter-jet", "fa-file", "fa-file-archive-o", "fa-file-audio-o", "fa-file-code-o", "fa-file-excel-o", "fa-file-image-o", "fa-file-movie-o", "fa-file-o", "fa-file-pdf-o", "fa-file-photo-o", "fa-file-picture-o", "fa-file-powerpoint-o", "fa-file-sound-o", "fa-file-text", "fa-file-text-o", "fa-file-video-o", "fa-file-word-o", "fa-file-zip-o", "fa-files-o", "fa-film", "fa-filter", "fa-fire", "fa-fire-extinguisher", "fa-firefox", "fa-first-order", "fa-flag", "fa-flag-checkered", "fa-flag-o", "fa-flash", "fa-flask", "fa-flickr", "fa-floppy-o", "fa-folder", "fa-folder-o", "fa-folder-open", "fa-folder-open-o", "fa-font", "fa-font-awesome", "fa-fonticons", "fa-fort-awesome", "fa-forumbee", "fa-forward", "fa-foursquare", "fa-free-code-camp", "fa-frown-o", "fa-futbol-o", "fa-gamepad", "fa-gavel", "fa-gbp", "fa-ge", "fa-gear", "fa-gears", "fa-genderless", "fa-get-pocket", "fa-gg", "fa-gg-circle", "fa-gift", "fa-git", "fa-git-square", "fa-github", "fa-github-alt", "fa-github-square", "fa-gitlab", "fa-gittip", "fa-glass", "fa-glide", "fa-glide-g", "fa-globe", "fa-google", "fa-google-plus", "fa-google-plus-circle", "fa-google-plus-official", "fa-google-plus-square", "fa-google-wallet", "fa-graduation-cap", "fa-gratipay", "fa-grav", "fa-group", "fa-h-square", "fa-hacker-news", "fa-hand-grab-o", "fa-hand-lizard-o", "fa-hand-o-down", "fa-hand-o-left", "fa-hand-o-right", "fa-hand-o-up", "fa-hand-paper-o", "fa-hand-peace-o", "fa-hand-pointer-o", "fa-hand-rock-o", "fa-hand-scissors-o", "fa-hand-spock-o", "fa-hand-stop-o", "fa-handshake-o", "fa-hard-of-hearing", "fa-hashtag", "fa-hdd-o", "fa-header", "fa-headphones", "fa-heart", "fa-heart-o", "fa-heartbeat", "fa-history", "fa-home", "fa-hospital-o", "fa-hotel", "fa-hourglass", "fa-hourglass-1", "fa-hourglass-2", "fa-hourglass-3", "fa-hourglass-end", "fa-hourglass-half", "fa-hourglass-o", "fa-hourglass-start", "fa-houzz", "fa-html5", "fa-i-cursor", "fa-id-badge", "fa-id-card", "fa-id-card-o", "fa-ils", "fa-image", "fa-imdb", "fa-inbox", "fa-indent", "fa-industry", "fa-info", "fa-info-circle", "fa-inr", "fa-instagram", "fa-institution", "fa-internet-explorer", "fa-intersex", "fa-ioxhost", "fa-italic", "fa-joomla", "fa-jpy", "fa-jsfiddle", "fa-key", "fa-keyboard-o", "fa-krw", "fa-language", "fa-laptop", "fa-lastfm", "fa-lastfm-square", "fa-leaf", "fa-leanpub", "fa-legal", "fa-lemon-o", "fa-level-down", "fa-level-up", "fa-life-bouy", "fa-life-buoy", "fa-life-ring", "fa-life-saver", "fa-lightbulb-o", "fa-line-chart", "fa-link", "fa-linkedin", "fa-linkedin-square", "fa-linode", "fa-linux", "fa-list", "fa-list-alt", "fa-list-ol", "fa-list-ul", "fa-location-arrow", "fa-lock", "fa-long-arrow-down", "fa-long-arrow-left", "fa-long-arrow-right", "fa-long-arrow-up", "fa-low-vision", "fa-magic", "fa-magnet", "fa-mail-forward", "fa-mail-reply", "fa-mail-reply-all", "fa-male", "fa-map", "fa-map-marker", "fa-map-o", "fa-map-pin", "fa-map-signs", "fa-mars", "fa-mars-double", "fa-mars-stroke", "fa-mars-stroke-h", "fa-mars-stroke-v", "fa-maxcdn", "fa-meanpath", "fa-medium", "fa-medkit", "fa-meetup", "fa-meh-o", "fa-mercury", "fa-microchip", "fa-microphone", "fa-microphone-slash", "fa-minus", "fa-minus-circle", "fa-minus-square", "fa-minus-square-o", "fa-mixcloud", "fa-mobile", "fa-mobile-phone", "fa-modx", "fa-money", "fa-moon-o", "fa-mortar-board", "fa-motorcycle", "fa-mouse-pointer", "fa-music", "fa-navicon", "fa-neuter", "fa-newspaper-o", "fa-object-group", "fa-object-ungroup", "fa-odnoklassniki", "fa-odnoklassniki-square", "fa-opencart", "fa-openid", "fa-opera", "fa-optin-monster", "fa-outdent", "fa-pagelines", "fa-paint-brush", "fa-paper-plane", "fa-paper-plane-o", "fa-paperclip", "fa-paragraph", "fa-paste", "fa-pause", "fa-pause-circle", "fa-pause-circle-o", "fa-paw", "fa-paypal", "fa-pencil", "fa-pencil-square", "fa-pencil-square-o", "fa-percent", "fa-phone", "fa-phone-square", "fa-photo", "fa-picture-o", "fa-pie-chart", "fa-pied-piper", "fa-pied-piper-alt", "fa-pied-piper-pp", "fa-pinterest", "fa-pinterest-p", "fa-pinterest-square", "fa-plane", "fa-play", "fa-play-circle", "fa-play-circle-o", "fa-plug", "fa-plus", "fa-plus-circle", "fa-plus-square", "fa-plus-square-o", "fa-podcast", "fa-power-off", "fa-print", "fa-product-hunt", "fa-puzzle-piece", "fa-qq", "fa-qrcode", "fa-question", "fa-question-circle", "fa-question-circle-o", "fa-quora", "fa-quote-left", "fa-quote-right", "fa-ra", "fa-random", "fa-ravelry", "fa-rebel", "fa-recycle", "fa-reddit", "fa-reddit-alien", "fa-reddit-square", "fa-refresh", "fa-registered", "fa-remove", "fa-renren", "fa-reorder", "fa-repeat", "fa-reply", "fa-reply-all", "fa-resistance", "fa-retweet", "fa-rmb", "fa-road", "fa-rocket", "fa-rotate-left", "fa-rotate-right", "fa-rouble", "fa-rss", "fa-rss-square", "fa-rub", "fa-ruble", "fa-rupee", "fa-s15", "fa-safari", "fa-save", "fa-scissors", "fa-scribd", "fa-search", "fa-search-minus", "fa-search-plus", "fa-sellsy", "fa-send", "fa-send-o", "fa-server", "fa-share", "fa-share-alt", "fa-share-alt-square", "fa-share-square", "fa-share-square-o", "fa-shekel", "fa-sheqel", "fa-shield", "fa-ship", "fa-shirtsinbulk", "fa-shopping-bag", "fa-shopping-basket", "fa-shopping-cart", "fa-shower", "fa-sign-in", "fa-sign-language", "fa-sign-out", "fa-signal", "fa-signing", "fa-simplybuilt", "fa-sitemap", "fa-skyatlas", "fa-skype", "fa-slack", "fa-sliders", "fa-slideshare", "fa-smile-o", "fa-snapchat", "fa-snapchat-ghost", "fa-snapchat-square", "fa-snowflake-o", "fa-soccer-ball-o", "fa-sort", "fa-sort-alpha-asc", "fa-sort-alpha-desc", "fa-sort-amount-asc", "fa-sort-amount-desc", "fa-sort-asc", "fa-sort-desc", "fa-sort-down", "fa-sort-numeric-asc", "fa-sort-numeric-desc", "fa-sort-up", "fa-soundcloud", "fa-space-shuttle", "fa-spinner", "fa-spoon", "fa-spotify", "fa-square", "fa-square-o", "fa-stack-exchange", "fa-stack-overflow", "fa-star", "fa-star-half", "fa-star-half-empty", "fa-star-half-full", "fa-star-half-o", "fa-star-o", "fa-steam", "fa-steam-square", "fa-step-backward", "fa-step-forward", "fa-stethoscope", "fa-sticky-note", "fa-sticky-note-o", "fa-stop", "fa-stop-circle", "fa-stop-circle-o", "fa-street-view", "fa-strikethrough", "fa-stumbleupon", "fa-stumbleupon-circle", "fa-subscript", "fa-subway", "fa-suitcase", "fa-sun-o", "fa-superpowers", "fa-superscript", "fa-support", "fa-table", "fa-tablet", "fa-tachometer", "fa-tag", "fa-tags", "fa-tasks", "fa-taxi", "fa-telegram", "fa-television", "fa-tencent-weibo", "fa-terminal", "fa-text-height", "fa-text-width", "fa-th", "fa-th-large", "fa-th-list", "fa-themeisle", "fa-thermometer", "fa-thermometer-0", "fa-thermometer-1", "fa-thermometer-2", "fa-thermometer-3", "fa-thermometer-4", "fa-thermometer-empty", "fa-thermometer-full", "fa-thermometer-half", "fa-thermometer-quarter", "fa-thermometer-three-quarters", "fa-thumb-tack", "fa-thumbs-down", "fa-thumbs-o-down", "fa-thumbs-o-up", "fa-thumbs-up", "fa-ticket", "fa-times", "fa-times-circle", "fa-times-circle-o", "fa-times-rectangle", "fa-times-rectangle-o", "fa-tint", "fa-toggle-down", "fa-toggle-left", "fa-toggle-off", "fa-toggle-on", "fa-toggle-right", "fa-toggle-up", "fa-trademark", "fa-train", "fa-transgender", "fa-transgender-alt", "fa-trash", "fa-trash-o", "fa-tree", "fa-trello", "fa-tripadvisor", "fa-trophy", "fa-truck", "fa-try", "fa-tty", "fa-tumblr", "fa-tumblr-square", "fa-turkish-lira", "fa-tv", "fa-twitch", "fa-twitter", "fa-twitter-square", "fa-umbrella", "fa-underline", "fa-undo", "fa-universal-access", "fa-university", "fa-unlink", "fa-unlock", "fa-unlock-alt", "fa-unsorted", "fa-upload", "fa-usb", "fa-usd", "fa-user", "fa-user-circle", "fa-user-circle-o", "fa-user-md", "fa-user-o", "fa-user-plus", "fa-user-secret", "fa-user-times", "fa-users", "fa-vcard", "fa-vcard-o", "fa-venus", "fa-venus-double", "fa-venus-mars", "fa-viacoin", "fa-viadeo", "fa-viadeo-square", "fa-video-camera", "fa-vimeo", "fa-vimeo-square", "fa-vine", "fa-vk", "fa-volume-control-phone", "fa-volume-down", "fa-volume-off", "fa-volume-up", "fa-warning", "fa-wechat", "fa-weibo", "fa-weixin", "fa-whatsapp", "fa-wheelchair", "fa-wheelchair-alt", "fa-wifi", "fa-wikipedia-w", "fa-window-close", "fa-window-close-o", "fa-window-maximize", "fa-window-minimize", "fa-window-restore", "fa-windows", "fa-won", "fa-wordpress", "fa-wpbeginner", "fa-wpexplorer", "fa-wpforms", "fa-wrench", "fa-xing", "fa-xing-square", "fa-y-combinator", "fa-y-combinator-square", "fa-yahoo", "fa-yc", "fa-yc-square", "fa-yelp", "fa-yen", "fa-yoast", "fa-youtube", "fa-youtube-play", "fa-youtube-square"];
        return view('cms::pages/cms-pages/icons', compact('icons'));
    }

    public function beautifyFields($request)
    {
        $fields = [];
        for ($i = 0; $i < count($request['name']); $i++) {
            // Check if field is unique
            foreach ($fields as $field) if ($field['name'] == $request['name'][$i]) throw ValidationException::withMessages(['Column "' . $request['name'][$i] . '" already exists']);

            // Check if migration type does not exist
            if ($request['form_field'][$i] != 'select multiple') {
                if (!$request['migration_type'][$i]) throw ValidationException::withMessages(['The migration_type.' . $i . ' field is required.']);
                elseif (!in_array($request['migration_type'][$i], $this->migration_types)) throw ValidationException::withMessages(['The migration_type.' . $i . ' field is not valid.']);
            }

            // Check if form field is valid
            if (!in_array($request['form_field'][$i], $this->form_fields)) throw ValidationException::withMessages(['The form_field.' . $i . ' field is not valid.']);

            // Check database table if exists
            if ($request['form_field'][$i] == 'select' && !CmsPage::where('database_table', $request['form_field_additionals_1'][$i])->first()) throw ValidationException::withMessages(['Database table not found in "' . $request['name'][$i] . '" field']);

            // Check database table if exists
            if ($request['form_field'][$i] == 'select multiple' && !CmsPage::where('database_table', $request['form_field_additionals_1'][$i])->first()) throw ValidationException::withMessages(['Database table not found in "' . $request['name'][$i] . '" field']);

            $fields[] = [
                'name' => $request['name'][$i],
                'migration_type' => $request['migration_type'][$i],
                'form_field' => $request['form_field'][$i],
                'form_field_additionals_1' => $request['form_field_additionals_1'][$i],
                'form_field_additionals_2' => $request['form_field_additionals_2'][$i],
                'description' => $request['description'][$i],
                'hide_index' => (isset($request['hide_index'][$i]) && $request['hide_index'][$i]) ? 1 : 0,
                'hide_create' => (isset($request['hide_create'][$i]) && $request['hide_create'][$i]) ? 1 : 0,
                'hide_edit' => (isset($request['hide_edit'][$i]) && $request['hide_edit'][$i]) ? 1 : 0,
                'hide_show' => (isset($request['hide_show'][$i]) && $request['hide_show'][$i]) ? 1 : 0,
                'nullable' => $request['nullable'][$i],
                'unique' => $request['unique'][$i],
            ];
        }

        return $fields;
    }

    public function beautifyTranslatableFields($request)
    {
        $fields = [];
        if ($request['translatable_name']) {
            for ($i = 0; $i < count($request['translatable_name']); $i++) {
                // Check if field is unique
                foreach ($fields as $field) if ($field['name'] == $request['translatable_name'][$i]) throw ValidationException::withMessages(['Column "' . $request['translatable_name'][$i] . '" already exists']);

                // Check if migration type does not exist
                if (!$request['translatable_migration_type'][$i]) throw ValidationException::withMessages(['The translatable_migration_type.' . $i . ' field is required.']);
                elseif (!in_array($request['translatable_migration_type'][$i], $this->migration_types)) throw ValidationException::withMessages(['The migration_type.' . $i . ' field is not valid.']);

                // Check if form field is valid
                if (!in_array($request['translatable_form_field'][$i], $this->form_fields) || $request['translatable_form_field'][$i] == 'select' || $request['translatable_form_field'][$i] == 'select multiple') throw ValidationException::withMessages(['The form_field.' . $i . ' field is not valid.']);

                $fields[] = [
                    'name' => $request['translatable_name'][$i],
                    'migration_type' => $request['translatable_migration_type'][$i],
                    'form_field' => $request['translatable_form_field'][$i],
                    'description' => $request['translatable_description'][$i],
                    'hide_index' => (isset($request['translatable_hide_index'][$i]) && $request['translatable_hide_index'][$i]) ? 1 : 0,
                    'hide_create' => (isset($request['translatable_hide_create'][$i]) && $request['translatable_hide_create'][$i]) ? 1 : 0,
                    'hide_edit' => (isset($request['translatable_hide_edit'][$i]) && $request['translatable_hide_edit'][$i]) ? 1 : 0,
                    'hide_show' => (isset($request['translatable_hide_show'][$i]) && $request['translatable_hide_show'][$i]) ? 1 : 0,
                    'nullable' => $request['translatable_nullable'][$i],
                ];
            }
        }

        return $fields;
    }

    public function uploadCkeditorImages(Request $request)
    {
        $request->validate([
            'upload' => 'required|image'
        ]);

        $image = $request->file('upload')->store('ht-ck-images');
        $url = Storage::url($image);

        echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($request->CKEditorFuncNum, '$url', '');</script>";
    }
}
