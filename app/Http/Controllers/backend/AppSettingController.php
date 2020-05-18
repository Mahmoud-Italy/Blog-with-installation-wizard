<?php

namespace App\Http\Controllers\backend;

use App\Http\Resources\AppSetting as AppSettingResource;
use App\Models\AppSettings\Entities\AppSetting;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Helper;

class AppSettingController extends Controller
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

            $total = AppSetting::all();
            $rows = AppSettingResource::collection(AppSetting::fetchData($request->all()));
            $data = ['all'=>count($total), 
                    'active'=>count($total->where('status', true)->where('trash', false)),
                    'inactive'=>count($total->where('status', false)->where('trash', false)), 
                    'trash'=>count($total->where('trash', true)),
                    'rows'=>$rows, 
                    'pagination'=>self::getPagination($rows)];
            return self::respondSuccess($data);
        } catch (\Exception $e) {
            return self::respondSomethingWrong($e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $row = AppSetting::createOrUpdate(NULL, $request->all());
        if($row === true) {
            return self::respondCreated();
        } else {
            return self::respondSomethingWrong('Internet Server Error, ',$row);
        }
    }

    public function update(Request $request, $id)
    {
        $row = AppSetting::createOrUpdate($id, $request->except('_method'));
        if($row === true) {
            return self::respondSuccess();
        } else {
            return self::respondSomethingWrong('Unable To Update Entry, '.$row);
        }
    }

    public function destroy($ids)
    {
    	try {
            // Just in case multi ids came without incryption
            if(strpos($ids, ',') !== false) {
                $multi = [];
                foreach(explode(',',$ids) as $id) {
                    $multi[] = $id;
                }
                AppSetting::whereIn('id', $multi)->delete();
            } else {
                AppSetting::where('id', $ids)->delete();
            }

    		return self::respondSuccess();
    	} catch (\Exception $e) {
    		return self::respondSomethingWrong($e->getMessage());
    	}
    }


    public function activeOrInactive($ids)
    {
    	try {
            if(strpos($ids, ',') !== false) {
                $multi = [];
                foreach(explode(',',$ids) as $id) {
                    $multi[] = $id;
                }
                foreach ($multi as $single) {
                    $row = AppSetting::where('id', $single)->first();
                    $row->status = ($row->status) ? false : true;
                    $row->trash = false;
                    $row->save();
                    $status = $row->status;
                }
            } else {
                $row = AppSetting::where('id', $ids)->first();
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
            if(strpos($ids, ',') !== false) {
                $multi = [];
                foreach(explode(',',$ids) as $id) {
                    $multi[] = $id;
                }
                AppSetting::whereIn('id', $multi)->update(['trash'=>true]);
            } else {
                AppSetting::where('id', $ids)->update(['trash'=>true]);
            }

    		return self::respondSuccess();
    	} catch (\Exception $e) {
    		return self::respondSomethingWrong($e->getMessage());
    	}
    }

    public function restoreFromTrash($ids)
    {
    	try {
            if(strpos($ids, ',') !== false) {
                $multi = [];
                foreach(explode(',',$ids) as $id) {
                    $multi[] = $id;
                }
                AppSetting::whereIn('id', $multi)->update(['trash'=>false]);
            } else {
                AppSetting::where('id', $ids)->update(['trash'=>false]);
            }

    		return self::respondSuccess();
    	} catch (\Exception $e) {
    		return self::respondSomethingWrong($e->getMessage());
    	}
    }

    public function export(Request $request)
    {
        $data = AppSetting::where('id', '!=', false);

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
        return self::respondSuccess(AppSettingResource::collection($data));
    }
}
