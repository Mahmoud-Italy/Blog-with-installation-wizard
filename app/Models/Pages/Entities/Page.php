<?php

namespace App\Models\Pages\Entities;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use App\Models\Users\Entities\User;
use App\Models\Metas\Entities\Meta;
use App\Models\Metas\Entities\Metable;
use App\Models\Images\Entities\Image;
use Illuminate\Database\Eloquent\Model;
use Helper;
use Log;
use DB;

class Page extends Model implements TranslatableContract
{   
    use Translatable;
    public $translatedAttributes = ['slug', 'title', 'body'];

    protected $guarded = [];

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function meta()
    {
        return $this->morphMany(Metable::class, 'metable');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    } 

    public function metables()
    {
        return $this->belongsTo(Metable::class, 'id', 'metable_id');
    }



    // fetch All
    public static function fetchAll($header)
    {
        $obj = self::with('user');

            if($header['accesstoken'][0]) {
                $user_id = Helper::whoIs($header['accesstoken'][0]);
                $role_id = User::getRoleId($user_id);
                if($role_id != 5 && $role_id != 4 && $role_id != 1) {
                    $obj = $obj->where('user_id', $user_id);
                }
            }

        $obj = $obj->get();
        return $obj;
    }

    // fetch Data
    public static function fetchData($header, $value)
    {
        $obj = self::translatedIn('en');

            if($header['accesstoken'][0]) {
                $user_id = Helper::whoIs($header['accesstoken'][0]);
                $role_id = User::getRoleId($user_id);
                if($role_id != 5 && $role_id != 4 && $role_id != 1) {
                    $obj = $obj->where('user_id', $user_id);
                }
            }

            if(isset($value['search'])) {
                $obj = $obj->whereTranslationLike('title', '%'.$value['search'].'%');
            }

            if(isset($value['order_by'])) {
                $obj = $obj->orderByTranslation('title', $value['order_by']);
            }

            if(isset($value['status'])) {
                $status = $value['status'];
                ($status == 'trash') ? $obj->where('trash', true) : '';

                ($status == 'active') ? $obj->where(['status'=> true, 'trash'=>false]) : '';
                ($status == 'inactive') ? $obj->where(['status'=> false, 'trash'=>false]) : '';
            }

        $obj = $obj->orderBy('id', 'DESC')->paginate($value['show'] ?? 10);
        return $obj;
    }


    
    // Create Or Update
    public static function createOrUpdate($id, $header, $file, $value)
    {
        try {
            // Begin Transaction between tables
            DB::beginTransaction();

                // 1. find Or New
                $row            = (isset($id)) ? self::find($id) : new self;
                $row->user_id   = ($header['accesstoken'][0]) ? Helper::whoIs($header['accesstoken'][0]) : NULL;
                $row->fill([
                    'en' => [
                        'slug'  => Helper::isSlug($value['slug']),
                        'title' => $value['title'],
                        'body'  => $value['body']
                    ],
                ]);
                $row->save();
                $row->touch(); // touch updated_at

               // 2. upload Image if exist
                if(isset($file)) {
                    $img = Image::uploadFile($id, $file, __NAMESPACE__.'\\'.class_basename(new self));
                    $row->image()->save($img);
                }

                // 3. morphTo Metables
                $meta = new Meta;
                $meta->fill([
                    'en' => [
                        'meta_title'       => $value['meta_title'],
                        'meta_keywords'    => $value['meta_keywords'],
                        'meta_description' => $value['meta_description']
                    ],
                ]);
                $meta->save();

                (isset($id)) ? Meta::where('id', $row->metables->meta_id)->delete() : NULL; // Delete Meta & Transatcion row
                $row->meta()->delete(); // Delete metables row

                $row->meta()->create(['meta_id'=>$meta->id]); // Insert into Metables
                $row->metables()->associate($meta); // Insert into Meta & Transations

                // 4. save Logs
                if(isset($id)) { 
                    $action    = 'Update Page'; 
                    $log       = 'Update page: '.$value['title']; 
                } else { 
                    $action    = 'Create Page'; 
                    $log       = 'Create new page: '.$value['title']; 
                }
                Log::create([
                    'user_id'  => $row->user_id,
                    'action'   => $action, 
                    'log'      => $log
                ]);


            DB::commit();
            // End Commit of Transaction

            return true;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    // get page name
    public static function getName($id)
    {
        $obj = '';
        $row = self::find($id);
        if($row) {
            $obj = $row->title;
        }
        return $obj;
    }

    // get page status
    public static function getStatus($id)
    {
        $obj = '';
        $row = self::find($id);
        if($row) {
            $obj = $row->status;
        }
        return $obj;
    }

    // get page id
    public static function getId($page)
    {
        $obj = '';
        $row = self::whereTranslation('slug', $page)->first();
        if($row) {
            $obj = $row->id;
        }
        return $obj;
    }

}
