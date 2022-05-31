<?php

namespace App\Services\PriceCalculationService\PriceCalculationWithDatabaseTariffs\TariffModels;

use Illuminate\Database\Eloquent\Model;

class TemperatureTariff extends Model
{
    public $timestamps = false;

    protected $table = 'tariffs_for_temperature';

}
