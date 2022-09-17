<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/laravel', function () {
    return view('welcome');
});

Route::get('home/', 'App\Http\Controllers\IndexController@index');
Route::get('/blog', 'App\Http\Controllers\IndexController@blog');
Route::get('/', 'App\Http\Controllers\UserController@login');
Route::get('registration', 'App\Http\Controllers\UserController@registration');
Route::post('registration-save', 'App\Http\Controllers\UserController@registration_save');
Route::get('admin/dashboard', 'App\Http\Controllers\UserController@admin_dashboard');
Route::post('myLogin', 'App\Http\Controllers\UserController@myLogin');
Route::post('client-data', 'App\Http\Controllers\IndexController@save_client');
Route::get('logout', 'App\Http\Controllers\LogoutController@perform');

Route::get('questions', 'App\Http\Controllers\QuestionController@create');
Route::get('create-category', 'App\Http\Controllers\HomeController@create_category');
Route::post('save-category', 'App\Http\Controllers\HomeController@save_category');
Route::get('delete-category/{id}', 'App\Http\Controllers\HomeController@delete_category');

Route::get('create-category-type', 'App\Http\Controllers\HomeController@create_category_type');
Route::post('save-category-type', 'App\Http\Controllers\HomeController@save_category_type');
Route::get('delete-category-type/{id}', 'App\Http\Controllers\HomeController@delete_category_type');
Route::get('get-type-list-depends-on-cat/{id}', 'App\Http\Controllers\HomeController@type_list_depends_on_category');
Route::get('create-subcatagory', 'App\Http\Controllers\HomeController@create_subcategory');
Route::post('save-subcategory', 'App\Http\Controllers\HomeController@save_subcategory');

Route::get('create-content', 'App\Http\Controllers\HomeController@create_content');
Route::post('save-content', 'App\Http\Controllers\HomeController@save_content');

Route::get('create-sub-content', 'App\Http\Controllers\HomeController@create_sub_content');
Route::post('save-subcontent', 'App\Http\Controllers\HomeController@save_subcontent');

Route::get('get-subcat-list-depends-on-cat/{type_id}/{cat_id}', 'App\Http\Controllers\HomeController@subcat_list_depands_on_cat');
Route::get('get-content-list-depends-on-cat/{type_id}/{cat_id}/{subcat_id}', 'App\Http\Controllers\HomeController@content_list_depands_on_cat');
// Route::get('get-subcontent-list-depends-on-cat/{type_id}/{cat_id}/{subcat_id}/{content_id}', 'App\Http\Controllers\HomeController@subcontent_list_depands_on_cat1');
Route::get('get-speaker-list-depends-on-cat/{type_id}/{cat_id}/{subcat_id}/{content_id}', 'App\Http\Controllers\HomeController@subcontent_list_depands_on_cat');
Route::get('get-lecture-list-depends-on-cat/{type_id}/{cat_id}/{subcat_id}/{content_id}/{speaker_id}', 'App\Http\Controllers\HomeController@lecture_list_depands_on_cat');

Route::get('create-speaker', 'App\Http\Controllers\HomeController@create_speaker');
Route::post('save-speaker', 'App\Http\Controllers\HomeController@save_speaker');
Route::get('speaker-list', 'App\Http\Controllers\HomeController@speaker_list')->name('speaker-list');

Route::get('create-lecture', 'App\Http\Controllers\HomeController@create_lecture');
Route::post('save-lecture', 'App\Http\Controllers\HomeController@save_lecture');

Route::get('file-upload', 'App\Http\Controllers\HomeController@file_upload');
Route::get('feature-file-upload', 'App\Http\Controllers\HomeController@feature_file_upload');
Route::post('save-file', 'App\Http\Controllers\HomeController@save_file_upload');
Route::post('save-feature-file', 'App\Http\Controllers\HomeController@save_feature_file');
Route::get('all-file-list', 'App\Http\Controllers\HomeController@all_file_list');

Route::get('muggidei-shiurim', 'App\Http\Controllers\HomeController@muggidei_shiurim');
Route::post('save-muggidei-shiurim', 'App\Http\Controllers\HomeController@save_muggidei_shiurim');

Route::get('create-nigunim-category', 'App\Http\Controllers\HomeController@create_nigunim_category');
Route::post('save-nigunim-category', 'App\Http\Controllers\HomeController@save_nigunim_category');
Route::get('delete-nigunim-category/{id}', 'App\Http\Controllers\HomeController@delete_nigunim_category');
Route::get('delete-nigunim-albam/{id}', 'App\Http\Controllers\HomeController@delete_nigunim_albam');

Route::get('create-nigunim-album', 'App\Http\Controllers\HomeController@create_nigunim_album');
Route::post('save-nigunim-album', 'App\Http\Controllers\HomeController@save_nigunim_album');
Route::get('create-nigunim-file', 'App\Http\Controllers\HomeController@create_nigunim_file');
Route::get('get-albam-list-depends-on-cat/{id}', 'App\Http\Controllers\HomeController@albam_list_on_cat');
Route::post('save-nigunim-file', 'App\Http\Controllers\HomeController@save_nigunim_file');
Route::get('create-topics-category', 'App\Http\Controllers\HomeController@create_topics_cat');
Route::get('create-parshioys-type', 'App\Http\Controllers\HomeController@create_parshiyos_type');
Route::post('save-topics-category', 'App\Http\Controllers\HomeController@save_topics_cat');
Route::post('save-parshiyos-type', 'App\Http\Controllers\HomeController@save_parshiyos_type');

Route::get('create-parshioys-content', 'App\Http\Controllers\HomeController@create_parshiyos_content');
Route::post('save-parshiyos-content', 'App\Http\Controllers\HomeController@save_parshiyos_content');
Route::get('create-parshioys-group', 'App\Http\Controllers\HomeController@create_parshiyos_group');
Route::post('save-parshios-group', 'App\Http\Controllers\HomeController@save_parshiyos_group');
Route::get('parshioys-file-upload', 'App\Http\Controllers\HomeController@parshioys_file_upload');
Route::post('save-parshiyos-file', 'App\Http\Controllers\HomeController@save_parshioys_file_upload');
Route::get('get-content-list-depends-on-type/{id}', 'App\Http\Controllers\HomeController@get_content_list_depends_type');

Route::get('yomim-tovim-holiday', 'App\Http\Controllers\HomeController@yomim_tovim_holiday');

Route::post('save-yomim-tovim', 'App\Http\Controllers\HomeController@save_yomim_tovim_holiday');

Route::get('add-current-parsha', 'App\Http\Controllers\HomeController@add_current_parsha');
Route::post('save-current-parsha', 'App\Http\Controllers\HomeController@save_current_parsha');

Route::get('set-upcoming-holiday', 'App\Http\Controllers\HomeController@set_upcoming_holiday');
Route::post('save-upcoming-holiday', 'App\Http\Controllers\HomeController@save_upcoming_holiday');

Route::get('kol-rabeinu', 'App\Http\Controllers\HomeController@kol_rabeinu');
Route::post('save-kol-rabeinue-category', 'App\Http\Controllers\HomeController@save_kol_rabeinu');

Route::get('year', 'App\Http\Controllers\HomeController@year');
Route::post('save-year', 'App\Http\Controllers\HomeController@save_year');

Route::get('month', 'App\Http\Controllers\HomeController@month');
Route::post('save-month', 'App\Http\Controllers\HomeController@save_month');

Route::get('create-event', 'App\Http\Controllers\HomeController@create_event');
Route::post('save-event', 'App\Http\Controllers\HomeController@save_event');

Route::get('niggun-category', 'App\Http\Controllers\HomeController@create_niggun_cat');
Route::post('save-niggun-category', 'App\Http\Controllers\HomeController@save_niggun_cat');

Route::get('create-stories-category', 'App\Http\Controllers\HomeController@create_stories_cat');
Route::post('save-story-category', 'App\Http\Controllers\HomeController@save_stories_cat');

Route::get('topics-of-sichos', 'App\Http\Controllers\HomeController@create_topics_of_sichos');
Route::post('save-topics-of-sichos-category', 'App\Http\Controllers\HomeController@save_topics_of_sichos');

Route::get('kol-rabeinu-file-upload', 'App\Http\Controllers\HomeController@kol_rabeinu_file_upload');
Route::post('save-kol-rabeinu-file', 'App\Http\Controllers\HomeController@kol_rabeinu_file_save');

Route::get('get-kol-rabeinu_subcat-list-depends-on-cat/{id}', 'App\Http\Controllers\HomeController@kol_rabeinu_subcat_depands');
Route::get('add-feature-for-kol-rabeinu', 'App\Http\Controllers\HomeController@kol_rabeinu_feature');
Route::get('set-feature-of-kol-rabeinu/{id}', 'App\Http\Controllers\HomeController@set_feature_of_kol_rabeinu');
Route::post('kol-rabeinu-feature-update', 'App\Http\Controllers\HomeController@kol_rabeinu_feature_update');

Route::get('add-feature-for-story', 'App\Http\Controllers\HomeController@story_file_feature');
Route::get('set-feature-story/{id}', 'App\Http\Controllers\HomeController@set_story_file_feature');
Route::post('story-feature-update', 'App\Http\Controllers\HomeController@story_feature_update');



Route::get('daily-shiurim', 'App\Http\Controllers\HomeController@daily_shiurim');
Route::get('set-daily-shurim-date/{id}/{name}', 'App\Http\Controllers\HomeController@daily_shiurim_date');
Route::post('daily-seuirm-val-update', 'App\Http\Controllers\HomeController@daily_seuirm_val_update');
Route::post('daily-seuirm-date-sate-for-inyonei-geulah', 'App\Http\Controllers\HomeController@daily_seuirm_val_update_for_inyonei_geulah');

Route::post('save-feature-status', 'App\Http\Controllers\HomeController@save_feature_status');
Route::get('create-story-category', 'App\Http\Controllers\HomeController@create_story_category');
Route::get('add-main-story-file', 'App\Http\Controllers\HomeController@add_main_story_file');
Route::post('save-main-story-category', 'App\Http\Controllers\HomeController@save_main_story_cat');
Route::post('save_main_story_file', 'App\Http\Controllers\HomeController@save_main_story_file');

Route::get('create-farbrengen-month', 'App\Http\Controllers\HomeController@create_farbrengen_month');
Route::post('save-farbrengen-month', 'App\Http\Controllers\HomeController@save_farbrengen_month');
Route::get('create-farbrengen-date', 'App\Http\Controllers\HomeController@create_farbrengen_date');
Route::get('add-parshas-hashavua-feature', 'App\Http\Controllers\HomeController@add_parshas_hashavua_feature');
Route::get('set-feature-parshas-hashavua/{id}', 'App\Http\Controllers\HomeController@set_parshas_hashavua_feature');
Route::post('parshas-hashvua-feature-update', 'App\Http\Controllers\HomeController@parshas_hasvua_feature_update');
Route::get('add-farbrengen-file', 'App\Http\Controllers\HomeController@add_farbrengen_file');
Route::post('save-farbrengen-date', 'App\Http\Controllers\HomeController@save_farbrengen_date');
Route::post('save-farbrengen-file', 'App\Http\Controllers\HomeController@save_farbrengen_file');


