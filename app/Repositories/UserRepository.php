<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;

class UserRepository implements UserRepositoryInterface
{
    /**
     * @var User
     */
    protected $model;

    public function __construct(User $user)
    {
        return $this->model = $user;
    }

    /**
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->model::all();
    }

    /**
     * @param int $id
     * @return Model $user
     */
    public function getById(int $id): Model
    {
        return $this->model->find($id);
    }

    /**
     * @param $id
     * @return Collection
     */
    public function getUserProfileById($id): Collection
    {
        return $this->getById($id)->merge($this->orders($id));
    }

    /**
     * @param int $id
     * @return Collection
     */
    public function orders(int $id): Collection
    {
        return $this->getById($id)->orders()->get();
    }
}
