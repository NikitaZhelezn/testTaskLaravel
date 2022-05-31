<?php

namespace App\Http\Resources;

use App\Repositories\Interfaces\OrderRepositoryInterface;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

class OrderResource extends JsonResource
{
    protected $orderRepository;

    public function __construct($resource)
    {
        $this->orderRepository = App::make(OrderRepositoryInterface::class);
        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'=> $this->id,
            'code'=> $this->code,
            'start_time'=> $this->start_time,
            'finish_time'=> $this->finish_time,
            'status_id'=> $this->orderRepository->status($this->id)->id,
            'status'=> $this->orderRepository->status($this->id)->name,
        ];
    }
}
