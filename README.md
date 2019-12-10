<h1>Installation:</h1>

<ul>
<li>Create the database and add the credentials to your .env file</li>
<li>Update your website URL inside of the APP_URL variable inside the .env file</li>
<li>Change default string length: https://laravel-news.com/laravel-5-4-key-too-long-error</li>
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
Finally run:
<pre>composer require hellotreedigital/cms
</pre>
</li>
</ul>
