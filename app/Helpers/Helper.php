<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use \League\OAuth2\Server\ResourceServer;
use \Laravel\Passport\TokenRepository;
use \Laravel\Passport\Guards\TokenGuard;
use \Laravel\Passport\ClientRepository;
use App\Models\Users\Entities\User;
use App\Models\Users\Entities\Role;
use App\Models\Users\Entities\User_permission;
use App\Models\AppSettings\Entities\AppSetting_manufacture;
use App\Models\Users\Entities\Permission;
use App\Models\Posts\Entities\Post_view;
use App\Models\Themes\Entities\Theme;
use Carbon\Carbon;
use Auth;
use App;
use DB;

class Helper 
{

    // Route Control
    public static function postRoute()
    {
        return 'news';
    }
    public static function catRoute()
    {
        return 'category';
    }
    public static function pageRoute()
    {
        return 'pg';
    }
    public static function tagRoute()
    {
        return 'tag';
    }
    // End Route Control

    // Get AWS Bucket
    public static function getAWS_Bucket($value='')
    {
        return 'https://thedge.s3.eu-west-2.amazonaws.com/';
    }

    // Get Path
    public static function getImagePath($image)
    {
        // if amazon set
        if(AppSetting_manufacture::getManufacture('AWS S3 Storage', 'AWS_ACCESS_KEY_ID') != '#') {
            return self::getAWS_Bucket().$image;
        } else {
            return \Request::root().'/'.$image;
        }
    }

    public static function locale()
    {
        return 'en';
    }

    public static function getAvatar($user_id='')
    {
        $obj = '/modules/backend/img/default_avatar.png';
        $row = User::find($user_id);
        if($row->image) {
            $obj = $row->image->url;
        }
       return $obj;
    }


    // Cache Control
    public static function cacheHours()
    {
        // per hours
        return 0;
    }

    // get Theme
    public static function theme()
    {
        $theme = Theme::where('active', true)->first();
        return $theme->name;
    }

    // isSlug
    public static function isSlug($slug)
    {
        return strtolower(str_replace(' ', '-', $slug));
    }

    // isTag
    public static function isTag($tag)
    {
        return strtolower(str_replace([' ','-'],['_','_'],$tag));
    }

    // is Authorized
    public static function isAuthorized($accessToken, $permission)
    {
        $obj = false;
        $user_id = self::whoIs($accessToken);
        $permissions = Permission::where('permission', $permission)->first();
        if($permissions) {
            $row = User_permission::where('user_id', $user_id)->where('permission_id', $permissions->id)->first();
            if($row) {
                $obj = true;
            }
        }
        return $obj;
    }


    public static function postViewslineChart($header, $type)
    {
        if($type == 'weekly') {
            return self::postViewsWeekly($header);
        } else if($type == 'monthly') {
            return self::postViewsMonthly($header);
        } else if ($type == 'quarter') {
            return self::postViewsQuarter($header);
        } else if ($type == 'yearly') {
            return self::postViewsYearly($header);
        }
    }

    public static function postViewsWeekly($header)
    {
        $sun     = self::viewsPerWeek('01');
        $mon     = self::viewsPerWeek('02');
        $thu     = self::viewsPerWeek('03');
        $wed     = self::viewsPerWeek('04');
        $tue     = self::viewsPerWeek('05');
        $fri     = self::viewsPerWeek('06');
        $sat     = self::viewsPerWeek('07');

        $series  = [$sun, $mon, $thu, $wed, $tue, $fri, $sat];
        $xaxis   = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursady', 'Friday', 'Saturday'];
        $data    = ['xaxis' => $xaxis, 'series'=>$series];
        return $data;
    }
    public static function viewsPerWeek($day)
    {
        return Post_view::whereRaw('WEEKDAY(created_at)='.$day)->count();
    }

    public static function postViewsMonthly($header)
    {
        $mo01     = self::viewsPerMonth('01');
        $mo02     = self::viewsPerMonth('02');
        $mo03     = self::viewsPerMonth('03');
        $mo04     = self::viewsPerMonth('04');
        $mo05     = self::viewsPerMonth('05');
        $mo06     = self::viewsPerMonth('06');
        $mo07     = self::viewsPerMonth('07');
        $mo08     = self::viewsPerMonth('08');
        $mo09     = self::viewsPerMonth('09');
        $mo10     = self::viewsPerMonth('10');
        $mo11     = self::viewsPerMonth('11');
        $mo12     = self::viewsPerMonth('12');

        $series   = [$mo01, $mo02, $mo03, $mo04, $mo05, $mo06, $mo07, $mo08, $mo09, $mo10, $mo11, $mo12];
        $xaxis    = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep','Oct','Nov','Dec'];
        $data     = ['xaxis' => $xaxis, 'series'=>$series];
        return $data;
    }

    public static function viewsPerMonth($month)
    {
        return Post_view::whereMonth('created_at', $month)->count();
    }


    public static function postViewsQuarter($header)
    {
        $quarter1   = self::viewsPerQuarter(['01', '03']);
        $quarter2   = self::viewsPerQuarter(['04', '06']);
        $quarter3   = self::viewsPerQuarter(['07', '09']);
        $quarter4   = self::viewsPerQuarter(['10', '12']);

        $series     = [$quarter1, $quarter2, $quarter3, $quarter4];
        $xaxis      = ['Jan - Mar', 'Apr - Jun', 'Jul - Sep', 'Oct - Dec'];
        $data       = ['xaxis' => $xaxis, 'series'=>$series];
        return $data;
    }
    public static function viewsPerQuarter($quarter)
    {
        return Post_view::whereBetween(DB::raw('MONTH(created_at)'), $quarter)->whereYear('created_at', date('Y'))->count();
    }


    public static function postViewsYearly($header)
    {
        $prev_year    = date('Y', strtotime("-1 year"));
        $current_year = date('Y');
        $next1_year   = date('Y', strtotime("+1 year"));
        $next2_year   = date('Y', strtotime("+2 year"));
        $next3_year   = date('Y', strtotime("+3 year"));

        $series0      = self::viewPerYear($prev_year);
        $series1      = self::viewPerYear($current_year);
        $series2      = self::viewPerYear($next1_year);
        $series3      = self::viewPerYear($next2_year);
        $series4      = self::viewPerYear($next3_year);

        $series       = [$series0, $series1, $series2, $series3, $series4];
        $xaxis        = [$prev_year, $current_year, $next1_year, $next2_year, $next3_year];
        $data         = ['xaxis' => $xaxis, 'series'=>$series];
        return $data;
    }
    public static function viewPerYear($year)
    {
        return Post_view::whereYear('created_at', $year)->count();
    }


    

    

    // ip info
    public static function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE) 
    {
        $output = NULL;
        if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
            $ip = $_SERVER["REMOTE_ADDR"];
            if ($deep_detect) {
                if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                    $ip = $_SERVER['HTTP_CLIENT_IP'];
            }
        }
        $purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
        $support    = array("country", "countrycode", "state", "region", "city", "location", "address");
        $continents = array(
            "AF" => "Africa",
            "AN" => "Antarctica",
            "AS" => "Asia",
            "EU" => "Europe",
            "OC" => "Australia (Oceania)",
            "NA" => "North America",
            "SA" => "South America"
        );
        if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
            $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
            if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
                switch ($purpose) {
                    case "location":
                        $output = array(
                            "city"           => @$ipdat->geoplugin_city,
                            "state"          => @$ipdat->geoplugin_regionName,
                            "country"        => @$ipdat->geoplugin_countryName,
                            "country_code"   => @$ipdat->geoplugin_countryCode,
                            "continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                            "continent_code" => @$ipdat->geoplugin_continentCode
                        );
                        break;
                    case "address":
                        $address = array($ipdat->geoplugin_countryName);
                        if (@strlen($ipdat->geoplugin_regionName) >= 1)
                            $address[] = $ipdat->geoplugin_regionName;
                        if (@strlen($ipdat->geoplugin_city) >= 1)
                            $address[] = $ipdat->geoplugin_city;
                        $output = implode(", ", array_reverse($address));
                        break;
                    case "city":
                        $output = @$ipdat->geoplugin_city;
                        break;
                    case "state":
                        $output = @$ipdat->geoplugin_regionName;
                        break;
                    case "region":
                        $output = @$ipdat->geoplugin_regionName;
                        break;
                    case "country":
                        $output = @$ipdat->geoplugin_countryName;
                        break;
                    case "countrycode":
                        $output = @$ipdat->geoplugin_countryCode;
                        break;
                }
            }
        }
        return $output;
    }

    // get user_id from accessToken new way.. working:)
    static function whoIs($bearerToken = false)
    {
        $user_id = 0;
        try {
            $tokenguard = new TokenGuard(
                App::make(ResourceServer::class), 
                Auth::createUserProvider('users'), 
                App::make(TokenRepository::class), 
                App::make(ClientRepository::class), 
                App::make('encrypter')
            );
            $request = Request::create('/');
            $request->headers->set('Authorization', 'Bearer ' . $bearerToken);
            $user_id = $tokenguard->user($request)->id;
        } catch (\Exception $e) {   }
        return $user_id;
    }

    // get user_id from accessToken with jti -- not working...
    static function whoIs_JTI($accessToken = false)
    {
        $user_id = 0;
        try {
            $token_parts = explode('.', $accessToken);
            $token_header = $token_parts[0];
            $token_header_json = base64_decode($token_header);
            $token_header_array = json_decode($token_header_json, true);
            $user_token = $token_header_array['jti'];
            $oauth_id = DB::table('oauth_access_tokens')->where('id', $user_token)->value('user_id');
            $user = User::findOrFail($oauth_id);
            $user_id = $user->id;
        } catch (\Exception $e) { }
        return $user_id;
    }

}