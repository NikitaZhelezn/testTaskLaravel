<?php

namespace App\Providers;

use App\Services\WriteOperationServices\OrderWriteOperationService\OrderWriteOperationService;
use App\Services\WriteOperationServices\OrderWriteOperationService\OrderWriteOperationServiceInterface;
use Illuminate\Support\ServiceProvider;

class WriteOperationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(OrderWriteOperationServiceInterface::class, OrderWriteOperationService::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
