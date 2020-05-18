<?php
namespace App\Repository;

use App\Http\Resources\Category as CategoryResource;
use App\Models\Categories\Entities\Category;
use Carbon\Carbon;
use Helper;

class Categories {

	CONST CACHE_KEY = 'Categories';

	// Cache Key
	public static function getCacheKey($key)
	{
		$key = strtoupper($key);
		return self::CACHE_KEY .".$key";
	}

	// Fetch Categories
	public static function fetchCategories($value='categories', $paginate=50)
	{
		$key = "getCategories.{$value}";
		$cacheKey = self::getCacheKey($key);
                                                
		return cache()->remember($cacheKey,Carbon::now()->addHours(Helper::cacheHours()), function() use($paginate) {
			return self::toCollection(CategoryResource::collection(
							Category::whereNULL('parent_id')
								->where(['status'=>true, 'trash'=>false])
	                            ->paginate($paginate)));
		});
	}


	// Find Category
	public static function findCategory($cat_id='')
	{
		$key = "findCategory.{$cat_id}";
		$cacheKey = self::getCacheKey($key);
                                                
		return cache()->remember($cacheKey, Carbon::now()->addHours(Helper::cacheHours()), function() use($cat_id) {
			return self::toCollection(new CategoryResource(Category::find($cat_id)));
		});
	}


	// Convert json to Collection Laravel
	public static function toCollection($rows='')
	{
		$json = response()->json(['rows'=>$rows]);
        $decode = json_decode(json_encode($json), true);
        return json_decode(json_encode($decode['original']['rows']));
	}

}