<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Helper;

class Tag extends JsonResource
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

            // Tag
            'tag' => $this->tag,

            // Dates
            'created_at' => ($this->created_at == $this->updated_at) 
                                ? 'Created <br/>'. $this->created_at->diffForHumans()
                                : NULL,
            'updated_at' => ($this->created_at != $this->updated_at) 
                                ? 'Updated <br/>'. $this->updated_at->diffForHumans()
                                : NULL,
            'deleted_at' => ($this->updated_at && $this->trash) 
                                ? 'Deleted <br/>'. $this->updated_at->diffForHumans()
                                : NULL,
            'timestamp' => $this->created_at,


            // Status & Visibility
            'posts' => ($this->posts) ? $this->posts->count() : 0,
            'status' => boolval($this->status),
            'trash' => boolval($this->trash),
            'loading' => false,
        ];
    }
}
