<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Helper;

class Page extends JsonResource
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
            
            // Image
            'image' => ($this->Image) ? Helper::getImagePath($this->Image->url) : NULL,

            // Content
            'slug' => $this->slug,
            'title' => $this->title,
            'body' => $this->body,

            // Metas
            // 'meta_title' => ($this->metables)  
            //     ? $this->metaScope($this->metables->meta_id, Helper::locale())->meta_title 
            //     : NULL,
            // 'meta_keywords' => ($this->metables) 
            //     ? $this->metaScope($this->metables->meta_id, Helper::locale())->meta_keywords 
            //     : NULL,
            // 'meta_description' => ($this->metables) 
            //     ? $this->metaScope($this->metables->meta_id, Helper::locale())->meta_description 
            //     : NULL,

            // Author
            'user_id' => $this->user_id,
            'author' => ($this->user) ? $this->user->name : NULL,
            'role' => ($this->user) ? $this->user->roles->role : NULL,

            // Dates
            'created_at' => ($this->created_at == $this->updated_at) 
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
            'status' => boolval($this->status),
            'trash' => boolval($this->trash),
            'loading' => false,
        ];
    }
}
