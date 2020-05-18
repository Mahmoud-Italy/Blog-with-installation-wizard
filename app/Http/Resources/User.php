<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Helper;

class User extends JsonResource
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
            'image' => ($this->Image) ? Helper::getImagePath($this->Image->url) : Helper::getAvatar($this->id),

            // Content
            'name' => $this->name,
            'email' => $this->email,
            'posts' => ($this->posts) ? $this->posts->count() : 0,

            // Roles
            'role_id' => $this->role_id,
            'role' => $this->roles->role,

            // Create By
            'created_by' => '',


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

            // Status & Visibilty 
            'suspended' => boolval($this->suspended),
            'trash' => boolval($this->trash),
            'loading' => false,

        ];
    }
}
