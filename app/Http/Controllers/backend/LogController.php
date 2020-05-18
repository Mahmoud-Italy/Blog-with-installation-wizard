<?php

namespace App\Http\Controllers\backend;

use App\Http\Resources\Log as LogResource;
use App\Models\Logs\Entities\Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Helper;

class LogController extends Controller
{
    protected $authorized = true;
    public function __construct(Request $request)
    {
        if(!Helper::isAuthorized($request->header('accesstoken'), $request->header('permission'))) {
            $this->authorized = false;
        }
    }

    public function index(Request $request)
    {
    	try {
            if(!$this->authorized) {
                return self::respondAccessDenied();
            }

            $total = Log::all();
    		$rows = LogResource::collection(Log::fetchData($request->all()));;
    		$data = ['all'        => count($total),
                     'rows'       => $rows, 
                     'pagination' => self::getPagination($rows)];
            return self::respondSuccess($data);
        } catch (\Exception $e) {
            return self::respondSomethingWrong($e->getMessage());
        }
    }
    
}
