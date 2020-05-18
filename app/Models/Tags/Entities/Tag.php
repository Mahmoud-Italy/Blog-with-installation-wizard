<?php

namespace App\Models\Tags\Entities;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use App\Models\Tags\Entities\TagTranslation;
use App\Models\Users\Entities\User;
use App\Models\Posts\Entities\Post;
use Illuminate\Database\Eloquent\Model;
use Helper;
use Log;
use DB;

class Tag extends Model implements TranslatableContract
{
    use Translatable;
    public $translatedAttributes = ['tag'];

    protected $guarded = [];

    public function posts()
    {
        return $this->morphedByMany(Post::class, 'taggable');
    }


    // fetch data
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
                $search = $value['search'];
                $obj = $obj->whereTranslationLike('tag', '%'.$search.'%');
            }

            if(isset($value['order_by'])) {
                $order_by = $value['order_by'];
                $obj = $obj->orderByTranslation('tag', $order_by);
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

    public static function createOrUpdate($id, $header, $value)
    {
        try {
            // being Transaction
            DB::beginTransaction();
            	// Find Or New
                $row           = (isset($id)) ? self::find($id) : new self;
                $row->user_id  = Helper::whoIs($header['accesstoken'][0]);
                $row->fill([
                    'en' => [
                        'tag'  => Helper::isTag($value['tag'])
                    ],
                ]);
                $row->save();
                $row->touch();

                // Logs Situation
                if(isset($id)) { 
                    $action    = 'Update Tag'; 
                    $log       = 'Update tag: '.$row->tag; 
                } else { 
                    $action    = 'Create Tag'; 
                    $log       = 'Create new tag: '.$row->tag; 
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


    // get Tag Name
    public static function getName($id)
    {
        $obj = '';
        $row = self::find($id);
        if($row) {
            $obj = $row->tag;
        }
        return $obj;
    }
    // get Tag Status
    public static function getStatus($id)
    {
        $obj = '';
        $row = self::find($id);
        if($row) {
            $obj = $row->status;
        }
        return $obj;
    }

    // Get Taggables Morphic Relations
    public static function getTaggables($post_id, $locale)
    {
        $tag_ids = [];
        $data = DB::table('taggables')
        				->where('taggable_id', $post_id)
        				->where('taggable_type', 'App\Models\Posts\Entities\Post')
        				->get();
        foreach ($data as $row) {
            $tag_ids[] = $row->tag_id;
        }
        $tags = self::getTagsPost($tag_ids, $locale);
        return $tags;
    }

    // get Tags Name for Multiselect Vue
    public static function getTagsNameForMultiSelect($value='')
    {
        $tagsable = DB::table('tags as t')
                        ->select('l.tag')
                        ->leftjoin('tag_translations as l', 'l.tag_id','=','t.id')
                        ->where('t.status', true)
                        ->where('t.trash', false)
                        ->where('l.locale', 'en')
                        ->pluck('l.tag');
        return $tagsable;
    }


    // get tag id by name
    public static function getId($tag)
    {
        $obj = '';
        $row = self::whereTranslation('tag', $tag)->first();
        if($row) {
            $obj = $row->id;
        }
        return $obj;
    }

    // get tags post
    public static function getTagsPost($tag_ids, $locale)
    {
        $tags = [];
        foreach ($tag_ids as $tag_id) {
            $row = self::where('id', $tag_id)->first();
            $tags[] = $row->tag;
        }
        return $tags;
    }

    // Get Tags Name for Multiselect Vue
    public static function getTagsName($value='')
    {
        $tags = [];
        $tagsable = DB::table('tags as t')
                            ->select('l.tag')
                            ->leftjoin('tag_translations as l', 'l.tag_id','=','t.id')
                            ->where('t.status', true)
                            ->where('t.trash', false)
                            ->where('l.locale', 'en')
                            ->pluck('l.tag');
        return $tagsable;
    }
}
