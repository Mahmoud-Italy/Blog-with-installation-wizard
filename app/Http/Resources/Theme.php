<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Theme extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'image' => $this->image,
            'name' => $this->name,

            'active' => boolval($this->active),
            'status' => boolval($this->status),
            'loading' => false,
        ];
    }
}
