<h1>Installation:</h1>
<ul>
	<li>Create the database and update your .env file</li>
	<li>
		Add the below to config/auth.php:
		<pre>
'guards' => [
	...
	'admin' => [
		'driver' => 'session',
		'provider' => 'admins',
  	],
	...
],
'providers' => [
	...
	'admins' => [
		'driver' => 'eloquent',
		'model' => Hellotreedigital\Cms\Models\Admin::class,
	],
	...
],
		</pre>
	</li>
	<li>Configure the filesystem in config/filesystem.php, use 'public' as default value if none</li>
	<li>
		Run:
		<pre>composer require hellotreedigital/cms</pre>
	</li>
</ul>

<h1>Publishables:</h1>
<ul>
	<li>
		CMS config:
		<pre>php artisan vendor:publish --tag=cms_config --force</pre>
	</li>
	<li>
		CMS intouch config:
		<pre>php artisan vendor:publish --tag=cms_intouch_config --force</pre>
	</li>
	<li>
		CMS ripply config:
		<pre>php artisan vendor:publish --tag=cms_ripply_config --force</pre>
	</li>
	<li>
		CMS scratch & courage config:
		<pre>php artisan vendor:publish --tag=cms_scratch_and_courage_config --force</pre>
	</li>
	<li>
		CMS imaginelabs. config:
		<pre>php artisan vendor:publish --tag=cms_imagine_labs_config --force</pre>
	</li>
	<li>
		CMS Purple Brains config:
		<pre>php artisan vendor:publish --tag=cms_purple_brains_config --force</pre>
	</li>
	<li>
		CMS routes:
		<pre>php artisan vendor:publish --tag=cms_routes --force</pre>
	</li>
	<li>
		CMS translatables:
		<pre>php artisan vendor:publish --tag=translatable --force</pre>
	</li>
</ul>

<h1>Http Logs Installation:</h1>
<ul>
	<li>
		Add the below to protected $middleware in app/Http/Kernel.php:
		<pre>\Hellotreedigital\Cms\Middlewares\HttpLogsMiddleware::class,</pre>
	</li>
</ul>

<h1>Preview Checking On The Website:</h1>
<ul>
	<li>
		If
		<pre>auth('admin')->check() && request('ht_preview_mode')</pre>
        returns true, then preview mode is on, you can get the preview data from
		<pre>session('ht-preview-mode-request')</pre>
        Code example:
        <pre>$row = auth('admin')->check() && request('ht_preview_mode') ? session('ht-preview-mode-request') : $row = Model::findOrFail($id);</pre>
	</li>
</ul>
