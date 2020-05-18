<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AppSetting extends JsonResource
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

            'app_name' => $this->app_name,
            'icon' => $this->icon,

            'setup' => boolval($this->setup),
            'status' => boolval($this->status),
            'trash' => boolval($this->trash),
        ];
    }
}
