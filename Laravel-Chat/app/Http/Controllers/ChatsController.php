<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;


class ChatsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show chats
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $chat_user = User::find($id);
        $users = User::where('id', '<>', Auth::id())->get();
        $allusers = User::all();
        return view('chat',compact('chat_user','users','allusers'));
    }

    /**
     * Fetch all messages
     *
     * @return Message
     */
    public function fetchMessages()
    {   

        return Message::with('user')->get();
        // $user = User::find(Auth::user()->id);
        // $messages = $user->messages;
        // return [
        //     'messages'=>$messages,
        //     'user' =>$user
        // ];
    }

    /**
     * Persist message to database
     *
     * @param  Request $request
     * @return Response
     */
    public function sendMessage(Request $request)
    {

        if(!empty($request->message)){ 
            
        //     $user = Auth::user();
        //     $message = $user->messages()->create([
        //     'message' => $request->input('message')
        // ]);
        $user = Auth::user();
        $message = new Message();
        $message->to = $request->to['id'];
        $message->from = $user->id;
        $message->message = $request->message;
        $message->save();

        $to = User::find($request->to['id']);
        $from = $user;
        broadcast(new MessageSent($to,$from,$message))->toOthers();

        // broadcast(new MessageSent($user, $message))->toOthers();


        }
        

        return ['status' => 'Message Sent!'];
    }

    public function uploadPhoto(Request $request)
    { 
        $user = Auth::user();
        $message = new Message();
        $message->from = Auth::user()->id;
        $message->to = $request->to;
        $message->message ='';
        $message->file = $request->image;
        $fileName = time().'_'.$request->image->getClientOriginalName();

        // // $message->file = $request->file('image')->storeAs('files',$fileName,'public');
        // $ext = $request->file('image')->getClientOriginalExtension();
        // // $path = public_path('storage/');
        // $request->file('image')->move("storage/files", "{$request->file('image')}.{$ext}");
        // $message->file = $request->file('image').$ext;

        // // $message->file = 'http://127.0.0.1:8000/storage/'.$file;

        /* check Request has file or not */
        if($request->hasFile('image')){

            /* get File from Request */
            $file = $request->file('image');

            /* get File Extension */
            $extension = $file->getClientOriginalExtension();

            /* Your File Destination */
            $destination = 'storage/files/';

            /* unique Name for file */
            $filename = uniqid() . '.' . $extension;

            /* finally move file to your destination */
            $file->move($destination, $filename);

            $message->file = "files/".$filename;
        }



        $message->save();
        
        $to = User::find($request->to);
        $from = $user;
        broadcast(new MessageSent($to,$from,$message))->toOthers();
        // broadcast(new MessageSent($user, $message))->toOthers();
        return response()->json(['message'=>$message]);
    }

}
