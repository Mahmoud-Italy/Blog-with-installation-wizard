<?php

namespace App\Models\Metas\Entities;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use App\Models\Posts\Entities\Post;
use Helper;

class Meta extends Model
{
    use Translatable;
    public $translatedAttributes = ['slug', 'meta_title', 'meta_keywords', 'meta_description'];

    protected $guarded = [];

    public function posts()
    {
        return $this->morphedByMany(Post::class, 'metable');
    }

    //
}
