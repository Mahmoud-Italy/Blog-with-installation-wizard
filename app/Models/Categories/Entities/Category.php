<?php

namespace App\Models\Categories\Entities;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use App\Models\Users\Entities\User;
use App\Models\Metas\Entities\Meta;
use App\Models\Metas\Entities\Metable;
use App\Models\Metas\Entities\MetaTranslation;
use App\Models\Images\Entities\Image;
use App\Models\Posts\Entities\Post;
use App\Models\Categories\Entities\CategoryTranslation;
use Helper;
use Log;
use DB;

class Category extends Model implements TranslatableContract
{
    use Translatable;
    public $translatedAttributes = ['slug', 'name'];
    
    protected $guarded = [];

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function meta()
    {
        return $this->morphMany(Metable::class, 'metable');
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'cat_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function metables()
    {
        return $this->belongsTo(Metable::class, 'id', 'metable_id')
                                ->where('metable_type', __NAMESPACE__.'\\'.class_basename(new self));
    }

    public function metaScope($meta_id, $locale)
    {
        return MetaTranslation::where('meta_id', $meta_id)
                                ->where('locale', $locale)
                                ->first();
    }

    public function childs() {
        return $this->hasMany(__NAMESPACE__.'\\'.class_basename(new self), 'parent_id', 'id'); 
    }

    public function parents()
    {
        return $this->belongsTo(CategoryTranslation::class, 'parent_id', 'category_id')->where('locale', Helper::locale());
    }






    // fetch Main all
    public static function fetchMainAll($header)
    {
        $obj = self::whereNULL('parent_id');

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

    // fetch Sub all
    public static function fetchSubAll($header)
    {
        $obj = self::whereNOTNULL('parent_id')
                        ->where('parent_id', self::getId($category));

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

    // fetch Main Categories 
    public static function fetchMainData($header, $value)
    {
        $obj = self::whereNULL('parent_id');

            if($header['accesstoken'][0]) {
                $user_id = Helper::whoIs($header['accesstoken'][0]);
                $role_id = User::getRoleId($user_id);
                if($role_id != 5 && $role_id != 4 && role_id != 1) {
                    $obj = $obj->where('user_id', $user_id);
                }
            }

            if(isset($value['search'])) {
                $obj = $obj->whereTranslationLike('name', '%'.$value['search'].'%');
            }

            if(isset($value['order_by'])) {
                $obj = $obj->orderByTranslation('name', $value['order_by']);
            }
            
            if(isset($value['status'])) {
                $status = $value['status'];
                ($status == 'trash') ? $obj->where('trash', true) : '';

                ($status == 'active') ? $obj->where(['status'=> true, 'trash'=>false]) : '';
                ($status == 'inactive') ? $obj->where(['status'=> false, 'trash'=>false]) : '';
            }

        $obj = $obj->orderBy('id','DESC')->paginate($value['show'] ?? 10);
        return $obj;
    }


    // fetch Sub Categories
    public static function fetchSubData($header, $value, $category)
    {
        $obj = self::translatedIn('en')->where('parent_id', self::getId($category));

            if($header['accesstoken'][0]) {
                $user_id = Helper::whoIs($header['accesstoken'][0]);
                $role_id = User::getRoleId($user_id);
                if($role_id != 5 && $role_id != 4 && role_id != 1) {
                    $obj = $obj->where('user_id', $user_id);
                }
            }

            if(isset($value['search'])) {
                $obj = $obj->whereTranslationLike('name', '%'.$value['search'].'%');
            }

            if(isset($value['order_by'])) {
                $obj = $obj->orderByTranslation('name', $value['order_by']);
            }

            if(isset($value['status'])) {
                $status = $value['status'];
                ($status == 'trash') ? $obj->where('trash', true) : '';

                ($status == 'active') ? $obj->where(['status'=> true, 'trash'=>false]) : '';
                ($status == 'inactive') ? $obj->where(['status'=> false, 'trash'=>false]) : '';
            }

        $obj = $obj->orderBy('id','DESC')->paginate($value['show'] ?? 10);
        return $obj;
    }


    // Create or Update
    public static function createOrUpdate($id, $header, $file, $value)
    {
        try {

            // begin Transaction between tables
            DB::beginTransaction();
               
                // 1. find Or New
                $row               = (isset($id)) ? self::find($id) : new self;
                $row->user_id      = ($header['accesstoken'][0]) ? Helper::whoIs($header['accesstoken'][0]) : NULL;
                $row->parent_id    = $value['parent_id'];
                $row->fill([
                    'en' => [
                        'slug'     => Helper::isSlug($value['slug']),
                        'name'     => $value['name']
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
                    $action     = 'Update Category'; 
                    $log        = 'Update category: '.$value['name']; 
                } else { 
                    $action     = 'Create Category'; 
                    $log        = 'Create new category: '.$value['name']; 
                }
                Log::create([
                    'user_id'   => $row->user_id, 
                    'action'    => $action, 
                    'log'       => $log
                ]);

            DB::commit();
            // End Commit of Transaction

            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }


    // Get Parent Id
    public static function getParentId($category_id)
    {
       $obj = $category_id;
       $row = self::where('id', $category_id)->whereNOTNULL('parent_id')->first();
       if($row) {
            $obj = $row->parent_id;
       }
       return $obj;
    }

    // get Sub id
    public static function getSubId($category_id)
    {
       $obj = NULL;
       $row = self::where('id', $category_id)->whereNULL('parent_id')->first();
       if($row) {
            $obj = $row->parent_id;
       }
       return $obj;
    }

    // get category name
    public static function getName($id)
    {
        $obj = '';
        $row = self::find($id);
        if($row) {
            $obj = $row->name;
        }
        return $obj;
    }

    // get category status
    public static function getStatus($id)
    {
        $obj = '';
        $row = self::find($id);
        if($row) {
            $obj = $row->status;
        }
        return $obj;
    }

    // get category id
    public static function getId($category)
    {
        $obj = '';
        $row = self::whereTranslation('slug', $category)->first();
        if($row) {
            $obj = $row->id;
        }
        return $obj;
    }

    // get category slug
    public static function getSlug($id)
    {
        $obj = '';
        $row = self::find($id);
        if($row) {
            $obj = $row->slug;
        }
        return $obj;
    }

}
