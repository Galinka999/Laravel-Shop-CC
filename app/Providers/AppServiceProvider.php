<?php

namespace App\Providers;

use App\Http\Kernel;
use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Model::shouldBeStrict(!app()->isProduction());

//        if(app()->isProduction()) {
            DB::listen(function ($query) {
                if($query->time >= 200) {
                    logger()
                        ->channel('telegram')
                        ->debug('query longer then 0.1 s: '. $query->time, [$query->sql, $query->bindings]);
                }
            });

            app(Kernel::class)->whenRequestLifecycleIsLongerThan(
                CarbonInterval::milliseconds(4),
                fn($startedAt, $request) =>
                logger()
                    ->channel('telegram')
                    ->debug('Жизненный цикл запроса превышает порог: ',  [
                        'user' => $request()->user()?->id,
                        'url' => $request()->url()
                    ])
            );
//        }
    }
}
