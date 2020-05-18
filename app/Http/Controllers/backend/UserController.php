<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Resources\User as UserResource;
use App\Models\Users\Entities\User;
use App\Models\Users\Entities\Role;
use App\Models\Users\Entities\Permission;
use App\Models\Users\Entities\User_permission;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Helper;
use Log;

class UserController extends Controller
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

            $total = User::all();
            $rows  = UserResource::collection(User::fetchData($request->all()));
            $data  = ['all'          => count($total), 
                    'administrators' => count($total->where('role_id',Role::getId('Administrator'))->where('trash',false)),
                    'contributors'   => count($total->where('role_id',Role::getId('Contributor'))->where('trash',false)), 
                    'authors'        => count($total->where('role_id', Role::getId('Author'))->where('trash',false)),
                    'editors'        => count($total->where('role_id', Role::getId('Editor'))->where('trash', false)),
                    'subscribers'    => count($total->where('role_id', Role::getId('Subscriber'))->where('trash', false)),
                    'trash'          => count($total->where('trash', true)),
                    'hasCreate'      => Helper::isAuthorized($request->header('accesstoken'), 'admin.users.create'),
                    'hasEdit'        => Helper::isAuthorized($request->header('accesstoken'), 'admin.users.edit'),
                    'hasDestroy'     => Helper::isAuthorized($request->header('accesstoken'), 'admin.users.destroy'),
                    'rows'           => $rows, 
                    'pagination'     => self::getPagination($rows)];
            return self::respondSuccess($data);
        } catch (\Exception $e) {
            return self::respondSomethingWrong($e->getMessage());
        }
        
    }

    public function permissions()
    {
    	$tables = Permission::fetchMultiDimensional();
    	$data = Permission::fetchTablesRelations($tables);
    	return self::respondSuccess($data);
    }

    public function store(Request $request)
    {
        if(!$this->authorized) {
            return self::respondAccessDenied();
        }

        $row = User::createOrUpdate(
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

    public function show($id)
    {
        if(!$this->authorized) {
            return self::respondAccessDenied();
        }

        $rows           = new UserResource(User::find(decrypt($id)));
        $permission_ids = User_permission::where('user_id', decrypt($id))->pluck('permission_id');
        return self::respondSuccess(['rows'=>$rows, 'permission_ids'=>$permission_ids]);
    }

    public function update(Request $request, $id)
    {
        if(!$this->authorized) {
            return self::respondAccessDenied();
        }

        $row = User::createOrUpdate(
                        decrypt($id), 
                        $request->headers->all(), 
                        $request->file('image'), 
                        $request->all());
        if($row === true) {
            return self::respondSuccess();
        } else {
            return self::respondSomethingWrong('Unable To Update, '.$row);
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
                    $user_ids[] = $id;
                }
                User::whereIn('id', $user_ids)->delete();
            } else {
                $user_ids[] = $ids;
                User::where('id', $user_ids)->delete();
            }

                // save Logs
                foreach ($user_ids as $user_id) {
                    $log[] = [
                        'user_id'    => Helper::whoIs($request->header('accesstoken')), 
                        'action'     => 'Destroy User', 
                        'log'        => 'Deleted permanently user: '.User::getName($user_id),
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
        $data = User::where(['trash'=>false]);

            if($request->ids) {
                $ids = $request->ids;
                if(strpos($ids, ',') !== false) {
                    foreach(explode(',',$ids) as $id) {
                        $user_ids[] = $id;
                    }
                    $data->whereIn('id', $user_ids);
                } else {
                    $user_ids[] = $ids;
                    $data->where('id', $user_ids);
                }
            }

                // save Logs
                $log[] = [
                    'user_id'    => Helper::whoIs($request->header('accesstoken')), 
                    'action'     => 'Export User', 
                    'log'        => 'Export data successfully',
                    'created_at' => Carbon::now()
                ];
                Log::insert($log);

        $data = $data->orderBy('id','DESC')->get();
        return self::respondSuccess(UserResource::collection($data));
    }
}
