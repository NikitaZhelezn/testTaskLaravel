<?php

namespace App\Repositories;

use App\Models\Room;
use App\Repositories\Interfaces\RoomRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class RoomRepository implements RoomRepositoryInterface
{
    protected $model;

    public function __construct(Room $room)
    {
        $this->model = $room;
    }

    /**
     * @param int $id
     * @return Model
     */
    public function location(int $id): Model
    {
        return $this->model->find($id)->location()->first();

    }

}
