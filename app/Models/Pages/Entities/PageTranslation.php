<?php

namespace App\Models\Pages\Entities;

use Illuminate\Database\Eloquent\Model;

class PageTranslation extends Model
{
    protected $guarded = [];

    public function Pages()
    {
        return $this->hasMany('App\Models\Pages\Entities\Page');
    }

    /** Here we go.. **/

    // get page id by title
    public static function getId($page)
    {
        $obj = NULL;
        $row = self::where('title', $page)->first();
        if($row) {
            $obj = $row->page_id;
        }
        return $obj;
    }
}
