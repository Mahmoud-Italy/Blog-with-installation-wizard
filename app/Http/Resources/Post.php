<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Categories\Entities\Category;
use App\Models\Tags\Entities\Tag;
use Helper;

class Post extends JsonResource
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
            'image' => ($this->Image) 
                            ? Helper::getImagePath($this->Image->url)
                            : \Request::root().'/img/default-post.jpg', // default post image

            // Content
            'slug_url' => $this->slug.'-'.$this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'body' => $this->body,

            // Meta
            'meta_title' => $this->metaScope($this->metables->meta_id, Helper::locale())->meta_title,
            'meta_keywords' => $this->metaScope($this->metables->meta_id, Helper::locale())->meta_keywords,
            'meta_description' => $this->metaScope($this->metables->meta_id, Helper::locale())->meta_description,

            // Author
            'user_id' => $this->user_id,
            'encrypt_user_id' => encrypt($this->user_id),
            'author' => ($this->user) ? $this->user->name : NULL,
            'role' => ($this->user) ? $this->user->roles->role : NULL,

            // Tags
            'tags' => Tag::getTaggables($this->id, Helper::locale()),
            //'tags' => $this->tags,

            // Additional values
            'comment' => 0,
            'views' => $this->views->count(),
            'estimate_reading_time' => $this->estimate_reading_time($this->body),

            // Category
            'category_id' => $this->cat_id,
            'category_slug' => $this->category->slug,
            'category' => $this->category->name,

            // Main Category
            'cat_id' => Category::getParentId($this->cat_id),
            'cat_slug' => Category::getSlug(Category::getParentId($this->cat_id)),
            'cat_name' => Category::getName(Category::getParentId($this->cat_id)),

            // Sub Category
            'subcat_id' => Category::getSubId($this->cat_id) ?? NULL,
            'subcat_slug' => Category::getSlug(Category::getSubId($this->cat_id)),
            'subcat_name' => Category::getName(Category::getSubId($this->cat_id)),

            // Dates
            'date' => $this->created_at->diffForHumans(),
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
            'status' => boolval($this->status),
            'trash' => boolval($this->trash),
            'loading' => false,
        ];
    }
}
