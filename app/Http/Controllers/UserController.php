<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;


class UserController extends Controller
{
    public function authenticate(Request $request){
        $credentials= $request->only('email', 'password');

        try{
            if(!$token = JWTAuth::attempt($credentials)){
                return response()->json(['error' => 'invalid_credentials'], 400);
            }
            
            //verification of email is verify
            if(JWTAuth::user()->email_verified_at== null){
                return response()->json([
                    "message" => 'email not verify',
                    "success" => false
                ], 400);
            }
        }catch(JWTEception $e){
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        //modufy only to send the necessary parameters!!!!!
        $user = JWTAuth::user();
            
        return response()->json(compact('user', 'token'));
    }

    public function getAuthenticatedUSer(){

        try{
            if(!$user = JWTAuth::parseToken()->authenticate()){
                return response()->json(['user_not_found'], 404);
            }    
        } catch(Tymon\JWTAuth\Exceptions\TokenExpiredException $e){
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch(Tymon\JWTAuth\Exceptions\TokenInvalidException $e){
            return response()->json(['token_invalid'], $e->getStatusCode());
        }catch(Tymon\JWTAuth\Exceptions\JWTException $e){
            return response()->json(['token_absent'], $e->getStatusCode());
        }
        return response()->json([compact('user')]);
    }

    public function register(Request $request){
        
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
        
        if($validator->fails()){
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $user = User::create([
            'name' => $request->get('name'),
            'surname' => $request->get('surname'),
            'email' => $request->get('email'),
            'phonenumber' => $request->get('phonenumber'),
            'password' => Hash::make($request->get('password')),
        ]);


        //sending email verificvation
        $user->sendEmailVerificationNotification();

        return response()->json([
            'message'=> 'Check your email'
        ]);

        //$token = JWTAuth::fromUser($user);

        //return response()->json(compact('user','token'), 201);
    }


}
