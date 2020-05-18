<?php

namespace App\Models\Users\Entities;

use Illuminate\Database\Eloquent\Model;
use Helper;
use Log;
use DB;

class Role extends Model
{
    protected $guarded = [];


    // fetch Data
    public static function fetchData($value)
    {
        $obj = self::orderBy('id','ASC');

            if(isset($value['status'])) {
                $status = $value['status'];
                ($status == 'trash') ? $obj->where('trash', true) : '';

                ($status == 'active') ? $obj->where(['status'=> true, 'trash'=>false]) : '';
                ($status == 'inactive') ? $obj->where(['status'=> false, 'trash'=>false]) : '';
            }

        $obj = $obj->paginate($value['show'] ?? 10);
        return $obj;
    }

    // Create Or Update
    public static function createOrUpdate($id, $value)
    {
        try {
            // Begin Transaction
            DB::beginTransaction();
                $row = (isset($id)) ? self::find($id) : new self;
                $row->role = $value['role'];
                $row->save();
            DB::commit();
            // End Commit of Transactions

                // Logs Situation
                // if(isset($id)) { 
                //     $action = 'Update Role'; 
                //     $log = 'Update role: '.$value['title']; } 
                // else { 
                //     $action = 'Create Role'; 
                //     $log = 'Create new role: '.$value['title']; }
                // Log::create(['user_id'=>$row->user_id, 'action'=> $action, 'log'=> $log]);

            return true;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    // get Role Name
    public static function getName($id)
    {
        $obj = '';
        $row = self::find($id);
        if($row) {
            $obj = $row->role;
        }
        return $obj;
    }

    // get status
    public static function getStatus($id)
    {
        $obj = '';
        $row = self::find($id);
        if($row) {
            $obj = $row->status;
        }
        return $obj;
    }

    // get Role id
    public static function getId($role)
    {
        $obj = '';
        $row = self::where('role', $role)->first();
        if($row) {
            $obj = $row->id;
        }
        return $obj;
    }

    
}
