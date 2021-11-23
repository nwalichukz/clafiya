<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Validator;
class AuthController extends Controller
{
    /**
     *  registers a user
     * 
     * @param $request
     * 
     * @return response
     * 
     */
    public static function save($request){
      User::create([
          'name' => $request['name'],
          'email' => $request['email'],
          'password' => bcrypt($request['password']),
      ]);

      return true;
    }

     /**
     *  creates a user
     * 
     * @param Request
     * 
     * @return response
     * 
     */
    public function create(Request $request){
        $validation = Validator::make($request->all(),
        [
         'name' => 'required',
         'email' => 'required|email|unique:users',
         'password' => 'required|min:8'
        
        ]);
        
    if($validation->fails()){
        return response()->json([
            'status' => 403,
            'message' => $validation->errors(),
        ]);
    }
   $save = self::save($request);
   if($save){
    return response()->json([
        'status' => 200,
        'message' => 'User created successfully',
    ]);
   }else{
    return response()->json([
        'status' => 403,
        'message' => 'Something went wrong user could not be created. Please try again',
    ]);
   }
    }

     /**
     *  logs in a user
     * 
     * @param $email, $password
     * 
     * @return response
     * 
     */
    public function login(Request $request){
        $validation = Validator::make($request->all(),
        [
         'email' => 'required|email',
         'password' => 'required|min:8'
        
        ]);
        
    if($validation->fails()){
        return response()->json([
            'status' => 403,
            'message' => $validation->errors(),
        ]);
    }
    $credentials = [
        'email' => $request['email'],
        'password' => $request['password']
    ];
    if(Auth::attempt($credentials)){
        
        $token = Auth()->user()->createToken('yourPass')->accessToken;
        return response()->json([
            'status' => 200,
            'message' => 'Login successful',
            'token' => $token,
            'user' => auth()->user()
        ]);

    }else{

        return response()->json([
            'status' => 403,
            'message' => 'Invalid login details',
        ]);
    }
    }

}
