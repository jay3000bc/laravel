<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Post;
use Mail;
use Session;
use Redirect;
class PagesController extends Controller {

	public function getIndex() {
		$posts =Post::orderBy('created_at','desc')->limit(3)->get();
		return view('pages.welcome')->with('posts',$posts);
	}
	public function getAbout() {
		return view('pages.about');
	}
	public function getContact() {
		return view('pages.contact');	
	} 
	public function postContact(Request $request) {
		$this->validate($request,[
			'email'=>'required|email',
			'subject'=>'required|min:3',
			'message'=>'required|min:10'
			]);
		$data =array(
			'email' => $request->email,
			'subject' => $request->subject,
			'bodyMessage' =>$request->message		
		);
		Mail::send('emails.contact',$data,function($message) use ($data){
			$message->from($data['email']);
			$message->to('mukesh@nickelfox.com');
			$message->subject($data['subject']);
		});
		Session::flash('success','The mail is sent succesfully');
		return Redirect::to('/');
	}
}