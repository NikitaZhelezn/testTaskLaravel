<?php

namespace App\Repositories\Interfaces;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface UserRepositoryInterface
{
    /**
     * @return Collection
     */
    public function all() : Collection;

    /**
     * @param int $id
     * @return Model $user
     */
    public function getById(int $id) : Model;

    /**
     * @param int $id
     * @return Collection
     */
    public function getUserProfileById(int $id): Collection;

    /**
     * @param int $id
     * @return Collection
     */
    public function orders(int $id) : Collection;
}
