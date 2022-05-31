<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Room;
use App\Models\Status;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(
            [
                LocationSeeder::class,
                StatusSeeder::class,
                TariffSeeder::class
            ]
        );

        User::factory()
            ->count(10)
            ->has(
                Order::factory()
                    ->count(rand(5, 15))
                    ->state(function (array $attributes, User $user) {
                        return [
                            'user_id' => $user->id,
                            'status_id' => Status::all()->pluck('id')->random()
                        ];
                    })
            )
            ->create();

        $this->call(
            [
                RoomSeeder::class,
            ]
        );
    }
}
