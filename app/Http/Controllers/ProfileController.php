<?php

namespace App\Http\Controllers;

use JWTAuth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use App\Models\Profile;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
 
        //$user = JWTAuth::user();
        
        //$postCount = $user->posts->count();

        //if the authenticated user contains $user is true otherwise is false
        $follows = (JWTAuth::user()) ?  JWTAuth::user()->following->contains($user->id): false;

        $postCount =  $user->posts->count();
       

        $followersCount = $user->profile->followers->count();
       

        $followingCount = $user->following->count();


         //Just I need to actualize the user 
       $profile = $user->profile;
        //Just I need to actualize posts
       $posts = $user->posts;
  


        //return response()->json(compact('user', 'follows'));
        return(compact('user', 'follows', 'postCount', 'followersCount', 'followingCount'));

        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
