<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Artisan;
use Helper;

class ArtisanController extends Controller
{
    public function cache(Request $request)
    {
    	try {
    		Artisan::call('cache:clear');
    		return self::respondSuccess();
    	} catch (Exception $e) {
    		return self::respondSomethingWrong($e->getMessage());
    	}
    }

    public function config()
    {
        try {
            Artisan::call('config:clear');
            return self::respondSuccess();
        } catch (Exception $e) {
            return self::respondSomethingWrong($e->getMessage());
        }
    }

    public function view()
    {
        try {
            Artisan::call('view:clear');
            return self::respondSuccess();
        } catch (Exception $e) {
            return self::respondSomethingWrong($e->getMessage());
        }
    }

    public function routeCache()
    {
    	try {
    		Artisan::call('route:cache');
    		return self::respondSuccess();
    	} catch (Exception $e) {
    		return self::respondSomethingWrong($e->getMessage());
    	}
    }

    public function routeClear()
    {
        try {
            Artisan::call('route:clear');
            return self::respondSuccess();
        } catch (Exception $e) {
            return self::respondSomethingWrong($e->getMessage());
        }
    }
    
}
