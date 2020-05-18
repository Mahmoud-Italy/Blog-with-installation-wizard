<?php

namespace App\Models\Users\Entities;

use Illuminate\Database\Eloquent\Model;

class Activation extends Model
{
    protected $guarded = [];

    // Completed
    public static function completed($value='')
    {
    	$row = self::where('user_id', $value['user_id'])->first();
    	$row->code = NULL;
    	$row->completed = true;
    	$row->completed_at = date('Y-m-d H:i:s');
    	$row->save();
    }
}
