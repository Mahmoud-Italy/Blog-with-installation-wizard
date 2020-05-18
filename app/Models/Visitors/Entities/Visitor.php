<?php

namespace App\Models\Visitors\Entities;

use App\Models\Users\Entities\User;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Request;
use Helper;
use DB;

class Visitor extends Model
{
    protected $guarded = [];


    // Save As Visitors
    public static function saveAsVisitor()
    {
        try {
            $row = new self;
            $row->ip = Request::ip();
            $row->view_at = date('Y-m-d');
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
        $obj = self::where('id','!=', 0);

            // Today & else = Yesterday, 28 Days, 90 Days , 180 Days
            if($days == 0) {
                $obj = $obj->whereDate('created_at', Carbon::now());
            } else if ($days != 0 && $days != 'infinity') {
                $obj = $obj->whereDate('created_at', '>=', Carbon::now()->subDay($days));
            } 

        $obj = $obj->count();
        return $obj;
    }



    // fetch Countries
    public static function fetchCountries($header, $days)
    {
        $countries = $total = [];

        $obj = self::whereNOTNULL('country');

            // Today   & else = Yesterday, 28 Days, 90 Days , 180 Days
            if($days == 0) {
                $obj = $obj->whereDate('view_at', Carbon::now());
            } else if ($days != 0 && $days != 'infinity') {
                $obj = $obj->whereDate('view_at', '>=', Carbon::now()->subDay($days));
            } 

        $obj = $obj->paginate(5);

        foreach ($obj as $value) {
            $countries[] = $value->country;
            $total[] = self::totalCountryVisits($value->country, $days);
        }

        $data = ['countries' => $countries, 'total'=>$total];
        return $data;
    }


    public static function totalCountryVisits($country, $days)
    {
        $obj = 0;
        $obj = self::where('country', $country);

            if($days == 0) {
                $obj = $obj->whereDate('view_at', Carbon::now());
            } else if ($days != 0 && $days != 'infinity') {
                $obj = $obj->whereDate('view_at', '>=', Carbon::now()->subDay($days));
            } 

        $obj = $obj->count();
        return $obj;
    }


}
