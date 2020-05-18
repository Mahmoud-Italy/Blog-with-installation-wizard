<?php

namespace App\Models\AppSettings\Entities;

use App\Models\AppSettings\Entities\AppSetting;
use Illuminate\Database\Eloquent\Model;

class AppSetting_manufacture extends Model
{
    protected $guarded = [];

    public function setting()
    {
        return $this->belongsTo(AppSetting::class, 'app_id');
    }
    //

    public static function fetchData($value='')
    {
        $obj = self::where('app_id', decrypt($value['app_id']));

            if(isset($value['status'])) {
                $status = $value['status'];
                ($status == 'trash') ? $obj->where('trash', true) : '';

                ($status == 'active') ? $obj->where(['status'=> true, 'trash'=>false]) : '';
                ($status == 'inactive') ? $obj->where(['status'=> false, 'trash'=>false]) : '';
            }

        $obj = $obj->orderBy('id','ASC')->paginate(20);
        return $obj;
    }

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



    // get Manufacture
    public static function getManufacture($app_name, $manufacture)
    {
        $obj = NULL;
        $app_id = AppSetting::getAppId($app_name);
        $row = self::where(['app_id'=>$app_id, 'index'=>$manufacture, 'status'=>true, 'trash'=>false])->first();
        if($row) {
            $obj = $row->value;
        }
        return $obj;
    }
}
