<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Post;
use App\Category;
use Session;
use App\Tag;
use Purifier;
use Image;
use Storage;
use App\post_tags;


class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $posts = Post::orderBy('id','DESC')->paginate(3);
        //echo $posts;

        return view('posts.index')->with('posts',$posts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        $tags=  Tag::all();
        return view('posts.create')->withCategories($categories)->withTags($tags);
       

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        // dd(count($request->tags));
        $this->validate($request,array(
            'title'=> 'required|max:255',
            'slug'=>'required|alpha_dash|min:5|max:255|unique:posts,slug',
            'category_id'=>'required|integer',
            'body'=>'required',
            'image'=>'sometimes|image'
        ));
        $post= new Post;
        $post->title = $request->title;
        $post->slug = $request->slug;
        $post->category_id = $request->category_id;
        // $post->body = Purifier::clean($request->body);
        $post->body = $request->body;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '.' .$image->getClientOriginalExtension();
            $location = public_path('images/'.$filename);
            // dd(is_writable($location . $filename));
            Image::make($image)->resize(400,400)->save($location);
            $post->images = $filename;
        }

        $post->save();
        // if($request->tags){
        //     $tags = array();
        //     $tags = $request->tags;
        //     for($i=0; $i<count($tags);$i++){
        //         $post_tags = new post_tags();
        //         $post_tags->post_id = $post->id;
        //         $post_tags->tag_id = $tags[$i];
        //         $post_tags->save();
        //     }
            
        // }
        $post->tags()->sync($request->tags,false);
        if(isset($request->tags)) {
            $post->tags()->sync($request->tags,false);
        } else{
            $post->tags()->sync(array());   
        }

        Session::flash('success','The Post is Successfully Saved');
        return redirect()->route('posts.show',$post->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
        $post= Post::find($id);
        //echo $post;
        return view('posts.show')->with('post',$post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categories= Category::all();
        $tags = Tag::all();
        $post= Post::find($id);
        return view('posts.edit')->with('post',$post)->withCategories($categories)->withTags($tags);
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
        $post= Post::find($id);
        $this->validate($request,array(
            'title'=> 'required|max:255',
            'category_id'=>'required|integer',
            'slug'=>"required|alpha_dash|min:5|max:255|unique:posts,slug,$id",
            'body'=>'required',
            'image'=>'image'
            ));
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '.' .$image->getClientOriginalExtension();
            $location = public_path('images/'.$filename);
            Image::make($image)->resize(400,400)->save($location);
            $oldFilename = $post->images;
            //dd($oldFilename);
            // update old filename
            $post->images = $filename;
            // delete old file 
            Storage::delete($oldFilename);

        }

        //$post= Post::find($id);
        $post->title = $request->title;
        $post->slug = $request->slug;
        $post->category_id = $request->category_id;
        $post->body = Purifier::clean($request->body);
        $post->save();
        if(isset($request->tags)) {
            $post->tags()->sync($request->tags);
        } else{
            $post->tags()->sync(array());   
        }
        
        Session::flash('success','The Post is Successfully Updated');
        
        return redirect()->route('posts.show',$post->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $post= Post::find($id);
      $post->tags()->detach();
      Storage::delete($post->images);
      $post->delete();
      Session::flash('success','The Post is successfully Deleted');
      return redirect()->route('posts.index');  
    }
}
