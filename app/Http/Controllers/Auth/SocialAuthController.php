<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Domain\Auth\Models\User;
use DomainException;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SocialAuthController extends Controller
{
    public function redirect(string $driver): RedirectResponse|\Illuminate\Http\RedirectResponse
    {
        try {
            return Socialite::driver($driver)->redirect();
        } catch (\Throwable $e) {
            throw new DomainException('Произошла ошибка или дравер не поддерживается.');
        }
    }

    public function callback(string $driver): \Illuminate\Http\RedirectResponse
    {
        if($driver !== 'github') {
            throw new DomainException('Дравер не поддерживается.');
        }
        $driverUser = Socialite::driver($driver)->user();

        $user = User::query()->updateOrCreate([
            $driver.'_id' => $driverUser->getId(),
        ], [
            'name' => $driverUser->getName() ?? $driverUser->getEmail(),
            'email' => $driverUser->getEmail(),
            'password' => bcrypt(str()->random(8)),
        ]);

        auth()->login($user);

        return redirect()
            ->intended(route('home'));
    }
}
