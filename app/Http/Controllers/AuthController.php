<?php

namespace App\Http\Controllers;

use App\Models\User;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(),[
            'name'=>'required|string',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|min:6|confirmed'
        ]);

        if($validator->fails()){
            $errors = $validator->errors();

            if($errors->has('name')){
                throw new \Exception
                ($errors->first('name'));
            }
            if($errors->has('email')){
                throw new \Exception
                ($errors->first('email'));
            }
            if($errors->has('password')){
                throw new \Exception
                ($errors->first('password'));
            }
        }

        $user = new User();

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->save();

      
        return response([
            'user'=>$user,
            'token'=>$user->createToken('secret')->plainTextToken
        ]);
    }

    public function login(Request $request){

        $validator = Validator::make($request->all(),[
            'email'=>'required|email|unique:users,email',
            'password'=>'required|min:6|confirmed'
        ]);

        $credentials = $request->only('email','password');

        if(!Auth::attempt($credentials)){
            
            return response([
                'message'=>'Invalid Credentials'
            ],403);
        }

        return response([
            'user'=>auth()->user(),
            'token'=>auth()->user()->createToken('secret')->plainTextToken
        ],200);
    }

    public function logout(){
        auth()->user()->tokens()->delete();
        return response([
            'message'=>'Logged Out'
        ],200);
    }

    public function userdetails(){
        return response([
            'user'=>auth()->user()
        ],200);
    }

    public function update(Request $request){
        
        $validator = Validator::make($request->all(), [
           'name'=>'required|string'  
        ]);

        if ($validator->fails()) {
            return response([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $image = $this->saveImage($request->image, 'profiles');

        $user = User::find(auth()->user()->id);

        if($user){
            $user->name = $request->input('name');
            $user->image = $image;
            $user->save();
            
            return response([
                'message'=>'User updated',
                'user'=>$user
            ],200);
    
        }
         
       
    }
}
