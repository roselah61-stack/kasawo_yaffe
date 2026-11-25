<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

use function Laravel\Prompts\password;
use function Laravel\Prompts\text;

class MakeAdmin extends Command
{
    protected $signature = 'kasawo:admin {email?} {--name=}';

    protected $description = 'Create a Kasawo Yaffe admin, or reset an existing admin’s password';

    public function handle(): int
    {
        $email = $this->argument('email') ?: text('Admin email', required: true);
        $name = $this->option('name') ?: text('Full name', default: User::where('email', $email)->value('name') ?? '', required: true);
        $pass = password('Password (min 10 characters)', required: true);

        $v = Validator::make(['email' => $email, 'password' => $pass], ['email' => 'email', 'password' => 'min:10']);
        if ($v->fails()) {
            $this->error($v->errors()->first());

            return self::FAILURE;
        }

        User::updateOrCreate(['email' => $email], ['name' => $name, 'password' => $pass, 'is_admin' => true]);
        $this->info("Admin ready: {$email}. Sign in at ".url('/admin'));

        return self::SUCCESS;
    }
}
