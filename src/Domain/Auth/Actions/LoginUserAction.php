<?php

declare(strict_types=1);

namespace Domain\Auth\Actions;

use Domain\Auth\Contracts\LoginUserContract;

final class LoginUserAction implements LoginUserContract
{
    public function __invoke(string $email, string $password): bool
    {
        if(!auth()->attempt([
            'email' => $email,
            'password' => $password,
        ])) {
            return false;
        }

        return true;
    }
}
