<?php

namespace App\Models\Sliders\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Models\Sliders\Entities\Slider_translation;
use App\Models\Images\Entities\Image;
use Storage;
use Helper;
use Log;
use DB;

class Slider extends Model
{
    protected $guarded = [];

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function translations()
    {
    	return $this->belongsTo(Slider_translation::class, 'id', 'slider_id')->where('locale', Helper::locale());
    }

    /** Here we go.. **/

    // fetch Data
    public static function fetchData($value)
    {
        $obj = self::with('translations');

            if(isset($value['status'])) {
                $status = $value['status'];
                ($status == 'trash') ? $obj->where('trash', true) : '';

                ($status == 'active') ? $obj->where(['status'=> true, 'trash'=>false]) : '';
                ($status == 'inactive') ? $obj->where(['status'=> false, 'trash'=>false]) : '';
            }

        $obj = $obj->orderBy('id','ASC')->paginate(20);
        return $obj;
    }

    // Create or Update
    public static function createOrUpdate($id, $header, $file, $value)
    {
        try {
            // Use Transaction to Save Balance
            DB::beginTransaction();
                // Find Or New
                $row = (isset($id)) ? self::find($id) : new self;
                $row->user_id = Helper::whoIs($header['accesstoken'][0]);
                $row->position = $value['position'];
                $row->save();


                // Upload Image to amazon
                if(isset($file)) {
                    (isset($id)) 
                    ? Image::where('imageable_id', $id)
                            ->where('imageable_type', __NAMESPACE__.'\\'.class_basename(new self))
                            ->delete() 
                    : '';
                    $img = new Image;

                        // Using amazon storage
                        $filePath = 'images/'.date('Y-m-d-h-i-s').'.'.$file->getClientOriginalExtension();
                        Storage::disk('s3')->put($filePath, file_get_contents($file));

                    $img->url = $filePath;
                    $row->image()->save($img);
                }


                // You should deleted old row to regenerate new translation
                (isset($id)) ? Slider_translation::where('slider_id', $id)->delete() : '';
                // Add New Translation
                $trans = new Slider_translation;
                $trans->slider_id = $row->id;
                $trans->locale = $value['locale'];
                $trans->call_to_action = $value['call_to_action'];
                $trans->title = $value['title'];
                $trans->body = $value['body'];
                $trans->save();

                // Logs Situation
                if(isset($id)) { 
                    $action = 'Update Slider'; 
                    $log = 'Update slider: '.$value['title']; } 
                else { 
                    $action = 'Create Slider'; 
                    $log = 'Create new slider: '.$value['title']; }
                Log::create(['user_id'=>$row->user_id, 'action'=> $action, 'log'=> $log]);

            DB::commit();
            // End Commit of Transactions

            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
