<?php

namespace Tests\Feature\Auth;

use App\Http\Middleware\EnsureUserHasRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class RoleMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_intern_is_blocked_by_admin_role_middleware(): void
    {
        $intern = User::factory()->intern()->create();
        $request = Request::create('/admin/dashboard', 'GET');
        $request->setUserResolver(fn () => $intern);

        $middleware = new EnsureUserHasRole();

        try {
            $middleware->handle($request, fn () => response('ok'), 'admin');

            $this->fail('Intern user should not pass the admin role middleware.');
        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $exception) {
            $this->assertSame(Response::HTTP_FORBIDDEN, $exception->getStatusCode());
        }
    }

    public function test_authenticated_user_is_redirected_to_role_dashboard(): void
    {
        $mentor = User::factory()->mentor()->create();

        $response = $this->actingAs($mentor)->get(route('dashboard'));

        $response->assertRedirect(route('dashboard.mentor'));
    }
}
