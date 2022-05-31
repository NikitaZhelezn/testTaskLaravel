<?php

namespace App\Repositories;

use App\Models\Order;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class OrderRepository implements OrderRepositoryInterface
{
    /**
     * @var Order
     */
    protected $model;

    public function __construct(Order $order)
    {
        $this->model = $order;
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
     * @return Model
     */
    public function status(int $id): Model
    {
        return $this->model->find($id)->status()->first();
    }

    /**
     * @param int $id
     * @return Collection
     */
    public function rooms(int $id): Collection
    {
        return $this->model->find($id)->rooms()->get();
    }


}
