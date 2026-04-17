<?php

namespace Database\Seeders;

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
                'roleName' => 'admin',
            ],
            [
                'name' => 'IMS Manager',
                'email' => 'manager@ims.test',
                'roleName' => 'manager',
            ],
            [
                'name' => 'IMS Intern',
                'email' => 'intern@ims.test',
                'roleName' => 'intern',
            ],
        ];

        foreach ($users as $userData) {
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ],
            );

            // Assign spatie role
            $user->assignRole($userData['roleName']);
        }
    }
}
