<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Http\Controllers\FacebookController;
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
    return view('welcome');
});
Route::get('/test', function(){
    // $data = DB::table('page')->get();
    // var_dump($data);
   // return \config('key.app_id');
  // return Carbon::now();
//   $photos = array();
//   $photos['attached_media[' . 1 . ']'] = '{"media_fbid":"' . "123123" . '"}';
//   dd($photos);
dd(public_path('../assets/') );
});



Route::get('/loginfb',function(){
    return view('login');
});
Route::get('/loginfbjs',function(){
    return view('social');
});
// Route::get('thongtin', ['as' => 'giaodien.thongtin', 'uses' => 'GiaoDienController@thongtin']);
Route::post('/createPage',['as'=>'page.createpage', 'uses'=>'Page@createPage']);
Route::post('/create-post',['as'=>'page.createpost', 'uses'=>'Page@createPost']);
Route::get('/get-info-page',['as'=>'page.getinfopage', 'uses'=>'Page@getInfoPage']);

Route::get('/demo-facebook',['as'=>'facebook.getpagetoken','uses'=>'FacebookController@getPageToken']);
// Route::get('/demo-facebook',function(){
//     $fb = new FacebookController();
//     $fb->getPageToken();
// });
Route::get('login-php',['as'=>'facebook.login','uses'=>'FacebookController@login']);
Route::get('call-back',['as'=>'facebook.callback','uses'=>'FacebookController@callBack']);
Route::get('post-page',['as'=>'facebook.postpage','uses'=>'FacebookController@postPage']);
Route::get('get-js',['as'=>'facebook.getjs','uses'=>'FacebookController@getJavascript']);
Route::get('post-video',['as'=> 'facebook.postvideo','uses'=>'FacebookController@postVideo']);
Route::get('post-content',['as'=> 'facebook.postcontent','uses'=>'FacebookController@postContent']);
Route::get('get-demo',function(){
    $photos = array();
    $photos['attached_media[' . 0 . ']'] = '{"media_fbid":"' ."12213". '"}';
    $photos['mess']='abc';
    dd($photos);
});
Route::resource('/demo222/', 'FacebookController')->parameters(['' => 'facebook-page']);

Route::get('/auth/redirect/{provider}', 'SocialController@redirect');
Route::get('/callback/{provider}', 'SocialController@callback');


