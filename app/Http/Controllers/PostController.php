<?php

namespace App\Http\Controllers;
use DB;
use JWTAuth;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use App\Models\MainCategory;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
class PostController extends Controller
{

    public function allPosts(){
         $posts = DB::table('users')
        ->select('user_id','users.name', 'users.surname','posts.title', 'posts.user_id', 'main_categories.mainCategory', 'posts.image', 'posts.content', 'posts.likes', 'posts.created_at')
        ->orderBy('created_at', 'DESC')
        ->join('posts', 'posts.user_id','=','users.id')
        ->join('main_categories', 'main_categories.id','=','posts.mainCategory')
        ->limit(8)->get();
        //$posts = User::with('posts')->get();
        //$postCount = $user->posts->count();


        //nees to be improve 
        $userCount = array();
        $postCount = User::all();
        foreach($postCount as $i => $value){
            array_push($userCount, ['name' => $value->name . ' ' . $value->surname, 'skills' => 'needs to be program', 'postCount' => User::find($value->id)->posts->count()]);
        }   
        return compact('posts','userCount');

    }




    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // get all post
        //$user = JWTAuth::user();
        //$posts = Post::where('user_id',"=", $user->id)->get();
        //return($posts);
        //return Post::all();

        $users = JWTAuth::user()->following()->pluck('profiles.user_id');
       //$posts = Post::whereIn('user_id', $users)->with('user')->latest()->paginate(5);
        $posts = Post::whereIn('user_id', $users)->with('user')->get();
        return(compact('posts'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(),[
            'title' => 'required',
            'content' => 'required',
            'mainCategory' => 'required',
            'image' => ['required','image'],
        ]);

        // //validate teh data frts
        // $validate = $request->validate([
        //     'title' => 'required',
        //     'content' => 'required',
        //     'image' => ['required','image'],
        // ]);
        if($validator->fails()){
            return response()->json([$validator->errors()], 400);
        }
        
        $imagePath = request('image')->store('uploads', 'public');

        //Resizing image with image intervention
        $image = Image::make(public_path("storage/{$imagePath}"))->fit(1200, 1200);
        $image->save();


        $newPost = Post::create([
            'title' => $request->get('title'),
            'image' => $imagePath,
            'likes' => $request->get('likes'),  
            'content' => $request->get('content'),
            'mainCategory' => $request->get('mainCategory'),
            'user_id' => JWTAuth::user()->id,
        ]);

        // create all post
        //return Post::create($request->all());
        return ($newPost);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //show post
        return Post::find($id);
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
        //update posts
        $post = Post::find($id);
        $post->update($request->all());
        return $post;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //delte posts
        return Post::destroy($id);
    }
}
