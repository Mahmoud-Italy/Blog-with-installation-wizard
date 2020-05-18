<?php

namespace App\Models\Metas\Entities;

use Illuminate\Database\Eloquent\Model;
use DB;

class MetaTranslation extends Model
{
    protected $guarded = [];

    // get Any Attributes
    public static function getAttr($metable_id, $metable_type, $attr, $locale)
    {
        $obj = '';
        $metables = DB::table('metables')
        				->where('metable_id', $metable_id)
        				->where('metable_type', $metable_type)
        				->first();
    	$row = self::where('meta_id', $metables->meta_id)
                        ->where('locale', $locale)
                        ->first();
        if($row) {
            $obj = $row->$attr;
        }
    	return $obj;
    }

}
