<?php

namespace App\Models\Logs\Entities;

use App\Models\Users\Entities\User;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    // fetch Data
    public static function fetchData($value)
    {
        return self::orderBy('id','DESC')->paginate($value['show'] ?? 10);
    }

}
