<?php

namespace Tests\Feature\App\Http\Controllers\Auth;

use App\Http\Controllers\Auth\ForgotPasswordController;
use Database\Factories\UserFactory;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ForgotPasswordControllerTest extends TestCase
{
    private function testingCredentials(): array
    {
        return [
            'email' => 'testing@mail.ru',
        ];
    }

    public function test_page_success(): void
    {
        $response = $this->get(action([ForgotPasswordController::class, 'page']));

        $response
            ->assertOk()
            ->assertSee('Забыли пароль')
            ->assertViewIs('auth.forgot-password');
    }

    public function test_handle_success(): void
    {
        $user = UserFactory::new()->create($this->testingCredentials());

        $this->post(action([ForgotPasswordController::class, 'handle']), $this->testingCredentials())
            ->assertRedirect();

        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_handle_fail(): void
    {
        $this->assertDatabaseMissing('users', $this->testingCredentials());

        $this->post(action([ForgotPasswordController::class, 'handle']), $this->testingCredentials())
            ->assertInvalid(['email']);

        Notification::assertNothingSent();
    }
}
