<?php

namespace Tests\Feature\App\Http\Controllers\Auth;

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Requests\SignUpFormRequest;
use App\Listeners\SendEmailNewUserListener;
use App\Notifications\NewUserNotification;
use Database\Factories\UserFactory;
use Domain\Auth\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    protected array $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = SignUpFormRequest::factory()->create([
            'email' => 'testing@caat.ru',
            'password' => '12345678',
            'password_confirmation' => '12345678'
        ]);
    }

    private function request(): TestResponse
    {
        return $this->post(
                action([RegisterController::class, 'handle']),
                $this->request
            );
    }

    private function findUser(): User
    {
        return User::query()->where(['email' => $this->request['email']])->first();
    }

    public function test_page_success(): void
    {
        $response = $this->get(action([RegisterController::class, 'page']));

        $response
            ->assertOk()
            ->assertSee('Регистрация')
            ->assertViewIs('auth.register');
    }

    public function test_is_validation_success(): void
    {
        $this->request()->assertValid();
    }

    public function test_should_fail_validation_on_password_confirm(): void
    {
        $this->request['password'] = '123';
        $this->request['password_confirmation'] = '1234';

        $this->request()->assertInvalid(['password']);
    }

    public function test_user_created_success(): void
    {
        $this->assertDatabaseMissing('users', [
            'email' => $this->request['email'],
        ]);

        $this->request();

        $this->assertDatabaseHas('users', [
            'email' => $this->request['email'],
        ]);
    }

    public function test_should_fail_validation_on_unique_email(): void
    {
        UserFactory::new()->create([
            'email' => $this->request['email'],
        ]);

        $this->assertDatabaseHas('users', [
            'email' => $this->request['email'],
        ]);

        $this->request()->assertInvalid(['email']);
    }

    public function test_is_registered_event_and_listeners_dispatched(): void
    {
        Event::fake();

        $this->request();

        Event::assertDispatched(Registered::class);
        Event::assertListening(Registered::class, SendEmailNewUserListener::class);
    }

    public function test_notification_sent(): void
    {
        $this->request();

        Notification::assertSentTo($this->findUser(), NewUserNotification::class);
    }

    public function test_user_authenticated_after_and_redirected(): void
    {
        $response = $this->request();

        $response->assertRedirect(route('home'));

        $this->assertAuthenticatedAs($this->findUser());
    }
}
