<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Helper;

class Category extends JsonResource
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

            // Parent
            'parent_id' => $this->parent_id,
            'parent_name' => ($this->parents) ? $this->parents->name : 'No Parent',
            'childs' => ($this->childs) ? self::collection($this->childs) : NULL,

            // Name
            'slug' => $this->slug,
            'name' => $this->name,

            // Meta 
            'meta_title' => $this->metaScope($this->metables->meta_id, Helper::locale())->meta_title,
            'meta_keywords' => $this->metaScope($this->metables->meta_id, Helper::locale())->meta_keywords,
            'meta_description' => $this->metaScope($this->metables->meta_id, Helper::locale())->meta_description,

            // Count Subcategories & Posts
            'sub' => $this->childs->count(),
            'posts' => ($this->posts) ? $this->posts->count() : 0,

            // Author
            'user_id' => $this->user_id,
            'author' => ($this->user) ? $this->user->name : NULL,
            'role' => ($this->user) ? $this->user->roles->role : NULL,


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

            // Status & Visibilty 
            'position' => $this->position,
            'status' => boolval($this->status),
            'trash' => boolval($this->trash),
            'loading' => false,
        ];
    }
}
