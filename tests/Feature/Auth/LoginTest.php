<?php

namespace Tests\Feature\Auth;

use App\Enums\UserRoleEnum;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_is_redirected_to_admin_dashboard_after_login(): void
    {
        $user = User::factory()->admin()->create([
            'email' => 'admin@example.com',
        ]);

        $response = $this->post(route('login.attempt'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route(UserRoleEnum::ADMIN->dashboardRouteName()));
        $this->assertAuthenticatedAs($user);
    }

    public function test_mentor_is_redirected_to_mentor_dashboard_after_login(): void
    {
        $user = User::factory()->mentor()->create([
            'email' => 'mentor@example.com',
        ]);

        $response = $this->post(route('login.attempt'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route(UserRoleEnum::MENTOR->dashboardRouteName()));
        $this->assertAuthenticatedAs($user);
    }

    public function test_intern_is_redirected_to_intern_dashboard_after_login(): void
    {
        $user = User::factory()->intern()->create([
            'email' => 'intern@example.com',
        ]);

        $response = $this->post(route('login.attempt'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route(UserRoleEnum::INTERN->dashboardRouteName()));
        $this->assertAuthenticatedAs($user);
    }
}
