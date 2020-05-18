<?php

namespace App\Models\Users\Entities;

use Illuminate\Database\Eloquent\Model;
use DB;

class Permission extends Model
{
    protected $guarded = [];

    /** Here we go.. **/

    // fetch Multi Dimensional
    public static function fetchMultiDimensional()
    {
    	// get all tables from migrations..
    	$tables = [];
    	$migrations = DB::table('migrations')->get();
    	foreach ($migrations as $migrate) {
    		// skip field_jobs table & OAuth & Any Translations & Post_view & Images & Meta & Manifest
    		if(strpos($migrate->migration, 'failed_jobs_table') !== false) {}
    		else if(strpos($migrate->migration, 'oauth') !== false) {}
    		else if(strpos($migrate->migration, 'translations') !== false) {}
    		else if(strpos($migrate->migration, 'post_views') !== false) {}
    		else if(strpos($migrate->migration, 'images') !== false) {}
    		else if(strpos($migrate->migration, 'metas') !== false) {}
    		else if(strpos($migrate->migration, 'manifest') !== false) {}
    		else {
    			// extract create_ and _table from migrations
    			$tables[] = explode('_table',explode('create_',$migrate->migration)[1])[0];
    		}
    	}
    	return $tables;
    }

    // fetch Table Relations
    public static function fetchTablesRelations(array $tables)
    {
    	// stupid code i know but just working till getting better...
    	$obj = [];
    	$permissions = self::get();
    	foreach ($tables as $table) {
    		foreach ($permissions as $permission) {
    			if(strpos($permission->permission, $table) !== false) {
    				// now all i need just id to F@king inject with user_permissions
    				$obj[$table][] = ['id'=>$permission->id, 
    								   'name'=>explode('admin.'.$table.'.', $permission->permission)[1]];
    			}
    		}
    	}
    	return $obj;
    }
}
