<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\Order;
use App\Models\Room;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $this->runRooms();
       $this->runOrderRoom();
    }


    public function runRooms()
    {
        $locations = Location::all()->pluck('id');
        foreach($locations as $location) {
            Room::factory()
                ->count(rand(5, 15))
                ->state([
                    'location_id' => $location
                ])
                ->create();
        }
    }

    public function runOrderRoom()
    {
        $orders = Order::all();
        $rooms_id = Room::all()->pluck('id')->toArray();

        $block_capacity = config('global.block_capacity');

        foreach ($orders as $order) {
            DB::table('order_room')->insert([
                [
                    'order_id' => $order->id,
                    'room_id' => $rooms_id[array_rand($rooms_id)],
                    'blocks_quantity' => round($order->capacity / $block_capacity)
                ],
            ]);
        }
    }
}
