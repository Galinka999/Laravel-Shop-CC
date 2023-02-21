<?php

namespace Domain\Auth\Providers;

use Domain\Auth\Actions\LoginUserAction;
use Domain\Auth\Actions\RegisterNewUserAction;
use Domain\Auth\Contracts\LoginUserContract;
use Domain\Auth\Contracts\RegisterNewUserContract;
use Illuminate\Support\ServiceProvider;

class ActionsServiceProvider extends ServiceProvider
{
    public array $bindings = [
        RegisterNewUserContract::class => RegisterNewUserAction::class,
        LoginUserContract::class => LoginUserAction::class,
    ];
}
