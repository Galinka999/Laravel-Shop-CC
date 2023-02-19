<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\SignInFormRequest;
use Domain\Auth\Contracts\LoginUserContract;
use DomainException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class LoginController extends Controller
{
    public function page(): Factory|View|Application
    {
        return view('auth.login');
    }

    public function handle(SignInFormRequest $request, LoginUserContract $action): RedirectResponse
    {
        try {
            if( $action($request->get('email'), $request->get('password'))) {

                $request->session()->regenerate();

                return redirect()
                    ->intended(route('home'));
            }

            return back()->withErrors([
                'email' => 'Предоставленные учетные данные не соответствуют нашим записям.',
            ])->onlyInput('email');


        } catch (\Throwable $e) {
            throw new DomainException('Что-то пошло не так. Попробуйте позже.');
        }
    }

    public function logout(): RedirectResponse
    {
        auth()->logout();

        request()->session()->invalidate();

        request()->session()->regenerateToken();

        return redirect()
            ->route('login');
    }

}
