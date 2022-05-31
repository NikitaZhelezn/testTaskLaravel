<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\Request;
use App\Models\Location;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface LocationRepositoryInterface
{
    /**
     * @param int $id
     * @return Collection
     */
    public function getById(int $id): Collection;

    /**
     * @return Location[]|\Illuminate\Database\Eloquent\Collection
     */
    public function all(): Collection;

    /**
     * @return array
     */
    public function getAvailableBlocksList(): array;

    /**
     * @param Request $request
     * @param Model $location_id
     * @return array
     */
    public function getAvailableBlocksForLocation(Request $request, Model $location_id): array;

}
