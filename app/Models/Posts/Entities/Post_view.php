<?php

namespace App\Models\Posts\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Models\Users\Entities\User;
use Carbon\Carbon;
use Request;
use Helper;
use DB;

class Post_view extends Model
{
    protected $guarded = [];

    public function post()
    {
        return $this->belongsTo('App\Models\Posts\Entities\Post');   
    }


    // Save views
    public static function addView($post_id)
    {
        try {
    		$row = new self;
    		$row->post_id = $post_id;
    		$row->ip = Request::ip();
            $row->country = Helper::ip_info(Request::ip())['country'];
            $row->user_agent = Request::header('User-Agent');
    		$row->save();
    	} catch (\Exception $e) {}
    }


    // fetch Period
    public static function fetchPeriod($header, $days)
    {
        $operator = '-';
        $percentage = '0%';
        $arrow = 'ti-arrow-down';

        // get Period Day
        $obj = self::fetchPeriodDay($header, $days);

        // find percentage & arrow
        if($days != 'infinity') {
            $obj2 = self::fetchPeriodDay($header, $days);
            if($obj >= $obj2) { $operator = '+'; $arrow = 'ti-arrow-up'; } 
            else { $operator = '-'; $arrow = 'ti-arrow-down'; }

            $diff = 0;
            if($obj > 0 && $obj2) { $diff = $obj / $obj2 * 100; }
            $percentage = $operator.''.$diff.'%';
        }

        $data = ['total'=>$obj, 'percentage'=>$percentage, 'arrow'=>$arrow];
        return $data;
        
    }

    public static function fetchPeriodDay($header, $days)
    {
        $obj = DB::table('post_views as v')
                    ->leftjoin('posts as p', 'v.post_id', '=', 'p.id')
                    ->where(['p.status'=> true, 'p.trash'=>false]);

            // Depend on Role
            $user_id = Helper::whoIs($header['accesstoken'][0]);
            $role_id = User::getRoleId($user_id);
            if($role_id != 5 && $role_id != 4) {
                $obj = $obj->where('p.user_id', $user_id);
            }

                // Today & else = Yesterday, 28 Days, 90 Days , 180 Days
            if($days == 0) {
                $obj = $obj->whereDate('v.created_at', Carbon::now());
            } else if ($days != 0 && $days != 'infinity') {
                $obj = $obj->whereDate('v.created_at', '>=', Carbon::now()->subDay($days));
            } 

        $obj = $obj->count();
        return $obj;
    }

}
