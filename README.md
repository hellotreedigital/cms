<h1>Installation:</h1>
<ul>
	<li>Create the database and update your .env file</li>
	<li>
		Add the below to config/auth.php:
		<pre>
'guards' => [
  'admin' => [
	  'driver' => 'session',
	  'provider' => 'admins',
  ],
],
'providers' => [
	'admins' => [
		'driver' => 'eloquent',
		'model' => Hellotreedigital\Cms\Models\Admin::class,
	],
],
		</pre>
	</li>
	<li>
		Run:
		<pre>
composer require hellotreedigital/cms
		</pre>
	</li>
</ul>

<h1>Publishable:</h1>
<ul>
	<li>
		CMS assets:
		<pre style="font-size: 0;">
<span style="font-size: 12px;">php artisan vendor:publish --tag=cms_assets --force</span>
		</pre>
	</li>
	<li>
		CMS config:
		<pre>
php artisan vendor:publish --tag=cms_config --force
		</pre>
	</li>
	<li>
		CMS routes:
		<pre>
php artisan vendor:publish --tag=cms_routes --force
		</pre>
	</li>
</ul>
