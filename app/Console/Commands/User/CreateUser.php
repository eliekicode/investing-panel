<?php

namespace App\Console\Commands\User;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Console\Command;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create {--name=} {--login=} {--password=} {--admin}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user account';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Creating a new user...');

        $name = $this->option('name');
        $login = $this->option('login');
        $password = $this->option('password');
        $isAdmin = $this->option('admin');

        if (blank($password)) {
            $password = Str::password(8);
        }

        User::updateOrCreate([
            'login' => $login,
        ], [
            'name' => $name,
            'password' => bcrypt($password),
            'type' => $isAdmin ? 'admin' : 'user',
            'token' => $password,
        ]);

        $this->info("User '{$login}' created successfully.");
    }
}
