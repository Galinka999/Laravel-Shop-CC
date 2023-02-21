<?php

declare(strict_types=1);

namespace Test\Feature\Auth\Actions;

use Domain\Auth\Contracts\RegisterNewUserContract;
use Domain\Auth\DTOs\NewUserDTO;
use Tests\TestCase;

final class RegisterNewUserActionTest extends TestCase
{
    public function test_success_user_created(): void
    {
        $this->assertDatabaseMissing('users', [
            'email' => 'testingqw@mail.ru'
        ]);

        $action = app(RegisterNewUserContract::class);

        $action(NewUserDTO::make('Test', 'testingqw@mail.ru', '123456'));

        $this->assertDatabaseHas('users', [
            'email' => 'testingqw@mail.ru'
        ]);
    }
}
