<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Users\Entities\User;
use App\Models\Users\Entities\Role;
use Illuminate\Http\Request;
use Helper;
use Auth;

class AuthController extends Controller
{
	//protected $statusCode = 500;
	//$statusCode=500
	public function __construct()
	{
		//return $this->statusCode = $statusCode;
		$this->middleware('guest', ['except' => 'logout']);
	}

    public function login(Request $request)
    {
      $err = $accessToken = NULL;
      // validation data
      
     //  	$this->validate($request, [
	    //     'email' => 'required|max:100|email',
	    //     'password' => 'required|confirmed',
	    // ]);

	    // dd('error');

      if(!request('email')) { $err = 'Email Address is required.'; }
      else if(!request('password')) { $err = 'Password is required.'; }

      // validation suspend
      //else if (User::where('email', request('email'))->where('status', false)->count()) { $err = 'Your account is deactived.'; }
      //else if (User::where('email', request('email'))->where('suspend', true)->count()) { $err = 'Your account has been suspended.'; }

      else if(Auth::attempt(['email' => request('email'), 'password' => request('password')])) { 
              $user = Auth::user(); 
              $success['token'] = $user->createToken('myApp')->accessToken; 
              $username = Auth::user()->name;
              $user_id = Auth::user()->id;
              $avatar = Helper::getAvatar(Auth::user()->id);
              $role = Role::getName($user->role_id);
              return $this->respondWithToken($success['token'],'','',$username,$user_id,$avatar,$role); } 

      else { $err = 'Invalid email or password'; }
      return response()->json(['statusCode'=>500, 'err'=>$err]);
    }

    protected function respondWithToken($accessToken, $refreshToken='', $expire_in='', $username, $user_id, $avatar, $role)
    {
	    return response()->json([
	        'statusCode' => 200,
	        'accessToken' => $accessToken,
	        'refreshToken' => $refreshToken,
	        'expire_in' => $expire_in,
	        'username' => $username,
          'user_id' => encrypt($user_id),
	        'avatar' => $avatar,
          'role' => $role,
	        'token_type' => 'bearer'
	    ]);
    }


    public function forgot(Request $request)
    {
    	// $statusCode = 500;
     //    $err = NULL;
     //    if(!$request->email) { $err = 'Email Address is required.'; }
     //    else if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) { $err = 'Invalid Email Address.'; }
     //    else if (!User::where('email', $request->email)->count()) { $err = 'Email Address not found.'; }
     //    else {
     //        try {
     //            $row = User::where('email', $request->email)->first();
     //            $row->reset_link = $row->id.''.time();
     //            $row->save();
                
     //            #Send Mail for Verifiy Account
     //            Mail::send('emails.forgot_pwd', ['user' => $row], function($message) use($row) {
     //                $message->to($row->email, $row->fname)->subject('Reset your password');
     //            });
                    
     //            $statusCode = 200;
     //        } catch (\Exception $e) { $err = 'Something went wrong.'; }
     //    }
     //    return response()->json(['statusCode'=>$statusCode, 'err'=>$err]);
    	return self::respondSuccess();
    }

    public function reset($value='')
    {
    	//
    	return self::respondSuccess();
    }

}
