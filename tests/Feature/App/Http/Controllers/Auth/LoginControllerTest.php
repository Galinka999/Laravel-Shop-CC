<?php

namespace Tests\Feature\App\Http\Controllers\Auth;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Requests\SignInFormRequest;
use Database\Factories\UserFactory;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_login_success(): void
    {
        $response = $this->get(action([LoginController::class, 'page']));

        $response
            ->assertOk()
            ->assertSee('Вход в аккаунт')
            ->assertViewIs('auth.login');
    }

    public function test_page_success(): void
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

    public function test_handle_success(): void
    {
        $user = Userfactory::new()->create([
            'email' => 'testing@cut.ru'
        ]);

        $this->actingAs($user)
            ->delete(action([LoginController::class, 'logout']));

        $this->assertGuest();

    }
}
