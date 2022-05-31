<?php

namespace App\Services\PriceCalculationService\PriceCalculationWithDatabaseTariffs\TariffModels;

use App\Models\Location;
use Illuminate\Database\Eloquent\Model;

class LocationTariff extends Model
{
    public $timestamps = false;

    protected $table = 'tariffs_for_locations';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }



}
