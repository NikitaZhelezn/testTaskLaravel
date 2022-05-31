<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface OrderRepositoryInterface
{
    /**
     * @return Collection
     */
    public function all(): Collection;

    /**
     * @param int $id
     * @return Model
     */
    public function status(int $id): Model;

    /**
     * @param int $id
     * @return Collection
     */
    public function rooms(int $id) : Collection;
}
