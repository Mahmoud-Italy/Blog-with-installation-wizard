<?php

namespace App\Http\Controllers\backend;

use App\Http\Resources\AppSetting_manufacture as AppSetting_manufactureResource;
use App\Models\AppSettings\Entities\AppSetting;
use App\Models\AppSettings\Entities\AppSetting_manufacture;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Helper;

class AppSettingManufactureController extends Controller
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

            $app = AppSetting::find(decrypt(request('app_id')));
            $total = AppSetting_manufacture::where('app_id', decrypt(request('app_id')))->get();
            $rows = AppSetting_manufactureResource::collection(AppSetting_manufacture::fetchData($request->all()));
            $data = ['all'=>count($total), 
                    'active'=>count($total->where('status', true)->where('trash', false)),
                    'inactive'=>count($total->where('status', false)->where('trash', false)), 
                    'trash'=>count($total->where('trash', true)),
                    'rows'=>$rows, 
                    'app'=>$app,
    'hasCreate'=>Helper::isAuthorized($request->header('accesstoken'), 'admin.app_setting_manufactures.create'),
    'hasEdit'=>Helper::isAuthorized($request->header('accesstoken'), 'admin.app_setting_manufactures.edit'),
    'hasDestroy'=>Helper::isAuthorized($request->header('accesstoken'), 'admin.app_setting_manufactures.destroy'),
                    'pagination'=>self::getPagination($rows)];
            return self::respondSuccess($data);
        } catch (\Exception $e) {
            return self::respondSomethingWrong($e->getMessage());
        }
    }

    public function setup($app_id)
    {
        try {
            $row = AppSetting::find($app_id);
            $row->setup = ($row->setup) ? false : true;
            $row->save();

            $status = 'hide';
            if($row->setup) {
                $status = 'show';
            }

            $app_name = AppSetting::getAppName($app_id);
            return self::respondSuccess([
                'app_status'=>$status, 'app_name'=>str_replace(' ','_',strtolower('nav_'.$app_name))]);
        } catch (\Exception $e) {
            return self::respondSomethingWrong($e->getMessage());
        }
    }

    public function store(Request $request)
    {
        if(!$this->authorized) {
            return self::respondAccessDenied();
        }

        $row = AppSetting_manufacture::createOrUpdate(NULL, $request->except('api_key'));
        if($row === true) {
            return self::respondCreated();
        } else {
            return self::respondSomethingWrong('Internet Server Error, ',$row);
        }
    }

    public function update(Request $request, $id)
    {
        if(!$this->authorized) {
            return self::respondAccessDenied();
        }

        $row = AppSetting_manufacture::createOrUpdate($id, $request->except('_method','api_key'));
        if($row === true) {
            return self::respondSuccess();
        } else {
            return self::respondSomethingWrong('Unable To Update Entry, '.$row);
        }
    }

    public function destroy($ids)
    {
    	try {
            if(!$this->authorized) {
                return self::respondAccessDenied();
            }

            // Just in case multi ids came without incryption
            if(strpos($ids, ',') !== false) {
                $multi = [];
                foreach(explode(',',$ids) as $id) {
                    $multi[] = $id;
                }
                AppSetting_manufacture::whereIn('id', $multi)->delete();
            } else {
                AppSetting_manufacture::where('id', $ids)->delete();
            }

    		return self::respondSuccess();
    	} catch (\Exception $e) {
    		return self::respondSomethingWrong($e->getMessage());
    	}
    }


    public function activeOrInactive($ids)
    {
    	try {
            if(!$this->authorized) {
                return self::respondAccessDenied();
            }

            if(strpos($ids, ',') !== false) {
                $multi = [];
                foreach(explode(',',$ids) as $id) {
                    $multi[] = $id;
                }
                foreach ($multi as $single) {
                    $row = AppSetting_manufacture::where('id', $single)->first();
                    $row->status = ($row->status) ? false : true;
                    $row->trash = false;
                    $row->save();
                    $status = $row->status;
                }
            } else {
                $row = AppSetting_manufacture::where('id', $ids)->first();
                $row->status = ($row->status) ? false : true;
                $row->trash = false;
                $row->save();
                $status = $row->status;
            }

    		return self::respondSuccess(['app_status'=>$status]);
    	} catch (\Exception $e) {
    		return self::respondSomethingWrong($e->getMessage());
    	}
    }

    public function moveToTrash($ids)
    {
    	try {
            if(!$this->authorized) {
                return self::respondAccessDenied();
            }

            if(strpos($ids, ',') !== false) {
                $multi = [];
                foreach(explode(',',$ids) as $id) {
                    $multi[] = $id;
                }
                AppSetting_manufacture::whereIn('id', $multi)->update(['trash'=>true]);
            } else {
                AppSetting_manufacture::where('id', $ids)->update(['trash'=>true]);
            }

    		return self::respondSuccess();
    	} catch (\Exception $e) {
    		return self::respondSomethingWrong($e->getMessage());
    	}
    }

    public function restoreFromTrash($ids)
    {
    	try {
            if(!$this->authorized) {
                return self::respondAccessDenied();
            }

            if(strpos($ids, ',') !== false) {
                $multi = [];
                foreach(explode(',',$ids) as $id) {
                    $multi[] = $id;
                }
                AppSetting_manufacture::whereIn('id', $multi)->update(['trash'=>false]);
            } else {
                AppSetting_manufacture::where('id', $ids)->update(['trash'=>false]);
            }

    		return self::respondSuccess();
    	} catch (\Exception $e) {
    		return self::respondSomethingWrong($e->getMessage());
    	}
    }

    public function export(Request $request)
    {
        $data = AppSetting_manufacture::where('id', '!=', false);

            if($request->ids) {
                $ids = $request->ids;
                if(strpos($ids, ',') !== false) {
                    $multi = [];
                    foreach(explode(',',$ids) as $id) {
                        $multi[] = $id;
                    }
                    $data->whereIn('id', $multi);
                } else {
                    $data->where('id', $ids);
                }
            }

        $data = $data->orderBy('id','ASC')->get();
        return self::respondSuccess(AppSetting_manufactureResource::collection($data));
    }
}
