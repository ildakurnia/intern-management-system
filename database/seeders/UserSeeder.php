<?php

namespace Database\Seeders;

use App\Enums\UserRoleEnum;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'IMS Admin',
                'email' => 'admin@ims.test',
                'role' => UserRoleEnum::ADMIN,
            ],
            [
                'name' => 'IMS Mentor',
                'email' => 'mentor@ims.test',
                'role' => UserRoleEnum::MENTOR,
            ],
            [
                'name' => 'IMS Intern',
                'email' => 'intern@ims.test',
                'role' => UserRoleEnum::INTERN,
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'password' => Hash::make('password'),
                    'role' => $user['role'],
                    'email_verified_at' => now(),
                ],
            );
        }
    }
}
