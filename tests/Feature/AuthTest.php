<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_login_page(): void
    {
        $this->get(route('login'))->assertOk()->assertSee('NIP');
    }

    public function test_guest_is_redirected_to_login_when_accessing_protected_page(): void
    {
        $this->get('/beranda')->assertRedirect(route('login'));
    }

    public function test_root_renders_welcome_page_for_guest(): void
    {
        $this->get('/')->assertOk()->assertSee('Sistem Manajemen');
    }

    public function test_user_can_login_with_nip_and_password(): void
    {
        $user = User::factory()->create(['password' => 'rahasia123', 'is_active' => true]);

        $this->post(route('login.attempt'), [
            'nip' => $user->nip,
            'password' => 'rahasia123',
        ])->assertRedirect(route('beranda'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_invalid_credentials_shows_generic_error(): void
    {
        $user = User::factory()->create(['password' => 'rahasia123']);

        $this->from(route('login'))->post(route('login.attempt'), [
            'nip' => $user->nip,
            'password' => 'salah',
        ])->assertSessionHasErrors('nip');

        $this->assertGuest();
    }

    public function test_inactive_user_cannot_login(): void
    {
        $user = User::factory()->create(['password' => 'rahasia123', 'is_active' => false]);

        $this->post(route('login.attempt'), [
            'nip' => $user->nip,
            'password' => 'rahasia123',
        ])->assertSessionHasErrors('nip');

        $this->assertGuest();
    }

    public function test_login_is_locked_after_five_failed_attempts(): void
    {
        $user = User::factory()->create(['password' => 'rahasia123']);

        for ($i = 0; $i < 5; $i++) {
            $this->post(route('login.attempt'), [
                'nip' => $user->nip,
                'password' => 'salah',
            ]);
        }

        $this->from(route('login'))->post(route('login.attempt'), [
            'nip' => $user->nip,
            'password' => 'rahasia123',
        ])->assertSessionHasErrors('nip');

        $this->assertGuest();
    }

    public function test_authenticated_user_is_redirected_away_from_login_page(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('login'))->assertRedirect('/');

        $this->actingAs($user)->get('/')->assertRedirect(route('beranda'));
    }

    public function test_logout_signs_user_out(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('logout'))
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }

    public function test_no_public_registration_route_exists(): void
    {
        $this->get('/register')->assertNotFound();
    }
}