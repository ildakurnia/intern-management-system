<?php

namespace Tests\Feature;

use App\Models\Division;
use App\Models\Institution;
use App\Models\Intern;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class InstitutionSelectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_search_master_institution(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);

        Institution::create([
            'name' => 'Politeknik Negeri Batam',
            'is_active' => true,
        ]);

        $this->actingAs($user)
            ->getJson(route('institutions.search', ['q' => 'batam']))
            ->assertOk()
            ->assertJsonFragment([
                'name' => 'Politeknik Negeri Batam',
            ]);
    }

    public function test_superadmin_can_create_intern_with_manual_institution_input(): void
    {
        $admin = User::factory()->create();
        $role = Role::findOrCreate('superadmin', 'web');
        Role::findOrCreate('intern', 'web');
        $admin->syncRoles([$role]);

        Permission::findOrCreate('admin.users.store', 'web');
        $admin->givePermissionTo('admin.users.store');

        $division = Division::create([
            'name' => 'Teknologi Informasi',
            'code' => 'TI',
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->post(route('admin.users.store'), [
                'name' => 'Intern Manual',
                'email' => 'manual-intern@example.com',
                'password' => 'Password123!',
                'password_confirmation' => 'Password123!',
                'role' => 'intern',
                'division_id' => $division->id,
                'type' => 'mahasiswa',
                'institution_search' => 'Universitas Luar Kota',
                'institution_id' => null,
                'institution_manual_name' => 'Universitas Luar Kota',
                'major' => 'Teknik Informatika',
                'start_date' => now()->toDateString(),
                'end_date' => now()->addMonth()->toDateString(),
                'identification_number' => '22001111',
            ])
            ->assertRedirect(route('admin.users.index'));

        $intern = Intern::query()->where('email', 'manual-intern@example.com')->firstOrFail();

        $this->assertNull($intern->institution_id);
        $this->assertSame('Universitas Luar Kota', $intern->institution_manual_name);
        $this->assertSame('Universitas Luar Kota', $intern->institution);
    }

    public function test_polibatam_mahasiswa_must_fill_bank_account_number(): void
    {
        $admin = User::factory()->create();
        $role = Role::findOrCreate('superadmin', 'web');
        Role::findOrCreate('intern', 'web');
        $admin->syncRoles([$role]);

        Permission::findOrCreate('admin.users.store', 'web');
        $admin->givePermissionTo('admin.users.store');

        $division = Division::create([
            'name' => 'Teknologi Informasi',
            'code' => 'TI',
            'is_active' => true,
        ]);

        $institution = Institution::create([
            'name' => 'Politeknik Negeri Batam',
            'is_active' => true,
            'is_allowance_eligible' => true,
        ]);

        $this->actingAs($admin)
            ->from(route('admin.users.create'))
            ->post(route('admin.users.store'), [
                'name' => 'Intern Polibatam',
                'email' => 'polibatam@example.com',
                'password' => 'Password123!',
                'password_confirmation' => 'Password123!',
                'role' => 'intern',
                'division_id' => $division->id,
                'type' => 'mahasiswa',
                'institution_search' => 'Politeknik Negeri Batam',
                'institution_id' => $institution->id,
                'institution_manual_name' => null,
                'major' => 'Teknik Informatika',
                'start_date' => now()->toDateString(),
                'end_date' => now()->addMonth()->toDateString(),
                'identification_number' => '22001112',
                'bank_account_number' => '',
            ])
            ->assertRedirect(route('admin.users.create'))
            ->assertSessionHasErrors('bank_account_number');
    }

    public function test_non_polibatam_intern_does_not_store_bank_account_number(): void
    {
        $admin = User::factory()->create();
        $role = Role::findOrCreate('superadmin', 'web');
        Role::findOrCreate('intern', 'web');
        $admin->syncRoles([$role]);

        Permission::findOrCreate('admin.users.store', 'web');
        $admin->givePermissionTo('admin.users.store');

        $division = Division::create([
            'name' => 'Teknologi Informasi',
            'code' => 'TI',
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->post(route('admin.users.store'), [
                'name' => 'Intern Manual Rekening',
                'email' => 'manual-rekening@example.com',
                'password' => 'Password123!',
                'password_confirmation' => 'Password123!',
                'role' => 'intern',
                'division_id' => $division->id,
                'type' => 'mahasiswa',
                'institution_search' => 'Universitas Luar Kota',
                'institution_id' => null,
                'institution_manual_name' => 'Universitas Luar Kota',
                'major' => 'Teknik Informatika',
                'start_date' => now()->toDateString(),
                'end_date' => now()->addMonth()->toDateString(),
                'identification_number' => '22001113',
                'bank_account_number' => '1234567890',
            ])
            ->assertRedirect(route('admin.users.index'));

        $intern = Intern::query()->where('email', 'manual-rekening@example.com')->firstOrFail();

        $this->assertNull($intern->bank_account_number);
    }
}
