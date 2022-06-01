<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/chat-{id}', [App\Http\Controllers\ChatsController::class, 'index'])->name('chat');
Route::get('/messages', [App\Http\Controllers\ChatsController::class, 'fetchMessages']);
Route::post('/messages', [App\Http\Controllers\ChatsController::class, 'sendMessage']);

Route::post('/formSubmit', [App\Http\Controllers\ChatsController::class, 'uploadPhoto']);


// Route::group(['middleware' => 'auth'], function(){
//     Route::get('video_chat', [App\Http\Controllers\VideoChatController::class, 'index']);
//     Route::post('auth/video_chat', [App\Http\Controllers\VideoChatController::class, 'auth']);
//   });


Route::get('/video-chat', function () {
    // fetch all users apart from the authenticated user
    $users = User::where('id', '<>', Auth::id())->get();
    return view('video-chat', ['users' => $users]);
});

// Endpoints to call or receive calls.
Route::post('/video/call-user', [App\Http\Controllers\VideoChatController::class, 'callUser']);
Route::post('/video/accept-call', [App\Http\Controllers\VideoChatController::class, 'acceptCall']);

Route::post('logout', [App\Http\Controllers\Auth\LogoutController::class, 'perform'])->name('logout');

Route::get('active/{id}', [App\Http\Controllers\HomeController::class, 'check_active'])->name('check.active');