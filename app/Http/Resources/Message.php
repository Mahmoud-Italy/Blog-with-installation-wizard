<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Message extends JsonResource
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
            
            // Content
            'name' => $this->name,
            'email' => $this->email,
            'message' => $this->message,


            // Dates
            'created_at' => ($this->created_at) 
                            ? 'Created <br/>'. $this->created_at->diffForHumans()
                             : NULL,
            'updated_at' => ($this->updated_at != $this->created_at) 
                            ? 'Updated <br/>'. $this->updated_at->diffForHumans()
                            : NULL,
            'deleted_at' => ($this->updated_at && $this->trash) 
                            ? 'Deleted <br/>'. $this->updated_at->diffForHumans()
                            : NULL,
            'timestamp' => $this->created_at,

            // Status & Visibilty
            'seen' => boolval($this->status),
            'trash' => boolval($this->trash),
            'loading' => false,
        ];
    }
}
