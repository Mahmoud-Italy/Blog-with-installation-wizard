<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Resources\Slider as SliderResource;
use App\Models\Sliders\Entities\Slider;
use App\Models\Sliders\Entities\Slider_translation;
use Illuminate\Http\Request;
use Helper;

class SliderController extends Controller
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

            $total = Slider::all();
            $rows = SliderResource::collection(Slider::fetchData($request->all()));
            $data = ['all'=>count($total), 
                    'active'=>count($total->where('status', true)->where('trash', false)),
                    'inactive'=>count($total->where('status', false)->where('trash', false)), 
                    'trash'=>count($total->where('trash', true)),
                    'rows'=>$rows, 
                    'hasCreate'=>Helper::isAuthorized($request->header('accesstoken'), 'admin.sliders.create'),
                    'hasEdit'=>Helper::isAuthorized($request->header('accesstoken'), 'admin.sliders.edit'),
                    'hasDestroy'=>Helper::isAuthorized($request->header('accesstoken'), 'admin.sliders.destroy'),
                    'pagination'=>self::getPagination($rows)];
            return self::respondSuccess($data);
        } catch (\Exception $e) {
            return self::respondSomethingWrong($e->getMessage());
        }
    }


    public function store(Request $request)
    {
        if(!$this->authorized) {
            return self::respondAccessDenied();
        }

        $row = Slider::createOrUpdate(NULL, $request->headers->all(), $request->file('image'), $request->all());
        if($row === true) {
            return self::respondCreated();
        } else {
            return self::respondSomethingWrong('Internet Server Error, '.$row);
        }
    }

    public function update(Request $request, $id)
    {
        if(!$this->authorized) {
            return self::respondAccessDenied();
        }

        $row = Slider::createOrUpdate($id, $request->headers->all(), $request->file('image'), $request->all());
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

            if(strpos($ids, ',') !== false) {
                $multi = [];
                foreach(explode(',',$ids) as $id) {
                    $multi[] = $id;
                }
                Slider::whereIn('id', $multi)->delete();
            } else {
                Slider::where('id', $ids)->delete();
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
                    $row = Slider::where('id', $single)->first();
                    $row->status = ($row->status) ? false : true;
                    $row->trash = false;
                    $row->save();
                    $status = $row->status;
                }
            } else {
                $row = Slider::where('id', $ids)->first();
                $row->status = ($row->status) ? false : true;
                $row->trash = false;
                $row->save();
                $status = $row->status;
            }

    		return self::respondSuccess(['slider_status'=>$status]);
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
                Slider::whereIn('id', $multi)->update(['trash'=>true]);
            } else {
                Slider::where('id', $ids)->update(['trash'=>true]);
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
                Slider::whereIn('id', $multi)->update(['trash'=>false]);
            } else {
                Slider::where('id', $ids)->update(['trash'=>false]);
            }

    		return self::respondSuccess();
    	} catch (\Exception $e) {
    		return self::respondSomethingWrong($e->getMessage());
    	}
    }

    public function export(Request $request)
    {
        $data = Slider::where(['status'=>true, 'trash'=>false]);

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
        return self::respondSuccess(SliderResource::collection($data));
    }
}
