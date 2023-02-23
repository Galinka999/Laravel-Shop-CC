<?php

namespace Tests\Feature\App\Http\Controllers\Auth;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Database\Factories\UserFactory;
use Domain\Auth\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class ResetPasswordControllerTest extends TestCase
{
    private string $token;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = UserFactory::new()->create();
        $this->token = Password::createToken($this->user);
    }

    public function test_page_success(): void
    {
        $response = $this->get(action([ResetPasswordController::class, 'page'], ['token' => $this->token]));

        $response
            ->assertOk()
            ->assertSee('Восстановление пароля')
            ->assertViewIs('auth.reset-password');
    }

    public function test_handle_success(): void
    {
        $password = '1234567890';
        $password_confirmation = '1234567890';

        $request = [
            'email' => $this->user->email,
            'password' => $password,
            'password_confirmation' => $password_confirmation,
            'token' => $this->token,
        ];

        Password::shouldReceive('reset')
            ->once()
            ->withSomeOfArgs($request)
            ->andReturn(Password::PASSWORD_RESET);

        $response = $this->post(action([ResetPasswordController::class, 'handle'], $request));

        $response->assertRedirect(action([LoginController::class, 'page']));
    }
}
