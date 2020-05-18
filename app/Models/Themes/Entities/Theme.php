<?php

namespace App\Models\Themes\Entities;

use Illuminate\Database\Eloquent\Model;
use Helper;
use Log;
use DB;

class Theme extends Model
{
    protected $guarded = [];


    /** Here we go.. **/

    // fetch data
    public static function fetchData($value='')
    {
    	$obj = self::orderBy('id','DESC');

            if(isset($value['status'])) {
                $status = $value['status'];
                ($status == 'trash') ? $obj->where('trash', true) : '';

                ($status == 'active') ? $obj->where(['status'=> true, 'trash'=>false]) : '';
                ($status == 'inactive') ? $obj->where(['status'=> false, 'trash'=>false]) : '';
            }

        $obj = $obj->paginate(20);
        return $obj;
    }

    // public static function createOrUpdate($id, array $headers, array $values)
    // {
    //     try {
    //         // Use Transaction to Save Balance
    //         DB::beginTransaction();
    //         	// Find Or New
    //             $row = (isset($id)) ? self::find($id) : new self;
    //             $row->user_id = Helper::whoIs($headers['accessToken']);
    //             $row->save();
            
    //             // You should deleted old row to regenerate new translation
    //             (isset($id)) ? Tag_translation::where('tag_id', $id)->delete() : '';

    //             // Add New Translation
    //             $trans = new Tag_translation;
    //             $val->tag_id = $row->id;
    //             $val->locale = 'en';
    //             $val->tag = strtolower(str_replace([' ','-'],['_','_'],$values['tag']));
    //             $val->save();
    //         DB::commit();
    //         // End Commit of Transactions

    //         return true;
    //     } catch (\Exception $e) {
    //         return $e->getMessage();
    //     }
    // }
}
