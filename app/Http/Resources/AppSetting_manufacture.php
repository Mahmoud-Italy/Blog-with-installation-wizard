<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AppSetting_manufacture extends JsonResource
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
            'encrypt_id' => $this->id,

            // App
            'app_name' => $this->app_name,
            'icon' => $this->icon,

            // Content
            'index' => $this->index,
            'value' => $this->value,
            'loading' => false,
        ];
    }
}
