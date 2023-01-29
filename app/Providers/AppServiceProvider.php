<?php

namespace App\Providers;

use App\Http\Kernel;
use Carbon\CarbonInterval;
use Illuminate\Database\Connection;
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
        Model::preventLazyLoading(!app()->isProduction());
        Model::preventSilentlyDiscardingAttributes(!app()->isProduction());

        DB::whenQueryingForLongerThan(500, function (Connection $connection) {
            logger()
                ->channel('telegram')
                ->debug('whenQueryingForLongerThan:'. $connection->query()->toSql());
        });

        $kernel = app(Kernel::class);

        $kernel->whenRequestLifecycleIsLongerThan(
            CarbonInterval::milliseconds(4),
            fn($startedAt, $request) =>
                logger()
                    ->channel('telegram')
                    ->debug('Жизненный цикл запроса превышает порог: ',  [
                        'user' => $request()->user()?->id,
                        'url' => $request()->url()
                    ])
        );

    }
}