<?php

namespace App\Console\Commands;

use App\Models\MailingCode;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateAdminCommand extends Command
{
    protected $signature = 'dearly:create-admin 
                            {--name= : The full name or title of the administrator}
                            {--email= : The private administrative email}
                            {--password= : Optional password (prompted securely if omitted)}
                            {--role=super_admin : The administrative role (super_admin, admin, moderator)}';

    protected $description = 'Create a new Dearly administrator or Super Administrator';

    public function handle(): int
    {
        $email = $this->option('email') ?: $this->ask('Enter administrator email');
        $name = $this->option('name') ?: $this->ask('Enter administrator name', 'Administrator');
        $role = $this->option('role') ?: $this->choice('Select role', ['super_admin', 'admin', 'moderator'], 0);
        $password = $this->option('password') ?: $this->secret('Enter a strong administrative password');

        if (!$password) {
            $this->error('Password cannot be empty.');
            return self::FAILURE;
        }

        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make($password),
                'role' => $role,
                'email_verified_at' => now(),
            ]
        );

        $nickname = 'Admin_' . Str::random(5);
        UserProfile::firstOrCreate(
            ['user_id' => $user->id],
            [
                'nickname' => $nickname,
                'age' => 30,
                'preferred_language' => 'en',
                'is_onboarded' => true,
            ]
        );

        MailingCode::firstOrCreate(
            ['user_id' => $user->id],
            [
                'code' => MailingCode::generateUniqueCode(),
                'is_active' => true,
                'permissions_policy' => 'anyone',
            ]
        );

        $this->info("Administrator [{$email}] created successfully with role [{$role}].");
        return self::SUCCESS;
    }
}
