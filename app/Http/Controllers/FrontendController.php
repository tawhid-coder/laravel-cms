<?php

namespace App\Http\Controllers;

use App\Tag;
use App\Post;
use App\User;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FrontendController extends Controller
{
    // homepage

    public function index(){

        // $data["posts"] = cache("posts",function(){
        //     return Post::paginate(10);
        // });

        $data["posts"] = Post::orderBy('created_at','desc')->paginate(10);


        $data["categories"] = Category::all();
    

        return view('frontend/home',$data);
    }

    public function postAuthor($author)
    {   
        $user = User::where('username',$author)->first();
        $data["user"] = $user;
        $data["posts"] = $user->posts()->paginate(10);
        return view('frontend/author',$data);
    }



    // showing single post

    public function postSingle($slug){
        
        $post = Post::where('slug',$slug)->first();
        


        $data['post'] = $post;
        return view('frontend/post-single',$data);
    }


    // Category Posts

    public function categoryPosts($slug){
        
        $category =  Category::where('slug',$slug)->first();
        $categories =  Category::all();
        
        $data['name'] =  $category->name;
        $data['posts'] =  $category->posts()->paginate(10);
        $data['categories'] = $categories;

        return view('frontend/category-posts',$data);

    }

        // Tag Posts

        public function tagPosts($slug){
        
            $tag =  Tag::where('slug',$slug)->first();
          $data['name'] =  $tag->name;
            $data['posts'] =  $tag->posts()->paginate(10);
          
            return view('frontend/tag-posts',$data);
    
        }

        
    // showing page

    public function page($token){
        return view('frontend/page');
    }

    // Search
    
    public function search(Request $request){
     
        $validator = Validator::make($request->all(),[
            'search' => 'required',
        ]);

        if($validator->fails()){

            return redirect()->back()->withErrors($validator);
        }


        $posts = Post::where('title' , 'like' , '%'.$request->search.'%')->
        orWhere('content','like','%'.$request->search.'%')->
        paginate(10);

        $categories =  Category::all();

        $data['name'] =  $request->search;
        $data['posts'] =  $posts;
        $data['categories'] = $categories;

        return view('frontend/search-posts',$data);

    }

}
