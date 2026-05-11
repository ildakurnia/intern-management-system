<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Division;
use App\Models\Institution;
use App\Models\Intern;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AllowanceFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow(Carbon::create(2026, 5, 7, 10, 0, 0, config('app.timezone')));
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_admin_can_view_monthly_allowance_list_for_eligible_polibatam_students(): void
    {
        $admin = User::factory()->create();
        $this->assignRoleAndPermissions($admin, 'admin', [
            'admin.allowances.index',
            'admin.allowances.show',
            'admin.allowances.print',
            'admin.allowances.show.print',
        ]);

        $eligibleInstitution = Institution::create([
            'name' => 'Politeknik Negeri Batam',
            'is_active' => true,
            'is_allowance_eligible' => true,
        ]);

        $otherInstitution = Institution::create([
            'name' => 'Universitas Luar Kota',
            'is_active' => true,
            'is_allowance_eligible' => false,
        ]);

        $eligibleIntern = $this->createIntern('Mahasiswa Polibatam', $eligibleInstitution);
        $nonEligibleIntern = $this->createIntern('Mahasiswa Non Polibatam', $otherInstitution);
        $nonEligibleIntern->update(['type' => 'mahasiswa']);

        $this->createAttendanceDays($eligibleIntern, 18);
        $this->createAttendanceDays($nonEligibleIntern, 12);

        $this->actingAs($admin)
            ->get(route('admin.allowances.index', ['month' => '2026-05']))
            ->assertOk()
            ->assertSee('Mahasiswa Polibatam')
            ->assertDontSee('Mahasiswa Non Polibatam')
            ->assertSee('18 hari')
            ->assertSee('Rp 409.091');
    }

    public function test_allowance_amount_is_capped_at_maximum_when_attendance_exceeds_22_days(): void
    {
        $admin = User::factory()->create();
        $this->assignRoleAndPermissions($admin, 'admin', [
            'admin.allowances.index',
            'admin.allowances.show',
        ]);

        $eligibleInstitution = Institution::create([
            'name' => 'Politeknik Negeri Batam',
            'is_active' => true,
            'is_allowance_eligible' => true,
        ]);

        $intern = $this->createIntern('Mahasiswa Maksimal', $eligibleInstitution);
        $this->createAttendanceDays($intern, 24);

        $this->actingAs($admin)
            ->get(route('admin.allowances.show', ['intern' => $intern, 'month' => '2026-05']))
            ->assertOk()
            ->assertSee('24')
            ->assertSee('22')
            ->assertSee('Masuk batas maksimal bulanan.')
            ->assertSee('Rp 500.000');
    }

    public function test_admin_can_open_printable_allowance_views(): void
    {
        $admin = User::factory()->create();
        $this->assignRoleAndPermissions($admin, 'admin', [
            'admin.allowances.index',
            'admin.allowances.show',
            'admin.allowances.print',
            'admin.allowances.show.print',
        ]);

        $eligibleInstitution = Institution::create([
            'name' => 'Politeknik Negeri Batam',
            'is_active' => true,
            'is_allowance_eligible' => true,
        ]);

        $intern = $this->createIntern('Mahasiswa Cetak', $eligibleInstitution);
        $this->createAttendanceDays($intern, 10);

        $this->actingAs($admin)
            ->get(route('admin.allowances.print', ['month' => '2026-05']))
            ->assertOk()
            ->assertSee('Perhitungan Uang Saku Mahasiswa Berdasarkan Absensi')
            ->assertSee('Mahasiswa Cetak');

        $this->actingAs($admin)
            ->get(route('admin.allowances.show.print', ['intern' => $intern, 'month' => '2026-05']))
            ->assertOk()
            ->assertSee('Detail Uang Saku Mahasiswa')
            ->assertSee('Mahasiswa Cetak')
            ->assertSee('Rp 227.273');
    }

    private function createIntern(string $name, Institution $institution): Intern
    {
        $division = Division::create([
            'name' => 'Divisi '.fake()->unique()->word(),
            'code' => strtoupper(fake()->unique()->lexify('???')),
        ]);

        $user = User::factory()->create([
            'name' => $name,
        ]);

        return Intern::create([
            'user_id' => $user->id,
            'division_id' => $division->id,
            'name' => $user->name,
            'email' => $user->email,
            'type' => 'mahasiswa',
            'institution' => $institution->name,
            'institution_id' => $institution->id,
            'major' => 'Informatika',
            'faculty' => 'Teknik',
            'nim' => fake()->unique()->numerify('2200####'),
            'semester' => '6',
            'start_date' => today()->subMonth(),
            'end_date' => today()->addMonth(),
            'status' => 'active',
            'ktp_path' => 'docs/ktp.pdf',
            'student_card_path' => 'docs/kartu.pdf',
            'bpjs_path' => 'docs/bpjs.pdf',
            'recommendation_letter_path' => 'docs/surat.pdf',
            'profile_completed_at' => now(),
            'documents_completed_at' => now(),
        ]);
    }

    private function createAttendanceDays(Intern $intern, int $days): void
    {
        for ($index = 1; $index <= $days; $index++) {
            $date = Carbon::create(2026, 5, $index, 8, 0, 0, config('app.timezone'));

            Attendance::create([
                'intern_id' => $intern->id,
                'date' => $date->toDateString(),
                'status' => $index % 5 === 0 ? Attendance::STATUS_LATE : Attendance::STATUS_PRESENT,
                'check_in_at' => $date->copy()->setTime(8, $index % 5 === 0 ? 20 : 0),
                'check_out_at' => $date->copy()->setTime(17, 0),
                'late_minutes' => $index % 5 === 0 ? 20 : 0,
                'work_minutes' => 540,
            ]);
        }
    }

    private function assignRoleAndPermissions(User $user, string $roleName, array $permissions = []): void
    {
        Role::findOrCreate($roleName, 'web');
        $user->syncRoles([$roleName]);

        foreach ($permissions as $permissionName) {
            Permission::findOrCreate($permissionName, 'web');
        }

        if ($permissions !== []) {
            $user->syncPermissions($permissions);
        }
    }
}
