<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;

class ChatsController extends Controller
{
    //

    //Add the below functions
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('chat');
    }

    public function fetchMessages()
    {
        return Message::with('user')->get();
    }

    public function sendMessage(Request $request)
    {
        
        // $message = $user->messages()->create([
        //     'message' => $request->input('message')
        // ]);
        if(!empty($request->message)){  

            $user = Auth::user();
            $message = new Message();
            $message->message = $request->message;
            $message->user_id = $user->id;
            $message->save();

            broadcast(new MessageSent($user, $message))->toOthers();
        }
        return ['status' => 'Message Sent!'];
    }


    public function uploadPhoto(Request $request)
    { 
        $user = Auth::user();
        $message = new Message();
        $message->user_id = Auth::user()->id;
        $message->message ='';
        $message->file = $request->image;
        $fileName = time().'_'.$request->image->getClientOriginalName();
        $file= $request->file('image')->storeAs('files',$fileName,'public');
        $message->file = 'http://127.0.0.1:8000/storage/'.$file;
        $message->save();
        
        broadcast(new MessageSent($user, $message))->toOthers();
        return response()->json(['message'=>$message]);
    }


}
