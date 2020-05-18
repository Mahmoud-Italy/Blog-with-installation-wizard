<?php

namespace App\Models\Posts\Entities;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use App\Models\Users\Entities\User;
use App\Models\Metas\Entities\Meta;
use App\Models\Metas\Entities\MetaTranslation;
use App\Models\Metas\Entities\Metable;
use App\Models\Tags\Entities\Tag;
use App\Models\Tags\Entities\Taggable;
use App\Models\Tags\Entities\TagTranslation;
use App\Models\Posts\Entities\PostTranslation;
use App\Models\Images\Entities\Image;
use App\Models\Categories\Entities\Category;
use App\Models\Categories\Entities\CategoryTranslation;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Helper;
use Log;
use DB;

class Post extends Model implements TranslatableContract
{   
    use Translatable;
    public $translatedAttributes = ['slug', 'title', 'body'];
    
    protected $guarded = [];
    

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    } 

    public function category()
    {
        return $this->belongsTo(Category::class, 'cat_id');
    } 

    public function cateogries()
    {
        return $this->hasMany('App\Models\Categories\Entitie\Category');
    }

    public function categoryScope($cat_id, $locale)
    {
        return CategoryTranslation::where('cat_id', $cat_id)
                            ->where('locale', $locale)
                            ->first();
    }

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    } 

    public function meta()
    {
        return $this->morphMany(Metable::class, 'metable');
    }

    public function tags()
    {
        return $this->morphMany(Taggable::class, 'taggable');
    }

    public function metables()
    {
        return $this->belongsTo(Metable::class, 'id', 'metable_id')
                                ->where('metable_type', __NAMESPACE__.'\\'.class_basename(new self));;
    }

    public function metaScope($meta_id, $locale)
    {
        return MetaTranslation::where('meta_id', $meta_id)
                            ->where('locale', $locale)
                            ->first();
    }

    public function tagScope($tag_id, $locale)
    {
        return TagTranslation::where('tag_id', $tag_id)
                            ->where('locale', $locale)
                            ->pluck('tag');
    }

    public function estimate_reading_time($body)
    {
        return PostTranslation::estimate_reading_time($body, true);
    }

    public function views()
    {
        return $this->hasMany('App\Models\Posts\Entities\Post_view');
    }

    public function scopeOrderByViews($query)
    {
        $query->leftJoin('post_views as v', 'v.post_id', '=', 'posts.id')
                        ->selectRaw('posts.*, count(v.id) as aggregate')
                        ->groupBy('posts.id')
                        ->orderBy('aggregate', 'desc');
    }




    /** Here we go.. **/

    // fetch All
    public static function fetchAll($header)
    {
        $obj = self::with('user');

            $user_id = Helper::whoIs($header['accesstoken'][0]);
            $role_id = User::getRoleId($user_id);
            if($role_id != 5 && $role_id != 4) {
                $obj = $obj->where('user_id', $user_id);
            }

        $obj = $obj->get();
        return $obj;
    }


    // fetch Data
    public static function fetchData($header, $value='')
    {
        DB::enableQueryLog(); // Enable query log

        $obj = self::translatedIn('en');

            if($header['accesstoken'][0]) {
                $user_id = Helper::whoIs($header['accesstoken'][0]);
                $role_id = User::getRoleId($user_id);
                if($role_id != 5 && $role_id != 4) {
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

                ($status == 'published') ? $obj->where(['status'=> true, 'trash'=>false]) : '';
                ($status == 'draft') ? $obj->where(['status'=> false, 'trash'=>false]) : '';
            }

            if(isset($value['filter_by'])) {
                $filter = $value['filter'];

                // Category
                if($value['filter_by'] == 'category') {
                    $obj = $obj->where('cat_id', Category::getId($filter));

                // Author
                } else if ($value['filter_by'] == 'author') {
                    $obj = $obj->where('user_id', decrypt($filter));
                }

            }


        $obj = $obj->orderBy('id','DESC')->paginate($value['show'] ?? 10);
            // dd(DB::getQueryLog());  // Show results of log
        return $obj;
    }



    // Create Or Update
    public static function createOrUpdate($id, $header, $file, $tags, $value)
    {
        try {
           // Begin Transaction between tables
            DB::beginTransaction();
                // 1. find Or New
                $row            = (isset($id)) ? self::find($id) : new self;
                $row->user_id   = ($header['accesstoken'][0]) ? Helper::whoIs($header['accesstoken'][0]) : NULL;
                $row->cat_id    = ($value['subcat_id'] != 'null') ? $value['subcat_id'] : $value['cat_id'];
                $row->fill([
                    'en' => [
                        'slug'  => Helper::isSlug($value['slug']),
                        'title' => $value['title'],
                        'body'  => $value['body']
                    ],
                ]);
                $row->save();
                $row->touch(); // touch updated_at to current timestamp



                // 2. Upload image if exist
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

                // 4. Add Tags if exists
                if(isset($tags)) {
                    $tagids = [];
                    foreach(explode(',',$tags) as $tag) {
                        $tagids[] = Tag::getId($tag);
                    }

                    // morphTo Taggables
                    $row->tags()->delete(); // Delete taggables row
                    foreach ($tagids as $tagid) {
                        $row->tags()->create(['tag_id'=>$tagid]);
                    } 
                }

                // Related No.3
                (isset($id)) ? Meta::where('id', $row->metables->meta_id)->delete() : NULL; // Delete Meta & Transatcion row
                $row->meta()->delete(); // Delete metables row

                $row->meta()->create(['meta_id'=>$meta->id]); // Insert into Metables
                $row->metables()->associate($meta); // Insert into Meta & Transations

                
                // 5. Add Logs Situation
                if(isset($id)) { 
                    $action    = 'Update Post'; 
                    $log       = 'Update post: '.$value['title']; 
                } else { 
                    $action    = 'Create Post'; 
                    $log       = 'Create new post: '.$value['title']; 
                }
                Log::create([
                    'user_id'  => $row->user_id,
                    'action'   => $action, 
                    'log'      => $log
                ]);

            DB::commit();
            // End Commit of Transaction

            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }



    // Count Posts in each Category
    public static function getCountPosts($cat_id)
    {
        $cat_ids = DB::table('categories')->where('parent_id', $cat_id)
                                ->where(['status'=>true, 'trash'=>false])
                                ->pluck('id');
        $posts = self::whereIn('cat_id', $cat_ids)
                                ->where(['status'=>true, 'trash'=>false])
                                ->count();
        return $posts;
    }

    // Explode post id from Slug
    public static function explodeIdFromSlug($post)
    {
        $exp = explode('-', $post);
        return end($exp);
    }

    // fetch Period
    public static function fetchPeriod($header, $days)
    {
        $operator = '-';
        $percentage = '0%';
        $arrow = 'ti-arrow-down';

        // get Period Day
        $obj = self::fetchPeriodDay($header, $days);

        // find percentage & arrow
        if($days != 'infinity') {
            $obj2 = self::fetchPeriodDay($header, $days);
            if($obj >= $obj2) { $operator = '+'; $arrow = 'ti-arrow-up'; } 
            else { $operator = '-'; $arrow = 'ti-arrow-down'; }

            $diff = 0;
            if($obj > 0 && $obj2) { $diff = $obj / $obj2 * 100; }
            $percentage = $operator.''.$diff.'%';
        }

        $data = ['total'=>$obj, 'percentage'=>$percentage, 'arrow'=>$arrow];
        return $data;
        
    }

    public static function fetchPeriodDay($header, $days)
    {
        $obj = self::where(['status'=>true, 'trash'=>false]);

                // Depend on Role
            if($header['accesstoken'][0]) {
                $user_id = Helper::whoIs($header['accesstoken'][0]);
                $role_id = User::getRoleId($user_id);
                if($role_id != 5 && $role_id != 4) {
                    $obj = $obj->where('user_id', $user_id);
                }
            }

                // Today   & else = Yesterday, 28 Days, 90 Days , 180 Days
            if($days == 0) {
                $obj = $obj->whereDate('created_at', Carbon::now());
            } else if ($days != 0 && $days != 'infinity') {
                $obj = $obj->whereDate('created_at', '>=', Carbon::now()->subDay($days));
            } 

        $obj = $obj->count();
        return $obj;
    }

    // get name
    public static function getName($id)
    {
        $obj = '';
        $row = self::find($id);
        if($row) {
            $obj = $row->title;
        }
        return $obj;
    }

    // get status
    public static function getStatus($id)
    {
        $obj = '';
        $row = self::find($id);
        if($row) {
            $obj = $row->status;
        }
        return $obj;
    }

    // get id
    public static function getId($post)
    {
        $obj = '';
        $row = self::whereTranslation('slug', $post)->first();
        if($row) {
            $obj = $row->id;
        }
        return $obj;
    }



}
