<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Helper;

class Log extends JsonResource
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

            // Log
            'log' => $this->log,

            // Action
            'action' => $this->action,

            // User
            'user_id' => ($this->user_id) ? $this->user_id : NULL,
            'encrypt_user_id' => ($this->user_id) ? encrypt($this->user_id) : NULL,
            'username' => ($this->user_id) ? $this->user->name : NULL,
            'avatar' => ($this->user_id) ? Helper::getAvatar($this->user_id) : NULL,

            // Dates
            'created_at' => ($this->created_at) 
                            ? 'Created <br/>'. $this->created_at->diffForHumans()
                            : NULL,
            // Loading
            'loading' => false,
        ];
    }
}
