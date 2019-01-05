<?php

namespace App\Http\Controllers;

use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $data['tags'] = Cache::get('tags',function(){
            return Tag::paginate(10);
        });

        return view('backend/tags',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend/create-tag');    

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
            'name' => 'required',
        ]);

        if($validator->fails()){

            return redirect()->back()->withErrors($validator);
        }

     
        $slug = str_slug($request->name);

        $tagExists = Tag::where('slug',$slug)->first();

        if($tagExists){
            Session::flash('type','danger');
            Session::flash('message','Tag Already exists');
    
            return redirect()->route('tags.create');
        }

        $tag = Tag::create([
            'name' => $request->name,
            'slug' => $slug,
            ]);

        
        Session::flash('type','success');
        Session::flash('message','Tag Successfully Added');

        return redirect()->route('tags.create');

    }
        // Tag live search 

        public function tagSearch(Request $request){
        
            $output = "";
    
            if($request->ajax()){
                $tags = Tag::where('name','LIKE','%'.$request->search.'%')->get();
                
                if($tags->count() > 0){
                    foreach($tags as $tag){
                        $output .='<tr class="gradeA odd" role="row">';
                        
                        $output .= sprintf('<td class="sorting_1">%s</td>',$tag->id);
                        $output .= sprintf('<td class="sorting_1">%s</td>',$tag->name);
                        $output .= sprintf('<td class="sorting_1"><a href="%s" class="btn btn-primary">Details</a></td>', route('tags.show',$tag->slug));
                        $output .='</tr>';
                    }
    
               
                }
    
            }
    
            return \response($output);
             
        }
    
    
        // Tag Limit 
    
            // Tag live search 
    
            public function tagLimit(Request $request){
            
                $output = "";
        
                if($request->ajax()){
                    $tags = Tag::take($request->limit)->get();
                    
                    if($tags->count() > 0){
                        foreach($tags as $tag){
                            $output .='<tr class="gradeA odd" role="row">';
                            
                            $output .= sprintf('<td class="sorting_1">%s</td>',$tag->id);
                            $output .= sprintf('<td class="sorting_1">%s</td>',$tag->name);
                            $output .= sprintf('<td class="sorting_1"><a href="%s" class="btn btn-primary">Details</a></td>', route('categories.show',$tag->slug));
                            $output .='</tr>';
                        }
        
                   
                    }
        
                }
        
                return \response($output);
                 
            }
        
    
     
            


    /**
     * Display the specified resource.
     *
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function show(Tag $tag)
    {   
        $data['tag'] = $tag;

        return view('backend/tag',$data);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function edit(Tag $tag)
    {
        $data["tag"] = $tag;

        return view('backend/edit-tag',$data);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tag $tag)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
        ]);

        if($validator->fails()){

            return redirect()->back()->withErrors($validator);
        }

     
        $slug = str_slug($request->name);

   

        $tag->create([
            'name' => $request->name,
            'slug' => $slug,
            ]);

        
        Session::flash('type','success');
        Session::flash('message','Tag Successfully Updated');

        return redirect()->route('tags.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();

        Session::flash('type','success');
        Session::flash('message','Tag Deleted Successfully');

        return redirect()->route('tags.index');
    }
}
