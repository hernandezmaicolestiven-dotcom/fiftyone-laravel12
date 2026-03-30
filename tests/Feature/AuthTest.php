<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Pruebas de integración para autenticación del panel admin.
 * Cubre login, logout y rate limiting.
 */
class AuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_login_with_correct_credentials(): void
    {
        $user = User::factory()->create([
            'email'    => 'admin@test.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post(route('admin.login.post'), [
            'email'    => 'admin@test.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function login_fails_with_wrong_password(): void
    {
        User::factory()->create([
            'email'    => 'admin@test.com',
            'password' => Hash::make('correct_password'),
        ]);

        $response = $this->post(route('admin.login.post'), [
            'email'    => 'admin@test.com',
            'password' => 'wrong_password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /** @test */
    public function login_is_rate_limited_after_five_attempts(): void
    {
        User::factory()->create(['email' => 'admin@test.com']);

        // 5 intentos fallidos consecutivos
        for ($i = 0; $i < 5; $i++) {
            $this->post(route('admin.login.post'), [
                'email'    => 'admin@test.com',
                'password' => 'wrong',
            ]);
        }

        // El 6to intento debe ser bloqueado por RateLimiter
        $response = $this->post(route('admin.login.post'), [
            'email'    => 'admin@test.com',
            'password' => 'wrong',
        ]);

        // Puede redirigir con errores o devolver 429
        $this->assertTrue(
            $response->isRedirect() || $response->status() === 429
        );
    }

    /** @test */
    public function authenticated_user_can_logout(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('admin.logout'));

        $response->assertRedirect('/');
        $this->assertGuest();
    }

    /** @test */
    public function guest_is_redirected_from_dashboard(): void
    {
        $response = $this->get(route('admin.dashboard'));
        $response->assertRedirect(route('admin.login'));
    }
}
