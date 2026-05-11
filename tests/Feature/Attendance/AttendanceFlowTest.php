<?php

namespace Tests\Feature\Attendance;

use App\Models\Attendance;
use App\Models\AttendanceLocation;
use App\Models\Division;
use App\Models\Intern;
use App\Models\User;
use App\Services\AttendanceService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AttendanceFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow(Carbon::create(2026, 5, 6, 7, 30, 0, config('app.timezone')));
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_intern_can_check_in_once_and_cannot_check_in_twice(): void
    {
        $user = $this->createInternUser([
            'intern.attendances.index',
            'intern.attendances.check-in',
        ]);

        $this->actingAs($user)
            ->post(route('intern.attendances.check-in'), $this->locationPayload())
            ->assertRedirect(route('intern.attendances.index'));

        $this->assertTrue(
            Attendance::query()
                ->where('intern_id', $user->intern->id)
                ->whereDate('date', today())
                ->where('status', Attendance::STATUS_PRESENT)
                ->exists()
        );

        $this->actingAs($user)
            ->post(route('intern.attendances.check-in'), $this->locationPayload())
            ->assertSessionHasErrors('attendance');
    }

    public function test_intern_can_submit_permission_without_approval(): void
    {
        $user = $this->createInternUser([
            'intern.attendances.index',
            'intern.attendances.submissions.store',
        ]);

        $this->actingAs($user)
            ->post(route('intern.attendances.submissions.store'), [
                'type' => Attendance::STATUS_PERMISSION,
                'date' => today()->toDateString(),
                'reason' => 'Ada keperluan keluarga yang tidak bisa ditinggalkan.',
            ])
            ->assertRedirect(route('intern.attendances.index'));

        $this->assertTrue(
            Attendance::query()
                ->where('intern_id', $user->intern->id)
                ->whereDate('date', today())
                ->where('status', Attendance::STATUS_PERMISSION)
                ->exists()
        );
    }

    public function test_intern_cannot_check_in_without_specific_permission(): void
    {
        $user = $this->createInternUser([
            'intern.attendances.index',
        ]);

        $this->actingAs($user)
            ->post(route('intern.attendances.check-in'), $this->locationPayload())
            ->assertForbidden();
    }

    public function test_mark_absent_command_creates_tidak_hadir_for_active_intern_without_attendance(): void
    {
        $user = $this->createInternUser();

        $this->artisan('attendance:mark-absent', ['--date' => today()->toDateString()])
            ->assertExitCode(0);

        $this->assertTrue(
            Attendance::query()
                ->where('intern_id', $user->intern->id)
                ->whereDate('date', today())
                ->where('status', Attendance::STATUS_ABSENT)
                ->exists()
        );
    }

    public function test_mentor_only_sees_attendances_from_own_division(): void
    {
        $divisionA = Division::create([
            'name' => 'Teknologi Informasi',
            'code' => 'TI',
        ]);

        $divisionB = Division::create([
            'name' => 'Keuangan',
            'code' => 'KEU',
        ]);

        $mentor = User::factory()->create([
            'division_id' => $divisionA->id,
        ]);
        $this->assignRoleAndPermissions($mentor, 'mentor', ['mentor.attendances.index']);

        $internA = $this->createInternUser([], $divisionA, 'Intern Divisi A');
        $internB = $this->createInternUser([], $divisionB, 'Intern Divisi B');

        Attendance::create([
            'intern_id' => $internA->intern->id,
            'date' => today()->toDateString(),
            'status' => Attendance::STATUS_PRESENT,
            'check_in_at' => now(),
        ]);

        Attendance::create([
            'intern_id' => $internB->intern->id,
            'date' => today()->toDateString(),
            'status' => Attendance::STATUS_PRESENT,
            'check_in_at' => now(),
        ]);

        $this->actingAs($mentor)
            ->get(route('mentor.attendances.index'))
            ->assertOk()
            ->assertSee('Intern Divisi A')
            ->assertDontSee('Intern Divisi B');
    }

    public function test_mentor_cannot_open_attendance_monitoring_without_permission(): void
    {
        $division = Division::create([
            'name' => 'Teknologi Informasi',
            'code' => 'TI',
        ]);

        $mentor = User::factory()->create([
            'division_id' => $division->id,
        ]);
        $this->assignRoleAndPermissions($mentor, 'mentor');

        $this->actingAs($mentor)
            ->get(route('mentor.attendances.index'))
            ->assertForbidden();
    }

    public function test_monitoring_summary_follows_applied_filters(): void
    {
        $divisionA = Division::create([
            'name' => 'Teknologi Informasi',
            'code' => 'TI',
        ]);

        $divisionB = Division::create([
            'name' => 'Keuangan',
            'code' => 'KEU',
        ]);

        $admin = User::factory()->create();
        $this->assignRoleAndPermissions($admin, 'admin', ['admin.attendances.index', 'admin.attendances.show']);

        $internA = $this->createInternUser([], $divisionA, 'Intern Divisi A');
        $internB = $this->createInternUser([], $divisionB, 'Intern Divisi B');

        Attendance::create([
            'intern_id' => $internA->intern->id,
            'date' => today()->toDateString(),
            'status' => Attendance::STATUS_PERMISSION,
            'reason' => 'Keperluan keluarga mendadak.',
        ]);

        Attendance::create([
            'intern_id' => $internB->intern->id,
            'date' => today()->subDay()->toDateString(),
            'status' => Attendance::STATUS_PRESENT,
            'check_in_at' => now()->subDay(),
        ]);

        $summary = app(AttendanceService::class)->getMonitoringSummary($admin, [
            'division_id' => $divisionA->id,
            'status' => Attendance::STATUS_PERMISSION,
            'date_from' => today()->toDateString(),
            'date_to' => today()->toDateString(),
        ]);

        $this->assertSame(1, $summary[Attendance::STATUS_PERMISSION]['count']);
        $this->assertSame(0, $summary[Attendance::STATUS_PRESENT]['count']);
    }

    public function test_admin_can_assign_multiple_attendance_locations_to_intern(): void
    {
        $admin = User::factory()->create();
        $this->assignRoleAndPermissions($admin, 'admin', [
            'admin.interns.index',
            'admin.interns.attendance-locations.update',
        ]);

        $user = $this->createInternUser();

        $locationA = AttendanceLocation::create([
            'name' => 'Kantor Pusat Persero',
            'latitude' => 1.1622890,
            'longitude' => 104.0049370,
            'radius_meters' => 100,
            'is_active' => true,
        ]);

        $locationB = AttendanceLocation::create([
            'name' => 'Depo 2',
            'latitude' => 1.1655877,
            'longitude' => 104.0029236,
            'radius_meters' => 75,
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->put(route('admin.interns.attendance-locations.update', $user->intern), [
                'location_ids' => [$locationA->id, $locationB->id],
                'primary_location_id' => $locationB->id,
            ])
            ->assertSessionHas('status');

        $intern = $user->intern->fresh('attendanceLocations');

        $this->assertCount(2, $intern->attendanceLocations);
        $this->assertEqualsCanonicalizing(
            [$locationA->id, $locationB->id],
            $intern->attendanceLocations->pluck('id')->all()
        );
        $this->assertSame(
            $locationB->id,
            optional($intern->attendanceLocations->first(fn ($location) => (bool) $location->pivot->is_primary))->id
        );
    }

    public function test_admin_monitoring_page_can_filter_by_category_and_link_to_detail(): void
    {
        $admin = User::factory()->create();
        $this->assignRoleAndPermissions($admin, 'admin', ['admin.attendances.index', 'admin.attendances.show']);

        $mahasiswa = $this->createInternUser([], null, 'Intern Mahasiswa');
        $siswa = $this->createInternUser([], null, 'Intern Siswa');

        $mahasiswa->intern->update(['type' => 'mahasiswa']);
        $siswa->intern->update(['type' => 'siswa']);

        $this->actingAs($admin)
            ->get(route('admin.attendances.index', ['category' => 'mahasiswa']))
            ->assertOk()
            ->assertSee('Intern Mahasiswa')
            ->assertDontSee('Intern Siswa')
            ->assertSee(route('admin.attendances.show', $mahasiswa->intern), false);
    }

    public function test_admin_can_view_intern_attendance_detail_page(): void
    {
        $admin = User::factory()->create();
        $this->assignRoleAndPermissions($admin, 'admin', ['admin.attendances.index', 'admin.attendances.show']);

        $user = $this->createInternUser([], null, 'Intern Detail');

        Attendance::create([
            'intern_id' => $user->intern->id,
            'date' => today()->toDateString(),
            'status' => Attendance::STATUS_PRESENT,
            'check_in_at' => now(),
        ]);

        $this->actingAs($admin)
            ->get(route('admin.attendances.show', $user->intern))
            ->assertOk()
            ->assertSee('Intern Detail')
            ->assertSee('Riwayat Absensi Lengkap');
    }

    public function test_check_in_stores_browser_coordinates_and_validated_location(): void
    {
        $user = $this->createInternUser([
            'intern.attendances.index',
            'intern.attendances.check-in',
        ]);

        $location = AttendanceLocation::create([
            'name' => 'Kantor Pusat Persero',
            'latitude' => 1.1622890,
            'longitude' => 104.0049370,
            'radius_meters' => 100,
            'is_active' => true,
        ]);

        $user->intern->attendanceLocations()->attach($location->id, [
            'is_primary' => true,
            'is_active' => true,
            'assigned_at' => now(),
        ]);

        $payload = [
            'latitude' => 1.1623001,
            'longitude' => 104.0049001,
            'accuracy' => 14.25,
        ];

        $this->actingAs($user)
            ->post(route('intern.attendances.check-in'), $payload)
            ->assertRedirect(route('intern.attendances.index'));

        $attendance = Attendance::query()->firstOrFail();

        $this->assertSame($location->id, $attendance->attendance_location_id);
        $this->assertEqualsWithDelta($payload['latitude'], (float) $attendance->check_in_latitude, 0.0000001);
        $this->assertEqualsWithDelta($payload['longitude'], (float) $attendance->check_in_longitude, 0.0000001);
        $this->assertSame(14.25, (float) $attendance->check_in_accuracy);
        $this->assertNotNull($attendance->check_in_distance_meters);
    }

    public function test_check_in_is_rejected_when_outside_assigned_location_radius(): void
    {
        $user = $this->createInternUser([
            'intern.attendances.index',
            'intern.attendances.check-in',
        ]);

        $location = AttendanceLocation::create([
            'name' => 'Kantor Pusat Persero',
            'latitude' => 1.1622890,
            'longitude' => 104.0049370,
            'radius_meters' => 50,
            'is_active' => true,
        ]);

        $user->intern->attendanceLocations()->attach($location->id, [
            'is_primary' => true,
            'is_active' => true,
            'assigned_at' => now(),
        ]);

        $this->actingAs($user)
            ->post(route('intern.attendances.check-in'), [
                'latitude' => 1.1700000,
                'longitude' => 104.0200000,
                'accuracy' => 10,
            ])
            ->assertSessionHasErrors('attendance');

        $this->assertDatabaseCount('attendances', 0);
    }

    public function test_check_in_can_pass_when_browser_accuracy_covers_small_gps_drift(): void
    {
        $user = $this->createInternUser([
            'intern.attendances.index',
            'intern.attendances.check-in',
        ]);

        $location = AttendanceLocation::create([
            'name' => 'Kantor Pusat Persero',
            'latitude' => 1.1622890,
            'longitude' => 104.0049370,
            'radius_meters' => 65,
            'is_active' => true,
        ]);

        $user->intern->attendanceLocations()->attach($location->id, [
            'is_primary' => true,
            'is_active' => true,
            'assigned_at' => now(),
        ]);

        $this->actingAs($user)
            ->post(route('intern.attendances.check-in'), [
                'latitude' => 1.1629640,
                'longitude' => 104.0049370,
                'accuracy' => 18,
            ])
            ->assertRedirect(route('intern.attendances.index'));

        $attendance = Attendance::query()->firstOrFail();

        $this->assertSame($location->id, $attendance->attendance_location_id);
        $this->assertNotNull($attendance->check_in_distance_meters);
        $this->assertGreaterThan(65, $attendance->check_in_distance_meters);
    }

    private function createInternUser(array $permissions = [], ?Division $division = null, ?string $name = null): User
    {
        $division ??= Division::create([
            'name' => 'Divisi '.fake()->unique()->word(),
            'code' => strtoupper(fake()->unique()->lexify('???')),
        ]);

        $user = User::factory()->create([
            'name' => $name ?? fake()->name(),
        ]);

        $this->assignRoleAndPermissions($user, 'intern', $permissions);

        Intern::create([
            'user_id' => $user->id,
            'division_id' => $division->id,
            'name' => $user->name,
            'email' => $user->email,
            'type' => 'mahasiswa',
            'institution' => 'Universitas Test',
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

        return $user->fresh('intern');
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

    private function locationPayload(): array
    {
        return [
            'latitude' => 1.1622890,
            'longitude' => 104.0049370,
            'accuracy' => 12.5,
        ];
    }
}
