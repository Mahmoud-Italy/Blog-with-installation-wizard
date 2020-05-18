<?php

namespace App\Models\Media\Entities;

use Illuminate\Database\Eloquent\Model;
use Storage;
use Helper;
use Log;

class Media extends Model
{
    protected $guarded = [];

    /** Here we go.. **/
    
    // Create Or Update
    public static function createOrUpdate($header, $file='')
    {
        try {
        	$row = new self;
            $row->user_id = Helper::whoIs($header['accesstoken'][0]);
        		$filePath = 'images/'.date('Y-m-d-h-i-s').'.'.$file->getClientOriginalExtension();
                Storage::disk('s3')->put($filePath, file_get_contents($file));
        	$row->file = $filePath;
            $row->mimeType = \File::mimeType($file);
            $row->size = self::filesize_formatted($file);
        	$row->save();

            // Logs Situation
            Log::create(['user_id'=>$row->user_id, 'action'=> 'Upload Media', 'log'=> 'Upload new media '.$row->mimeType]);
            
            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    // Total size format
    public static function filesize_formatted($path)
    {
        $size = filesize($path);
        $units = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $power = $size > 0 ? floor(log($size, 1024)) : 0;
        return number_format($size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
    }

    // get Files Size
    public static function getFileSize($value='')
    {
        $size = [];
        $rows = self::all();
        foreach ($rows as $row) {
            $type = explode(' ',$row->size)[1];
                if($type == 'KB') {
                    $size[] = explode(' ',$row->size)[0] * 1000;
                } else if ($type == 'MB') {
                    $size[] = explode(' ',$row->size)[0] * 1000000;
                }
        }
        return self::calculate_size(array_sum($size));
    }

    // Calculate Size
    public static function calculate_size($size='')
    {
        $units = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $power = $size > 0 ? floor(log($size, 1024)) : 0;
        return number_format($size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
    }
}
