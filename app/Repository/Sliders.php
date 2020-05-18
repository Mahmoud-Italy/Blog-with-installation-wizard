<?php
namespace App\Repository;

use App\Http\Resources\Slider as SliderResource;
use App\Models\Sliders\Entities\Slider;
use Carbon\Carbon;
use Helper;
use DB;

class Sliders {

	CONST CACHE_KEY = 'SLIDERS';

	// Cache Key
	public static function getCacheKey($key)
	{
		$key = strtoupper($key);
		return self::CACHE_KEY .".$key";
	}

	// Fetch Sliders
	public static function fetchSliders($value='sliders')
	{
		$key = "getSliders.{$value}";
		$cacheKey = self::getCacheKey($key);
		
		return cache()->remember($cacheKey, Carbon::now()->addHours(Helper::cacheHours()), 
			function() use($value) {
				return self::toCollection(SliderResource::collection(
							Slider::where(['status'=>true, 'trash'=>false])
								->orderBy('position','DESC')
								->get()));
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