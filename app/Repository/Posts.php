<?php
namespace App\Repository;

use App\Http\Resources\Post as PostResource;
use App\Models\Posts\Entities\Post;
use App\Models\Posts\Entities\PostTranslation;
use App\Models\Tags\Entities\Tag;
use App\Models\Tags\Entities\TagTranslation;
use App\Models\Categories\Entities\Category;
use Carbon\Carbon;
use Helper;
use DB;

class Posts {

	CONST CACHE_KEY = 'POSTS';

	// Cache Key
	public static function getCacheKey($key)
	{
		$key = strtoupper($key);
		return self::CACHE_KEY .".$key";
	}


	// Fetch Latest Posts
	public static function fetchLatestPosts($value='latest', $paginate=4, $page=1)
	{
		$key = "getLatestPosts.{$value}.{$page}";
		$cacheKey = self::getCacheKey($key);

		return cache()->remember($cacheKey,Carbon::now()->addHours(Helper::cacheHours()), 
			function() use($paginate, $page) {
				$data = PostResource::collection(
						Post::where(['status'=>true, 'trash'=>false])
							->orderBy('id','DESC')
							->paginate($paginate, ['*'], 'page', $page));

			return ['data'=>self::toCollection($data), 'pagination'=>self::toPagination($data)];
		});
	}

	// Fetch Posts in Category
	public static function fetchPostsInCategory($value, $cat_ids='', $paginate=9, $page=1)
	{
		$key = "getPostsInCategory.{$value}.{$page}";
		$cacheKey = self::getCacheKey($key);

		return cache()->remember($cacheKey,Carbon::now()->addHours(Helper::cacheHours()), 
			function() use($cat_ids, $paginate, $page) {
				$data = PostResource::collection(
						Post::whereIn('cat_id', $cat_ids)
							->where(['status'=>true, 'trash'=>false])
							->orderBy('id','DESC')
							->paginate($paginate, ['*'], 'page', $page));

			return ['data'=>self::toCollection($data), 'pagination'=>self::toPagination($data)];
		});
	}

	// Find Post
	public static function findPost($post_id)
	{
		$key = "findPost.{$post_id}";
		$cacheKey = self::getCacheKey($key);
		
		return cache()->remember($cacheKey, Carbon::now()->addHours(Helper::cacheHours()), function() use($post_id) {
			return self::toCollection(new PostResource(Post::find($post_id)));
		});
	}


	// Fetch Sliders
	public static function fetchSliders($value='sliders', $paginate=5)
	{
		$key = "getSliders.{$value}";
		$cacheKey = self::getCacheKey($key);
		
		return cache()->remember($cacheKey, Carbon::now()->addHours(Helper::cacheHours()), 
			function() use($paginate) {
			return self::toCollection(PostResource::collection(
						Post::where(['status'=>true, 'trash'=>false])
							->orderBy('id','DESC')
							->paginate($paginate)));
		});
	}


	// Fetch Tranding
	public static function fetchTrending($value='trending', $paginate=5)
	{
		$key = "getTrending.{$value}";
		$cacheKey = self::getCacheKey($key);
		
		return cache()->remember($cacheKey, Carbon::now()->addHours(Helper::cacheHours()), 
			function() use($paginate) {
				return self::toCollection(PostResource::collection(
							Post::where(['status'=>true,'trash'=>false])
								->orderByViews()
								->paginate($paginate)));
		});
	}



	// Fetch Recently Viewed
	public static function fetchRecentlyViewed($value='recentlyViewied', $paginate)
	{
		$key = "getRecentlyViewed.{$value}.{$paginate}";
		$cacheKey = self::getCacheKey($key);
		
		return cache()->remember($cacheKey, Carbon::now()->addHours(Helper::cacheHours()), 
			function() use($paginate) {
			return self::toCollection(PostResource::collection(
						Post::where(['status'=>true,'trash'=>false])
							->inRandomOrder()
							->limit($paginate)
							->orderBy('id','ASC')
							->get()));
				// skip(5)->take($pagination)->get()
		});
	}


	// Get next Post
    public static function getNextPost($cat_id, $post_id)
    {
    	$key = "getNextPost.{$post_id}";
		$cacheKey = self::getCacheKey($key);
		
		return cache()->remember($cacheKey, Carbon::now()->addHours(Helper::cacheHours()), 
			function() use($cat_id, $post_id) {

				$hasNext = Post::where('cat_id', $cat_id)
							->where('id', '>', $post_id)
	                        ->where(['status'=>true, 'trash'=>false])
							->first() ?? NULL;
			
			return ($hasNext) ? $data = self::toCollection(new PostResource($hasNext)) : NULL;
		});
    }

    // Get prev Post
    public static function getPrevPost($cat_id, $post_id)
    {
    	$key = "getPrevPost.{$post_id}";
		$cacheKey = self::getCacheKey($key);
		
		return cache()->remember($cacheKey, Carbon::now()->addHours(Helper::cacheHours()), 
			function() use($cat_id, $post_id) {
				
				$hasPrev = Post::where('cat_id', $cat_id)
							->where('id', '<', $post_id)
	                        ->where(['status'=>true, 'trash'=>false])
							->first() ?? NULL;

			return ($hasPrev) ? $data = self::toCollection(new PostResource($hasPrev)) : NULL;
		});
    }

    // Get Related Post
    public static function getRelatedPost($cat_id, $post_id, $paginate=9)
    {
    	$key = "getRelatedPost.{$post_id}";
		$cacheKey = self::getCacheKey($key);
		
		return cache()->remember($cacheKey, Carbon::now()->addHours(Helper::cacheHours()), 
			function() use($cat_id, $post_id, $paginate) {

				$hasPost = Post::where('cat_id', $cat_id)
							->where('id', '!=', $post_id)
	                        ->where(['status'=>true, 'trash'=>false])
	                        ->orderBy('id','DESC')
							->paginate($paginate) ?? NULL;

			return ($hasPost) ? self::toCollection(PostResource::collection($hasPost)) : NULL;
		});
    }

    // fetch Search
    public static function fetchSearch($q='search', $locale, $paginate=9, $page=1)
    {
    	$key = "getSearch.{$q}.{$page}";
		$cacheKey = self::getCacheKey($key);
		
		return cache()->remember($cacheKey, Carbon::now()->addHours(Helper::cacheHours()), 
			function() use($q, $locale, $paginate, $page) {
			
				$post_ids = PostTranslation::where('locale', $locale)
							->where('title', 'LIKE', '%'.$q.'%')
							->pluck('post_id') ?? NULL;
						
				if($post_ids) {
					$data = PostResource::collection(
							Post::whereIn('id', $post_ids)
								->where(['status'=>true, 'trash'=>false])
								->orderBy('id','DESC')
								->paginate($paginate, ['*'], 'page', $page));
				}

			return ['data'=>self::toCollection($data ?? NULL), 'pagination'=>self::toPagination($data ?? NULL)];
		});
    }



    // fetch Tags
    public static function fetchTag($tag, $locale, $paginate=9, $page=1)
    {
    	$key = "getTag.{$tag}.{$page}";
		$cacheKey = self::getCacheKey($key);
		
		return cache()->remember($cacheKey, Carbon::now()->addHours(Helper::cacheHours()), 
			function() use($tag, $locale, $paginate, $page) {

				$tags = TagTranslation::where('tag', $tag)
								->where('locale', $locale)
								->first();

				$post_ids = DB::table('taggables')
								->where('tag_id', $tags->tag_id ?? 0)
								->where('taggable_type', 'App\Models\Posts\Entities\Post')
								->pluck('taggable_id') ?? [0];

				$data = PostResource::collection(
							Post::whereIn('id', $post_ids)
								->where(['status'=>true, 'trash'=>false])
								->orderBy('id','DESC')
								->paginate($paginate, ['*'], 'page', $page));

			return ['data'=>self::toCollection($data), 'pagination'=>self::toPagination($data)];
		});
    }


	// Convert json to Collection Laravel
	public static function toCollection($rows='')
	{
		$json = response()->json(['rows'=>$rows]);
        $decode = json_decode(json_encode($json), true);
        return json_decode(json_encode($decode['original']['rows']));
	}

	// Extract Pagination
	public static function toPagination($data)
    {
        $nextPageUrl = $data->nextPageUrl();
        $perPage = $data->perPage();
        $total = $data->total();
        $paginate = ['total'=>$total, 'per_page'=>$perPage, 'next_page_url'=>$nextPageUrl];
        return self::toCollection($paginate);
    }


}