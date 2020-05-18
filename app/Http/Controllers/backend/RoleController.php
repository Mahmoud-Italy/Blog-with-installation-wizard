<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Resources\Role as RoleResource;
use App\Models\Users\Entities\Role;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Helper;
use Log;

class RoleController extends Controller
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

            $total = Role::all();
            $rows  = RoleResource::collection(Role::fetchData($request->all()));
            $data  = ['all'       => count($total), 
                    'active'      => count($total->where('status', true)->where('trash', false)),
                    'inactive'    => count($total->where('status', false)->where('trash', false)), 
                    'trash'       => count($total->where('trash', true)),
                    'hasCreate'   => Helper::isAuthorized($request->header('accesstoken'), 'admin.roles.create'),
                    'hasEdit'     => Helper::isAuthorized($request->header('accesstoken'), 'admin.roles.edit'),
                    'hasDestroy'  => Helper::isAuthorized($request->header('accesstoken'), 'admin.roles.destroy'),
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

        $row = Role::createOrUpdate(
                        NULL, 
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

        $row = Role::createOrUpdate(
                        $id, 
                        $request->all());
        if($row === true) {
            return self::respondSuccess();
        } else {
            return self::respondSomethingWrong('Unable To Update Entry, '.$row);
        }
    }

    public function destroy(Request $request, $ids)
    {
    	try {
            if(!$this->authorized) {
                return self::respondAccessDenied();
            }

            if(strpos($ids, ',') !== false) {
                foreach(explode(',',$ids) as $id) {
                    $role_ids[] = $id;
                }
                Role::whereIn('id', $role_ids)->delete();
            } else {
                $role_ids[]= $ids;
                Role::where('id', $role_ids)->delete();
            }

                // save Logs
                foreach ($role_ids as $role_id) {
                    $log[] = [
                        'user_id'    => Helper::whoIs($request->header('accesstoken')), 
                        'action'     => 'Destroy Role', 
                        'log'        => 'Deleted permanently role: '.Role::getName($role_id),
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
    	try {
            if(!$this->authorized) {
                return self::respondAccessDenied();
            }

            if(strpos($ids, ',') !== false) {
                foreach(explode(',',$ids) as $id) {
                    $role_ids[] = $id;
                }
                foreach ($role_ids as $single) {
                    $row = Role::where('id', $single)->first();
                    $row->status = ($row->status) ? false : true;
                    $row->trash = false;
                    $row->save();
                    $status = $row->status;
                }
            } else {
                $role_ids[] = $ids;
                $row = Role::where('id', $role_ids)->first();
                $row->status = ($row->status) ? false : true;
                $row->trash = false;
                $row->save();
                $status = $row->status;
            }

                // save Logs
                foreach ($role_ids as $role_id) {
                    $activeOrInactive = (Role::getStatus($role_id)) ? 'Activate' : 'Deactivate';
                    $log[] = [
                        'user_id'    => Helper::whoIs($request->header('accesstoken')), 
                        'action'     => 'Update Role', 
                        'log'        => $activeOrInactive . '  role: '.Role::getName($role_id),
                        'created_at' => Carbon::now()
                    ];
                } Log::insert($log);

    		return self::respondSuccess(['role_status'=>$status]);
    	} catch (\Exception $e) {
    		return self::respondSomethingWrong($e->getMessage());
    	}
    }

    public function moveToTrash(Request $request, $ids)
    {
    	try {
            if(!$this->authorized) {
                return self::respondAccessDenied();
            }

            if(strpos($ids, ',') !== false) {
                foreach(explode(',',$ids) as $id) {
                    $role_ids[] = $id;
                }
                Role::whereIn('id', $role_ids)->update(['trash'=>true]);
            } else {
                $role_ids[] = $ids;
                Role::where('id', $role_ids)->update(['trash'=>true]);
            }

                // save Logs
                foreach ($role_ids as $role_id) {
                    $log[] = [
                        'user_id'    => Helper::whoIs($request->header('accesstoken')), 
                        'action'     => 'Destroy Role', 
                        'log'        => 'Move to trash role: '.Role::getName($role_id),
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
    	try {
            if(!$this->authorized) {
                return self::respondAccessDenied();
            }
            
            if(strpos($ids, ',') !== false) {
                foreach(explode(',',$ids) as $id) {
                    $role_ids[] = $id;
                }
                Role::whereIn('id', $role_ids)->update(['trash'=>false]);
            } else {
                $role_ids[] = $ids;
                Role::where('id', $role_ids)->update(['trash'=>false]);
            }

                // save Logs
                foreach ($role_ids as $role_id) {
                    $log[] = [
                        'user_id'    => Helper::whoIs($request->header('accesstoken')), 
                        'action'     => 'Restore Role', 
                        'log'        => 'Restore from trash role: '.Role::getName($role_id),
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
        $data = Role::where(['status'=>true, 'trash'=>false]);

            if($request->ids) {
                $ids = $request->ids;
                if(strpos($ids, ',') !== false) {
                    foreach(explode(',',$ids) as $id) {
                        $role_ids[] = $id;
                    }
                    $data->whereIn('id', $role_ids);
                } else {
                    $role_ids[] = $ids;
                    $data->where('id', $role_ids);
                }
            }

                // save Logs
                $log[] = [
                    'user_id'    => Helper::whoIs($request->header('accesstoken')), 
                    'action'     => 'Export Role', 
                    'log'        => 'Export data successfully',
                    'created_at' => Carbon::now()
                ];
                Log::insert($log);

        $data = $data->orderBy('id','DESC')->get();
        return self::respondSuccess(RoleResource::collection($data));
    }
}
