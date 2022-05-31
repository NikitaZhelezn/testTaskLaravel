<?php

namespace App\Repositories;

use App\Http\Requests\Request;
use App\Models\Location;
use App\Repositories\Interfaces\LocationRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class LocationRepository implements LocationRepositoryInterface
{

    /**
     * @var Location
     */
    public $model;

    /**
     * @param Location $location
     */
    public function __construct(Location $location)
    {
        $this->model = $location;
    }

    /**
     * @return Location[]|\Illuminate\Database\Eloquent\Collection
     */
    public function all() :Collection
    {
        return $this->model::all();
    }

    /**
     * @param int $id
     * @return Collection
     */
    public function getById(int $id): Collection
    {
        return $this->model->find($id)->first();
    }

    /**
     * @return array
     */
    public function getAvailableBlocksList(): array
    {
        $newOrderStartTime = $newOrderStartTime ?? today();
        $newOrderFinishTime = $newOrderFinishTime ?? new \DateTime('tomorrow');

        $locations_statistics = [];

        foreach (Location::all() as $location) {
            $taken_blocks_query = $this->getTakenBlocksQuery($newOrderStartTime, $newOrderFinishTime);

            $location_blocks_quantity = DB::table('rooms')
                ->where('location_id', '=', $location->id)
                ->sum('blocks_quantity');

            $location_taken_blocks_quantity = $taken_blocks_query
                ->where('location_id', '=', 5)
                ->sum('blocks_quantity');

            $locations_statistics[] =
                ['location_id' => $location->id,
                    'location_city' => $location->city,
                    'location_blocks_quantity' => $location_blocks_quantity,
                    'location_blocks_available_quantity' => $location_blocks_quantity - $location_taken_blocks_quantity
                ];
        }
        return $locations_statistics;
    }

    /**
     * @param Request $request
     * @param Model $location
     * @return array
     */
    public function getAvailableBlocksForLocation(Request $request, Model $location): array
    {
        $location_rooms = DB::table('rooms')
            ->where('location_id', '=', $location->id)
            ->whereBetween('temperature', [$request->temperature - 2, $request->temperature + 2])
            ->get();

        $locations_statistics = [];

        foreach ($location_rooms as $room) {

            $taken_blocks_of_this_room = $this->getTakenBlocksQueryForRoom([
                'start_time' => $request->start_time,
                'finish_time' => $request->finish_time,
                'location_id' => $location->id,
                'temperature' => $request->temperature,
                'room_id' => $room->id
            ]);

            if ($taken_blocks_of_this_room->isEmpty()) {
                $locations_statistics[] =
                    ['location_id' => $location->id,
                        'location_city' => $location->city,
                        'room_id' => $room->id,
                        'temperature' => $room->temperature,
                        'location_blocks_quantity' => $room->blocks_quantity,
                        'location_blocks_available_quantity' => $room->blocks_quantity
                    ];
            } else {
                $locations_statistics[] =
                    ['location_id' => $location->id,
                        'location_city' => $location->city,
                        'room_id' => $room->id,
                        'temperature' => $room->temperature,
                        'location_blocks_quantity' => $room->blocks_quantity,
                        'location_blocks_available_quantity' => $room->blocks_quantity - $taken_blocks_of_this_room->blocks_quantity
                    ];
            }
        }
        return $locations_statistics;
    }

    /**
     * @param $newOrderStartTime
     * @param $newOrderFinishTime
     * @return Collection
     */
    protected function getTakenBlocksQuery($newOrderStartTime, $newOrderFinishTime): Collection
    {
        return DB::table('orders')
            ->select(['orders.id', 'order_room.blocks_quantity', 'order_room.room_id', 'orders.start_time',
                'locations.city', 'rooms.location_id', 'rooms.temperature'])
            ->join('order_room', 'order_room.order_id', '=', 'orders.id')
            ->join('rooms', 'rooms.id', '=', 'order_room.room_id')
            ->join('locations', 'locations.id', '=', 'rooms.location_id')
            ->whereBetween('start_time', [$newOrderStartTime, $newOrderFinishTime])
            ->oRwhereBetween('finish_time', [$newOrderStartTime, $newOrderFinishTime])
            ->get();
    }

    /**
     * @param array $filtersForQuery
     * @return mixed
     */
    protected function getTakenBlocksQueryForRoom(array $filtersForQuery)
    {
        $taken_blocks_for_room = $this->getTakenBlocksQuery($filtersForQuery['start_time'], $filtersForQuery['finish_time']);
        $taken_blocks_for_room = $this->addTemperatureFilterToBlocks($taken_blocks_for_room, $filtersForQuery['temperature']);
        $taken_blocks_for_room = $this->addLocationFilterBlocks($taken_blocks_for_room, $filtersForQuery['location_id']);
        $taken_blocks_for_room = $this->addRoomFilterBlocks($taken_blocks_for_room, $filtersForQuery['room_id']);
        return $taken_blocks_for_room;
    }

    /**
     * @param $taken_blocks_query
     * @param int $temperature
     * @return mixed
     */
    protected function addTemperatureFilterToBlocks($taken_blocks_query, int $temperature)
    {
        return $taken_blocks_query->whereBetween('temperature', [$temperature - 2, $temperature + 2]);
    }

    /**
     * @param $taken_blocks_query
     * @param int $locationId
     * @return mixed
     */
    protected function addLocationFilterBlocks($taken_blocks_query, int $locationId)
    {
        return $taken_blocks_query->where('location_id', '=', $locationId);
    }

    /**
     * @param $taken_blocks_query
     * @param int $roomId
     * @return mixed
     */
    protected function addRoomFilterBlocks($taken_blocks_query, int $roomId)
    {
        return $taken_blocks_query->where('id', '=', $roomId);
    }

}
