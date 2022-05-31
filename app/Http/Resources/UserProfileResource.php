<?php

namespace App\Http\Resources;

use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

class UserProfileResource extends JsonResource
{

    protected $userRepository;

    public function __construct($resource)
    {
        $this->userRepository = App::make(UserRepositoryInterface::class);
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
            'id'=> $this->id,
            'name'=> $this->name,
            'email'=> $this->email,
            'orders'=> new OrderResourceCollection($this->userRepository->orders($this->id))
//            'name'=> $this->name,

        ];
    }
}
