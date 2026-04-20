<?php

namespace Database\Seeders;

use App\Models\Division;
use App\Models\Intern;
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
        $this->renameLegacyManagerUser();

        $users = [
            [
                'name' => 'IMS Superadmin',
                'email' => 'superadmin@ims.test',
                'roleName' => 'superadmin',
            ],
            [
                'name' => 'IMS Admin',
                'email' => 'admin@ims.test',
                'roleName' => 'admin',
            ],
            [
                'name' => 'IMS Mentor',
                'email' => 'mentor@ims.test',
                'roleName' => 'mentor',
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

            $user->syncRoles([$userData['roleName']]);
        }

        $division = Division::firstOrCreate(
            ['code' => 'IT'],
            ['name' => 'IT', 'is_active' => true],
        );

        $internUser = User::where('email', 'intern@ims.test')->first();

        if ($internUser) {
            $internUser->update(['division_id' => $division->id]);

            Intern::updateOrCreate(
                ['email' => 'intern@ims.test'],
                [
                    'user_id' => $internUser->id,
                    'division_id' => $division->id,
                    'name' => 'IMS Intern',
                    'phone' => '081234567890',
                    'address' => 'Batam',
                    'birth_date' => '2004-01-01',
                    'gender' => 'female',
                    'type' => 'mahasiswa',
                    'institution' => 'Universitas Contoh',
                    'major' => 'Sistem Informasi',
                    'nim' => '2023121210',
                    'semester' => '6',
                    'start_date' => now()->toDateString(),
                    'end_date' => now()->addMonths(3)->toDateString(),
                    'status' => 'active',
                    'registration_status' => 'registered',
                    'registered_at' => now(),
                    'profile_completed_at' => now(),
                    'documents_completed_at' => now(),
                    'ktp_path' => 'seed/ktp.pdf',
                    'student_card_path' => 'seed/student-card.pdf',
                    'bpjs_path' => 'seed/bpjs.pdf',
                ],
            );
        }
    }

    private function renameLegacyManagerUser(): void
    {
        $legacyManager = User::query()
            ->where('email', 'manager@ims.test')
            ->first();

        if (! $legacyManager) {
            return;
        }

        $mentorEmailExists = User::query()
            ->where('email', 'mentor@ims.test')
            ->where($legacyManager->getKeyName(), '!=', $legacyManager->getKey())
            ->exists();

        if ($mentorEmailExists) {
            return;
        }

        $legacyManager->forceFill([
            'name' => 'IMS Mentor',
            'email' => 'mentor@ims.test',
        ])->save();
    }
}
