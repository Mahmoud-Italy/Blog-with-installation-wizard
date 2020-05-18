<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Helper;

class Slider extends JsonResource
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
            'image' => ($this->Image) ? Helper::getImagePath($this->Image->url) : NULL,

            // Content
            'title' => $this->translations->title,
            'body' => $this->translations->body,
            'position' => $this->position,
            'call_to_action' => $this->translations->call_to_action,

            // Dates
            'created_at' => ($this->created_at == $this->updated_at) 
                            ? 'Created <br/>'. $this->translations->created_at->diffForHumans()
                            : NULL,
            'updated_at' => ($this->created_at != $this->updated_at) 
                            ? 'Updated <br/>'. $this->translations->updated_at->diffForHumans()
                            : NULL,
            'deleted_at' => ($this->updated_at && $this->trash) 
                            ? 'Deleted <br/>'. $this->translations->updated_at->diffForHumans()
                            : NULL,

            // Status & Visibilty 
            'status' => boolval($this->status),
            'trash' => boolval($this->trash),
            'loading' => false,
        ];
    }
}
