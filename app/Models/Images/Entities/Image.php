<?php

namespace App\Models\Images\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Models\AppSettings\Entities\AppSetting_manufacture;
use Storage;

class Image extends Model
{
    protected $guarded = [];

    public function imageable()
    {
        return $this->morphTo();
    }

    //
    public static function uploadFile($id, $file, $modelName)
    {
    	// remove old image if exists
    	(isset($id)) ? Image::where('imageable_id', $id)
                            ->where('imageable_type', $modelName)
                            ->delete() : '';


    	$row = new self;
        
                
                // Using amazon storage if exists
            if(AppSetting_manufacture::getManufacture('AWS S3 Storage', 'AWS_ACCESS_KEY_ID') != '#') {
                $filePath = 'images/'.date('Y-m-d-h-i-s').'.'.$file->getClientOriginalExtension();
                Storage::disk('s3')->put($filePath, file_get_contents($file));
            } else {
                $path = 'uploads/';
                $fileName = date('Y-m-d-h-i-s').'.'.$file->getClientOriginalExtension();
                $file->move(public_path().'/'.$path, $fileName);
                $filePath = $path.$fileName;;
            }

        $row->url = $filePath;
        return $row;
    }

}
