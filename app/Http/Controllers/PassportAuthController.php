<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;

class PassportAuthController extends Controller
{
    /**
     * Registration
     */
    public function register(Request $request)
    {
        $otp = mt_rand(0000,9999);
        $this->validate($request, [
            'phone' => 'required|min:10',
         
        ]);
          
    if (User::where('phone',$request->phone)->exists()) {
        User::where('phone',$request->phone)->update(['password' => bcrypt($otp)]);
          return response()->json(['message'=>'updated','code'=>$otp], 200);
        }
    else {
        $user = User::create([
            'phone' => $request->phone,
            'password' => bcrypt($otp)
        ]);
       
         return response()->json(['message'=>'created','code'=>$otp], 200);
    }
        
    }
 
    /**
     * Login
     */
    public function confirm(Request $request)
    {
         $data = [
            'phone' => $request->phone,
            'password' => $request->password
        ];
 
        if (auth()->attempt($data)) {
            $token = auth()->user()->createToken('LaravelAuthApp')->accessToken;
            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }   
}


