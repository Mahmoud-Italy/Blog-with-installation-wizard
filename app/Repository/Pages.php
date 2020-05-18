<?php
namespace App\Repository;

use App\Http\Resources\Page as PageResource;
use App\Models\Pages\Entities\Page;
use Carbon\Carbon;
use Helper;

class Pages {

	CONST CACHE_KEY = 'Pages';

	public static function getCacheKey($key)
	{
		$key = strtoupper($key);
		return self::CACHE_KEY .".$key";
	}

	// Fetch Pages
	public static function fetchPages($value='pages', $skip=0)
	{
		$key = "getPages.{$value}";
		$cacheKey = self::getCacheKey($key);
		
		return cache()->remember($cacheKey, Carbon::now()->addHours(Helper::cacheHours()), function() use($skip) {
			return self::toCollection(PageResource::collection(
										Page::where(['status'=>true,'trash'=>false])
											->skip($skip)
											->take(50)
											->get()));
		});
	}

	// Fetch Page
	public static function findPage($pgid='')
	{
		$key = "findPage.{$pgid}";
		$cacheKey = self::getCacheKey($key);
		
		return cache()->remember($cacheKey, Carbon::now()->addHours(Helper::cacheHours()), function() use($pgid) {
			return self::toCollection(new PageResource(Page::find($pgid)));
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