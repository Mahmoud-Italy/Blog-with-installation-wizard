<?php

namespace App\Models\AppSettings\Entities;

use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    protected $guarded = [];

    /** Here we go.. **/

    // fetch Data
    public static function fetchData($value='')
    {
        $obj = self::orderBy('app_name','ASC');

            if(isset($value['status'])) {
                $status = $value['status'];
                ($status == 'trash') ? $obj->where('trash', true) : '';

                ($status == 'active') ? $obj->where(['status'=> true, 'trash'=>false]) : '';
                ($status == 'inactive') ? $obj->where(['status'=> false, 'trash'=>false]) : '';
            }

        $obj = $obj->paginate(20);
        return $obj;
    }

    // Create Or Update
    public static function createOrUpdate($id, $formatted_array)
    {
        try {
            
	    	$row = (isset($id)) ? self::find($id) : null;
		    if ($row === null) {
		        self::create($formatted_array);
		    } else {
		        $row->update($formatted_array);
		    }
	    
            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    // get App New
    public static function getAppName($app_id)
    {
        $obj = '';
        $row = self::find($app_id);
        if($row) {
            $obj = $row->app_name;
        }
       return $obj;
    }

    // get App Id
    public static function getAppId($app_name)
    {
        $obj = '';
        $row = self::where('app_name',$app_name)->first();
        if($row) {
            $obj = $row->id;
        }
       return $obj;
    }

    // is app Set up
    public static function isSetup($app_name)
    {
        $obj = false;
        $row = self::where('app_name', $app_name)->where(['setup'=>true, 'status'=>true, 'trash'=>false])->first();
        if($row) {
            $obj = true;
        }
        return $obj;
    }

}
