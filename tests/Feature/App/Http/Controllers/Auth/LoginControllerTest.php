<?php

namespace Tests\Feature\App\Http\Controllers\Auth;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Requests\SignInFormRequest;
use Database\Factories\UserFactory;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    public function test_page_success(): void
    {
        $response = $this->get(action([LoginController::class, 'page']));

        $response
            ->assertOk()
            ->assertSee('Вход в аккаунт')
            ->assertViewIs('auth.login');
    }

    public function test_handle_success(): void
    {
        $password = '12345678';

        $user = UserFactory::new()->create([
            'email' => 'testing@cut.ru',
            'password' => bcrypt($password)
        ]);

        $request = SignInFormRequest::factory()->create([
            'email' => $user->email,
            'password' => $password,
        ]);

        $response = $this->post(action([LoginController::class, 'handle']), $request);

        $response
            ->assertValid()
            ->assertRedirect(route('home'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_handle_fail(): void
    {
        $request = SignInFormRequest::factory()->create([
            'email' => 'notfound@mail.ru',
            'password' => str()->random(10),
        ]);

        $this->post(action([LoginController::class, 'handle']), $request)
            ->assertInvalid(['email']);

        $this->assertGuest();
    }

    public function test_logout_success(): void
    {
        $user = Userfactory::new()->create([
            'email' => 'testing@cut.ru'
        ]);

        $this->actingAs($user)
            ->delete(action([LoginController::class, 'logout']));

        $this->assertGuest();
    }

    public function test_logout_guest_middleware_fail(): void
    {
        $this->delete(action([LoginController::class, 'logout']))
            ->assertRedirect(route('home'));
    }
}
