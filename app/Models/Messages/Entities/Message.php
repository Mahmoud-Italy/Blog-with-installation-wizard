<?php

namespace App\Models\Messages\Entities;

use Illuminate\Database\Eloquent\Model;
use Helper;

class Message extends Model
{
    protected $guarded = [];


    // fetch Data
    public static function fetchData($value)
    {
        $obj = self::orderBy('id','DESC');

            if(isset($value['status'])) {
                $status = $value['status'];
                ($status == 'trash') ? $obj->where('trash', true) : '';

                ($status == 'seen') ? $obj->where(['status'=> true, 'trash'=>false]) : '';
                ($status == 'unseen') ? $obj->where(['status'=> false, 'trash'=>false]) : '';
            }

        $obj = $obj->paginate($value['show'] ?? 10);
        return $obj;
    }

    // get email
    public static function getEmail($id)
    {
        $obj = '';
        $row = self::find($id);
        if($row) {
            $obj = $row->email;
        }
        return $obj;
    }


    // get id
    public static function getId($email)
    {
        $obj = '';
        $row = self::where('email', $email)->first();
        if($row) {
            $obj = $row->id;
        }
        return $obj;
    }
}
