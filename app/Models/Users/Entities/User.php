<?php

namespace App\Models\Users\Entities;

use App\Models\Users\Entities\Activation;
use App\Models\Users\Entities\Role;
use App\Models\Users\Entities\User_permission;
use App\Models\Images\Entities\Image;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Carbon\Carbon;
use Helper;
use Log;
use DB;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role_id', 'last_login'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function posts()
    {
        return $this->hasMany('App\Models\Posts\Entities\Post', 'user_id');
    }

    public function categories()
    {
        return $this->hasMany('App\Models\Categories\Entitie\Category');
    }

    public function roles()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    /** Here we go.. **/

    // fetch Data
    public static function fetchData($value)
    {
        $obj = self::orderBy('id','DESC');

            if(isset($value['search'])) {
                $obj = $obj->where('name', '%'.$value['search'].'%');
            }

            if(isset($value['order_by'])) {
                $obj = $obj->orderBy('name', $value['order_by']);
            }

            if(isset($value['status'])) {
                $status = $value['status'];
                ($status == 'trash') ? $obj->where('trash', true) : '';
                ($status == 'suspend') ? $obj->where('trash', true) : '';

                // Administrators
                ($status == 'administrator') 
                    ? $obj->where(['role_id'=> Role::getId('Administrator'), 'trash'=>false]) 
                    : '';

                // Contributors
                ($status == 'contributor') 
                    ? $obj->where(['role_id'=> Role::getId('Contributor'), 'trash'=>false]) 
                    : '';

                // Authors
                ($status == 'author') 
                    ? $obj->where(['role_id'=> Role::getId('Author'), 'trash'=>false]) 
                    : '';

                // Editors 
                ($status == 'editor') 
                    ? $obj->where(['role_id'=> Role::getId('Editor'), 'trash'=>false]) 
                    : '';

                // Subscribers
                ($status == 'subscriber') 
                    ? $obj->where(['role_id'=> Role::getId('Subscriber'), 'trash'=>false]) 
                    : '';

            }

        $obj = $obj->paginate($value['show'] ?? 10);
        return $obj;
    }

    // Create Or Update
    public static function createOrUpdate($id, $header, $file, $value)
    {
        try {
            // Begin Transaction
            DB::beginTransaction();
                // Add Page
                $row = (isset($id)) ? self::find($id) : new self;
                $row->name = $value['name'];
                $row->email = $value['email'];
                    /** Ignore default bcrypt of NULL  **/
                    if($value['password'] != 'FceHTv9eX3yDj9#') { 
                        $row->password = $value['password']; 
                    }
                $row->role_id = $value['role_id'];
                $row->created_by =  Helper::whoIs($header['accesstoken'][0]);
                $row->save();

                // Add Images
                if(isset($file)) {
                    (isset($id)) ? Image::where('imageable_id', $id)
                                        ->where('imageable_type', __NAMESPACE__.'\\'.class_basename(new self))
                                        ->delete() : '';
                    $img = new Image;
                        // Using amazon storage
                        $filePath = 'images/'.date('Y-m-d-h-i-s').'.'.$file->getClientOriginalExtension();
                        Storage::disk('s3')->put($filePath, file_get_contents($file));
                    $img->url = $filePath;

                    // morphTo Image
                    $row->image()->save($img);
                }
                
                // Add Activiation
                $activiation = Activation::create(['user_id' =>$row->id]);
                Activation::completed($activiation);

                if(isset($value['permission_id'])) {
                    (isset($id)) ? User_permission::where('user_id', $id)->delete() : NULL;

                    // Add User Permission
                    foreach (explode(',', $value['permission_id']) as $arr) {
                        $values[] = $arr;
                    }
                    foreach ($values as $value) {
                        $permission[] = [
                            'user_id' => $row->id,
                            'permission_id' => $value
                        ];
                    }
                    User_permission::insert($permission);
                }

                // Logs Situation
                if(isset($id)) { 
                    $action = 'Update User'; 
                    $log = 'Update user: '.$row->name; } 
                else { 
                    $action = 'Create User'; 
                    $log = 'Create new user: '.$row->name; }
                Log::create(['user_id'=>$row->created_by, 'action'=> $action, 'log'=> $log]);
                
            DB::commit();
            // End Commit of Transactions

            return true;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public static function getRoleId($user_id)
    {
        $obj = 0;
        $row = self::find($user_id);
        if($row) {
            $obj = $row->role_id;
        }
        return $obj;
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
        $obj = self::where(['trash'=>false]);

                // Depend on Role
            $user_id = Helper::whoIs($header['accesstoken'][0]);
            $role_id = self::getRoleId($user_id);
            if($role_id != 5 && $role_id != 4) {
                $obj = $obj->where('user_id', $user_id);
            }

                // Today & else = Yesterday, 28 Days, 90 Days , 180 Days
            if($days == 0) {
                $obj = $obj->whereDate('created_at', Carbon::now());
            } else if ($days != 0 && $days != 'infinity') {
                $obj = $obj->whereDate('created_at', '>=', Carbon::now()->subDay($days));
            } 

        $obj = $obj->count();
        return $obj;
    }


    // get Name
    public static function getName($id)
    {
        $obj = '';
        $row = self::find($id);
        if($row) {
            $obj = $row->name;
        }
        return $obj;
    }

    // get id
    public static function getId($name)
    {
        $obj = '';
        $row = self::where('name', $name)->first();
        if($row) {
            $obj = $row->id;
        }
        return $obj;
    }
}
