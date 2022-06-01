<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;
use App\User;
use App\Post;
use Session;
class CommentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth',['except' => 'store']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$id)
    {
        $post= Post::find($id);
        $comment = new Comment;
        $comment->comment = $request->comment;
        $comment->name = $request->name;
        $comment->email =$request->email;
        $comment->approved = true;
        $comment->post()->associate($post);
        $comment->save();
        Session::flash('success',"The comment Posted Succesfully");
        return redirect()->route('blog.single',[$post->slug]);

        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       $comment = Comment::find($id);
       return view('comments.edit')->with('comment',$comment);
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
        $comment = Comment::find($id);
        $this->validate($request,array(
            'comment'=>'required'
            ));

        $comment->comment = $request->comment;
        $comment->save();
        Session::flash("success","The comment is updated Succesfully");
        return redirect()->route('posts.show',$comment->post->id);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $comment = Comment::find($id);
        return view('comments.delete')->with('comment',$comment);
    }
    public function destroy($id)
    {
      $comment = Comment::find($id);
      $post_id =$comment->post->id;
      $comment->delete();
      Session::flash('success','Comment Deleted'); 
      return redirect()->route('posts.show',$post_id);
    }
}
