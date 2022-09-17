<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Support\Facades\Auth; 
use App\Models\Category;
use App\Models\CategoryType;
use App\Models\SubCategory;
use App\Models\Content;
use App\Models\Speaker;
use App\Models\SubContent;
use App\Models\Lecture;
use App\Models\UploadFile;
use App\Models\User;
use App\Models\FollowingSpeaker;
use App\Models\ListenLaterFile;
use App\Models\NigunimCategory;
use App\Models\AlbamsList;
use App\Models\NigunimFile;
use App\Models\RecentlyPlayedList;
use App\Models\KolRabeinuCategory;
use App\Models\KolRabeinuFile;
use App\Models\MainStoryCategory;
use App\Models\StoryFile;
use App\Models\FarbrengenMonth;
use App\Models\FarbrengenDate;
use App\Models\FarbrengenFile;
use App\Models\TopicsCategory;
use App\Models\HolidayList;
use DB;

class ApiController extends Controller
{
    public function category_list()
    {
        $data = Category::all();
        $arr = [];
        foreach($data as $val){
            $arr[] = array(
                "category_name" => $val->name,
                "id" => $val->id,
            );
        }
        if($arr){
           return $this->success_error(false, "success", $arr, 200);
        }else{
            return $this->success_error(false, "data not found", '', 400);
        }
    }

    public function category_type_list($id)
    {
       $type = DB::table('category_types as t')->select(
                't.id as id',
                't.type_name',
                't.category_id',
                'c.name'
            )
            ->leftjoin('categories as c', 't.category_id', '=', 'c.id')
            ->where('t.category_id', $id)
            ->get();
        $typeList = [];
        $subList = [];

        foreach($type as $val){
            $typeList[] = array(
                "category_name" => $val->name,
                "type_name" => $val->type_name,
                "id" => $val->id,
                "category_id" => $val->category_id,
            );

            $subcat = DB::table('sub_categories')->where('category_id', $val->category_id)->where('type_id', $val->id)->get();

            foreach($subcat as $dt){
                $subList[] = array(
                "subcategory_name" => $dt->subcategory_name,
                "id" => $dt->id,
                "type_id" => $dt->type_id,
            );

            }
            
        }

        $data['typeList'] = $typeList;
        $data['subList'] = $subList;
        if($data){
           return $this->success_error(false, "success", $data, 200);
        }else{
            return $this->success_error(false, "data not found", '', 400);
        }     
    }

    public function sub_category_list($cat_id, $type_id)
    {
        $data = DB::table('sub_categories as s')->select(
                's.id as id',
                's.category_id',
                's.type_id',
                's.subcategory_name',
                'c.name',
                't.type_name'
            )
                ->leftjoin('categories as c', 's.category_id', '=', 'c.id')
                ->leftjoin('category_types as t', 's.type_id', '=', 't.id')
                ->where('s.category_id', $cat_id)->where('s.type_id', $type_id)
                ->get();
        $arr = [];        
        foreach($data as $val){
            $arr[] = array(
                "category_name" => $val->name,
                "type_name" => $val->type_name,
                "subcategory_name" => $val->subcategory_name,
                "id" => $val->id,
                "category_id" => $val->category_id,
                "type_id" => $val->type_id,
            );
        }
        if($arr){
           return $this->success_error(false, "success", $arr, 200);
        }else{
            return $this->success_error(false, "data not found", '', 400);
        }         
        
    }

    public function content_list($category_id, $type_id, $sub_cat_id)
    {
       $content = DB::table('contents as con')->select(
                'con.id as id',
                'con.category_id',
                'con.type_id',
                'con.subcategory_id',
                'con.content_name',
                'c.name',
                't.type_name',
                's.subcategory_name'
            )
                ->leftjoin('categories as c', 'con.category_id', '=', 'c.id')
                ->leftjoin('category_types as t', 'con.type_id', '=', 't.id')
                ->leftjoin('sub_categories as s', 'con.subcategory_id', '=', 's.id')
                ->where('con.category_id', $category_id)->where('con.type_id', $type_id)->where('con.subcategory_id', $sub_cat_id)
                ->get();

        $contentList = [];
        $speakerList = [];
        $sectionList = [];      
        foreach($content as $val){
            $contentList[] = array(
                "category_name" => $val->name,
                "type_name" => $val->type_name,
                "subcategory_name" => $val->subcategory_name,
                "content_name" => $val->content_name,
                "content_id" => $val->id,
                "category_id" => $val->category_id,
                "type_id" => $val->type_id,
                "subcategory_id" => $val->subcategory_id,
            );

            $speaker = DB::table('sub_contents as sc')->select(
                'sc.id as id',
                'sc.category_id',
                'sc.type_id',
                'sc.subcategory_id',
                'sc.content_id',
                'sc.speaker_id',
                'sp.speaker_name'
            )
            ->leftjoin('speakers as sp', 'sc.speaker_id', '=', 'sp.id')
            ->where('category_id', $val->category_id)->where('type_id', $val->type_id)->where('subcategory_id', $val->subcategory_id)->where('content_id', $val->id)->get(); 

            foreach ($speaker as $sp) {
                $speakerList[] = array(
                    "speaker_name" => $sp->speaker_name,
                    "speaker_id" => $sp->speaker_id,
                    "content_id" => $sp->content_id,
                );

                $section = DB::table('lectures')->where('category_id', $val->category_id)->where('type_id', $val->type_id)->where('subcategory_id', $val->subcategory_id)->where('content_id', $val->id)->where('speaker_id', $sp->speaker_id)->get();

                foreach ($section as $sc) {
                    $sectionList[] = array(
                        "lucture_name" => $sc->lecture_name,
                        "section_id" => $sc->id,
                        "speaker_id" => $sc->speaker_id,
                        "content_id" => $sc->content_id,
                        "category_id" => $sc->category_id,
                        "type_id" => $sc->type_id,
                        "subcategory_id" => $sc->subcategory_id,
                    );
                }    
            }

        } 

        $data['contentList'] = $contentList;
        $data['speakerList'] = $speakerList;
        $data['sectionList'] = $sectionList;

        if($data){
           return $this->success_error(false, "success", $data, 200);
        }else{
            return $this->success_error(false, "data not found", '', 400);
        }



    }




    public function file_list($cat_id, $type_id, $sub_cat, $cont_id, $speaker_id, $lecture_id)
    {
        $data = DB::table('upload_files as sc')->select(
                'sc.id as id',
                'sc.category_id',
                'sc.type_id',
                'sc.subcategory_id',
                'sc.content_id',
                'sc.subcontent_id',
                'sc.lecture_id',
                'sc.speaker_id',
                'sc.title',
                'sc.short_description',
                'sc.topics',
                'sc.file_type',
                'sc.audio',
                'sc.video',
                'sc.audio_link',
                'sc.video_link',
                'sc.file_link_type',
                'c.name',
                't.type_name',
                's.subcategory_name',
                'con.content_name',
                'sbc.subcontent_name',
                'lc.lecture_name',
                'sp.speaker_name',
            )
                ->leftjoin('categories as c', 'sc.category_id', '=', 'c.id')
                ->leftjoin('category_types as t', 'sc.type_id', '=', 't.id')
                ->leftjoin('sub_categories as s', 'sc.subcategory_id', '=', 's.id')
                ->leftjoin('contents as con', 'sc.content_id', '=', 'con.id')
                ->leftjoin('sub_contents as sbc', 'sc.subcontent_id', '=', 'sbc.id')
                ->leftjoin('lectures as lc', 'sc.lecture_id', '=', 'lc.id')
                ->leftjoin('speakers as sp', 'sc.speaker_id', '=', 'sp.id')
                ->where('sc.category_id', $cat_id)->where('sc.type_id', $type_id)->where('sc.subcategory_id', $sub_cat)->where('sc.content_id', $cont_id)->where('sc.speaker_id', $speaker_id)->where('sc.lecture_id', $lecture_id)
                ->get();

        $arr = [];        
        foreach($data as $val){
            $arr[] = array(
                "category_name" => $val->name,
                "type_name" => $val->type_name,
                "subcategory_name" => $val->subcategory_name,
                "content_name" => $val->content_name,
                "subcontent_name" => $val->subcontent_name,
                "lecture_name" => $val->lecture_name,
                "speaker_name" => $val->speaker_name,
                "title" => $val->title,
                "short_description" => $val->short_description,
                "topics" => $val->topics,
                "file_type" => $val->file_type,
                "audio_link" => $val->audio_link,
                "video_link" => $val->video_link,
                "file_link_type" => $val->file_link_type,
                "audio" => 'public/audio_file/'.$val->audio,
                "video" => 'public/video_file/'.$val->video,
                "id" => $val->id,
                "category_id" => $val->category_id,
                "type_id" => $val->type_id,
                "subcategory_id" => $val->subcategory_id,
                "content_id" => $val->content_id,
                "subcontent_id" => $val->subcontent_id,
                "lecture_id" => $val->lecture_id,
                "speaker_id" => $val->speaker_id,
            );
        }
        if($arr){
           return $this->success_error(false, "success", $arr, 200);
        }else{
            return $this->success_error(false, "data not found", '', 400);
        }        
    }

    public function file_by_id($id)
    {
        $data = DB::table('upload_files as sc')->select(
                'sc.id as id',
                'sc.category_id',
                'sc.type_id',
                'sc.subcategory_id',
                'sc.content_id',
                'sc.subcontent_id',
                'sc.lecture_id',
                'sc.speaker_id',
                'sc.title',
                'sc.short_description',
                'sc.topics',
                'sc.file_type',
                'sc.audio',
                'sc.video',
                'sc.audio_link',
                'sc.video_link',
                'sc.file_link_type',
                'c.name',
                't.type_name',
                's.subcategory_name',
                'con.content_name',
                'sbc.subcontent_name',
                'lc.lecture_name',
                'sp.speaker_name',
            )
                ->leftjoin('categories as c', 'sc.category_id', '=', 'c.id')
                ->leftjoin('category_types as t', 'sc.type_id', '=', 't.id')
                ->leftjoin('sub_categories as s', 'sc.subcategory_id', '=', 's.id')
                ->leftjoin('contents as con', 'sc.content_id', '=', 'con.id')
                ->leftjoin('sub_contents as sbc', 'sc.subcontent_id', '=', 'sbc.id')
                ->leftjoin('lectures as lc', 'sc.lecture_id', '=', 'lc.id')
                ->leftjoin('speakers as sp', 'sc.speaker_id', '=', 'sp.id')
                ->where('sc.id', $id)
                ->get();

        $arr = [];        
        foreach($data as $val){
            $arr[] = array(
                "category_name" => $val->name,
                "type_name" => $val->type_name,
                "subcategory_name" => $val->subcategory_name,
                "content_name" => $val->content_name,
                "subcontent_name" => $val->subcontent_name,
                "lecture_name" => $val->lecture_name,
                "speaker_name" => $val->speaker_name,
                "title" => $val->title,
                "short_description" => $val->short_description,
                "topics" => $val->topics,
                "file_type" => $val->file_type,
                "audio_link" => $val->audio_link,
                "video_link" => $val->video_link,
                "file_link_type" => $val->file_link_type,
                "audio" => 'public/audio_file/'.$val->audio,
                "video" => 'public/video_file/'.$val->video,
                "id" => $val->id,
                "category_id" => $val->category_id,
                "type_id" => $val->type_id,
                "subcategory_id" => $val->subcategory_id,
                "content_id" => $val->content_id,
                "subcontent_id" => $val->subcontent_id,
                "lecture_id" => $val->lecture_id,
                "speaker_id" => $val->speaker_id,
            );
        }
        if($arr){
           return $this->success_error(false, "success", $arr, 200);
        }else{
            return $this->success_error(false, "data not found", '', 400);
        }

    }

    public function speaker_list()
    {
        $sp = Speaker::all();
        $speaker = [];
        $subcat = [];
        $content = [];
        $sectionlist = [];
        foreach($sp as $val){
            $class_number = UploadFile::where('speaker_id', $val->id)->count();
            $speaker[] = array(
                "speaker_name" => $val->speaker_name,
                "speaker_id" => $val->id,
                "class_number" => $class_number
            );

            $subc = DB::table('sub_contents as sc')->select(
                'sc.id as subcon_id',
                'sc.subcategory_id',
                'sc.speaker_id',
                'sub.subcategory_name',
                'sub.id as subcat_id'
            )
            ->leftjoin('sub_categories as sub', 'sc.subcategory_id', '=', 'sub.id')
            ->where('sc.speaker_id', $val->id)->get(); 

            foreach($subc as $sub){
                $subcatclass_number = UploadFile::where('subcategory_id', $sub->subcategory_id)->count();
                $subcat[] = array(
                    "subcategory_name" => $sub->subcategory_name,
                    "subcategory_id" => $sub->subcat_id,
                    "subcatclass_number" => $subcatclass_number,
                    "speaker_id" => $val->id
                );

                $con = DB::table('sub_contents as scon')->select(
                    'scon.id as id',
                    'scon.content_id',
                    'scon.speaker_id',
                    'con.content_name'
                )
                ->leftjoin('contents as con', 'scon.content_id', '=', 'con.id')
                ->where('scon.speaker_id', $val->id)->where('scon.subcategory_id', $sub->subcat_id)->get();

                foreach($con as $ct){
                    $conclass_number = UploadFile::where('content_id', $ct->content_id)->count();
                    $content[] = array(
                        "content_name" => $ct->content_name,
                        "content_id" => $ct->content_id,
                        "conclass_number" => $conclass_number,
                        "speaker_id" => $val->id,
                        "subcategory_id" => $sub->subcat_id
                    );

                    $section = Lecture::where('speaker_id', $val->id)->where('subcategory_id', $sub->subcat_id)->where('content_id', $ct->content_id)->get();

                    foreach($section as $sec){
                        $secclass_number = UploadFile::where('lecture_id', $sec->lecture_id)->count();
                        $sectionlist[] = array(
                            "section_name" => $sec->lecture_name,
                            "section_id" => $sec->id,
                            "secclass_number" => $secclass_number,
                            "speaker_id" => $val->id,
                            "subcategory_id" => $sub->subcat_id,
                            "content_id" => $ct->content_id
                        );
                    } 

                }
            }
        }

        $data['speaker'] = $speaker;
        $data['subcat'] = $subcat;
        $data['content'] = $content;
        $data['sectionlist'] = $sectionlist;

        if($data){
           return $this->success_error(false, "success", $data, 200);
        }else{
            return $this->success_error(false, "data not found", '', 400);
        }     

    }

    public function file_list_by_speaker($id)
    {
        $data = DB::table('upload_files as sc')->select(
                'sc.id as id',
                'sc.category_id',
                'sc.type_id',
                'sc.subcategory_id',
                'sc.content_id',
                'sc.subcontent_id',
                'sc.lecture_id',
                'sc.speaker_id',
                'sc.title',
                'sc.short_description',
                'sc.topics',
                'sc.file_type',
                'sc.audio',
                'sc.video',
                'sc.audio_link',
                'sc.video_link',
                'sc.file_link_type',
                'c.name',
                't.type_name',
                's.subcategory_name',
                'con.content_name',
                'sbc.subcontent_name',
                'lc.lecture_name',
                'sp.speaker_name',
            )
                ->leftjoin('categories as c', 'sc.category_id', '=', 'c.id')
                ->leftjoin('category_types as t', 'sc.type_id', '=', 't.id')
                ->leftjoin('sub_categories as s', 'sc.subcategory_id', '=', 's.id')
                ->leftjoin('contents as con', 'sc.content_id', '=', 'con.id')
                ->leftjoin('sub_contents as sbc', 'sc.subcontent_id', '=', 'sbc.id')
                ->leftjoin('lectures as lc', 'sc.lecture_id', '=', 'lc.id')
                ->leftjoin('speakers as sp', 'sc.speaker_id', '=', 'sp.id')
                ->where('sc.speaker_id', $id)
                ->get();

        $arr = [];        
        foreach($data as $val){
            $arr[] = array(
                "category_name" => $val->name,
                "type_name" => $val->type_name,
                "subcategory_name" => $val->subcategory_name,
                "content_name" => $val->content_name,
                "subcontent_name" => $val->subcontent_name,
                "lecture_name" => $val->lecture_name,
                "speaker_name" => $val->speaker_name,
                "title" => $val->title,
                "short_description" => $val->short_description,
                "topics" => $val->topics,
                "file_type" => $val->file_type,
                "audio_link" => $val->audio_link,
                "video_link" => $val->video_link,
                "file_link_type" => $val->file_link_type,
                "audio" => 'public/audio_file/'.$val->audio,
                "video" => 'public/video_file/'.$val->video,
                "id" => $val->id,
                "category_id" => $val->category_id,
                "type_id" => $val->type_id,
                "subcategory_id" => $val->subcategory_id,
                "content_id" => $val->content_id,
                "subcontent_id" => $val->subcontent_id,
                "lecture_id" => $val->lecture_id,
                "speaker_id" => $val->speaker_id,
            );
        }
        if($arr){
           return $this->success_error(false, "success", $arr, 200);
        }else{
            return $this->success_error(false, "data not found", '', 400);
        }        
    }

    public function file_list_depends_speaker($speaker_id, $subcat_id, $content_id, $lecture_id)
    {
        $data = DB::table('upload_files as sc')->select(
                'sc.id as id',
                'sc.category_id',
                'sc.type_id',
                'sc.subcategory_id',
                'sc.content_id',
                'sc.subcontent_id',
                'sc.lecture_id',
                'sc.speaker_id',
                'sc.title',
                'sc.short_description',
                'sc.topics',
                'sc.file_type',
                'sc.audio',
                'sc.video',
                'sc.audio_link',
                'sc.video_link',
                'sc.file_link_type',
                'c.name',
                't.type_name',
                's.subcategory_name',
                'con.content_name',
                'sbc.subcontent_name',
                'lc.lecture_name',
                'sp.speaker_name',
            )
                ->leftjoin('categories as c', 'sc.category_id', '=', 'c.id')
                ->leftjoin('category_types as t', 'sc.type_id', '=', 't.id')
                ->leftjoin('sub_categories as s', 'sc.subcategory_id', '=', 's.id')
                ->leftjoin('contents as con', 'sc.content_id', '=', 'con.id')
                ->leftjoin('sub_contents as sbc', 'sc.subcontent_id', '=', 'sbc.id')
                ->leftjoin('lectures as lc', 'sc.lecture_id', '=', 'lc.id')
                ->leftjoin('speakers as sp', 'sc.speaker_id', '=', 'sp.id')
                ->where('sc.speaker_id', $speaker_id)->where('sc.subcategory_id', $subcat_id)->where('sc.content_id', $content_id)->where('sc.lecture_id', $lecture_id)
                ->get();

        $arr = [];        
        foreach($data as $val){
            $arr[] = array(
                "category_name" => $val->name,
                "type_name" => $val->type_name,
                "subcategory_name" => $val->subcategory_name,
                "content_name" => $val->content_name,
                "subcontent_name" => $val->subcontent_name,
                "lecture_name" => $val->lecture_name,
                "speaker_name" => $val->speaker_name,
                "title" => $val->title,
                "short_description" => $val->short_description,
                "topics" => $val->topics,
                "file_type" => $val->file_type,
                "audio_link" => $val->audio_link,
                "video_link" => $val->video_link,
                "file_link_type" => $val->file_link_type,
                "audio" => 'public/audio_file/'.$val->audio,
                "video" => 'public/video_file/'.$val->video,
                "id" => $val->id,
                "category_id" => $val->category_id,
                "type_id" => $val->type_id,
                "subcategory_id" => $val->subcategory_id,
                "content_id" => $val->content_id,
                "subcontent_id" => $val->subcontent_id,
                "lecture_id" => $val->lecture_id,
                "speaker_id" => $val->speaker_id,
            );
        }
        if($arr){
           return $this->success_error(false, "success", $arr, 200);
        }else{
            return $this->success_error(false, "data not found", '', 400);
        }        
    }
    
    public function register(Request $req){
        if($req->name == "" ||
            $req->phone == "" ||
            $req->email == "" ||
            $req->type == ""){
             $nullmsg = "";
            if (!$req->name) {
                $nullmsg .= 'fullname, ';
            }
            if (!$req->phone) {
                $nullmsg .= 'Phone, ';
            }
            if (!$req->password) {
                $nullmsg .= 'Password, ';
            }
            if (!$req->email) {
                $nullmsg .= 'Email, ';
            }
            if (!$req->type) {
                $nullmsg .= 'Usertype, ';
            }

            $nullmsg = rtrim($nullmsg, ", \t\n");
            return $this->success_error(true, $nullmsg." cannot left empty", '', 400);
        }

        $enmpty_data = (object) null;
        

        $UserCq = User::where("username", $req->email)->exists();
        $emailCq = User::where("email", $req->email)->whereNotNull('email')->exists();
        $mobileCq = User::where("phone", $req->phone)->exists();

        if($emailCq){

            return $this->success_error(true, $req->email.' exists in server.', $enmpty_data, 400);
        }
        elseif ($UserCq) {
            return $this->success_error(true, $req->email.' exists in server.', $enmpty_data, 400);
        }
        elseif ($mobileCq) {
            return $this->success_error(true, $req->phone.' exists in server.', $enmpty_data, 400);
        }

        else {

            $pv_data = new User;
            $pv_data->name = $req->name;
            $pv_data->email = $req->email;
            $pv_data->username = $req->email;
            $pv_data->phone = $req->phone;
            $pv_data->password = Hash::make($req->password);
            $pv_data->type = $req->type;


            if ($pv_data->save()) {

                $user = $pv_data;
                $err["id"] = $user->id != "" ? $user->id : '-';
                $err["name"] = $user->name != "" ? $user->name : '-';
                $err["type"] = $user->type != "" ? $user->type : '-';
                $err["username"] = $user->username != "" ? $user->username : '-';
                $err["phone"] = $user->phone != "" ? $user->phone : '-';
             
            }
            return $this->success_error(false, 'Registration Successful!', $err, 200);
        }
    }

    public function login(Request $req){
        if ($req->email == "" || $req->password == "") {
            $nullmsg = "";
            if (!$req->email) {
                $nullmsg .= 'Email, ';
            }
            if (!$req->password) {
                $nullmsg .= 'Password, ';
            }
            $nullmsg = rtrim($nullmsg, ", \t\n");
            return $this->success_error(true, $nullmsg." cannot left empty", '', 400);
        }

        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
            $err["id"] = $user->id != "" ? $user->id : '-';
            $err["name"] = $user->name != "" ? $user->name : '-';
            $err["type"] = $user->type != "" ? $user->type : '-';
            $err["email"] = $user->email != "" ? $user->email : '-';
            $err["phone"] = $user->phone != "" ? $user->phone : '-';
            return $this->success_error(false, 'Login Successfull!', $err, 200);
        } else {
            return $this->success_error(true, "Invalid Credentials.", "", 401);
        }
    }

    public function following_speaker($user_id, $speaker_id)
    {
        $UserCq = FollowingSpeaker::where("user_id", $user_id)->where('speaker_id', $speaker_id)->exists();

        if($UserCq){

            return $this->success_error(true, 'This speaker already following ', "", 400);
        }
        $dt = new FollowingSpeaker;
        $dt->user_id = $user_id;
        $dt->speaker_id = $speaker_id;
        if ($dt->save()) {
            return $this->success_error(false, 'success', '', 200);
        }else{
            return $this->success_error(true, "failed", "", 401);
        }
    }

    public function following_list($user_id)
    {
        $data = DB::table('following_speakers as fl')->select(
                'fl.id as id',
                'fl.speaker_id',
                'fl.user_id',
                'sp.speaker_name',
                'sp.speaker_image',
            )
                ->leftjoin('speakers as sp', 'fl.speaker_id', '=', 'sp.id')
                ->where('fl.user_id', $user_id)
                ->get();
        $arr = [];        
        foreach($data as $val){
            $arr[] = array(
                "speaker_id" => $val->speaker_id,
                "speaker_name" => $val->speaker_name,
                "speaker_image" => 'files/'.$val->speaker_image,
                "user_id" => $val->user_id,
                "following_id" => $val->id,
            );
        }

        if($arr){
           return $this->success_error(false, "success", $arr, 200);
        }else{
            return $this->success_error(true, "data not found", $arr, 200);
        } 
    }

    public function unfollow_speaker($user_id, $speaker_id)
    {
        $dt = FollowingSpeaker::where('user_id', $user_id)->where('speaker_id', $speaker_id)->first();
        if ($dt->delete()) {
            return $this->success_error(false, "Unfollow Successful", "", 200);
        }else{
            return $this->success_error(true, "Unfollow unsuccessful", "", 400);        }
    }

    public function listen_later_add($user_id, $file_id)
    {
        $dt = new ListenLaterFile;
        $dt->user_id = $user_id;
        $dt->file_id = $file_id;
        if ($dt->save()) {
            return $this->success_error(false, 'success', '', 200);
        }else{
            return $this->success_error(true, "failed", "", 401);
        }
    }

    public function listen_later_list($user_id)
    {

        $data = DB::table('listen_later_files as lt')->select(
                'lt.id as id',
                'lt.user_id',
                'lt.file_id',
                'sc.category_id',
                'sc.type_id',
                'sc.subcategory_id',
                'sc.content_id',
                'sc.subcontent_id',
                'sc.lecture_id',
                'sc.speaker_id',
                'sc.title',
                'sc.short_description',
                'sc.topics',
                'sc.file_type',
                'sc.audio',
                'sc.video',
                'sc.audio_link',
                'sc.video_link',
                'sc.file_link_type',
                'c.name',
                't.type_name',
                's.subcategory_name',
                'con.content_name',
                'sbc.subcontent_name',
                'lc.lecture_name',
                'sp.speaker_name',
            )
                ->leftjoin('upload_files as sc', 'lt.file_id', '=', 'sc.id')
                ->leftjoin('categories as c', 'sc.category_id', '=', 'c.id')
                ->leftjoin('category_types as t', 'sc.type_id', '=', 't.id')
                ->leftjoin('sub_categories as s', 'sc.subcategory_id', '=', 's.id')
                ->leftjoin('contents as con', 'sc.content_id', '=', 'con.id')
                ->leftjoin('sub_contents as sbc', 'sc.subcontent_id', '=', 'sbc.id')
                ->leftjoin('lectures as lc', 'sc.lecture_id', '=', 'lc.id')
                ->leftjoin('speakers as sp', 'sc.speaker_id', '=', 'sp.id')
                ->where('lt.user_id', $user_id)
                ->get();

        $arr = [];        
        foreach($data as $val){
            $arr[] = array(
                "category_name" => $val->name,
                "type_name" => $val->type_name,
                "subcategory_name" => $val->subcategory_name,
                "content_name" => $val->content_name,
                "subcontent_name" => $val->subcontent_name,
                "lecture_name" => $val->lecture_name,
                "speaker_name" => $val->speaker_name,
                "title" => $val->title,
                "short_description" => $val->short_description,
                "topics" => $val->topics,
                "file_type" => $val->file_type,
                "audio_link" => $val->audio_link,
                "video_link" => $val->video_link,
                "file_link_type" => $val->file_link_type,
                "audio" => 'public/audio_file/'.$val->audio,
                "video" => 'public/video_file/'.$val->video,
                "file_id" => $val->file_id,
                "user_id" => $val->user_id,
                "category_id" => $val->category_id,
                "type_id" => $val->type_id,
                "subcategory_id" => $val->subcategory_id,
                "content_id" => $val->content_id,
                "subcontent_id" => $val->subcontent_id,
                "lecture_id" => $val->lecture_id,
                "speaker_id" => $val->speaker_id,
            );
        }
        if($arr){
           return $this->success_error(false, "success", $arr, 200);
        }else{
            return $this->success_error(false, "data not found", $arr, 200);
        } 
    }

    public function delete_listen_later($user_id, $file_id)
    {
        $dt = ListenLaterFile::where('user_id', $user_id)->where('file_id', $file_id)->first();
        if ($dt->delete()) {
            return $this->success_error(false, "Successful", "", 200);
        }else{
            return $this->success_error(true, "unsuccessful", "", 400);        }
    }

    public function nigunim_speaker($nigunim_id)
    {
        $data = NigunimCategory::where('nigunim_id', $nigunim_id)->get();
        $arr = [];
        foreach($data as $val){
            $arr[] = array(
                "nigunim_id" => $val->nigunim_id,
                "nigunim_speaker_name" => $val->nigunim_category_name,
                "id" => $val->id,
            );
        }
        if($arr){
           return $this->success_error(false, "success", $arr, 200);
        }else{
            return $this->success_error(false, "data not found", '', 400);
        }
    }

    public function nigunim_albam($nigunim_id, $cat_id)
    {
         $data = DB::table('albams_lists as s')->select(
                's.id as id',
                's.nigunim_category_id',
                's.nigunim_id',
                's.albam_name',
                'c.nigunim_category_name',
            )
                ->leftjoin('nigunim_categories as c', 's.nigunim_category_id', '=', 'c.id')
                ->where('s.nigunim_id', $nigunim_id)->where('s.nigunim_category_id', $cat_id)
                ->get();
        $arr = [];        
        foreach($data as $val){
            $arr[] = array(
                "albam_name" => $val->albam_name,
                "nigunim_category_id" => $val->nigunim_category_id,
                "nigunim_speaker_name" => $val->nigunim_category_name,
                "id" => $val->id,
                "nigunim_id" => $val->nigunim_id,

            );
        }
        if($arr){
           return $this->success_error(false, "success", $arr, 200);
        }else{
            return $this->success_error(false, "data not found", '', 400);
        }  
    }

    public function nigunim_file_list($nigunim_id, $cat_id, $albam_id)
    {
        $data = DB::table('nigunim_files as s')->select(
                's.id as id',
                's.nigunim_category_id',
                's.nigunim_id',
                's.nigunim_albam_id',
                's.audio',
                'c.nigunim_category_name',
                'al.albam_name',
            )
                ->leftjoin('nigunim_categories as c', 's.nigunim_category_id', '=', 'c.id')
                ->leftjoin('albams_lists as al', 's.nigunim_albam_id', '=', 'al.id')
                ->where('s.nigunim_id', $nigunim_id)->where('s.nigunim_category_id', $cat_id)->where('s.nigunim_albam_id', $albam_id)
                ->get();
        $arr = [];        
        foreach($data as $val){
            $arr[] = array(
                "albam_name" => $val->albam_name,
                "nigunim_category_id" => $val->nigunim_category_id,
                "nigunim_speaker_name" => $val->nigunim_category_name,
                "id" => $val->id,
                "nigunim_id" => $val->nigunim_id,
                "nigunim_albam_id" => $val->nigunim_albam_id,
                "audio" => 'public/audio_file/'.$val->audio,
            );
        }
        if($arr){
           return $this->success_error(false, "success", $arr, 200);
        }else{
            return $this->success_error(false, "data not found", '', 400);
        } 
    }

    public function nigunim_file_list_by_cat($cat_id)
    {
        $data = DB::table('nigunim_files as s')->select(
                's.id as id',
                's.nigunim_category_id',
                's.nigunim_id',
                's.nigunim_albam_id',
                's.audio',
                'c.nigunim_category_name',
                'al.albam_name',
            )
                ->leftjoin('nigunim_categories as c', 's.nigunim_category_id', '=', 'c.id')
                ->leftjoin('albams_lists as al', 's.nigunim_albam_id', '=', 'al.id')
                ->where('s.nigunim_category_id', $cat_id)
                ->get();
        $arr = [];        
        foreach($data as $val){
            $arr[] = array(
                "albam_name" => $val->albam_name,
                "nigunim_category_id" => $val->nigunim_category_id,
                "nigunim_speaker_name" => $val->nigunim_category_name,
                "id" => $val->id,
                "nigunim_id" => $val->nigunim_id,
                "nigunim_albam_id" => $val->nigunim_albam_id,
                "audio" => 'public/audio_file/'.$val->audio,
            );
        }
        if($arr){
           return $this->success_error(false, "success", $arr, 200);
        }else{
            return $this->success_error(false, "data not found", '', 400);
        } 
    }

    public function nigunim_file_details($id)
    {
        $data = DB::table('nigunim_files as s')->select(
                's.id as id',
                's.nigunim_category_id',
                's.nigunim_id',
                's.nigunim_albam_id',
                's.audio',
                'c.nigunim_category_name',
                'al.albam_name',
            )
                ->leftjoin('nigunim_categories as c', 's.nigunim_category_id', '=', 'c.id')
                ->leftjoin('albams_lists as al', 's.nigunim_albam_id', '=', 'al.id')
                ->where('s.id', $id)
                ->get();
        $arr = [];        
        foreach($data as $val){
            $arr[] = array(
                "albam_name" => $val->albam_name,
                "nigunim_category_id" => $val->nigunim_category_id,
                "nigunim_speaker_name" => $val->nigunim_category_name,
                "id" => $val->id,
                "nigunim_id" => $val->nigunim_id,
                "nigunim_albam_id" => $val->nigunim_albam_id,
                "audio" => 'public/audio_file/'.$val->audio,
            );
        }
        if($arr){
           return $this->success_error(false, "success", $arr, 200);
        }else{
            return $this->success_error(false, "data not found", '', 400);
        } 
    }

    public function recently_played($user_id, $file_id)
    {
        $dt = new RecentlyPlayedList;
        $dt->user_id = $user_id;
        $dt->file_id = $file_id;
        if ($dt->save()) {
            return $this->success_error(false, 'success', '', 200);
        }else{
            return $this->success_error(true, "failed", "", 401);
        }
    } 

     public function recently_played_list($user_id)
    {

        $data = DB::table('recently_played_lists as lt')->select(
                'lt.id as id',
                'lt.user_id',
                'lt.file_id',
                'sc.category_id',
                'sc.type_id',
                'sc.subcategory_id',
                'sc.content_id',
                'sc.subcontent_id',
                'sc.lecture_id',
                'sc.speaker_id',
                'sc.title',
                'sc.short_description',
                'sc.topics',
                'sc.file_type',
                'sc.audio',
                'sc.video',
                'sc.audio_link',
                'sc.video_link',
                'sc.file_link_type',
                'c.name',
                't.type_name',
                's.subcategory_name',
                'con.content_name',
                'sbc.subcontent_name',
                'lc.lecture_name',
                'sp.speaker_name',
            )
                ->leftjoin('upload_files as sc', 'lt.file_id', '=', 'sc.id')
                ->leftjoin('categories as c', 'sc.category_id', '=', 'c.id')
                ->leftjoin('category_types as t', 'sc.type_id', '=', 't.id')
                ->leftjoin('sub_categories as s', 'sc.subcategory_id', '=', 's.id')
                ->leftjoin('contents as con', 'sc.content_id', '=', 'con.id')
                ->leftjoin('sub_contents as sbc', 'sc.subcontent_id', '=', 'sbc.id')
                ->leftjoin('lectures as lc', 'sc.lecture_id', '=', 'lc.id')
                ->leftjoin('speakers as sp', 'sc.speaker_id', '=', 'sp.id')
                ->where('lt.user_id', $user_id)->orderBy('lt.id', 'DESC')
                ->get();

        $arr = [];        
        foreach($data as $val){
            $arr[] = array(
                "category_name" => $val->name,
                "type_name" => $val->type_name,
                "subcategory_name" => $val->subcategory_name,
                "content_name" => $val->content_name,
                "subcontent_name" => $val->subcontent_name,
                "lecture_name" => $val->lecture_name,
                "speaker_name" => $val->speaker_name,
                "title" => $val->title,
                "short_description" => $val->short_description,
                "topics" => $val->topics,
                "file_type" => $val->file_type,
                "audio_link" => $val->audio_link,
                "video_link" => $val->video_link,
                "file_link_type" => $val->file_link_type,
                "audio" => 'public/audio_file/'.$val->audio,
                "video" => 'public/video_file/'.$val->video,
                "file_id" => $val->file_id,
                "user_id" => $val->user_id,
                "category_id" => $val->category_id,
                "type_id" => $val->type_id,
                "subcategory_id" => $val->subcategory_id,
                "content_id" => $val->content_id,
                "subcontent_id" => $val->subcontent_id,
                "lecture_id" => $val->lecture_id,
                "speaker_id" => $val->speaker_id,
            );
        }
        if($arr){
           return $this->success_error(false, "success", $arr, 200);
        }else{
            return $this->success_error(false, "data not found", $arr, 400);
        } 
    }

    public function topics_list($category_id)
    {
         $topics = DB::table('topics_categories as con')->select(
                'con.id as id',
                'con.category_name as topics_category_name',
                'c.name',
            )
                ->leftjoin('categories as c', 'con.topic_id', '=', 'c.id')
                ->where('con.topic_id', $category_id)
                ->get();

        $topicsList = [];
        $parshiyosList = [];
        $yomiList = [];      
        foreach($topics as $val){
            $topicsList[] = array(
                "topics_category_name" => $val->topics_category_name,
                "id" => $val->id,
                "main_category_name" => $val->name,
            );
        }
        $yomi = DB::table('holiday_lists')->whereNotNull('date_from')->get();
                foreach ($yomi as $sc) {
                    $yomiList[] = array(
                        "id" => $sc->id,
                        "holiday_name" => $sc->holiday_name,
                    );
                }

        $parshiyos = DB::table('current_parsha_lists as sc')->select(
                'sc.id as id',
                'sc.content_id as content_id',
                'cp.content_name'
            )
            ->leftjoin('parshioys_contents as cp', 'sc.content_id', '=', 'cp.id')->get(); 

            foreach ($parshiyos as $sp) {
                $parshiyosList[] = array(
                    "content_id" => $sp->content_id,
                    "content_name" => $sp->content_name
                );      
            }        


        $data['topicsList'] = $topicsList;
        $data['parshiyosList'] = $parshiyosList; 
        $data['yomiList'] = $yomiList;

        if($data){
           return $this->success_error(false, "success", $data, 200);
        }else{
            return $this->success_error(false, "data not found", $data, 400);
        }
    }

    public function all_parsha()
    {
        $currentParsha = [];

        $parshiyos = DB::table('current_parsha_lists as sc')->select(
                'sc.id as id',
                'sc.content_id as content_id',
                'cp.content_name'
            )
            ->leftjoin('parshioys_contents as cp', 'sc.content_id', '=', 'cp.id')->get(); 

            foreach ($parshiyos as $sp) {
                $currentParsha[] = array(
                    "content_id" => $sp->content_id,
                    "content_name" => $sp->content_name
                );      
            }
         $typeList = [];  
         $content_list = []; 
         $parshiyos_type = DB::table('parshiyos_types')->get();
                foreach ($parshiyos_type as $sc) {
                    $typeList[] = array(
                        "id" => $sc->id,
                        "type_name" => $sc->type_name,
                        "parshiyos_id" => $sc->category_id,
                        "main_cat_id" => $sc->main_cat_id,
                    );

                    $contents = DB::table('parshioys_contents')->where('type_id', $sc->id)->get();
                        foreach ($contents as $sc) {
                        $content_list[] = array(
                            "id" => $sc->id,
                            "content_name" => $sc->content_name,
                            "type_id" => $sc->type_id
                        );
                    }
                }
        $data['currentParsha'] = $currentParsha;          
        $data['typeList'] = $typeList;
        $data['content_list'] = $content_list;

        if($data){
           return $this->success_error(false, "success", $data, 200);
        }else{
            return $this->success_error(false, "data not found", $data, 400);
        }          
    }

    public function file_list_by_content($id)
    {
        // $files = DB::table('parshiyos_files as fl')->select(
        //         'fl.id as id',
        //         'fl.category_id',
        //         'fl.type_id',
        //         'fl.content_id',
        //         'fl.group_id',
        //         'fl.speaker_id',
        //         'fl.inside_or_outside',
        //         'fl.main_cat_id',
        //         'fl.title',
        //         'fl.description',
        //         'fl.file_or_link',
        //         'fl.file_link_type',
        //         'fl.file_type',
        //         'fl.audio',
        //         'fl.video',
        //         'fl.audio_link',
        //         'fl.video_link',
        //         'c.name',
        //         'tp.type_name',
        //         'con.content_name',
        //         'gp.group_name',
        //         'sp.speaker_name',
        //         'tc.category_name',
        //     )
        //         ->leftjoin('categories as c', 'fl.main_cat_id', '=', 'c.id')
        //         ->leftjoin('parshiyos_types as tp', 'fl.type_id', '=', 'tp.id')
        //         ->leftjoin('parshioys_contents as con', 'fl.content_id', '=', 'con.id')
        //         ->leftjoin('parshiyos_groups as gp', 'fl.group_id', '=', 'gp.id')
        //         ->leftjoin('speakers as sp', 'fl.speaker_id', '=', 'sp.id')
        //         ->leftjoin('topics_categories as tc', 'fl.category_id', '=', 'tc.id')
        //         ->where('fl.content_id', $id)
        //         ->get();
        // $fileList = [];
        // foreach ($files as $val) {
        //     $fileList[] = array(
        //         "id" => $val->id,
        //         "category_id" => $val->category_id,
        //         "main_cat_id" => $val->main_cat_id,
        //         "type_id" => $val->type_id,
        //         "content_id" => $val->content_id,
        //         "group_id" => $val->group_id,
        //         "speaker_id" => $val->speaker_id,
        //         "inside_or_outside" => $val->inside_or_outside,
        //         "title" => $val->title,
        //         "description" => $val->description,
        //         "file_or_link" => $val->file_or_link,
        //         "file_link_type" => $val->file_link_type,
        //         "file_type" => $val->file_type,
        //         "audio" => 'public/audio_file/'.$val->audio,
        //         "video" => 'public/video_file/'.$val->video,
        //         "audio_link" => $val->audio_link,
        //         "video_link" => $val->video_link,
        //         "main_cat_name" => $val->name,
        //         "category_name" => $val->category_name,
        //         "type_name" => $val->type_name,
        //         "content_name" => $val->content_name,
        //         "group_name" => $val->group_name,
        //         "speaker_name" => $val->speaker_name,
        //     );      
        // } 

        $parshiyos_groups = [];
        $fileList = [];

        $groups = DB::table('parshiyos_groups as sc')->select(
                'sc.id as id',
                'sc.group_name',
            )
            ->join('parshiyos_files as fl', 'fl.group_id', '=', 'sc.id')
            ->where('fl.content_id', $id)
            ->groupBy('sc.id')
            ->get(); 

            foreach ($groups as $gp) {
                $parshiyos_groups[] = array(
                    "id" => $gp->id,
                    "group_name" => $gp->group_name
                );   

                $files = DB::table('parshiyos_files as fl')->select(
                        'fl.id as id',
                        'fl.category_id',
                        'fl.type_id',
                        'fl.content_id',
                        'fl.group_id',
                        'fl.speaker_id',
                        'fl.inside_or_outside',
                        'fl.main_cat_id',
                        'fl.title',
                        'fl.description',
                        'fl.file_or_link',
                        'fl.file_link_type',
                        'fl.file_type',
                        'fl.audio',
                        'fl.video',
                        'fl.audio_link',
                        'fl.video_link',
                        'c.name',
                        'tp.type_name',
                        'con.content_name',
                        'gp.group_name',
                        'sp.speaker_name',
                        'tc.category_name',
                    )
                        ->leftjoin('categories as c', 'fl.main_cat_id', '=', 'c.id')
                        ->leftjoin('parshiyos_types as tp', 'fl.type_id', '=', 'tp.id')
                        ->leftjoin('parshioys_contents as con', 'fl.content_id', '=', 'con.id')
                        ->leftjoin('parshiyos_groups as gp', 'fl.group_id', '=', 'gp.id')
                        ->leftjoin('speakers as sp', 'fl.speaker_id', '=', 'sp.id')
                        ->leftjoin('topics_categories as tc', 'fl.category_id', '=', 'tc.id')
                        ->where('fl.content_id', $id)->where('gp.id', $gp->id)
                        ->get();
                
                foreach ($files as $val) {
                    $fileList[] = array(
                        "id" => $val->id,
                        "category_id" => $val->category_id,
                        "main_cat_id" => $val->main_cat_id,
                        "type_id" => $val->type_id,
                        "content_id" => $val->content_id,
                        "group_id" => $val->group_id,
                        "speaker_id" => $val->speaker_id,
                        "inside_or_outside" => $val->inside_or_outside,
                        "title" => $val->title,
                        "description" => $val->description,
                        "file_or_link" => $val->file_or_link,
                        "file_link_type" => $val->file_link_type,
                        "file_type" => $val->file_type,
                        "audio" => 'public/audio_file/'.$val->audio,
                        "video" => 'public/video_file/'.$val->video,
                        "audio_link" => $val->audio_link,
                        "video_link" => $val->video_link,
                        "main_cat_name" => $val->name,
                        "category_name" => $val->category_name,
                        "type_name" => $val->type_name,
                        "content_name" => $val->content_name,
                        "group_name" => $val->group_name,
                        "speaker_name" => $val->speaker_name,
                    );      
                }    
            }

        
        $data['parshiyos_groups'] = $parshiyos_groups;          
        $data['fileList'] = $fileList;

        if($data){
           return $this->success_error(false, "success", $data, 200);
        }else{
            return $this->success_error(false, "data not found", $data, 400);
        }   
    }

    public function file_list_by_category($id)
    {

        $parshiyos_groups = [];
        $fileList = [];

        $groups = DB::table('parshiyos_groups as sc')->select(
                'sc.id as id',
                'sc.group_name',
            )
            ->join('parshiyos_files as fl', 'fl.group_id', '=', 'sc.id')
            ->where('fl.category_id', $id)
            ->groupBy('sc.id')
            ->get(); 

            foreach ($groups as $gp) {
                $parshiyos_groups[] = array(
                    "id" => $gp->id,
                    "group_name" => $gp->group_name
                );   

                $files = DB::table('parshiyos_files as fl')->select(
                        'fl.id as id',
                        'fl.category_id',
                        'fl.type_id',
                        'fl.content_id',
                        'fl.group_id',
                        'fl.speaker_id',
                        'fl.inside_or_outside',
                        'fl.main_cat_id',
                        'fl.title',
                        'fl.description',
                        'fl.file_or_link',
                        'fl.file_link_type',
                        'fl.file_type',
                        'fl.audio',
                        'fl.video',
                        'fl.audio_link',
                        'fl.video_link',
                        'c.name',
                        'tp.type_name',
                        'con.content_name',
                        'gp.group_name',
                        'sp.speaker_name',
                        'tc.category_name',
                    )
                        ->leftjoin('categories as c', 'fl.main_cat_id', '=', 'c.id')
                        ->leftjoin('parshiyos_types as tp', 'fl.type_id', '=', 'tp.id')
                        ->leftjoin('parshioys_contents as con', 'fl.content_id', '=', 'con.id')
                        ->leftjoin('parshiyos_groups as gp', 'fl.group_id', '=', 'gp.id')
                        ->leftjoin('speakers as sp', 'fl.speaker_id', '=', 'sp.id')
                        ->leftjoin('topics_categories as tc', 'fl.category_id', '=', 'tc.id')
                        ->where('fl.category_id', $id)->where('gp.id', $gp->id)
                        ->get();
                
                foreach ($files as $val) {
                    $fileList[] = array(
                        "id" => $val->id,
                        "category_id" => $val->category_id,
                        "main_cat_id" => $val->main_cat_id,
                        "type_id" => $val->type_id,
                        "content_id" => $val->content_id,
                        "group_id" => $val->group_id,
                        "speaker_id" => $val->speaker_id,
                        "inside_or_outside" => $val->inside_or_outside,
                        "title" => $val->title,
                        "description" => $val->description,
                        "file_or_link" => $val->file_or_link,
                        "file_link_type" => $val->file_link_type,
                        "file_type" => $val->file_type,
                        "audio" => 'public/audio_file/'.$val->audio,
                        "video" => 'public/video_file/'.$val->video,
                        "audio_link" => $val->audio_link,
                        "video_link" => $val->video_link,
                        "main_cat_name" => $val->name,
                        "category_name" => $val->category_name,
                        "type_name" => $val->type_name,
                        "content_name" => $val->content_name,
                        "group_name" => $val->group_name,
                        "speaker_name" => $val->speaker_name,
                    );      
                }    
            }

        
        $data['parshiyos_groups'] = $parshiyos_groups;          
        $data['fileList'] = $fileList;

        if($data){
           return $this->success_error(false, "success", $data, 200);
        }else{
            return $this->success_error(false, "data not found", $data, 400);
        }
    }

    public function all_holidays()
    {
        $upcomingHoliday = [];
        $allHoliday = [];
        $yomi = DB::table('holiday_lists')->whereNotNull('date_from')->get();

        foreach ($yomi as $sc) {
            $upcomingHoliday[] = array(
                "id" => $sc->id,
                "holiday_name" => $sc->holiday_name,
            );
        }

        $holiday = DB::table('holiday_lists')->get();

        foreach ($holiday as $val) {
            $allHoliday[] = array(
                "id" => $val->id,
                "category_id" => $val->category_id,
                "main_cat_id" => $val->main_cat_id,
                "holiday_name" => $val->holiday_name
            );
        }    

        $data['upcomingHoliday'] = $upcomingHoliday;          
        $data['allHoliday'] = $allHoliday;

        if($data){
           return $this->success_error(false, "success", $data, 200);
        }else{
            return $this->success_error(false, "data not found", $data, 400);
        }     

    }

    public function holiday_list_by_cat($id)
    {
        $holiday_groups = [];
        $fileList = [];

        $groups = DB::table('parshiyos_groups as sc')->select(
                'sc.id as id',
                'sc.group_name',
            )
            ->join('parshiyos_files as fl', 'fl.group_id', '=', 'sc.id')
            ->where('fl.holiday_id', $id)
            ->groupBy('sc.id')
            ->get(); 

            foreach ($groups as $gp) {
                $holiday_groups[] = array(
                    "id" => $gp->id,
                    "group_name" => $gp->group_name
                );   

                $files = DB::table('parshiyos_files as fl')->select(
                        'fl.id as id',
                        'fl.category_id',
                        'fl.holiday_id',
                        'fl.type_id',
                        'fl.content_id',
                        'fl.group_id',
                        'fl.speaker_id',
                        'fl.inside_or_outside',
                        'fl.main_cat_id',
                        'fl.title',
                        'fl.description',
                        'fl.file_or_link',
                        'fl.file_link_type',
                        'fl.file_type',
                        'fl.audio',
                        'fl.video',
                        'fl.audio_link',
                        'fl.video_link',
                        'c.name',
                        'tp.type_name',
                        'con.content_name',
                        'gp.group_name',
                        'sp.speaker_name',
                        'tc.category_name',
                        'hl.holiday_name',
                    )
                        ->leftjoin('categories as c', 'fl.main_cat_id', '=', 'c.id')
                        ->leftjoin('parshiyos_types as tp', 'fl.type_id', '=', 'tp.id')
                        ->leftjoin('parshioys_contents as con', 'fl.content_id', '=', 'con.id')
                        ->leftjoin('parshiyos_groups as gp', 'fl.group_id', '=', 'gp.id')
                        ->leftjoin('speakers as sp', 'fl.speaker_id', '=', 'sp.id')
                        ->leftjoin('topics_categories as tc', 'fl.category_id', '=', 'tc.id')
                        ->leftjoin('holiday_lists as hl', 'fl.holiday_id', '=', 'hl.id')
                        ->where('fl.holiday_id', $id)->where('gp.id', $gp->id)
                        ->get();
                
                foreach ($files as $val) {
                    $fileList[] = array(
                        "id" => $val->id,
                        "category_id" => $val->category_id,
                        "main_cat_id" => $val->main_cat_id,
                        "holiday_id" => $val->holiday_id,
                        "type_id" => $val->type_id,
                        "content_id" => $val->content_id,
                        "group_id" => $val->group_id,
                        "speaker_id" => $val->speaker_id,
                        "inside_or_outside" => $val->inside_or_outside,
                        "title" => $val->title,
                        "description" => $val->description,
                        "file_or_link" => $val->file_or_link,
                        "file_link_type" => $val->file_link_type,
                        "file_type" => $val->file_type,
                        "audio" => 'public/audio_file/'.$val->audio,
                        "video" => 'public/video_file/'.$val->video,
                        "audio_link" => $val->audio_link,
                        "video_link" => $val->video_link,
                        "main_cat_name" => $val->name,
                        "category_name" => $val->category_name,
                        "type_name" => $val->type_name,
                        "content_name" => $val->content_name,
                        "group_name" => $val->group_name,
                        "holiday_name" => $val->holiday_name,
                        "speaker_name" => $val->speaker_name,
                    );      
                }    
            }

        
        $data['holiday_groups'] = $holiday_groups;          
        $data['fileList'] = $fileList;

        if($data){
           return $this->success_error(false, "success", $data, 200);
        }else{
            return $this->success_error(false, "data not found", $data, 400);
        }

        // $files = DB::table('parshiyos_files as fl')->select(
        //         'fl.id as id',
        //         'fl.category_id',
        //         'fl.type_id',
        //         'fl.content_id',
        //         'fl.group_id',
        //         'fl.speaker_id',
        //         'fl.inside_or_outside',
        //         'fl.main_cat_id',
        //         'fl.title',
        //         'fl.description',
        //         'fl.file_or_link',
        //         'fl.file_link_type',
        //         'fl.file_type',
        //         'fl.audio',
        //         'fl.video',
        //         'fl.audio_link',
        //         'fl.video_link',
        //         'c.name',
        //         'tp.type_name',
        //         'con.content_name',
        //         'gp.group_name',
        //         'sp.speaker_name',
        //         'tc.category_name',
        //     )
        //         ->leftjoin('categories as c', 'fl.main_cat_id', '=', 'c.id')
        //         ->leftjoin('parshiyos_types as tp', 'fl.type_id', '=', 'tp.id')
        //         ->leftjoin('parshioys_contents as con', 'fl.content_id', '=', 'con.id')
        //         ->leftjoin('parshiyos_groups as gp', 'fl.group_id', '=', 'gp.id')
        //         ->leftjoin('speakers as sp', 'fl.speaker_id', '=', 'sp.id')
        //         ->leftjoin('topics_categories as tc', 'fl.category_id', '=', 'tc.id')
        //         ->where('fl.holiday_id', $id)
        //         ->get();
        // $fileList = [];
        // foreach ($files as $val) {
        //     $fileList[] = array(
        //         "id" => $val->id,
        //         "category_id" => $val->category_id,
        //         "main_cat_id" => $val->main_cat_id,
        //         "group_id" => $val->group_id,
        //         "speaker_id" => $val->speaker_id,
        //         "inside_or_outside" => $val->inside_or_outside,
        //         "title" => $val->title,
        //         "description" => $val->description,
        //         "file_or_link" => $val->file_or_link,
        //         "file_link_type" => $val->file_link_type,
        //         "file_type" => $val->file_type,
        //         "audio" => 'public/audio_file/'.$val->audio,
        //         "video" => 'public/video_file/'.$val->video,
        //         "audio_link" => $val->audio_link,
        //         "video_link" => $val->video_link,
        //         "main_cat_name" => $val->name,
        //         "category_name" => $val->category_name,
        //         "group_name" => $val->group_name,
        //         "speaker_name" => $val->speaker_name,
        //     );      
        // } 

        // if($fileList){
        //    return $this->success_error(false, "success", $fileList, 200);
        // }else{
        //     return $this->success_error(false, "data not found", $fileList, 400);
        // }
    }

    public function topics_file_id($id)
    {
      
        $fileList = [];  

                $files = DB::table('parshiyos_files as fl')->select(
                        'fl.id as id',
                        'fl.category_id',
                        'fl.holiday_id',
                        'fl.type_id',
                        'fl.content_id',
                        'fl.group_id',
                        'fl.speaker_id',
                        'fl.inside_or_outside',
                        'fl.main_cat_id',
                        'fl.title',
                        'fl.description',
                        'fl.file_or_link',
                        'fl.file_link_type',
                        'fl.file_type',
                        'fl.audio',
                        'fl.video',
                        'fl.audio_link',
                        'fl.video_link',
                        'c.name',
                        'tp.type_name',
                        'con.content_name',
                        'gp.group_name',
                        'sp.speaker_name',
                        'tc.category_name',
                        'hl.holiday_name',
                    )
                        ->leftjoin('categories as c', 'fl.main_cat_id', '=', 'c.id')
                        ->leftjoin('parshiyos_types as tp', 'fl.type_id', '=', 'tp.id')
                        ->leftjoin('parshioys_contents as con', 'fl.content_id', '=', 'con.id')
                        ->leftjoin('parshiyos_groups as gp', 'fl.group_id', '=', 'gp.id')
                        ->leftjoin('speakers as sp', 'fl.speaker_id', '=', 'sp.id')
                        ->leftjoin('topics_categories as tc', 'fl.category_id', '=', 'tc.id')
                        ->leftjoin('holiday_lists as hl', 'fl.holiday_id', '=', 'hl.id')
                        ->where('fl.id', $id)
                        ->get();
                
                foreach ($files as $val) {
                    $fileList[] = array(
                        "id" => $val->id,
                        "category_id" => $val->category_id,
                        "main_cat_id" => $val->main_cat_id,
                        "holiday_id" => $val->holiday_id,
                        "type_id" => $val->type_id,
                        "content_id" => $val->content_id,
                        "group_id" => $val->group_id,
                        "speaker_id" => $val->speaker_id,
                        "inside_or_outside" => $val->inside_or_outside,
                        "title" => $val->title,
                        "description" => $val->description,
                        "file_or_link" => $val->file_or_link,
                        "file_link_type" => $val->file_link_type,
                        "file_type" => $val->file_type,
                        "audio" => 'public/audio_file/'.$val->audio,
                        "video" => 'public/video_file/'.$val->video,
                        "audio_link" => $val->audio_link,
                        "video_link" => $val->video_link,
                        "main_cat_name" => $val->name,
                        "category_name" => $val->category_name,
                        "type_name" => $val->type_name,
                        "content_name" => $val->content_name,
                        "group_name" => $val->group_name,
                        "holiday_name" => $val->holiday_name,
                        "speaker_name" => $val->speaker_name,
                    );      
                }    
            

                  
        $data['fileList'] = $fileList;

        if($data){
           return $this->success_error(false, "success", $data, 200);
        }else{
            return $this->success_error(false, "data not found", $data, 400);
        }
    }

    public function kol_rabeinu_category()
    {
        $kolList = [];
        $fileList = [];
        $data = DB::table('kol_rabeinu_categories')->get();      
        foreach($data as $dt){
            $class_number = KolRabeinuFile::where('category_id', $dt->id)->count();
            $kolList[] = array(
                "id" => $dt->id,
                "category_name" => $dt->category_name,
                "class_number" => $class_number
            );

            $files = DB::table('kol_rabeinu_files as sc')->select(
                'sc.id as id',
                'sc.category_id',
                'c.category_name',
                'sc.title',
                'sc.file_or_link',
                'sc.file_link_type',
                'sc.audio_link',
                'sc.video_link',
                'sc.file_type',
                'sc.audio',
                'sc.video',
            )
            ->leftjoin('kol_rabeinu_categories as c', 'sc.category_id', '=', 'c.id')
            ->where('sc.category_id', $dt->id)
            ->where('sc.feature_status', 1)
            ->get(); 
            foreach ($files as $key => $val) {
                $fileList[] = array(
                "id" => $val->id,
                "category_name" => $val->category_name,
                "category_id" => $val->category_id,
                "title" => $val->title,
                "file_or_link" => $val->file_or_link,
                "file_link_type" => $val->file_link_type,
                "audio_link" => $val->audio_link,
                "video_link" => $val->video_link,
                "file_type" => $val->file_type,
                "audio" => 'public/audio_file/'.$val->audio,
                "video" => 'public/video_file/'.$val->video,
            );
            }
        }

        $arr['kolList'] = $kolList;          
        $arr['fileList'] = $fileList;

        if($arr){
           return $this->success_error(false, "success", $arr, 200);
        }else{
            return $this->success_error(false, "data not found", $arr, 400);
        }
    }

    public function topics_of_sichos_cat_list($id)
    {
        $subCatlist = [];
        $topics_of_se_catlist = DB::table('kol_rabeinu_categories as kr')->select(
                'kr.id as cat_id',
                'kr.category_name',
                'sub.id as subCatId',
                'sub.kol_rebeinu_sub_cat_name',
            )
            ->join('kol_rabeinu_sub_categories as sub', 'sub.category_id', '=', 'kr.id')
            ->where('kr.id', $id)
            ->get(); 

        foreach($topics_of_se_catlist as $dt){

            $subCatlist[] = array(
                "category_name" => $dt->category_name,
                "id" => $dt->subCatId,
                "sub_cat_name" => $dt->kol_rebeinu_sub_cat_name,
            );
        } 
        if($subCatlist){
           return $this->success_error(false, "success", $subCatlist, 200);
        }else{
            return $this->success_error(false, "data not found", $subCatlist, 400);
        }   
    }

    public function kol_subcat_file_list($id)
    {
        $filelist = [];
        $subfilelist = DB::table('kol_rabeinu_files as fl')->select(
                'fl.id as file_id',
                'fl.title',
                'fl.description',
                'fl.file_or_link',
                'fl.file_link_type',
                'fl.file_type',
                'fl.audio',
                'fl.video',
                'fl.audio_link',
                'fl.video_link',
                'fl.category_id',
                'fl.subcategory_id',
                'cat.category_name',
                'subcat.kol_rebeinu_sub_cat_name',

            )
            ->join('kol_rabeinu_sub_categories as subcat', 'subcat.id', '=', 'fl.subcategory_id')
            ->join('kol_rabeinu_categories as cat', 'cat.id', '=', 'fl.category_id')
            ->where('subcat.id', $id)
            ->get(); 

        foreach($subfilelist as $dt){

            $filelist[] = array(
                "category_name" => $dt->category_name,
                "subcategory_name" => $dt->kol_rebeinu_sub_cat_name,
                "file_id" => $dt->file_id,
                "title" => $dt->title,
                "file_or_link" => $dt->file_or_link,
                "file_link_type" => $dt->file_link_type,
                "file_type" => $dt->file_type,
                "audio" => 'public/audio_file/'.$dt->audio,
                "video" => 'public/video_file/'.$dt->video,
                "audio_link" => $dt->audio_link,
                "video_link" => $dt->video_link,
                "category_id" => $dt->category_id,
                "subcategory_id" => $dt->subcategory_id,
            );
        } 
        if($filelist){
           return $this->success_error(false, "success", $filelist, 200);
        }else{
            return $this->success_error(false, "data not found", $filelist, 400);
        }   
    }

    public function kol_file_by_id($id)
    {
        $filelist = [];
        $subfilelist = DB::table('kol_rabeinu_files as fl')->select(
                'fl.id as file_id',
                'fl.title',
                'fl.description',
                'fl.file_or_link',
                'fl.file_link_type',
                'fl.file_type',
                'fl.audio',
                'fl.video',
                'fl.audio_link',
                'fl.video_link',
                'fl.category_id',
                'fl.subcategory_id',
                'cat.category_name',
                'subcat.kol_rebeinu_sub_cat_name',

            )
            ->join('kol_rabeinu_sub_categories as subcat', 'subcat.id', '=', 'fl.subcategory_id')
            ->join('kol_rabeinu_categories as cat', 'cat.id', '=', 'fl.category_id')
            ->where('fl.id', $id)
            ->get(); 

        foreach($subfilelist as $dt){

            $filelist[] = array(
                "category_name" => $dt->category_name,
                "subcategory_name" => $dt->kol_rebeinu_sub_cat_name,
                "file_id" => $dt->file_id,
                "title" => $dt->title,
                "file_or_link" => $dt->file_or_link,
                "file_link_type" => $dt->file_link_type,
                "file_type" => $dt->file_type,
                "audio" => 'public/audio_file/'.$dt->audio,
                "video" => 'public/video_file/'.$dt->video,
                "audio_link" => $dt->audio_link,
                "video_link" => $dt->video_link,
                "category_id" => $dt->category_id,
                "subcategory_id" => $dt->subcategory_id,
            );
        } 
        if($filelist){
           return $this->success_error(false, "success", $filelist, 200);
        }else{
            return $this->success_error(false, "data not found", $filelist, 400);
        }   
    
    }

    public function sichos_kodesh($id)
    {
        $yearList = [];
        $monthList = [];
        $eventList = [];
        $fileList = [];
        $data = KolRabeinuFile::where('category_id', $id)->orderBy('year', 'asc')->groupBy('year')->get();      
        foreach($data as $dt){
            $yearList[] = array(
                "id" => $dt->id,
                "year" => $dt->year
            );

            $month = KolRabeinuFile::where('year', $dt->year)->get();
            foreach ($month as $key => $val) {
                $monthList[] = array(
                    "month" => $val->month,
                    "year" => $dt->year
                );

                $event = DB::table('kol_rabeinu_files as fl')->select(
                    'fl.event',
                    'ev.event_name')
                ->join('events as ev', 'ev.id', '=', 'fl.event')
                ->where('fl.Category_id', $id)
                ->where('fl.year', $dt->year)
                ->where('fl.month', $val->month)
                ->get(); 

                foreach ($event as $key => $ev) {

                    $eventList[] = array(
                        "event_name" => $ev->event_name,
                        "month" => $val->month,
                        "year" => $dt->year
                    ); 

                    $fileli = KolRabeinuFile::where('year', $dt->year)->where('category_id', $id)->where('month', $val->month)->where('event', $ev->event)->get();

                    foreach ($fileli as $key => $fval) {
                        $fileList[] = array(
                            "file_id" => $fval->id,
                            "title" => $fval->title,
                            "file_or_link" => $fval->file_or_link,
                            "file_link_type" => $fval->file_link_type,
                            "file_type" => $fval->file_type,
                            "audio" => 'public/audio_file/'.$fval->audio,
                            "video" => 'public/video_file/'.$fval->video,
                            "audio_link" => $fval->audio_link,
                            "video_link" => $fval->video_link,
                            "month" => $val->month,
                            "year" => $dt->year,
                            "event_name" => $ev->event_name,
                            "event" => $ev->event,
                        );  
                    }    
                }    

            }
        }

        $arr['yearList'] = $yearList;          
        $arr['monthList'] = $monthList;
        $arr['eventList'] = $eventList;
        $arr['fileList'] = $fileList;

        if($arr){
           return $this->success_error(false, "success", $arr, 200);
        }else{
            return $this->success_error(false, "data not found", $arr, 400);
        }
    }

    public function mammer($id)
    {
        $yearList = [];
        $monthList = [];
        $fileList = [];
        $data = KolRabeinuFile::where('category_id', $id)->orderBy('year', 'asc')->groupBy('year')->get();      
        foreach($data as $dt){
            $yearList[] = array(
                "id" => $dt->id,
                "year" => $dt->year
            );

            $month = KolRabeinuFile::where('year', $dt->year)->get();
            foreach ($month as $key => $val) {
                $monthList[] = array(
                    "month" => $val->month,
                    "year" => $dt->year
                );

                    $fileli = KolRabeinuFile::where('year', $dt->year)->where('category_id', $id)->where('month', $val->month)->get();

                    foreach ($fileli as $key => $fval) {
                        $fileList[] = array(
                            "file_id" => $fval->id,
                            "title" => $fval->title,
                            "file_or_link" => $fval->file_or_link,
                            "file_link_type" => $fval->file_link_type,
                            "file_type" => $fval->file_type,
                            "audio" => 'public/audio_file/'.$fval->audio,
                            "video" => 'public/video_file/'.$fval->video,
                            "audio_link" => $fval->audio_link,
                            "video_link" => $fval->video_link,
                            "month" => $val->month,
                            "year" => $dt->year
                        );  
                    }    
                    

            }
        }

        $arr['yearList'] = $yearList;          
        $arr['monthList'] = $monthList;
        $arr['fileList'] = $fileList;

        if($arr){
           return $this->success_error(false, "success", $arr, 200);
        }else{
            return $this->success_error(false, "data not found", $arr, 400);
        }
    }

    public function story_category()
    {
        $data = MainStoryCategory::all();
        $arr = [];
        foreach($data as $val){
            $story_number = StoryFile::where('category_id', $val->id)->count();
            $arr[] = array(
                "category_name" => $val->category_name,
                "story_number" => $story_number,
                "id" => $val->id,
            );
        }
        if($arr){
           return $this->success_error(false, "success", $arr, 200);
        }else{
            return $this->success_error(false, "data not found", '', 400);
        }
    }

    public function story_file($id)
    {
        $data = [];
        $files = DB::table('story_files as fl')->select(
                    'fl.id as id',
                    'fl.category_id',
                    'fl.title',
                    'fl.description',
                    'fl.file_or_link',
                    'fl.file_link_type',
                    'fl.file_type',
                    'fl.audio',
                    'fl.video',
                    'fl.audio_link',
                    'fl.video_link',
                    'c.category_name',
                )
                ->join('main_story_categories as c', 'c.id', '=', 'fl.category_id')
                ->where('fl.Category_id', $id)
                ->get();
        foreach ($files as $val) {
                $data[] = array(
                    "id" => $val->id,
                    "category_id" => $val->category_id,
                    "category_name" => $val->category_name,
                    "title" => $val->title,
                    "description" => $val->description,
                    "file_or_link" => $val->file_or_link,
                    "file_link_type" => $val->file_link_type,
                    "file_type" => $val->file_type,
                    "audio" => 'public/audio_file/'.$val->audio,
                    "video" => 'public/video_file/'.$val->video,
                    "audio_link" => $val->audio_link,
                    "video_link" => $val->video_link,
                    "category_name" => $val->category_name,
                );      
            }
        if($data){
           return $this->success_error(false, "success", $data, 200);
        }else{
            return $this->success_error(false, "data not found", $data, 400);
        }                    

    }

    public function story_file_by_id($id)
    {
        $data = [];
        $files = DB::table('story_files as fl')->select(
                    'fl.id as id',
                    'fl.category_id',
                    'fl.title',
                    'fl.description',
                    'fl.file_or_link',
                    'fl.file_link_type',
                    'fl.file_type',
                    'fl.audio',
                    'fl.video',
                    'fl.audio_link',
                    'fl.video_link',
                    'c.category_name',
                )
                ->join('main_story_categories as c', 'c.id', '=', 'fl.category_id')
                ->where('fl.id', $id)
                ->get();
        foreach ($files as $val) {
                $data[] = array(
                    "id" => $val->id,
                    "category_id" => $val->category_id,
                    "category_name" => $val->category_name,
                    "title" => $val->title,
                    "description" => $val->description,
                    "file_or_link" => $val->file_or_link,
                    "file_link_type" => $val->file_link_type,
                    "file_type" => $val->file_type,
                    "audio" => 'public/audio_file/'.$val->audio,
                    "video" => 'public/video_file/'.$val->video,
                    "audio_link" => $val->audio_link,
                    "video_link" => $val->video_link,
                    "category_name" => $val->category_name,
                );      
            }
        if($data){
           return $this->success_error(false, "success", $data, 200);
        }else{
            return $this->success_error(false, "data not found", $data, 400);
        }
    }

    public function farbrengen_months()
    {
        $monthList = [];
        $dateList = [];
        $speakerList = [];
        $data = FarbrengenMonth::get();      
        foreach($data as $dt){
            $farbrengen_number = FarbrengenFile::where('month', $dt->month)->count();
            $monthList[] = array(
                "id" => $dt->id,
                "month" => $dt->month,
                "farbrengen_number" => $farbrengen_number
            );

            $date = FarbrengenDate::where('month', $dt->month)->get();
            foreach ($date as $key => $val) {
                $class_number = FarbrengenFile::where('month', $val->date)->count();
                $dateList[] = array(
                    "month" => $val->month,
                    "date" => $val->date,
                    "id" => $val->id,
                    "class_number" => $class_number
                );
            }
        }

        $speakers = DB::table('farbrengen_files as fl')->select(
                    'fl.speaker_id',
                    'sp.speaker_name',
                )
                ->join('speakers as sp', 'sp.id', '=', 'fl.speaker_id')
                ->groupBy('speaker_id')
                ->get();
        foreach ($speakers as $val) {
                $farbrengen_number = FarbrengenFile::where('speaker_id', $val->speaker_id)->count();  
                $speakerList[] = array(
                    "speaker_id" => $val->speaker_id,
                    "speaker_name" => $val->speaker_name,
                    "farbrengen_number" => $farbrengen_number
                );      
            }

        $arr['monthList'] = $monthList;
        $arr['dateList'] = $dateList;
        $arr['speakerList'] = $speakerList;

        if($arr){
           return $this->success_error(false, "success", $arr, 200);
        }else{
            return $this->success_error(false, "data not found", $arr, 400);
        }    
    }

    // public function farbrengen_speaker()
    // {
    //     $data = [];
    //     $speakers = DB::table('farbrengen_files as fl')->select(
    //                 'fl.speaker_id',
    //                 'sp.speaker_name',
    //             )
    //             ->join('Speakers as sp', 'sp.id', '=', 'fl.speaker_id')
    //             ->groupBy('speaker_id')
    //             ->get();
    //     foreach ($speakers as $val) {
    //             $farbrengen_number = FarbrengenFile::where('speaker_id', $val->speaker_id)->count();  
    //             $data[] = array(
    //                 "speaker_id" => $val->speaker_id,
    //                 "speaker_name" => $val->speaker_name,
    //                 "farbrengen_number" => $farbrengen_number
    //             );      
    //         }
    //     if($data){
    //        return $this->success_error(false, "success", $data, 200);
    //     }else{
    //         return $this->success_error(false, "data not found", $data, 400);
    //     }              
    // }

    public function farbrengen_by_date($month, $date)
    {
        $speakerList = [];
        $filesList = [];
        $speakers = DB::table('farbrengen_files as fl')->select(
                    'fl.id',
                    'fl.month',
                    'fl.date',
                    'fl.speaker_id',
                    'sp.speaker_name',
                    'm.month as month_name',
                    'd.date as date_name',
                )
                ->join('speakers as sp', 'sp.id', '=', 'fl.speaker_id')
                ->join('farbrengen_months as m', 'm.id', '=', 'fl.month')
                ->join('farbrengen_dates as d', 'd.id', '=', 'fl.date')
                ->where('fl.month', $month)
                ->where('fl.date', $date)
                ->groupBy('fl.speaker_id')
                ->get();
        foreach ($speakers as $val) {
                $farbrengen_number = FarbrengenFile::where('speaker_id', $val->speaker_id)->count();  
                $speakerList[] = array(
                    "speaker_id" => $val->speaker_id,
                    "speaker_name" => $val->speaker_name,
                    "farbrengen_number" => $farbrengen_number,
                    "month_id" => $val->month,
                    "date_id" => $val->date,
                    "month_name" => $val->month_name, 
                    "date_name" => $val->date_name, 
                );  

                $files = DB::table('farbrengen_files as fl')->select(
                    'fl.id',
                    'fl.month',
                    'fl.date',
                    'fl.speaker_id',
                    'sp.speaker_name',
                    'fl.title',
                    'fl.description',
                    'fl.file_or_link',
                    'fl.file_link_type',
                    'fl.file_type',
                    'fl.audio',
                    'fl.video',
                    'fl.audio_link',
                    'fl.video_link',
                    'm.month as month_name',
                    'd.date as date_name',
                )
                ->join('speakers as sp', 'sp.id', '=', 'fl.speaker_id')
                ->join('farbrengen_months as m', 'm.id', '=', 'fl.month')
                ->join('farbrengen_dates as d', 'd.id', '=', 'fl.date')
                ->where('fl.speaker_id', $val->speaker_id)
                ->get();    

                foreach ($files as $dt) { 
                    $filesList[] = array(
                        "speaker_id" => $dt->speaker_id,
                        "speaker_name" => $dt->speaker_name,
                        "month_id" => $dt->month,
                        "date_id" => $dt->date,
                        "month_name" => $dt->month_name, 
                        "date_name" => $dt->date_name, 
                        "title" => $dt->title,
                        "description" => $dt->description,
                        "file_or_link" => $dt->file_or_link,
                        "file_link_type" => $dt->file_link_type,
                        "file_type" => $dt->file_type,
                        "audio" => 'public/audio_file/'.$dt->audio,
                        "video" => 'public/video_file/'.$dt->video,
                        "audio_link" => $dt->audio_link,
                        "video_link" => $dt->video_link,

                    );
                }
            }
        $arr['speakerList'] = $speakerList;
        $arr['filesList'] = $filesList;    
        if($arr){
           return $this->success_error(false, "success", $arr, 200);
        }else{
            return $this->success_error(false, "data not found", $arr, 400);
        }        
    }

    public function farbrengen_by_id($id)
    {
        $data = [];
        $files = DB::table('farbrengen_files as fl')->select(
                    'fl.id',
                    'fl.month',
                    'fl.date',
                    'fl.speaker_id',
                    'sp.speaker_name',
                    'fl.title',
                    'fl.description',
                    'fl.file_or_link',
                    'fl.file_link_type',
                    'fl.file_type',
                    'fl.audio',
                    'fl.video',
                    'fl.audio_link',
                    'fl.video_link',
                    'm.month as month_name',
                    'd.date as date_name',
                )
                ->join('speakers as sp', 'sp.id', '=', 'fl.speaker_id')
                ->join('farbrengen_months as m', 'm.id', '=', 'fl.month')
                ->join('farbrengen_dates as d', 'd.id', '=', 'fl.date')
                ->where('fl.id', $id)
                ->get();    

                foreach ($files as $dt) { 
                    $data[] = array(
                        "speaker_id" => $dt->speaker_id,
                        "speaker_name" => $dt->speaker_name,
                        "month_id" => $dt->month,
                        "date_id" => $dt->date,
                        "month_name" => $dt->month_name, 
                        "date_name" => $dt->date_name, 
                        "title" => $dt->title,
                        "description" => $dt->description,
                        "file_or_link" => $dt->file_or_link,
                        "file_link_type" => $dt->file_link_type,
                        "file_type" => $dt->file_type,
                        "audio" => 'public/audio_file/'.$dt->audio,
                        "video" => 'public/video_file/'.$dt->video,
                        "audio_link" => $dt->audio_link,
                        "video_link" => $dt->video_link,

                    );
                }

        if($data){
           return $this->success_error(false, "success", $data, 200);
        }else{
            return $this->success_error(false, "data not found", $data, 400);
        }         
    }

    public function home_page()
    {
        $chumas = SubCategory::where('subcategory_name', 'Chumash')->first();
        $rambam = SubCategory::where('subcategory_name', 'Rambam')->first();
        $dof_yomi = SubCategory::where('subcategory_name', 'Dof yomi')->first();
        $tanaya = Content::where('content_name', 'Tanya')->first();
        $hayom_yom = Content::where('content_name', 'Hayom yom')->first();
        $inyonei_geulah = TopicsCategory::where('category_name', 'Inyonei geulah umoshiach')->first();
     
        $chumascat = array('category_id' => $chumas->id, 'category_name' => $chumas->subcategory_name); 
        $rambamcat = array('category_id' => $rambam->id, 'category_name' => $rambam->subcategory_name); 
        $dof_yomi_cat = array('category_id' => $dof_yomi->id, 'category_name' => $dof_yomi->subcategory_name); 
        $tanaya_cat = array('category_id' => $tanaya->id, 'category_name' => $tanaya->content_name); 
        $hayom_yom_cat = array('category_id' => $hayom_yom->id, 'category_name' => $hayom_yom->content_name); 
        $inyonei_geulah_cat = array('category_id' => $inyonei_geulah->id, 'category_name' => $inyonei_geulah->category_name); 
        $date = date("d");
        $dateint = $date;
        $month = date("m");
        $monthint = $month;
        $chumasfile = [];
        $rambamfile = [];
        $dof_yomi_file = [];
        $tanaya_file = [];
        $hayom_yom_file = [];
        $inyonei_geulah_file = [];
        $file = [];

        $parshiyos_files_data = DB::table('parshiyos_files as fl')->select(
                        'fl.id as id',
                        'fl.category_id',
                        'fl.type_id',
                        'fl.content_id',
                        'fl.group_id',
                        'fl.speaker_id',
                        'fl.inside_or_outside',
                        'fl.main_cat_id',
                        'fl.title',
                        'fl.description',
                        'fl.file_or_link',
                        'fl.file_link_type',
                        'fl.file_type',
                        'fl.audio',
                        'fl.video',
                        'fl.audio_link',
                        'fl.video_link',
                        'c.name',
                        'tp.type_name',
                        'con.content_name',
                        'gp.group_name',
                        'sp.speaker_name',
                        'tc.category_name',
                    )
                        ->leftjoin('categories as c', 'fl.main_cat_id', '=', 'c.id')
                        ->leftjoin('parshiyos_types as tp', 'fl.type_id', '=', 'tp.id')
                        ->leftjoin('parshioys_contents as con', 'fl.content_id', '=', 'con.id')
                        ->leftjoin('parshiyos_groups as gp', 'fl.group_id', '=', 'gp.id')
                        ->leftjoin('speakers as sp', 'fl.speaker_id', '=', 'sp.id')
                        ->leftjoin('topics_categories as tc', 'fl.category_id', '=', 'tc.id')
                        ->where('fl.category_id', $inyonei_geulah->id)                        ->get();
                
                foreach ($parshiyos_files_data as $val) {
                    $inyonei_geulah_file[] = array(
                        "id" => $val->id,
                        "category_id" => $val->category_id,
                        "main_cat_id" => $val->main_cat_id,
                        "type_id" => $val->type_id,
                        "content_id" => $val->content_id,
                        "group_id" => $val->group_id,
                        "speaker_id" => $val->speaker_id,
                        "inside_or_outside" => $val->inside_or_outside,
                        "title" => $val->title,
                        "description" => $val->description,
                        "file_or_link" => $val->file_or_link,
                        "file_link_type" => $val->file_link_type,
                        "file_type" => $val->file_type,
                        "audio" => 'public/audio_file/'.$val->audio,
                        "video" => 'public/video_file/'.$val->video,
                        "audio_link" => $val->audio_link,
                        "video_link" => $val->video_link,
                        "main_cat_name" => $val->name,
                        "category_name" => $val->category_name,
                        "type_name" => $val->type_name,
                        "content_name" => $val->content_name,
                        "group_name" => $val->group_name,
                        "speaker_name" => $val->speaker_name,
                    );      
                }

        $hayom_yom_data = DB::table('upload_files as sc')->select(
                'sc.id as id',
                'sc.category_id',
                'sc.type_id',
                'sc.subcategory_id',
                'sc.content_id',
                'sc.subcontent_id',
                'sc.lecture_id',
                'sc.speaker_id',
                'sc.title',
                'sc.short_description',
                'sc.topics',
                'sc.file_type',
                'sc.audio',
                'sc.video',
                'sc.audio_link',
                'sc.video_link',
                'sc.file_link_type',
                'c.name',
                't.type_name',
                's.subcategory_name',
                'con.content_name',
                'sbc.subcontent_name',
                'lc.lecture_name',
                'sp.speaker_name',
            )
                ->leftjoin('categories as c', 'sc.category_id', '=', 'c.id')
                ->leftjoin('category_types as t', 'sc.type_id', '=', 't.id')
                ->leftjoin('sub_categories as s', 'sc.subcategory_id', '=', 's.id')
                ->leftjoin('contents as con', 'sc.content_id', '=', 'con.id')
                ->leftjoin('sub_contents as sbc', 'sc.subcontent_id', '=', 'sbc.id')
                ->leftjoin('lectures as lc', 'sc.lecture_id', '=', 'lc.id')
                ->leftjoin('speakers as sp', 'sc.speaker_id', '=', 'sp.id')
                ->where('sc.content_id', $hayom_yom->id)
                ->where('sc.date', $date)
                ->where('sc.month', $month)
                ->get();        

        foreach ($hayom_yom_data as $val) { 
                    $hayom_yom_file[] = array(
                      
                        "category_name" => $val->name,
                        "type_name" => $val->type_name,
                        "subcategory_name" => $val->subcategory_name,
                        "content_name" => $val->content_name,
                        "subcontent_name" => $val->subcontent_name,
                        "lecture_name" => $val->lecture_name,
                        "speaker_name" => $val->speaker_name,
                        "title" => $val->title,
                        "short_description" => $val->short_description,
                        "topics" => $val->topics,
                        "file_type" => $val->file_type,
                        "audio_link" => $val->audio_link,
                        "video_link" => $val->video_link,
                        "file_link_type" => $val->file_link_type,
                        "audio" => 'public/audio_file/'.$val->audio,
                        "video" => 'public/video_file/'.$val->video,
                        "id" => $val->id,       
                    );
                }
        
        $tanaya_data = DB::table('upload_files as sc')->select(
                'sc.id as id',
                'sc.category_id',
                'sc.type_id',
                'sc.subcategory_id',
                'sc.content_id',
                'sc.subcontent_id',
                'sc.lecture_id',
                'sc.speaker_id',
                'sc.title',
                'sc.short_description',
                'sc.topics',
                'sc.file_type',
                'sc.audio',
                'sc.video',
                'sc.audio_link',
                'sc.video_link',
                'sc.file_link_type',
                'c.name',
                't.type_name',
                's.subcategory_name',
                'con.content_name',
                'sbc.subcontent_name',
                'lc.lecture_name',
                'sp.speaker_name',
            )
                ->leftjoin('categories as c', 'sc.category_id', '=', 'c.id')
                ->leftjoin('category_types as t', 'sc.type_id', '=', 't.id')
                ->leftjoin('sub_categories as s', 'sc.subcategory_id', '=', 's.id')
                ->leftjoin('contents as con', 'sc.content_id', '=', 'con.id')
                ->leftjoin('sub_contents as sbc', 'sc.subcontent_id', '=', 'sbc.id')
                ->leftjoin('lectures as lc', 'sc.lecture_id', '=', 'lc.id')
                ->leftjoin('speakers as sp', 'sc.speaker_id', '=', 'sp.id')
                ->where('sc.content_id', $tanaya->id)
                ->where('sc.date', $date)
                ->where('sc.month', $month)
                ->get();        

        foreach ($tanaya_data as $val) { 
                    $tanaya_file[] = array(
                      
                        "category_name" => $val->name,
                        "type_name" => $val->type_name,
                        "subcategory_name" => $val->subcategory_name,
                        "content_name" => $val->content_name,
                        "subcontent_name" => $val->subcontent_name,
                        "lecture_name" => $val->lecture_name,
                        "speaker_name" => $val->speaker_name,
                        "title" => $val->title,
                        "short_description" => $val->short_description,
                        "topics" => $val->topics,
                        "file_type" => $val->file_type,
                        "audio_link" => $val->audio_link,
                        "video_link" => $val->video_link,
                        "file_link_type" => $val->file_link_type,
                        "audio" => 'public/audio_file/'.$val->audio,
                        "video" => 'public/video_file/'.$val->video,
                        "id" => $val->id,       
                    );
                }

        $dof_yomidata = DB::table('upload_files as sc')->select(
                'sc.id as id',
                'sc.category_id',
                'sc.type_id',
                'sc.subcategory_id',
                'sc.content_id',
                'sc.subcontent_id',
                'sc.lecture_id',
                'sc.speaker_id',
                'sc.title',
                'sc.short_description',
                'sc.topics',
                'sc.file_type',
                'sc.audio',
                'sc.video',
                'sc.audio_link',
                'sc.video_link',
                'sc.file_link_type',
                'c.name',
                't.type_name',
                's.subcategory_name',
                'con.content_name',
                'sbc.subcontent_name',
                'lc.lecture_name',
                'sp.speaker_name',
            )
                ->leftjoin('categories as c', 'sc.category_id', '=', 'c.id')
                ->leftjoin('category_types as t', 'sc.type_id', '=', 't.id')
                ->leftjoin('sub_categories as s', 'sc.subcategory_id', '=', 's.id')
                ->leftjoin('contents as con', 'sc.content_id', '=', 'con.id')
                ->leftjoin('sub_contents as sbc', 'sc.subcontent_id', '=', 'sbc.id')
                ->leftjoin('lectures as lc', 'sc.lecture_id', '=', 'lc.id')
                ->leftjoin('speakers as sp', 'sc.speaker_id', '=', 'sp.id')
                ->where('sc.subcategory_id', $dof_yomi->id)
                ->where('sc.date', $date)
                ->where('sc.month', $month)
                ->get();        

        foreach ($dof_yomidata as $val) { 
                    $dof_yomi_file[] = array(
                      
                        "category_name" => $val->name,
                        "type_name" => $val->type_name,
                        "subcategory_name" => $val->subcategory_name,
                        "content_name" => $val->content_name,
                        "subcontent_name" => $val->subcontent_name,
                        "lecture_name" => $val->lecture_name,
                        "speaker_name" => $val->speaker_name,
                        "title" => $val->title,
                        "short_description" => $val->short_description,
                        "topics" => $val->topics,
                        "file_type" => $val->file_type,
                        "audio_link" => $val->audio_link,
                        "video_link" => $val->video_link,
                        "file_link_type" => $val->file_link_type,
                        "audio" => 'public/audio_file/'.$val->audio,
                        "video" => 'public/video_file/'.$val->video,
                        "id" => $val->id,       
                    );
                }

        $rambamdata = DB::table('upload_files as sc')->select(
                'sc.id as id',
                'sc.category_id',
                'sc.type_id',
                'sc.subcategory_id',
                'sc.content_id',
                'sc.subcontent_id',
                'sc.lecture_id',
                'sc.speaker_id',
                'sc.title',
                'sc.short_description',
                'sc.topics',
                'sc.file_type',
                'sc.audio',
                'sc.video',
                'sc.audio_link',
                'sc.video_link',
                'sc.file_link_type',
                'c.name',
                't.type_name',
                's.subcategory_name',
                'con.content_name',
                'sbc.subcontent_name',
                'lc.lecture_name',
                'sp.speaker_name',
            )
                ->leftjoin('categories as c', 'sc.category_id', '=', 'c.id')
                ->leftjoin('category_types as t', 'sc.type_id', '=', 't.id')
                ->leftjoin('sub_categories as s', 'sc.subcategory_id', '=', 's.id')
                ->leftjoin('contents as con', 'sc.content_id', '=', 'con.id')
                ->leftjoin('sub_contents as sbc', 'sc.subcontent_id', '=', 'sbc.id')
                ->leftjoin('lectures as lc', 'sc.lecture_id', '=', 'lc.id')
                ->leftjoin('speakers as sp', 'sc.speaker_id', '=', 'sp.id')
                ->where('sc.subcategory_id', $rambam->id)
                ->where('sc.date', $date)
                ->where('sc.month', $month)
                ->get();        

        foreach ($rambamdata as $val) { 
                    $rambamfile[] = array(
                      
                        "category_name" => $val->name,
                        "type_name" => $val->type_name,
                        "subcategory_name" => $val->subcategory_name,
                        "content_name" => $val->content_name,
                        "subcontent_name" => $val->subcontent_name,
                        "lecture_name" => $val->lecture_name,
                        "speaker_name" => $val->speaker_name,
                        "title" => $val->title,
                        "short_description" => $val->short_description,
                        "topics" => $val->topics,
                        "file_type" => $val->file_type,
                        "audio_link" => $val->audio_link,
                        "video_link" => $val->video_link,
                        "file_link_type" => $val->file_link_type,
                        "audio" => 'public/audio_file/'.$val->audio,
                        "video" => 'public/video_file/'.$val->video,
                        "id" => $val->id,
                        
                      

                    );
                } 
        
        $chumasdata = DB::table('upload_files as sc')->select(
                'sc.id as id',
                'sc.category_id',
                'sc.type_id',
                'sc.subcategory_id',
                'sc.content_id',
                'sc.subcontent_id',
                'sc.lecture_id',
                'sc.speaker_id',
                'sc.title',
                'sc.short_description',
                'sc.topics',
                'sc.file_type',
                'sc.audio',
                'sc.video',
                'sc.audio_link',
                'sc.video_link',
                'sc.file_link_type',
                'c.name',
                't.type_name',
                's.subcategory_name',
                'con.content_name',
                'sbc.subcontent_name',
                'lc.lecture_name',
                'sp.speaker_name',
            )
                ->leftjoin('categories as c', 'sc.category_id', '=', 'c.id')
                ->leftjoin('category_types as t', 'sc.type_id', '=', 't.id')
                ->leftjoin('sub_categories as s', 'sc.subcategory_id', '=', 's.id')
                ->leftjoin('contents as con', 'sc.content_id', '=', 'con.id')
                ->leftjoin('sub_contents as sbc', 'sc.subcontent_id', '=', 'sbc.id')
                ->leftjoin('lectures as lc', 'sc.lecture_id', '=', 'lc.id')
                ->leftjoin('speakers as sp', 'sc.speaker_id', '=', 'sp.id')
                ->where('sc.subcategory_id', $chumas->id)
                ->where('sc.date', $date)
                ->where('sc.month', $month)
                ->get();        

        foreach ($chumasdata as $val) { 
                    $chumasfile[] = array(
                      
                        "category_name" => $val->name,
                        "type_name" => $val->type_name,
                        "subcategory_name" => $val->subcategory_name,
                        "content_name" => $val->content_name,
                        "subcontent_name" => $val->subcontent_name,
                        "lecture_name" => $val->lecture_name,
                        "speaker_name" => $val->speaker_name,
                        "title" => $val->title,
                        "short_description" => $val->short_description,
                        "topics" => $val->topics,
                        "file_type" => $val->file_type,
                        "audio_link" => $val->audio_link,
                        "video_link" => $val->video_link,
                        "file_link_type" => $val->file_link_type,
                        "audio" => 'public/audio_file/'.$val->audio,
                        "video" => 'public/video_file/'.$val->video,
                        "id" => $val->id,

                    );
                } 

        $kolcatfile = [];        
        $kolfile = [];        

        $kol_rabeinu_category = KolRabeinuCategory::get();
        foreach($kol_rabeinu_category as $cat){
            $kolcatfile[] = array(
                "cat_id" => $cat->id,
                "category_name" => $cat->category_name,
            );

            $kolfiledata = DB::table('kol_rabeinu_files as sc')->select(
                'sc.id as id',
                'sc.category_id',
                'c.category_name',
                'sc.title',
                'sc.file_or_link',
                'sc.file_link_type',
                'sc.audio_link',
                'sc.video_link',
                'sc.file_type',
                'sc.audio',
                'sc.video',
            )
            ->leftjoin('kol_rabeinu_categories as c', 'sc.category_id', '=', 'c.id')
            ->where('sc.category_id', $cat->id)
            ->where('sc.feature_status', 1)
            ->get(); 
            foreach ($kolfiledata as $key => $val) {
                    $kolfile[] = array(
                    "id" => $val->id,
                    "category_name" => $val->category_name,
                    "category_id" => $val->category_id,
                    "title" => $val->title,
                    "file_or_link" => $val->file_or_link,
                    "file_link_type" => $val->file_link_type,
                    "audio_link" => $val->audio_link,
                    "video_link" => $val->video_link,
                    "file_type" => $val->file_type,
                    "audio" => 'public/audio_file/'.$val->audio,
                    "video" => 'public/video_file/'.$val->video,
                );
            }
        }

        $storyfile = [];

        $storydata = DB::table('story_files as sc')->select(
                'sc.id as id',
                'sc.category_id',
                'c.story_category_name',
                'sc.title',
                'sc.file_or_link',
                'sc.file_link_type',
                'sc.audio_link',
                'sc.video_link',
                'sc.file_type',
                'sc.audio',
                'sc.video',
            )
            ->leftjoin('story_categories as c', 'sc.category_id', '=', 'c.id')
            ->where('sc.feature_status', 1)
            ->get(); 
            foreach ($storydata as $key => $val) {
                    $storyfile[] = array(
                    "id" => $val->id,
                    "category_name" => $val->story_category_name,
                    "category_id" => $val->category_id,
                    "title" => $val->title,
                    "file_or_link" => $val->file_or_link,
                    "file_link_type" => $val->file_link_type,
                    "audio_link" => $val->audio_link,
                    "video_link" => $val->video_link,
                    "file_type" => $val->file_type,
                    "audio" => 'public/audio_file/'.$val->audio,
                    "video" => 'public/video_file/'.$val->video,
                );
            }
        $nigunimcat = [];    
        $nigunim_categories = NigunimCategory::get();
        foreach ($nigunim_categories as $key => $val) {
                    $nigunimcat[] = array(
                    "category_id" => $val->id,
                    "category_name" => $val->nigunim_category_name
                );
            }
        
        $date = date("Y-m-d");
        $holiday = HolidayList::where('date_from', '<=', $date)->where('date_to', '>=', $date)->get();
        $holidaylist = [];  
        $parshiyos_files_list = [];  
        foreach ($holiday as $key => $val) {
                    $holidaylist[] = array(
                    "holiday_id" => $val->id,
                    "holiday_name" => $val->holiday_name
                );
                $parshiyos_files = DB::table('parshiyos_files as fl')->select(
                        'fl.id as id',
                        'fl.category_id',
                        'fl.type_id',
                        'fl.content_id',
                        'fl.group_id',
                        'fl.speaker_id',
                        'fl.inside_or_outside',
                        'fl.main_cat_id',
                        'fl.title',
                        'fl.description',
                        'fl.file_or_link',
                        'fl.file_link_type',
                        'fl.file_type',
                        'fl.audio',
                        'fl.video',
                        'fl.audio_link',
                        'fl.video_link',
                        'c.name',
                        'tp.type_name',
                        'con.content_name',
                        'gp.group_name',
                        'sp.speaker_name',
                        'tc.category_name',
                    )
                        ->leftjoin('categories as c', 'fl.main_cat_id', '=', 'c.id')
                        ->leftjoin('parshiyos_types as tp', 'fl.type_id', '=', 'tp.id')
                        ->leftjoin('parshioys_contents as con', 'fl.content_id', '=', 'con.id')
                        ->leftjoin('parshiyos_groups as gp', 'fl.group_id', '=', 'gp.id')
                        ->leftjoin('speakers as sp', 'fl.speaker_id', '=', 'sp.id')
                        ->leftjoin('topics_categories as tc', 'fl.category_id', '=', 'tc.id')
                        ->where('fl.holiday_id', $val->id)
                        ->get();
                
                foreach ($parshiyos_files as $val) {
                    $parshiyos_files_list[] = array(
                        "id" => $val->id,
                        "category_id" => $val->category_id,
                        "main_cat_id" => $val->main_cat_id,
                        "type_id" => $val->type_id,
                        "content_id" => $val->content_id,
                        "group_id" => $val->group_id,
                        "speaker_id" => $val->speaker_id,
                        "inside_or_outside" => $val->inside_or_outside,
                        "title" => $val->title,
                        "description" => $val->description,
                        "file_or_link" => $val->file_or_link,
                        "file_link_type" => $val->file_link_type,
                        "file_type" => $val->file_type,
                        "audio" => 'public/audio_file/'.$val->audio,
                        "video" => 'public/video_file/'.$val->video,
                        "audio_link" => $val->audio_link,
                        "video_link" => $val->video_link,
                        "main_cat_name" => $val->name,
                        "category_name" => $val->category_name,
                        "type_name" => $val->type_name,
                        "content_name" => $val->content_name,
                        "group_name" => $val->group_name,
                        "speaker_name" => $val->speaker_name,
                    );      
                }    
            }
            $parshas_hashvua = [];

            $parshas_hashvua_li = DB::table('parshiyos_files as fl')->select(
                        'fl.id as id',
                        'fl.category_id',
                        'fl.holiday_id',
                        'fl.type_id',
                        'fl.content_id',
                        'fl.group_id',
                        'fl.speaker_id',
                        'fl.inside_or_outside',
                        'fl.main_cat_id',
                        'fl.title',
                        'fl.description',
                        'fl.file_or_link',
                        'fl.file_link_type',
                        'fl.file_type',
                        'fl.audio',
                        'fl.video',
                        'fl.audio_link',
                        'fl.video_link',
                        'c.name',
                        'tp.type_name',
                        'con.content_name',
                        'gp.group_name',
                        'sp.speaker_name',
                        'tc.category_name',
                        'hl.holiday_name',
                    )
                        ->leftjoin('categories as c', 'fl.main_cat_id', '=', 'c.id')
                        ->leftjoin('parshiyos_types as tp', 'fl.type_id', '=', 'tp.id')
                        ->leftjoin('parshioys_contents as con', 'fl.content_id', '=', 'con.id')
                        ->leftjoin('parshiyos_groups as gp', 'fl.group_id', '=', 'gp.id')
                        ->leftjoin('speakers as sp', 'fl.speaker_id', '=', 'sp.id')
                        ->leftjoin('topics_categories as tc', 'fl.category_id', '=', 'tc.id')
                        ->leftjoin('holiday_lists as hl', 'fl.holiday_id', '=', 'hl.id')
                        ->where('fl.feature_status', 1)
                        ->get();
                
                foreach ($parshas_hashvua_li as $val) {
                    $parshas_hashvua[] = array(
                        "id" => $val->id,
                        "category_id" => $val->category_id,
                        "main_cat_id" => $val->main_cat_id,
                        "holiday_id" => $val->holiday_id,
                        "type_id" => $val->type_id,
                        "content_id" => $val->content_id,
                        "group_id" => $val->group_id,
                        "speaker_id" => $val->speaker_id,
                        "inside_or_outside" => $val->inside_or_outside,
                        "title" => $val->title,
                        "description" => $val->description,
                        "file_or_link" => $val->file_or_link,
                        "file_link_type" => $val->file_link_type,
                        "file_type" => $val->file_type,
                        "audio" => 'public/audio_file/'.$val->audio,
                        "video" => 'public/video_file/'.$val->video,
                        "audio_link" => $val->audio_link,
                        "video_link" => $val->video_link,
                        "main_cat_name" => $val->name,
                        "category_name" => $val->category_name,
                        "type_name" => $val->type_name,
                        "content_name" => $val->content_name,
                        "group_name" => $val->group_name,
                        "holiday_name" => $val->holiday_name,
                        "speaker_name" => $val->speaker_name,
                    );      
                } 
        $feature = [];         
        $feature_file = DB::table('feature_files as sc')->select(
                'sc.id as id',
                
                'sc.speaker_id',
                'sc.title',
                'sc.short_description',
                'sc.topics',
                'sc.file_type',
                'sc.audio',
                'sc.video',
                'sc.audio_link',
                'sc.video_link',
                'sc.file_link_type',
                'sp.speaker_name',
            )
                
                ->leftjoin('speakers as sp', 'sc.speaker_id', '=', 'sp.id')
                
                ->get();        

        foreach ($feature_file as $val) { 
                    $feature[] = array(
                      
                        "speaker_name" => $val->speaker_name,
                        "title" => $val->title,
                        "short_description" => $val->short_description,
                        "topics" => $val->topics,
                        "file_type" => $val->file_type,
                        "audio_link" => $val->audio_link,
                        "video_link" => $val->video_link,
                        "file_link_type" => $val->file_link_type,
                        "audio" => 'public/audio_file/'.$val->audio,
                        "video" => 'public/video_file/'.$val->video,
                        "id" => $val->id,       
                    );
                }                  

        $arr['chumascat'] = $chumascat;
        $arr['chumasdata'] = $chumasfile;  
        $arr['rambamcat'] = $rambamcat;                           
        $arr['rambamdata'] = $rambamfile; 
        $arr['dof_yomi_cat'] = $dof_yomi_cat;                                
        $arr['dof_yomi_data'] = $dof_yomi_file; 
        $arr['tanaya_cat'] = $tanaya_cat;               
                       
        $arr['tanaya_data'] = $tanaya_file;     
        $arr['hayom_yom_cat'] = $hayom_yom_cat;          
        $arr['hayom_yom_data'] = $hayom_yom_file; 
        $arr['inyonei_geulah_cat'] = $inyonei_geulah_cat;          
        $arr['inyonei_geulah_data'] = $inyonei_geulah_file;    
        $arr['kolcat'] = $kolcatfile;    
        $arr['kolfile'] = $kolfile;    
        $arr['storyfile'] = $storyfile;
        $arr['nigunimcat'] = $nigunimcat;    
        $arr['parshiyos_files_list'] = $parshiyos_files_list;    
        $arr['parshas_hashvua'] = $parshas_hashvua;    
        $arr['feature'] = $feature;    

        
        if($arr){
           return $this->success_error(false, "success", $arr, 200);
        }else{
            return $this->success_error(false, "data not found", $arr, 400);
        }               
    }


    public function success_error($status, $msg, $data, $code){
        return response()->json([
            'error' => $status,
            'msg' => $msg,
            'data' => $data
        ], $code);
    }

    
}
