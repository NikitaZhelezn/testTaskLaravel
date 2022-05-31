<?php

namespace App\Providers;

use App\Services\PriceCalculationService\PriceCalculationServiceInterface;
use App\Services\PriceCalculationService\PriceCalculationWithDatabaseTariffs\TariffCalculation;
use Illuminate\Support\ServiceProvider;

class PriceCalculationProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(PriceCalculationServiceInterface::class, TariffCalculation::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

    }
}
