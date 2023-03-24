<?php

namespace Hellotreedigital\Cms\Commands;

use Illuminate\Console\Command;
use DB;

class CreateAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create {--name=} {--email=} {--password=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates admin';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->option('name');
        $email = $this->option('email');
        $password = $this->option('password');

        DB::table('admins')->insert([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password),
        ]);

        return;
    }
}
