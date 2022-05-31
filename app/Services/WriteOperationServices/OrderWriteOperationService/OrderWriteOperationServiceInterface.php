<?php

namespace App\Services\WriteOperationServices\OrderWriteOperationService;

use App\Exceptions\TransactionFailedException;
use App\Http\Requests\Request;
use Illuminate\Database\Eloquent\Model;

interface OrderWriteOperationServiceInterface
{
    /**
     * @param Request $request
     * @param array $blocks_in_rooms_to_order
     * @param array $blocks_price
     * @return Model $order
     * @throws TransactionFailedException
     */
    public function store(Request $request, array $blocks_in_rooms_to_order, array $blocks_price) : Model;

}
