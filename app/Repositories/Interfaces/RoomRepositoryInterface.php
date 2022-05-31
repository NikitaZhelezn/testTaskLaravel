<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface RoomRepositoryInterface
{
    /**
     * @param int $id
     * @return Model
     */
    public function location(int $id) : Model;

}
