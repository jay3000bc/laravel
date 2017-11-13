<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/
Auth::routes();
// categories
Route::resource('categories','CategoryController',['except' => ['create']]);
Route::resource('tags','TagController',['except' => ['create']]);
//Route::resource('comments','CommentsController',['except' => ['create']]);
Route::post('comments/{id}','CommentsController@store');
Route::get('comments/{id}/edit','CommentsController@edit');
Route::put('comments/{id}','CommentsController@update');
Route::get('comments/{id}','CommentsController@delete');
Route::DELETE('comments/{id}','CommentsController@destroy');
Route::group(['middleware'=> ['web']],function(){
	Route::get('blog/{slug}',['as'=>'blog.single','uses'=>'BlogController@getSingle'])->where('slug','[\w\d\-\_]+');
	//Route::get('pages/welcome')
	Route::get('contact','PagesController@getContact');
	Route::post('contact','PagesController@postContact');
	Route::get('about','PagesController@getAbout');
	Route::get('/','PagesController@getIndex');
	Route::resource('posts','PostController');
});