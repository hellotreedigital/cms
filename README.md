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
<pre>composer require hellotreedigital/cms
</pre>
</li>
</ul>
