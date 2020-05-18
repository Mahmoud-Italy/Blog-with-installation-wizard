<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Resources\Theme as ThemeResource;
use App\Models\Themes\Entities\Theme;
use Illuminate\Http\Request;
use Helper;

class ThemeController extends Controller
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
            
            $total = Theme::all();
            $rows = ThemeResource::collection(Theme::fetchData($request->all()));
            $data = ['all'=>count($total), 
                    'active'=>count($total->where('status', true)->where('trash', false)),
                    'inactive'=>count($total->where('status', false)->where('trash', false)), 
                    'trash'=>count($total->where('trash', true)),
                    'rows'=>$rows, 
                    'hasEdit'=>Helper::isAuthorized($request->header('accesstoken'), 'admin.themes.edit'),
                    'pagination'=>self::getPagination($rows)];
            return self::respondSuccess($data);
        } catch (\Exception $e) {
            return self::respondSomethingWrong($e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            if(!$this->authorized) {
                return self::respondAccessDenied();
            }

            // Reset Activation Themes
            Theme::where('active',true)->update(['active'=>false]);

            // ReActivate New Theme
            $row = Theme::where('id', $request->theme_id)->first();
            $row->active = true;
            $row->save();

            return self::respondSuccess();
        } catch (\Exception $e) {
            return self::respondSomethingWrong($e->getMessage());
        }
    }
}
