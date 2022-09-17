<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('user-registration', 'App\Http\Controllers\ApiController@register');
Route::post('user-login', 'App\Http\Controllers\ApiController@login');

Route::get('category-list', 'App\Http\Controllers\ApiController@category_list');
Route::get('category-type-list/{id}', 'App\Http\Controllers\ApiController@category_type_list');
Route::get('sub-category-list/{cat_id}/{type_id}', 'App\Http\Controllers\ApiController@sub_category_list');
Route::get('content-list/{cat_id}/{type_id}/{sub_cat_id}', 'App\Http\Controllers\ApiController@content_list');
Route::get('sub-content-list/{cat_id}/{type_id}/{sub_cat_id}/{content_id}', 'App\Http\Controllers\ApiController@subcontent_list');
Route::get('lecture-list/{cat_id}/{type_id}/{sub_cat_id}/{content_id}/{speaker_id}', 'App\Http\Controllers\ApiController@lecture_list');
Route::get('file-list/{cat_id}/{type_id}/{sub_cat_id}/{content_id}/{speaker_id}/{lecture_id}', 'App\Http\Controllers\ApiController@file_list');
Route::get('file-by-id/{id}', 'App\Http\Controllers\ApiController@file_by_id');
Route::get('speaker-list', 'App\Http\Controllers\ApiController@speaker_list');
Route::get('file-list-by-speaker/{speaker_id}', 'App\Http\Controllers\ApiController@file_list_by_speaker');
Route::get('file-list-depands-speaker/{speaker_id}/{subcategory_id}/{content_id}/{lecture_id}', 'App\Http\Controllers\ApiController@file_list_depends_speaker');

Route::get('following-speaker/{user_id}/{speaker_id}', 'App\Http\Controllers\ApiController@following_speaker');
Route::get('following-list/{user_id}/', 'App\Http\Controllers\ApiController@following_list');

Route::get('unfollow-speaker/{user_id}/{speaker_id}', 'App\Http\Controllers\ApiController@unfollow_speaker');

Route::get('listen-later/{user_id}/{file_id}', 'App\Http\Controllers\ApiController@listen_later_add');
Route::get('listen-later-list/{user_id}/', 'App\Http\Controllers\ApiController@listen_later_list');
Route::get('delete-listen-later/{user_id}/{file_id}', 'App\Http\Controllers\ApiController@delete_listen_later');

Route::get('nigunim-speaker/{nigunim_id}', 'App\Http\Controllers\ApiController@nigunim_speaker');
Route::get('nigunim-albam/{nigunim_id}/{cat_id}', 'App\Http\Controllers\ApiController@nigunim_albam');
Route::get('nigunim-file-list/{nigunim_id}/{cat_id}/{albam_id}', 'App\Http\Controllers\ApiController@nigunim_file_list');
Route::get('nigunim-file-details/{file_id}', 'App\Http\Controllers\ApiController@nigunim_file_details');

Route::get('recently-played/{user_id}/{file_id}', 'App\Http\Controllers\ApiController@recently_played');
Route::get('recently-played-list/{user_id}/', 'App\Http\Controllers\ApiController@recently_played_list');

Route::get('topics-list/{id}', 'App\Http\Controllers\ApiController@topics_list');
Route::get('all-parsha', 'App\Http\Controllers\ApiController@all_parsha');
Route::get('file-list-by-content/{id}', 'App\Http\Controllers\ApiController@file_list_by_content');
Route::get('file-list-by-category/{id}', 'App\Http\Controllers\ApiController@file_list_by_category');
Route::get('all-holidays', 'App\Http\Controllers\ApiController@all_holidays');
Route::get('holiday-list-by-category/{id}', 'App\Http\Controllers\ApiController@holiday_list_by_cat');
Route::get('topics-file-by-id/{id}', 'App\Http\Controllers\ApiController@topics_file_id');
Route::get('kol-rebeinu-category', 'App\Http\Controllers\ApiController@kol_rabeinu_category');
Route::get('topics-of-sichos-cat-list/{id}', 'App\Http\Controllers\ApiController@topics_of_sichos_cat_list');
Route::get('kol-rebeinu-subcategory-file-list/{id}', 'App\Http\Controllers\ApiController@kol_subcat_file_list');
Route::get('kol-rebeinu-file-by-id/{id}', 'App\Http\Controllers\ApiController@kol_file_by_id');
Route::get('sichos-kodesh/{id}', 'App\Http\Controllers\ApiController@sichos_kodesh');
Route::get('mammer/{id}', 'App\Http\Controllers\ApiController@mammer');
Route::get('story-category', 'App\Http\Controllers\ApiController@story_category');
Route::get('story-file/{id}', 'App\Http\Controllers\ApiController@story_file');
Route::get('story-file-by-id/{id}', 'App\Http\Controllers\ApiController@story_file_by_id');
Route::get('farbrengen-months', 'App\Http\Controllers\ApiController@farbrengen_months');
Route::get('farbrengen-speaker', 'App\Http\Controllers\ApiController@farbrengen_speaker');
Route::get('farbrengen-by-date-month/{month}/{date}', 'App\Http\Controllers\ApiController@farbrengen_by_date');
Route::get('farbrengen-by-id/{id}', 'App\Http\Controllers\ApiController@farbrengen_by_id');
Route::get('home', 'App\Http\Controllers\ApiController@home_page');
Route::get('nigunim-file-list-by-cat/{id}', 'App\Http\Controllers\ApiController@nigunim_file_list_by_cat');





