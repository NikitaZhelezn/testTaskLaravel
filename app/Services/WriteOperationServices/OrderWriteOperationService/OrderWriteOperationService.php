<?php

namespace App\Services\WriteOperationServices\OrderWriteOperationService;

use App\Exceptions\TransactionFailedException;
use App\Http\Requests\Request;
use App\Models\Order;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OrderWriteOperationService implements OrderWriteOperationServiceInterface
{

    protected $model;

    public function __construct(Order $order)
    {
        $this->model = $order;
    }

    /**
     * @param Request $request
     * @param array $blocks_in_rooms_to_order
     * @param array $blocks_price
     * @return Model $order
     * @throws TransactionFailedException
     */
    public function store(Request $request, array $blocks_in_rooms_to_order, array $blocks_price): Model
    {
        try {
            DB::beginTransaction();
            $order = $this->model->create([
                'code' => $this->createOrderCode(),
                'capacity' => $blocks_price['blocks_quantity'] * config('global.block_capacity'),
                'price' => $blocks_price['price'],
                'debt' => $blocks_price['price'],
                'start_time' => $request->start_time,
                'finish_time' => $request->finish_time,
                'user_id' => auth()->user()->id
            ]);
            $this->addOrderRoomToDatabase($blocks_in_rooms_to_order, $order->id);
            DB::commit();
            return $order;
        } catch (Exception $exception) {
            DB::rollBack();
            throw new TransactionFailedException();
        }
    }

    /**
     * @param $blocks_in_rooms_to_order
     * @param $order_id
     * @return void
     */
    protected function addOrderRoomToDatabase($blocks_in_rooms_to_order, $order_id)
    {
            foreach ($blocks_in_rooms_to_order as $room) {
                DB::table('order_room')->insert(
                    [
                        'order_id' => $order_id,
                        'room_id' => $room['room_id'],
                        'blocks_quantity' => $room['blocks_quantity']]);
            }
    }

    /**
     * @return false|string
     */
    protected function createOrderCode()
    {
        $code = substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
            ceil(12 / strlen($x)))), 1, 12);

        $orders_with_such_code = $this->model::all()->where('code', '=', $code);
        if(!$orders_with_such_code->isEmpty()) $code = $this->createOrderCode();
        return $code;
    }

}
