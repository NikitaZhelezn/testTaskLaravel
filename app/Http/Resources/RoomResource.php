<?php

namespace App\Http\Resources;

use App\Repositories\Interfaces\RoomRepositoryInterface;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

class RoomResource extends JsonResource
{
    protected $roomRepository;

    public function __construct($resource)
    {
        $this->roomRepository = App::make(RoomRepositoryInterface::class);
        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'temperature' => $this->temperature,
            'blocks_quantity' => $this->blocks_quantity,
            'location_id' => $this->roomRepository->location($this->id)->id,
            'location_city' => $this->roomRepository->location($this->id)->city
        ];
    }
}
