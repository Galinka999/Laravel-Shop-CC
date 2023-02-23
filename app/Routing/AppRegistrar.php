<?php

declare(strict_types=1);

namespace App\Routing;

use App\Contracts\RouteRegistrar;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ThumbnailController;
use Illuminate\Contracts\Routing\Registrar;
use Illuminate\Support\Facades\Route;

final class AppRegistrar implements RouteRegistrar
{

    public function map(Registrar $registrar): void
    {
        Route::middleware('web')->group(function () {
            Route::get('/', HomeController::class)->name('home');

            Route::get('/storage/images/{dir}/{method}/{size}/{file}', ThumbnailController::class)
                ->where('method', 'resize|crop|fit')
                ->where('size', '\d+x\d+')
                ->where('file', '.+\.(png|jpg|gif|jpeg|bmp)$')
                ->name('thumbnail');
        });
    }
}
