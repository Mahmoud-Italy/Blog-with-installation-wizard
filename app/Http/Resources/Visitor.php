<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Visitor extends JsonResource
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
            'encrypt_id' => encrypt($this->id),


            'ip' => $this->ip,
            'country' => $this->country,
            'user_agent' => $this->user_agent,
            'view_at' => $this->view_at,

            'loading' => false,
        ];
    }
}
