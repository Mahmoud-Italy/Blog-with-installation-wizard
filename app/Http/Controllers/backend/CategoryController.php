<?php

namespace App\Http\Controllers\backend;

use App\Http\Resources\Category as CategoryResource;
use App\Models\Categories\Entities\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Helper;
use Log;

class CategoryController extends Controller
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

            $total = Category::fetchMainAll($request->headers->all());
            $rows  = CategoryResource::collection(Category::fetchMainData($request->headers->all(), $request->all()));
            $data  = ['all'       => count($total), 
                    'active'      => count($total->where('status', true)->where('trash', false)),
                    'inactive'    => count($total->where('status', false)->where('trash', false)), 
                    'trash'       => count($total->where('trash', true)),
                    'hasCreate'   => Helper::isAuthorized($request->header('accesstoken'), 'admin.categories.create'),
                    'hasEdit'     => Helper::isAuthorized($request->header('accesstoken'), 'admin.categories.edit'),
                    'hasDestroy'  => Helper::isAuthorized($request->header('accesstoken'), 'admin.categories.destroy'),
                    'rows'        => $rows, 
                    'pagination'  => self::getPagination($rows)];
            return self::respondSuccess($data);
        } catch (\Exception $e) {
            return self::respondSomethingWrong($e->getMessage());
        }
    }


    public function subcategory($category, Request $request)
    {
        try {
            if(!$this->authorized) {
                return self::respondAccessDenied();
            }

            $total = Category::fetchSubAll($request->headers->all(), $category);
            $rows  = CategoryResource::collection(Category::fetchSubData($request->headers->all(),$request->all(),$category));
            $data  = ['all'       => count($total), 
                    'active'      => count($total->where('status', true)->where('trash', false)),
                    'inactive'    => count($total->where('status', false)->where('trash', false)), 
                    'trash'       => count($total->where('trash', true)),
                    'category_id' => Category::getId($category),
                    'hasCreate'   => Helper::isAuthorized($request->header('accesstoken'), 'admin.categories.create'),
                    'hasEdit'     => Helper::isAuthorized($request->header('accesstoken'), 'admin.categories.edit'),
                    'hasDestroy'  => Helper::isAuthorized($request->header('accesstoken'), 'admin.categories.destroy'),
                    'rows'        => $rows, 
                    'pagination'  => self::getPagination($rows)];
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

        $row = Category::createOrUpdate(
                            NULL, 
                            $request->headers->all(),
                            $request->file('image'),
                            $request->all());
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

        $row = Category::createOrUpdate(
                            $id, 
                            $request->headers->all(),
                            $request->file('image'),
                            $request->all());
        if($row === true) {
            return self::respondSuccess();
        } else {
            return self::respondSomethingWrong('Unable To Update Entry, '.$row);
        }
    }

    public function destroy(Request $request, $ids)
    {
        if(!$this->authorized) {
            return self::respondAccessDenied();
        }

    	try {
            if(strpos($ids, ',') !== false) {
                foreach(explode(',',$ids) as $id) {
                    $cat_ids[] = $id;
                }
                Category::whereIn('id', $cat_ids)->delete();
            } else {
                $cat_ids[] = $ids;
                Category::where('id', $cat_ids)->delete();
            }

                // save Logs
                foreach ($cat_ids as $cat_id) {
                    $log[] = [
                        'user_id'    => Helper::whoIs($request->header('accesstoken')), 
                        'action'     => 'Destroy Category', 
                        'log'        => 'Deleted permanently category: '.Category::getName($cat_id),
                        'created_at' => Carbon::now()
                    ];
                } Log::insert($log);

    		return self::respondSuccess();
    	} catch (\Exception $e) {
    		return self::respondSomethingWrong($e->getMessage());
    	}
    }


    public function activeOrInactive(Request $request, $ids)
    {
        if(!$this->authorized) {
            return self::respondAccessDenied();
        }

    	try {
            if(strpos($ids, ',') !== false) {
                foreach(explode(',',$ids) as $id) {
                    $cat_ids[] = $id;
                }
                foreach ($cat_ids as $single) {
                    $row = Category::where('id', $single)->first();
                    $row->status = ($row->status) ? false : true;
                    $row->trash = false;
                    $row->save();
                    $status = $row->status;
                }
            } else {
                $cat_ids[] = $ids;
                $row = Category::where('id', $cat_ids)->first();
                $row->status = ($row->status) ? false : true;
                $row->trash = false;
                $row->save();
                $status = $row->status;
            }

                // save Logs
                foreach ($cat_ids as $cat_id) {
                    $activeOrInactive = (Category::getStatus($cat_id)) ? 'Activate' : 'Deactivate';
                    $log[] = [
                        'user_id'     => Helper::whoIs($request->header('accesstoken')), 
                        'action'      => 'Update Category', 
                        'log'         => $activeOrInactive. ' category: '.Category::getName($cat_id),
                        'created_at'  => Carbon::now()
                    ];
                } Log::insert($log);

    		return self::respondSuccess(['category_status'=>$status]);
    	} catch (\Exception $e) {
    		return self::respondSomethingWrong($e->getMessage());
    	}
    }

    public function moveToTrash(Request $request, $ids)
    {
        if(!$this->authorized) {
            return self::respondAccessDenied();
        }

    	try {
            if(strpos($ids, ',') !== false) {
                foreach(explode(',',$ids) as $id) {
                    $cat_ids[] = $id;
                }
                Category::whereIn('id', $cat_ids)->update(['trash'=>true]);
            } else {
                $cat_ids[] = $ids;
                Category::where('id', $cat_ids)->update(['trash'=>true]);
            }

                // save Logs
                foreach ($cat_ids as $cat_id) {
                    $log[] = [
                        'user_id'    => Helper::whoIs($request->header('accesstoken')), 
                        'action'     => 'Update Category', 
                        'log'        => 'Move to trash category: '.Category::getName($cat_id),
                        'created_at' => Carbon::now()
                    ];
                } Log::insert($log);

    		return self::respondSuccess();
    	} catch (\Exception $e) {
    		return self::respondSomethingWrong($e->getMessage());
    	}
    }

    public function restoreFromTrash(Request $request, $ids)
    {
        if(!$this->authorized) {
            return self::respondAccessDenied();
        }

    	try {
            if(strpos($ids, ',') !== false) {
                foreach(explode(',',$ids) as $id) {
                    $cat_ids[] = $id;
                }
                Category::whereIn('id', $cat_ids)->update(['trash'=>false]);
            } else {
                $cat_ids[] = $ids;
                Category::where('id', $cat_ids)->update(['trash'=>false]);
            }

                // save Logs
                foreach ($cat_ids as $cat_id) {
                    $log[] = [
                        'user_id'    => Helper::whoIs($request->header('accesstoken')), 
                        'action'     => 'Restore Category', 
                        'log'        => 'Restore from trash category: '.Category::getName($cat_id),
                        'created_at' => Carbon::now()
                    ];
                } Log::insert($log);

    		return self::respondSuccess();
    	} catch (\Exception $e) {
    		return self::respondSomethingWrong($e->getMessage());
    	}
    }

    public function export(Request $request)
    {
        if(!$this->authorized) {
            return self::respondAccessDenied();
        }
        
        $data = Category::where(['status'=>true, 'trash'=>false]);

            if($request->ids) {
                $ids = $request->ids;
                if(strpos($ids, ',') !== false) {
                    foreach(explode(',',$ids) as $id) {
                        $cat_ids[] = $id;
                    }
                    $data->whereIn('id', $cat_ids);
                } else {
                    $cat_ids[] = $ids;
                    $data->where('id', $cat_ids);
                }
            }

                // save Logs
                $log[] = [
                    'user_id'    => Helper::whoIs($request->header('accesstoken')), 
                    'action'     => 'Export Category', 
                    'log'        => 'Export data successfully',
                    'created_at' => Carbon::now()
                ];
                Log::insert($log);

        $data = $data->orderBy('id','DESC')->get();
        return self::respondSuccess(CategoryResource::collection($data));
    }

}
