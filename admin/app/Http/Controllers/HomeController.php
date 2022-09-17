<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;  
use App\Models\Category;
use App\Models\CategoryType;
use App\Models\SubCategory;
use App\Models\Content;
use App\Models\Speaker;
use App\Models\SubContent;
use App\Models\Lecture;
use App\Models\UploadFile;
use App\Models\MuggideiList;
use App\Models\NigunimCategory;
use App\Models\AlbamsList;
use App\Models\NigunimFile;
use App\Models\TopicsCategory;
use App\Models\ParshiyosType;
use App\Models\ParshioysContent;
use App\Models\ParshiyosGroup;
use App\Models\ParshiyosFile;
use App\Models\HolidayList;
use App\Models\CurrentParshaList;
use App\Models\UpcomingHoliday;
use App\Models\KolRabeinuCategory;
use App\Models\Year;
use App\Models\Month;
use App\Models\Event;
use App\Models\NiggunCategory;
use App\Models\StoryCategory;
use App\Models\TopicsOfSichosCategory;
use App\Models\KolRabeinuSubCategory;
use App\Models\KolRabeinuFile;
use App\Models\MainStoryCategory;
use App\Models\StoryFile;
use App\Models\FarbrengenMonth;
use App\Models\FarbrengenDate;
use App\Models\FarbrengenFile;
use App\Models\FeatureFile;
use DB;


class HomeController extends Controller
{
    public function create_category()
    {
        $data = Category::all();
        return view('home.create_category', compact('data'));
    }

    public function save_category(Request $req)
    {
        if($req->category_id > 0){
            $dt = Category::find($req->category_id);
        }else{
            $dt = new Category;
        }
        $dt->name = $req->name;
        if ($dt->save()) {
            if ($req->category_id > 0) {
               return Redirect::to('create-category')->with(['success' => 'Category Update Successfully']);
            }else{
               return Redirect::to('create-category')->with(['success' => 'Category Created Successfully']);
           }
        }else{
            if ($req->category_id > 0) {
               return Redirect::to('create-category')->with(['success' => 'Category Update Unsuccessfully']);
            }else{
                return Redirect::to('create-category')->with(['success' => 'Category Created Unsuccessfully']);
            }
        }
        
    }

    public function delete_category($id)
    {
        $dt = Category::find($id);
        if ($dt->delete()) {
            return Redirect::to('create-category')->with(['success' => 'Category Delete Successful']);
        }else{
            return Redirect::to('create-category')->with(['success' => 'Category Delete Unsuccessful']);
        }
    }

    public function create_category_type()
    {
        $cat = Category::all();

        $data = DB::table('category_types as t')->select(
                't.id as id',
                't.type_name',
                't.category_id',
                'c.name'
            )
                ->leftjoin('categories as c', 't.category_id', '=', 'c.id')
                ->get();

        return view('home.create_category_type', compact('data', 'cat'));
    }

    public function save_category_type(Request $req)
    {
        if($req->id > 0){
            $dt = CategoryType::find($req->id);
        }else{
            $dt = new CategoryType;
        }
        $dt->type_name = $req->type_name;
        $dt->category_id = $req->category_id;
        if ($dt->save()) {
            if ($req->id > 0) {
               return Redirect::to('create-category-type')->with(['success' => 'Category Type Update Successfully']);
            }else{
               return Redirect::to('create-category-type')->with(['success' => 'Category Type Created Successfully']);
           }
        }else{
            if ($req->id > 0) {
               return Redirect::to('create-category-type')->with(['success' => 'Category Type Update Unsuccessfully']);
            }else{
                return Redirect::to('create-category-type')->with(['success' => 'Category Type Created Unsuccessfully']);
            }
        }
    }

    public function delete_category_type($id)
    {
        $dt = CategoryType::find($id);
        if ($dt->delete()) {
            return Redirect::to('create-category-type')->with(['success' => 'Category Type Delete Successful']);
        }else{
            return Redirect::to('create-category-type')->with(['success' => 'Category Type Delete Unsuccessful']);
        }
    }

    public function type_list_depends_on_category($id)
    {
        echo json_encode(DB::table('category_types')->where('category_id', $id)->get());
    }

    public function create_subcategory()
    {
        $cat = Category::all();
        $type = CategoryType::all();
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
                ->get();
        return view('home.create_subcategory', compact('cat', 'type', 'data'));
        
    }

    public function save_subcategory(Request $req)
    {
        $sucat = $req->subcategory;
        for ($i=0; $i < count($sucat); $i++) { 
            $data = [
                'category_id' => $req->category_id,
                'type_id' => $req->type_id,
                'subcategory_name' => $sucat[$i]
            ];
            DB::table('sub_categories')->insert($data);
        }
        return Redirect::to('create-subcatagory')->with(['success' => 'Subcategory Create Successful']);
    }

    public function create_content()
    {
        $cat = Category::all();
        $type = CategoryType::all();
        $sub = SubCategory::all();
         $data = DB::table('contents as con')->select(
                'con.id as id',
                'con.category_id',
                'con.type_id',
                'con.subcategory_id',
                'con.content_name',
                'c.name',
                't.type_name',
                's.subcategory_name',
            )
                ->leftjoin('categories as c', 'con.category_id', '=', 'c.id')
                ->leftjoin('category_types as t', 'con.type_id', '=', 't.id')
                ->leftjoin('sub_categories as s', 'con.subcategory_id', '=', 's.id')
                ->get();
        return view('home.create_content', compact('cat', 'type', 'sub', 'data'));
    }

    public function save_content(Request $req)
    {
        $content = $req->content;
        for ($i=0; $i < count($content); $i++) { 
            $data = [
                'category_id' => $req->category_id,
                'type_id' => $req->type_id,
                'subcategory_id' => $req->subcategory_id,
                'content_name' => $content[$i]
            ];
            DB::table('contents')->insert($data);
        }
        return Redirect::to('create-content')->with(['success' => 'Content Create Successful']);
    }

    public function create_speaker()
    {
        return view('home.create_speaker');
    }

    public function save_speaker(Request $req)
    {
        $validateor = \Validator::make($req->all(),[
            'speaker_name' => 'required|string',
            'speaker_image' => 'required|image'
        ],[
            'speaker_name.required'=>'speaker name is required',
            'speaker_name.string'=>'speaker name must be a string',
            'speaker_name.required'=>'speaker name is required',
            'speaker_image.required'=>'speaker image is required',
            'speaker_image.required'=>'speaker image must be an image',
        ]);
        if(!$validateor->passes()){
            return response()->json(['code'=> 0, 'error'=>$validateor->errors()->toArray()]);
        }else{
            $path = 'files/';
            $file = $req->file('speaker_image');
            $file_name = time().'_'.$file->getClientOriginalName();

            $upload = $file->move('public/files', $file_name);

            if ($upload) {
                Speaker::insert([
                    'speaker_name'=>$req->speaker_name,
                    'short_description'=>$req->short_description,
                    'speaker_image'=>$file_name,

                ]);
                return response()->json(['code'=>1, 'msg'=>'Speaker Image upload successful']);
            }
        }
    }

    public function speaker_list()
    {
        $speakers = Speaker::all();
        $data = view('home.speaker_list', ['spk'=>$speakers])->render();
        return response()->json(['code'=>1, 'result'=>$data]);
    }

    public function create_sub_content()
    {
        $cat = Category::all();
        $speaker = Speaker::all();
         $data = DB::table('sub_contents as sc')->select(
                'sc.id as id',
                'sc.category_id',
                'sc.type_id',
                'sc.subcategory_id',
                'sc.content_id',
                'sc.speaker_id',
                'c.name',
                't.type_name',
                's.subcategory_name',
                'con.content_name',
                'sp.speaker_name',
            )
                ->leftjoin('categories as c', 'sc.category_id', '=', 'c.id')
                ->leftjoin('category_types as t', 'sc.type_id', '=', 't.id')
                ->leftjoin('sub_categories as s', 'sc.subcategory_id', '=', 's.id')
                ->leftjoin('contents as con', 'sc.content_id', '=', 'con.id')
                ->leftjoin('speakers as sp', 'sc.speaker_id', '=', 'sp.id')
                ->get();
        return view('home.create_sub_content', compact('cat', 'data', 'speaker'));
    }

    public function save_subcontent(Request $req)
    {
        $det = new SubContent;
        $det->category_id = $req->category_id;
        $det->type_id = $req->type_id;
        $det->subcategory_id = $req->subcategory_id;
        $det->content_id = $req->content_id;
        $det->speaker_id = $req->speaker_id;
        if($det->save()){
            Redirect::to('create-sub-content')->with(['success' => 'Speaker Add Successful']);
        }
    }

    public function subcat_list_depands_on_cat($type_id, $cat_id)
    {
        echo json_encode(DB::table('sub_categories')->where('category_id', $cat_id)->where('type_id', $type_id)->get());
    }

    public function content_list_depands_on_cat($type_id, $cat_id, $subcat_id)
    {
        echo json_encode(DB::table('contents')->where('category_id', $cat_id)->where('type_id', $type_id)->where('subcategory_id', $subcat_id)->get());
    }
    public function subcontent_list_depands_on_cat($type_id, $cat_id, $subcat_id, $content_id)
    {
        // echo json_encode(DB::table('sub_contents')->where('category_id', $cat_id)->where('type_id', $type_id)->where('subcategory_id', $subcat_id)->where('content_id', $content_id)->get());

       echo json_encode(DB::table('sub_contents as t')->select(
                't.id as id',
                't.speaker_id',
                'sp.speaker_name'
            )
                ->leftjoin('speakers as sp', 't.speaker_id', '=', 'sp.id')
                ->get());

    }

    public function lecture_list_depands_on_cat($type_id, $cat_id, $subcat_id, $content_id, $speaker_id)
    {
        echo json_encode(DB::table('lectures')->where('category_id', $cat_id)->where('type_id', $type_id)->where('subcategory_id', $subcat_id)->where('content_id', $content_id)->where('speaker_id', $speaker_id)->get());
    }

    public function create_lecture()
    {
         $cat = Category::all();
        $type = CategoryType::all();
        $sub = SubCategory::all();

         $data = DB::table('lectures as sc')->select(
                'sc.id as id',
                'sc.category_id',
                'sc.type_id',
                'sc.subcategory_id',
                'sc.content_id',
                'sc.subcontent_id',
                'sc.lecture_name',
                'sc.speaker_id',
                'c.name',
                't.type_name',
                's.subcategory_name',
                'con.content_name',
                'sbc.subcontent_name',
                'sp.speaker_name',
            )
                ->leftjoin('categories as c', 'sc.category_id', '=', 'c.id')
                ->leftjoin('category_types as t', 'sc.type_id', '=', 't.id')
                ->leftjoin('sub_categories as s', 'sc.subcategory_id', '=', 's.id')
                ->leftjoin('contents as con', 'sc.content_id', '=', 'con.id')
                ->leftjoin('sub_contents as sbc', 'sc.subcontent_id', '=', 'sbc.id')
                ->leftjoin('speakers as sp', 'sc.speaker_id', '=', 'sp.id')
                ->get();
        
        return view('home.create_lecture', compact('cat', 'type', 'sub', 'data'));
    }

    public function save_lecture(Request $req)
    {
        $lecture = $req->lecture;
        for ($i=0; $i < count($lecture); $i++) { 
            $data = [
                'category_id' => $req->category_id,
                'type_id' => $req->type_id,
                'subcategory_id' => $req->subcategory_id,
                'content_id' => $req->content_id,
                'subcontent_id' => $req->subcontent_id,
                'speaker_id' => $req->speaker_id,
                'lecture_name' => $lecture[$i]
            ];
            DB::table('lectures')->insert($data);
        }
        return Redirect::to('create-lecture')->with(['success' => 'Lecture Create Successful']);
    }

    public function file_upload()
    {
        $cat = Category::all();
        $speaker = Speaker::all();
        return view('home.file_upload', compact('cat', 'speaker'));
    }

    public function feature_file_upload()
    {
        $cat = Category::all();
        $speaker = Speaker::all();
        return view('home.feature_file_upload', compact('cat', 'speaker'));
    }

    public function save_feature_file(Request $req)
    {
        $dt = new FeatureFile;
        if ($req->file_or_link == 1) {
            if($req->file_link_type == 1){
                $req->validate([
                    'audio_link'=>'required',
                ]);
            }else{
                $req->validate([
                    'video_link'=>'required',
                ]);
            }
            
        
        }elseif($req->file_or_link == 2){
            if ($req->file_type == 2) {
               $req->validate([
                    'video'=>'required|mimes:mp4,ogx,oga,ogv,ogg,webm,mkv',
                ]);
            }
            if ($req->file_type == 1) {
               $req->validate([
                    'audio'=>'required|mimes:mp3,mp4,mpeg',
                ]);
            }
        }
        
        
        if ($req->file_or_link == 1){
            $dt->file_link_type =$req->file_link_type;
            if ($req->file_link_type == 1) {
                $dt->audio_link = $req->audio_link;
            }elseif($req->file_link_type == 2){
                $dt->video_link = $req->video_link;
            }

        }elseif($req->file_or_link == 2){
            if ($req->file_type == 2) {
                $path = 'video/';
                $file = $req->file('video');
                $file_name = time().'_'.$file->getClientOriginalName();
                $upload = $file->move('public/video_file', $file_name);
                 
                if($upload){
                    $dt->video = $file_name;
                }        
            }
            if ($req->file_type == 1) {
                $path = 'audio/';
                $file = $req->file('audio');
                $file_name = time().'_'.$file->getClientOriginalName();
                $upload = $file->move('public/audio_file', $file_name);
                if($upload){
                    $dt->audio = $file_name;
                } 

            }

        }
        
        $dt->speaker_id = $req->speaker_id;
        $dt->title = $req->title;
        $dt->short_description = $req->short_description;
        $dt->topics = $req->topics;
        $dt->file_type = $req->file_type;

        if ($dt->save()) {
            return Redirect::to('feature-file-upload')->with(['success' => 'File Upload Successful']);
        }else{
            return Redirect::to('feature-file-upload')->with(['success' => 'File Upload Unsuccessful']);
        }

    }


    public function save_file_upload(Request $req)
    {
        $dt = new UploadFile;
        if ($req->file_or_link == 1) {
            if($req->file_link_type == 1){
                $req->validate([
                    'audio_link'=>'required',
                ]);
            }else{
                $req->validate([
                    'video_link'=>'required',
                ]);
            }
            
        
        }elseif($req->file_or_link == 2){
            if ($req->file_type == 2) {
               $req->validate([
                    'video'=>'required|mimes:mp4,ogx,oga,ogv,ogg,webm,mkv',
                ]);
            }
            if ($req->file_type == 1) {
               $req->validate([
                    'audio'=>'required|mimes:mp3,mp4,mpeg',
                ]);
            }
        }
        
        
        if ($req->file_or_link == 1){
            $dt->file_link_type =$req->file_link_type;
            if ($req->file_link_type == 1) {
                $dt->audio_link = $req->audio_link;
            }elseif($req->file_link_type == 2){
                $dt->video_link = $req->video_link;
            }

        }elseif($req->file_or_link == 2){
            if ($req->file_type == 2) {
                $path = 'video/';
                $file = $req->file('video');
                $file_name = time().'_'.$file->getClientOriginalName();
                $upload = $file->move('public/video_file', $file_name);
                 
                if($upload){
                    $dt->video = $file_name;
                }        
            }
            if ($req->file_type == 1) {
                $path = 'audio/';
                $file = $req->file('audio');
                $file_name = time().'_'.$file->getClientOriginalName();
                $upload = $file->move('public/audio_file', $file_name);
                if($upload){
                    $dt->audio = $file_name;
                } 

            }

        }
        

        $dt->category_id = $req->category_id;
        $dt->type_id = $req->type_id;
        $dt->subcategory_id = $req->subcategory_id;
        $dt->content_id = $req->content_id;
        $dt->subcontent_id = $req->subcontent_id;
        $dt->lecture_id = $req->lecture_id;
        $dt->speaker_id = $req->speaker_id;
        $dt->title = $req->title;
        $dt->short_description = $req->short_description;
        $dt->topics = $req->topics;
        $dt->file_type = $req->file_type;

        if ($dt->save()) {
            return Redirect::to('file-upload')->with(['success' => 'File Upload Successful']);
        }else{
            return Redirect::to('file-upload')->with(['success' => 'File Upload Unsuccessful']);
        }
       
    }

    public function all_file_list()
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
                ->where('sc.file_type', 2)
                ->orWhere('sc.file_link_type', 2)
                ->get();
        
        return view('home.file_list', compact('data'));
    }

    public function muggidei_shiurim()
    {
        $speaker = Speaker::all();
        $data = DB::table('muggidei_lists as sc')->select(
                'sc.id as id',
                'sc.speaker_id',
                'sc.title',
                'sc.short_description',
                'sc.topics',
                'sc.file_type',
                'sc.file_or_link',
                'sc.audio',
                'sc.video',
                'sc.audio_link',
                'sc.video_link',
                'sc.file_link_type',
                'sp.speaker_name'
               
            )
                ->leftjoin('speakers as sp', 'sc.speaker_id', '=', 'sp.id')
                ->where('sc.file_type', 2)
                ->orWhere('sc.file_link_type', 2)
                ->get();

         return view('home.muggidei_shiurim', compact('speaker', 'data'));
    }

    public function save_muggidei_shiurim(Request $req)
    {
        $dt = new MuggideiList;
        if ($req->file_or_link == 1) {
            if($req->file_link_type == 1){
                $req->validate([
                    'audio_link'=>'required',
                ]);
            }else{
                $req->validate([
                    'video_link'=>'required',
                ]);
            }
            
        
        }elseif($req->file_or_link == 2){
            if ($req->file_type == 2) {
               $req->validate([
                    'video'=>'required|mimes:mp4,ogx,oga,ogv,ogg,webm,mkv',
                ]);
            }
            if ($req->file_type == 1) {
               $req->validate([
                    'audio'=>'required|mimes:mp3,mp4,mpeg',
                ]);
            }
        }
        
        if ($req->file_or_link == 1){
            $dt->file_link_type =$req->file_link_type;
            if ($req->file_link_type == 1) {
                $dt->audio_link = $req->audio_link;
            }elseif($req->file_link_type == 2){
                $dt->video_link = $req->video_link;
            }

        }elseif($req->file_or_link == 2){
            if ($req->file_type == 2) {
                $path = 'video/';
                $file = $req->file('video');
                $file_name = time().'_'.$file->getClientOriginalName();

                $upload = $file->storeAs($path, $file_name, 'public'); 
                if($upload){
                    $dt->video = $file_name;
                }        
            }
            if ($req->file_type == 1) {
                $path = 'audio/';
                $file = $req->file('audio');
                $file_name = time().'_'.$file->getClientOriginalName();

                $upload = $file->storeAs($path, $file_name, 'public');
                if($upload){
                    $dt->audio = $file_name;
                } 

            }

        }

        $dt->speaker_id = $req->speaker_id;
        $dt->file_or_link = $req->file_or_link;
        $dt->title = $req->title;
        $dt->short_description = $req->short_description;
        $dt->topics = $req->topics;
        $dt->file_type = $req->file_type;

        if ($dt->save()) {
            return Redirect::to('muggidei-shiurim')->with(['success' => 'Muggidei Shiurim Add Successful']);
        }else{
            return Redirect::to('muggidei-shiurim')->with(['success' => 'Muggidei Shiurim Add Unsuccessful']);
        }
    }

    public function create_nigunim_category()
    {
        $cat = Category::all();

        $data = NigunimCategory::all();

        return view('home.create_nigunim_category', compact('data', 'cat'));
    }

    public function save_nigunim_category(Request $req)
    {
        if($req->id > 0){
            $dt = NigunimCategory::find($req->id);
        }else{
            $dt = new NigunimCategory;
        }
        $dt->nigunim_id = $req->nigunim_id;
        $dt->nigunim_category_name = $req->nigunim_category_name;
        $dt->nigunim_name = 'Nigunim';
        if ($dt->save()) {
            if ($req->id > 0) {
               return Redirect::to('create-nigunim-category')->with(['success' => 'Nigunim Category Update Successfully']);
            }else{
               return Redirect::to('create-nigunim-category')->with(['success' => 'Nigunim Category Created Successfully']);
           }
        }else{
            if ($req->id > 0) {
               return Redirect::to('create-nigunim-category')->with(['success' => 'Nigunim Category Update Unsuccessfully']);
            }else{
                return Redirect::to('create-nigunim-category')->with(['success' => 'Nigunim Category Created Unsuccessfully']);
            }
        }

    }

    public function delete_nigunim_category($id)
    {
        $dt = NigunimCategory::find($id);
        if ($dt->delete()) {
            return Redirect::to('create-nigunim-category')->with(['success' => 'Category Delete Successful']);
        }else{
            return Redirect::to('create-nigunim-category')->with(['success' => 'Category Delete Unsuccessful']);
        }
    }

    public function create_nigunim_album()
    {
        $nig = Category::where('name', 'Nigunim')->first();
        $nigcat = NigunimCategory::all();
         $data = DB::table('albams_lists as t')->select(
                't.id as id',
                't.albam_name',
                't.nigunim_category_id',
                't.nigunim_id',
                'c.nigunim_category_name'
            )
                ->leftjoin('nigunim_categories as c', 't.nigunim_category_id', '=', 'c.id')
                ->get();


        return view('home.create_nigunim_album', compact('nigcat', 'nig', 'data'));
    }

    public function save_nigunim_album(Request $req)
    {
        if($req->id > 0){
            $dt = AlbamsList::find($req->id);
        }else{
            $dt = new AlbamsList;
        }
        $dt->nigunim_id = $req->nigunim_id;
        $dt->nigunim_category_id = $req->nigunim_category_id;
        $dt->albam_name = $req->albam_name;
        if ($dt->save()) {
            if ($req->id > 0) {
               return Redirect::to('create-nigunim-album')->with(['success' => 'Nigunim Albam Update Successfully']);
            }else{
               return Redirect::to('create-nigunim-album')->with(['success' => 'Nigunim Albam Created Successfully']);
           }
        }else{
            if ($req->id > 0) {
               return Redirect::to('create-nigunim-album')->with(['success' => 'Nigunim Albam Update Unsuccessfully']);
            }else{
                return Redirect::to('create-nigunim-album')->with(['success' => 'Nigunim Albam Created Unsuccessfully']);
            }
        }
    }

    public function delete_nigunim_albam($id)
    {
        $dt = AlbamsList::find($id);
        if ($dt->delete()) {
            return Redirect::to('create-nigunim-album')->with(['success' => 'Albam Delete Successful']);
        }else{
            return Redirect::to('create-nigunim-album')->with(['success' => 'Albam Delete Unsuccessful']);
        }
    }

    public function create_nigunim_file()
    {
        $nig = Category::where('name', 'Nigunim')->first();
        $nigcat = NigunimCategory::all();
        $speaker = Speaker::all();
         $data = DB::table('albams_lists as t')->select(
                't.id as id',
                't.albam_name',
                't.nigunim_category_id',
                't.nigunim_id',
                'c.nigunim_category_name'
            )
                ->leftjoin('nigunim_categories as c', 't.nigunim_category_id', '=', 'c.id')
                ->get();


        return view('home.create_nigunim_file', compact('nigcat', 'nig', 'speaker'));
    }

    public function albam_list_on_cat($id)
    {
        echo json_encode(DB::table('albams_lists')->where('nigunim_category_id', $id)->get());
    }

    public function save_nigunim_file(Request $req)
    {
        $dt = new NigunimFile;
        $req->validate([
                'audio'=>'required|mimes:mp3,mp4,mpeg',
            ]);

        $path = 'audio/';
        $file = $req->file('audio');
        $file_name = time().'_'.$file->getClientOriginalName();
        $upload = $file->move('public/audio_file', $file_name);
        if($upload){
            $dt->audio = $file_name;
        }

        $dt->nigunim_category_id = $req->nigunim_category_id;
        $dt->nigunim_albam_id = $req->albam_id;
        $dt->title = $req->title;
        $dt->topics = $req->topics;
        $dt->nigunim_id = $req->nigunim_id;
        if ($dt->save()) {
            return Redirect::to('create-nigunim-file')->with(['success' => 'File Upload Successful']);
        }else{
            return Redirect::to('create-nigunim-file')->with(['success' => 'File Upload Unsuccessful']);
        }

    }

    public function create_topics_cat()
    {
        $cat = Category::where('name', 'Topics')->first();

        $data = TopicsCategory::all();
        return view('home.create_topics_category', compact('data', 'cat'));
    }

    public function save_topics_cat(Request $req)
    {
        if($req->category_id > 0){
            $dt = TopicsCategory::find($req->category_id);
        }else{
            $dt = new TopicsCategory;
        }
        $dt->category_name = $req->category_name;
        $dt->topic_id = $req->topic_id;
        if ($dt->save()) {
            if ($req->category_id > 0) {
               return Redirect::to('create-topics-category')->with(['success' => 'Category Update Successfully']);
            }else{
               return Redirect::to('create-topics-category')->with(['success' => 'Category Created Successfully']);
           }
        }else{
            if ($req->category_id > 0) {
               return Redirect::to('create-topics-category')->with(['success' => 'Category Update Unsuccessfully']);
            }else{
                return Redirect::to('create-topics-category')->with(['success' => 'Category Created Unsuccessfully']);
            }
        }

    }

    public function create_parshiyos_type()
    {
        $cat = TopicsCategory::where('category_name', 'Parshiyos')->first();

        $data = ParshiyosType::all();
        $main_cat = Category::where('name', 'Topics')->first();

        return view('home.create_parshiyos_type', compact('data', 'cat', 'main_cat'));
    }

    public function save_parshiyos_type(Request $req)
    {
        if($req->id > 0){
            $dt = ParshiyosType::find($req->id);
        }else{
            $dt = new ParshiyosType;
        }
        $dt->category_id = $req->category_id;
        $dt->type_name = $req->type_name;
        $dt->main_cat_id = $req->main_cat_id;
        $dt->parshiyos_name = 'Parshiyos';
        if ($dt->save()) {
            if ($req->id > 0) {
               return Redirect::to('create-parshioys-type')->with(['success' => 'Parshiyos Type Update Successfully']);
            }else{
               return Redirect::to('create-parshioys-type')->with(['success' => 'Parshiyos Type Created Successfully']);
           }
        }else{
            if ($req->id > 0) {
               return Redirect::to('create-parshioys-type')->with(['success' => 'Parshiyos Type Update Unsuccessfully']);
            }else{
                return Redirect::to('create-parshioys-type')->with(['success' => 'Parshiyos Type Created Unsuccessfully']);
            }
        }
    }

    public function create_parshiyos_content()
    {
        $cat = TopicsCategory::where('category_name', 'Parshiyos')->first();
        $main_cat = Category::where('name', 'Topics')->first();

        $type = ParshiyosType::all();
        $contents = DB::table('parshioys_contents as t')->select(
                't.id as id',
                't.content_name',
                'c.type_name'
            )
                ->leftjoin('parshiyos_types as c', 't.type_id', '=', 'c.id')
                ->get();

        return view('home.create_parshiyos_content', compact('type', 'cat', 'main_cat', 'contents'));
    }

    public function save_parshiyos_content(Request $req)
    {
        if($req->id > 0){
            $dt = ParshioysContent::find($req->id);
        }else{
            $dt = new ParshioysContent;
        }
        $dt->main_cat_id = $req->main_cat_id;
        $dt->category_id = $req->category_id;
        $dt->type_id = $req->type_id;
        $dt->content_name = $req->content_name;
        if ($dt->save()) {
            if ($req->id > 0) {
               return Redirect::to('create-parshioys-content')->with(['success' => 'Parshiyos Content Update Successfully']);
            }else{
               return Redirect::to('create-parshioys-content')->with(['success' => 'Parshiyos Content Created Successfully']);
           }
        }else{
            if ($req->id > 0) {
               return Redirect::to('create-parshioys-content')->with(['success' => 'Parshiyos Content Update Unsuccessfully']);
            }else{
                return Redirect::to('create-parshioys-content')->with(['success' => 'Parshiyos Content Created Unsuccessfully']);
            }
        }
    }

    public function create_parshiyos_group()
    {
        $main_cat = Category::where('name', 'Topics')->first();
        $data = ParshiyosGroup::all();

        return view('home.create_parshiyos_group', compact('main_cat', 'data'));
    }

     

    public function save_parshiyos_group(Request $req)
    {
        if($req->id > 0){
            $dt = ParshiyosGroup::find($req->id);
        }else{
            $dt = new ParshiyosGroup;
        }
        $dt->main_cat_id = $req->main_cat_id;
        $dt->group_name = $req->group_name;
        if ($dt->save()) {
            if ($req->id > 0) {
               return Redirect::to('create-parshioys-group')->with(['success' => 'Parshiyos Group Update Successfully']);
            }else{
               return Redirect::to('create-parshioys-group')->with(['success' => 'Parshiyos Group Created Successfully']);
           }
        }else{
            if ($req->id > 0) {
               return Redirect::to('create-parshioys-group')->with(['success' => 'Parshiyos Group Update Unsuccessfully']);
            }else{
                return Redirect::to('create-parshioys-group')->with(['success' => 'Parshiyos Group Created Unsuccessfully']);
            }
        }
    }

    public function parshioys_file_upload()
    {
        $cat = TopicsCategory::where('category_name', 'Parshiyos')->first();
        $main_cat = Category::where('name', 'Topics')->first();
        $speaker = Speaker::all();
        $type = ParshiyosType::all();
        $contents = ParshioysContent::all();
        $groups = ParshiyosGroup::all();
        $category = TopicsCategory::all();
        $holiday = HolidayList::all();

        return view('home.parshiyos_file_upload', compact('type', 'cat', 'main_cat', 'contents', 'groups', 'speaker', 'category', 'holiday'));
        
    }

    public function get_content_list_depends_type($id)
    {
        echo json_encode(DB::table('parshioys_contents')->where('type_id', $id)->get());

    }

    public function save_parshioys_file_upload(Request $req)
    {
        $dt = new ParshiyosFile;
        if ($req->file_or_link == 1) {
            if($req->file_link_type == 1){
                $req->validate([
                    'audio_link'=>'required',
                ]);
            }else{
                $req->validate([
                    'video_link'=>'required',
                ]);
            }
            
        
        }elseif($req->file_or_link == 2){
            if ($req->file_type == 2) {
               $req->validate([
                    'video'=>'required|mimes:mp4,ogx,oga,ogv,ogg,webm,mkv',
                ]);
            }
            if ($req->file_type == 1) {
               $req->validate([
                    'audio'=>'required|mimes:mp3,mp4,mpeg',
                ]);
            }
        }
        
        
        if ($req->file_or_link == 1){
            $dt->file_link_type =$req->file_link_type;
            if ($req->file_link_type == 1) {
                $dt->audio_link = $req->audio_link;
            }elseif($req->file_link_type == 2){
                $dt->video_link = $req->video_link;
            }

        }elseif($req->file_or_link == 2){
            if ($req->file_type == 2) {
                $path = 'video/';
                $file = $req->file('video');
                $file_name = time().'_'.$file->getClientOriginalName();
                $upload = $file->move('public/video_file', $file_name);
                 
                if($upload){
                    $dt->video = $file_name;
                }        
            }
            if ($req->file_type == 1) {
                $path = 'audio/';
                $file = $req->file('audio');
                $file_name = time().'_'.$file->getClientOriginalName();
                $upload = $file->move('public/audio_file', $file_name);
                if($upload){
                    $dt->audio = $file_name;
                } 

            }

        }
        
        $dt->file_or_link = $req->file_or_link;  
        $dt->category_id = $req->category_id;
        if($req->type_id > 0){
            $dt->type_id = $req->type_id;
        }
        
        $dt->main_cat_id = $req->main_cat_id;
        if($req->type_id > 0){
            $dt->content_id = $req->content_id;
        }
        
        $dt->speaker_id = $req->speaker_id;
        $dt->title = $req->title;
        $dt->file_type = $req->file_type;

        if($req->group_id > 0){
            $dt->group_id = $req->group_id;
        }
        
        $dt->inside_or_outside = $req->inside_or_outside;
        if($req->holiday_id > 0){
            $dt->holiday_id = $req->holiday_id;
        }
        

        if ($dt->save()) {
            return Redirect::to('parshioys-file-upload')->with(['success' => 'File Upload Successful']);
        }else{
            return Redirect::to('parshioys-file-upload')->with(['success' => 'File Upload Unsuccessful']);
        }
        
    }

    public function yomim_tovim_holiday()
    {
        $cat = TopicsCategory::where('category_name', 'Yomim Tovim')->first();

        $data = HolidayList::all();
        $main_cat = Category::where('name', 'Topics')->first();

        return view('home.create_yomim_tovim', compact('data', 'cat', 'main_cat'));
    }

    public function save_yomim_tovim_holiday(Request $req)
    {
        if($req->id > 0){
            $dt = HolidayList::find($req->id);
        }else{
            $dt = new HolidayList;
        }
        $dt->main_cat_id = $req->main_cat_id;
        $dt->category_id = $req->category_id;
        $dt->holiday_name = $req->holiday_name;
        if ($dt->save()) {
            if ($req->id > 0) {
               return Redirect::to('yomim-tovim-holiday')->with(['success' => 'Holiday Update Successfully']);
            }else{
               return Redirect::to('yomim-tovim-holiday')->with(['success' => 'Holiday Created Successfully']);
           }
        }else{
            if ($req->id > 0) {
               return Redirect::to('yomim-tovim-holiday')->with(['success' => 'Holiday Update Unsuccessfully']);
            }else{
                return Redirect::to('yomim-tovim-holiday')->with(['success' => 'Holiday Created Unsuccessfully']);
            }
        }
    }

    public function add_current_parsha()
    {
        $cat = TopicsCategory::where('category_name', 'Parshiyos')->first();
        $main_cat = Category::where('name', 'Topics')->first();

        $type = ParshiyosType::all();
       // $contents = ParshioysContent::all();
        $contents = DB::table('parshioys_contents as t')->select(
                't.id as id',
                't.content_name',
                'c.type_name',
                'c.id as type_id',
                'p.content_id as content_id',
                'p.type_id as current_type_id',
            )
                ->leftjoin('parshiyos_types as c', 't.type_id', '=', 'c.id')
                ->leftjoin('current_parsha_lists as p', 't.id', '=', 'p.content_id')
                ->get();
        return view('home.add_current_parsha', compact('type', 'contents', 'cat', 'main_cat'));

    }

    public function save_current_parsha(Request $req)
    {
        CurrentParshaList::truncate();
        $type_id = $req->type_id;
        $main_cat_id = $req->main_cat_id;
        $cat_id = $req->cat_id;
        $arr = $req->current;

        foreach ($arr as $val) {
            $dt = new CurrentParshaList;
            $dt->type_id = $type_id;
            $dt->main_cat_id = $main_cat_id;
            $dt->cat_id = $cat_id;
            $dt->content_id = $val;
            $dt->save();
        }
        return Redirect::to('add-current-parsha')->with(['success' => 'Current Parsha Add Successful']);
    }

    public function add_parshas_hashavua_feature()
    {
        $contents = DB::table('current_parsha_lists as t')->select(
                'p.id as id',
                'p.content_name',
                'c.type_name',
                'c.id as type_id',
                't.content_id as content_id',
                't.type_id as current_type_id',
            )
                ->join('parshiyos_types as c', 't.type_id', '=', 'c.id')
                ->join('parshioys_contents as p', 'p.id', '=', 't.content_id')
                ->get();
        return view('home.add_parshas_hashavua_feature', compact('contents'));

    }

    public function set_parshas_hashavua_feature($id)
    {
        $data = DB::table('parshiyos_files as t')->select(
                't.id as id',
                't.feature_status',
                't.title',
                'c.content_name',
                't.content_id as content_id',
            )
                
                ->join('parshioys_contents as c', 'c.id', '=', 't.content_id')
                ->where('t.content_id', $id)
                ->get();
        return view('home.parshas_hashavua_feature_update', compact('data'));
    }

    public function parshas_hasvua_feature_update(Request $req)
    {
        $id = $req->id;
        $status = $req->status;
        $dt = ParshiyosFile::find($id);
        if($status == 1){
            $dt->feature_status = 1;
        }else{
            $dt->feature_status = 0;
        }
        
        if ($dt->save()) {
            return response()->json(['error'=>false, 'msg'=>'Date Added successful']);
        }
    }

    public function set_upcoming_holiday()
    {
        $cat = TopicsCategory::where('category_name', 'Yomim Tovim')->first();

        $data = HolidayList::all();
        $main_cat = Category::where('name', 'Topics')->first();
        return view('home.upcoming_holiday', compact('data', 'cat', 'main_cat'));
    }

    public function save_upcoming_holiday(Request $req)
    {
        $id = $req->id;
        $dt = HolidayList::find($id);
        $dt->date_from = $req->date_from;
        $dt->date_to = $req->date_to;
        if ($dt->save()) {
            return response()->json(['error'=>false, 'msg'=>'Date Added successful']);
        }
    }

    public function kol_rabeinu()
    {
        $cat = Category::where('name', 'Kol Rabeinu')->first();

        $data = KolRabeinuCategory::all();
        return view('home.kol_rabeinu', compact('data', 'cat'));
    }

    public function save_kol_rabeinu(Request $req)
    {
        if($req->id > 0){
            $dt = KolRabeinuCategory::find($req->id);
        }else{
            $dt = new KolRabeinuCategory;
        }
        $dt->category_name = $req->category_name;
        $dt->kol_id = $req->kol_id;
        if ($dt->save()) {
            if ($req->id > 0) {
               return Redirect::to('kol-rabeinu')->with(['success' => 'Category Update Successfully']);
            }else{
               return Redirect::to('kol-rabeinu')->with(['success' => 'Category Created Successfully']);
           }
        }else{
            if ($req->id > 0) {
               return Redirect::to('kol-rabeinu')->with(['success' => 'Category Update Unsuccessfully']);
            }else{
                return Redirect::to('kol-rabeinu')->with(['success' => 'Category Created Unsuccessfully']);
            }
        }

    }

    public function year()
    {

        $data = Year::all();
        return view('home.year', compact('data'));
    }

    public function save_year(Request $req)
    {
        if($req->id > 0){
            $dt = Year::find($req->id);
        }else{
            $dt = new Year;
        }
        $dt->year = $req->year;
        if ($dt->save()) {
            if ($req->id > 0) {
               return Redirect::to('year')->with(['success' => 'Year Update']);
            }else{
               return Redirect::to('year')->with(['success' => 'Year Created Successfully']);
           }
        }else{
            if ($req->id > 0) {
               return Redirect::to('year')->with(['success' => 'Year Update Unsuccessfully']);
            }else{
                return Redirect::to('year')->with(['success' => 'Year Created Unsuccessfully']);
            }
        }
    }

    public function month()
    {

        $data = Month::all();
        return view('home.month', compact('data'));
    }

    public function save_month(Request $req)
    {
        if($req->id > 0){
            $dt = Month::find($req->id);
        }else{
            $dt = new Month;
        }
        $dt->month = $req->month;
        if ($dt->save()) {
            if ($req->id > 0) {
               return Redirect::to('month')->with(['success' => 'Month Updated']);
            }else{
               return Redirect::to('month')->with(['success' => 'Month Created Successfully']);
           }
        }else{
            if ($req->id > 0) {
               return Redirect::to('month')->with(['success' => 'Month Update Unsuccessfully']);
            }else{
                return Redirect::to('month')->with(['success' => 'Month Created Unsuccessfully']);
            }
        }
    }

    public function create_event()
    {
        $data = Event::all();
        return view('home.create_event', compact('data'));
    }

    public function save_event(Request $req)
    {
        if($req->id > 0){
            $dt = Event::find($req->id);
        }else{
            $dt = new Event;
        }
        $dt->event_name = $req->event_name;
        if ($dt->save()) {
            if ($req->id > 0) {
               return Redirect::to('create-event')->with(['success' => 'Event Updated']);
            }else{
               return Redirect::to('create-event')->with(['success' => 'Event Created Successfully']);
           }
        }else{
            if ($req->id > 0) {
               return Redirect::to('create-event')->with(['success' => 'Event Update Unsuccessfully']);
            }else{
                return Redirect::to('create-event')->with(['success' => 'Event Created Unsuccessfully']);
            }
        }
    }

    public function create_niggun_cat()
    {
        $cat = Category::where('name', 'Kol Rabeinu')->first();
        $category = KolRabeinuCategory::where('category_name', 'Niggun')->first();
        $data = KolRabeinuSubCategory::where('category_id', $category->id)->get();
        return view('home.create_niggun', compact('data', 'cat', 'category'));
    }

    public function save_niggun_cat(Request $req)
    {
        if($req->id > 0){
            $dt = KolRabeinuSubCategory::find($req->id);
        }else{
            $dt = new KolRabeinuSubCategory;
        }
        $dt->kol_rebeinu_sub_cat_name = $req->kol_rebeinu_sub_cat_name;
        $dt->category_id = $req->category_id;
        $dt->main_cat_id = $req->main_cat_id;
        if ($dt->save()) {
            if ($req->id > 0) {
               return Redirect::to('niggun-category')->with(['success' => 'Niggun Category Updated']);
            }else{
               return Redirect::to('niggun-category')->with(['success' => 'Niggun Category Created Successfully']);
           }
        }else{
            if ($req->id > 0) {
               return Redirect::to('niggun-category')->with(['success' => 'Niggun Category Update Unsuccessfully']);
            }else{
                return Redirect::to('niggun-category')->with(['success' => 'Niggun Category Created Unsuccessfully']);
            }
        }
    }

    public function create_stories_cat()
    {
        $cat = Category::where('name', 'Kol Rabeinu')->first();
        $category = KolRabeinuCategory::where('category_name', 'Story')->first();
        $data = KolRabeinuSubCategory::where('category_id', $category->id)->get();
        return view('home.create_story_cat', compact('data', 'cat', 'category'));
    }

    public function save_stories_cat(Request $req)
    {
        if($req->id > 0){
            $dt = KolRabeinuSubCategory::find($req->id);
        }else{
            $dt = new KolRabeinuSubCategory;
        }
        $dt->kol_rebeinu_sub_cat_name = $req->kol_rebeinu_sub_cat_name;
        $dt->category_id = $req->category_id;
        $dt->main_cat_id = $req->main_cat_id;
        if ($dt->save()) {
            if ($req->id > 0) {
               return Redirect::to('create-stories-category')->with(['success' => 'Story Category Updated']);
            }else{
               return Redirect::to('create-stories-category')->with(['success' => 'Story Category Created Successfully']);
           }
        }else{
            if ($req->id > 0) {
               return Redirect::to('create-stories-category')->with(['success' => 'Story Category Update Unsuccessfully']);
            }else{
                return Redirect::to('create-stories-category')->with(['success' => 'Story Category Created Unsuccessfully']);
            }
        }
    }

    public function create_topics_of_sichos()
    {
        $cat = Category::where('name', 'Kol Rabeinu')->first();
        $category = KolRabeinuCategory::where('category_name', 'Topics of Sichos')->first();
        $data = KolRabeinuSubCategory::where('category_id', $category->id)->get();
       
        return view('home.create_topics_of_sichos_cat', compact('data', 'cat', 'category'));
        
    }

    public function save_topics_of_sichos(Request $req)
    {
        if($req->id > 0){
            $dt = KolRabeinuSubCategory::find($req->id);
        }else{
            $dt = new KolRabeinuSubCategory;
        }
        $dt->kol_rebeinu_sub_cat_name = $req->kol_rebeinu_sub_cat_name;
        $dt->category_id = $req->category_id;
        $dt->main_cat_id = $req->main_cat_id;
        if ($dt->save()) {
            if ($req->id > 0) {
               return Redirect::to('topics-of-sichos')->with(['success' => 'Topics Of Sichos Category Updated']);
            }else{
               return Redirect::to('topics-of-sichos')->with(['success' => 'Topics Of Sichos Category Created Successfully']);
           }
        }else{
            if ($req->id > 0) {
               return Redirect::to('topics-of-sichos')->with(['success' => 'Topics Of Sichos Category Update Unsuccessfully']);
            }else{
                return Redirect::to('topics-of-sichos')->with(['success' => 'Topics Of Sichos Category Created Unsuccessfully']);
            }
        }
    }

    public function kol_rabeinu_file_upload()
    {
        $category = KolRabeinuCategory::all();
        $main_cat = Category::where('name', 'Kol Rabeinu')->first();
        $speaker = Speaker::all();
        $years = Year::all();
        $months = Month::all();
        $events = Event::all();
        
        $nigguncat = NiggunCategory::all();
        $storycat = StoryCategory::all();
        $sichoscat = TopicsOfSichosCategory::all();

        return view('home.kol_rabeinu_file_upload', compact('category', 'main_cat', 'speaker', 'years', 'months', 'events', 'nigguncat', 'storycat', 'sichoscat'));
    }

    public function kol_rabeinu_subcat_depands($id)
    {
        echo json_encode(DB::table('kol_rabeinu_sub_categories')->where('category_id', $id)->get());
    }

    public function kol_rabeinu_file_save(Request $req)
    {
        $dt = new KolRabeinuFile;
        if ($req->file_or_link == 1) {
            if($req->file_link_type == 1){
                $req->validate([
                    'audio_link'=>'required',
                ]);
            }else{
                $req->validate([
                    'video_link'=>'required',
                ]);
            }
            
        
        }elseif($req->file_or_link == 2){
            if ($req->file_type == 2) {
               $req->validate([
                    'video'=>'required|mimes:mp4,ogx,oga,ogv,ogg,webm,mkv',
                ]);
            }
            if ($req->file_type == 1) {
               $req->validate([
                    'audio'=>'required|mimes:mp3,mp4,mpeg',
                ]);
            }
        }
        
        
        if ($req->file_or_link == 1){
            $dt->file_link_type =$req->file_link_type;
            if ($req->file_link_type == 1) {
                $dt->audio_link = $req->audio_link;
            }elseif($req->file_link_type == 2){
                $dt->video_link = $req->video_link;
            }

        }elseif($req->file_or_link == 2){
            if ($req->file_type == 2) {
                $path = 'video/';
                $file = $req->file('video');
                $file_name = time().'_'.$file->getClientOriginalName();
                $upload = $file->move('public/video_file', $file_name);
                 
                if($upload){
                    $dt->video = $file_name;
                }        
            }
            if ($req->file_type == 1) {
                $path = 'audio/';
                $file = $req->file('audio');
                $file_name = time().'_'.$file->getClientOriginalName();
                $upload = $file->move('public/audio_file', $file_name);
                if($upload){
                    $dt->audio = $file_name;
                } 

            }

        }
        
        $dt->file_or_link = $req->file_or_link;  
        $dt->category_id = $req->category_id;
        $dt->main_cat_id = $req->main_cat_id;
        if($req->subcategory_id > 0){
            $dt->subcategory_id = $req->subcategory_id;
        }
        if(! $req->subcategory_id){
            $dt->year = $req->year;
        }
        if(! $req->subcategory_id){
            $dt->month = $req->month;
        }
        if($req->event > 0){
            $dt->event = $req->event;
        }
        
        
        
        $dt->title = $req->title;
        $dt->file_type = $req->file_type;
        

        if ($dt->save()) {
            return Redirect::to('kol-rabeinu-file-upload')->with(['success' => 'File Upload Successful']);
        }else{
            return Redirect::to('kol-rabeinu-file-upload')->with(['success' => 'File Upload Unsuccessful']);
        }
    }

    public function story_file_feature()
    {
        $category = StoryCategory::all();
        return view('home.story_feature_add', compact('category'));
    }

    public function set_story_file_feature($id)
    {
        $data = DB::table('story_files as t')->select(
                't.id as id',
                't.category_id',
                't.feature_status',
                't.title',
                'c.story_category_name',
            )
                ->leftjoin('story_categories as c', 't.category_id', '=', 'c.id')
                ->where('t.category_id', $id)
                ->get();
        return view('home.story_feature_update', compact('data')); 
    }


    public function kol_rabeinu_feature()
    {
        $category = KolRabeinuCategory::all();
        return view('home.kol_rabeinu_feature_add', compact('category'));
    }

    public function set_feature_of_kol_rabeinu($id)
    {
        $data = DB::table('kol_rabeinu_files as t')->select(
                't.id as id',
                't.category_id',
                't.subcategory_id',
                't.feature_status',
                't.title',
                'c.category_name',
                's.kol_rebeinu_sub_cat_name',
            )
                ->leftjoin('kol_rabeinu_categories as c', 't.category_id', '=', 'c.id')
                ->leftjoin('kol_rabeinu_sub_categories as s', 't.subcategory_id', '=', 's.id')
                ->where('t.category_id', $id)
                ->get();
        return view('home.kol_rabeinu_feature_update', compact('data'));        
    }

    public function story_feature_update(Request $req)
    {
        $id = $req->id;
        $status = $req->status;
        $cat_id = $req->category_id;
        // $val = KolRabeinuFile::where('category_id', $cat_id)->where('feature_status', 1)->get();
        // if ($val) {
        //     foreach($val as $v){
        //         $v->feature_status = 0;
        //         $v->save();
        //     }     
        // }
        $dt = StoryFile::find($id);
        if($status == 1){
            $dt->feature_status = 1;
        }else{
            $dt->feature_status = 0;
        }
        
        if ($dt->save()) {
            return response()->json(['error'=>false, 'msg'=>'Date Added successful']);
        }
    }

    

    public function kol_rabeinu_feature_update(Request $req)
    {
        $id = $req->id;
        $cat_id = $req->category_id;
        $val = KolRabeinuFile::where('category_id', $cat_id)->where('feature_status', 1)->get();
        if ($val) {
            foreach($val as $v){
                $v->feature_status = 0;
                $v->save();
            }     
        }
        $dt = KolRabeinuFile::find($id);
        $dt->feature_status = 1;
        if ($dt->save()) {
            return response()->json(['error'=>false, 'msg'=>'Date Added successful']);
        }
    }

    public function kol_rabeinu_feature1()
    {
        $category = KolRabeinuCategory::all();
        $years = Year::all();
        $months = Month::all();
        $events = Event::all();
        
        $nigguncat = NiggunCategory::all();
        $storycat = StoryCategory::all();
        $sichoscat = TopicsOfSichosCategory::all();

        $nig = KolRabeinuCategory::where('category_name', 'Niggun')->first();
        $niggun_file = DB::table('kol_rabeinu_files as t')->select(
                't.id as id',
                't.category_id',
                't.subcategory_id',
                't.feature_status',
                't.title',
                'c.category_name',
                's.kol_rebeinu_sub_cat_name',
            )
                ->leftjoin('kol_rabeinu_categories as c', 't.category_id', '=', 'c.id')
                ->leftjoin('kol_rabeinu_sub_categories as s', 't.subcategory_id', '=', 's.id')
                ->where('t.category_id', $nig->id)
                ->get();
        $story = KolRabeinuCategory::where('category_name', 'Story')->first();
        $story_file = DB::table('kol_rabeinu_files as t')->select(
                't.id as id',
                't.category_id',
                't.subcategory_id',
                't.feature_status',
                't.title',
                'c.category_name',
                's.kol_rebeinu_sub_cat_name',
            )
                ->leftjoin('kol_rabeinu_categories as c', 't.category_id', '=', 'c.id')
                ->leftjoin('kol_rabeinu_sub_categories as s', 't.subcategory_id', '=', 's.id')
                ->where('t.category_id', $story->id)
                ->get();        

        return view('home.set_feature_in _kol_rabeinu', compact('category', 'years', 'months', 'events', 'nigguncat', 'storycat', 'sichoscat', 'niggun_file', 'story_file'));
    }

    public function save_feature_status(Request $req)
    {
        $id = $req->id;
        $cat_id = $req->category_id;
        $val = KolRabeinuFile::where('category_id', $cat_id)->where('feature_status', 1)->get();
        if ($val) {
            foreach($val as $v){
                $v->feature_status = 0;
                $v->save();
            }     
        }
        $dt = KolRabeinuFile::find($id);
        $dt->feature_status = 1;
        if ($dt->save()) {
            return response()->json(['error'=>false, 'msg'=>'Date Added successful']);
        }
    }

    public function create_story_category()
    {
        $data = MainStoryCategory::all();
        return view('home.create_main_story', compact('data'));
    }

    public function save_main_story_cat(Request $req)
    {
        if($req->id > 0){
            $dt = MainStoryCategory::find($req->id);
        }else{
            $dt = new MainStoryCategory;
        }
        $dt->category_name = $req->category_name;
        if ($dt->save()) {
            if ($req->id > 0) {
               return Redirect::to('create-story-category')->with(['success' => 'Story Category Updated']);
            }else{
               return Redirect::to('create-story-category')->with(['success' => 'Story Category Created Successfully']);
           }
        }else{
            if ($req->id > 0) {
               return Redirect::to('create-story-category')->with(['success' => 'Story Category Update Unsuccessfully']);
            }else{
                return Redirect::to('create-story-category')->with(['success' => 'Story Category Created Unsuccessfully']);
            }
        }
    }

    public function add_main_story_file()
    {
        $data = MainStoryCategory::all();
        return view('home.add_main_story_file', compact('data'));
    }

    public function save_main_story_file(Request $req)
    {
        $dt = new StoryFile;
        if ($req->file_or_link == 1) {
            if($req->file_link_type == 1){
                $req->validate([
                    'audio_link'=>'required',
                ]);
            }else{
                $req->validate([
                    'video_link'=>'required',
                ]);
            }
            
        
        }elseif($req->file_or_link == 2){
            if ($req->file_type == 2) {
               $req->validate([
                    'video'=>'required|mimes:mp4,ogx,oga,ogv,ogg,webm,mkv',
                ]);
            }
            if ($req->file_type == 1) {
               $req->validate([
                    'audio'=>'required|mimes:mp3,mp4,mpeg',
                ]);
            }
        }
        
        
        if ($req->file_or_link == 1){
            $dt->file_link_type =$req->file_link_type;
            if ($req->file_link_type == 1) {
                $dt->audio_link = $req->audio_link;
            }elseif($req->file_link_type == 2){
                $dt->video_link = $req->video_link;
            }

        }elseif($req->file_or_link == 2){
            if ($req->file_type == 2) {
                $path = 'video/';
                $file = $req->file('video');
                $file_name = time().'_'.$file->getClientOriginalName();
                $upload = $file->move('public/video_file', $file_name);
                 
                if($upload){
                    $dt->video = $file_name;
                }        
            }
            if ($req->file_type == 1) {
                $path = 'audio/';
                $file = $req->file('audio');
                $file_name = time().'_'.$file->getClientOriginalName();
                $upload = $file->move('public/audio_file', $file_name);
                if($upload){
                    $dt->audio = $file_name;
                } 

            }

        }
        
        $dt->file_or_link = $req->file_or_link;  
        $dt->category_id = $req->category_id; 
        $dt->title = $req->title;
        $dt->file_type = $req->file_type;
        

        if ($dt->save()) {
            return Redirect::to('add-main-story-file')->with(['success' => 'File Upload Successful']);
        }else{
            return Redirect::to('add-main-story-file')->with(['success' => 'File Upload Unsuccessful']);
        }
    }

    public function create_farbrengen_month()
    {
        $data = FarbrengenMonth::all();
        return view('home.add_farbrengen_month', compact('data'));
    }

    public function save_farbrengen_month(Request $req)
    {
        if($req->id > 0){
            $dt = FarbrengenMonth::find($req->id);
        }else{
            $dt = new FarbrengenMonth;
        }
        $dt->month = $req->month;
        if ($dt->save()) {
            if ($req->id > 0) {
               return Redirect::to('create-farbrengen-month')->with(['success' => 'farbrengen Updated']);
            }else{
               return Redirect::to('create-farbrengen-month')->with(['success' => 'Farbrengen Created Successfully']);
           }
        }else{
            if ($req->id > 0) {
               return Redirect::to('create-farbrengen-month')->with(['success' => 'Farbrengen Update Unsuccessfully']);
            }else{
                return Redirect::to('create-farbrengen-month')->with(['success' => 'Farbrengen Category Created Unsuccessfully']);
            }
        }
    }

    public function create_farbrengen_date()
    {
        $month = FarbrengenMonth::all();
        $data = FarbrengenDate::all();
        return view('home.add_farbrengen_date', compact('data', 'month'));
    }

    public function save_farbrengen_date(Request $req)
    {
        if($req->id > 0){
            $dt = FarbrengenDate::find($req->id);
        }else{
            $dt = new FarbrengenDate;
        }
        $dt->date = $req->date;
        $dt->month = $req->month;
        if ($dt->save()) {
            if ($req->id > 0) {
               return Redirect::to('create-farbrengen-date')->with(['success' => 'farbrengen Updated']);
            }else{
               return Redirect::to('create-farbrengen-date')->with(['success' => 'Farbrengen Created Successfully']);
           }
        }else{
            if ($req->id > 0) {
               return Redirect::to('create-farbrengen-date')->with(['success' => 'Farbrengen Update Unsuccessfully']);
            }else{
                return Redirect::to('create-farbrengen-date')->with(['success' => 'Farbrengen Category Created Unsuccessfully']);
            }
        }
    }

    public function add_farbrengen_file()
    {
        $date = FarbrengenDate::all();
        $month = FarbrengenMonth::all();
        $speaker = Speaker::all();
        return view('home.add_farbrengen_file', compact('date', 'month', 'speaker'));
        
    }

    public function save_farbrengen_file(Request $req)
    {
        $dt = new FarbrengenFile;
        if ($req->file_or_link == 1) {
            if($req->file_link_type == 1){
                $req->validate([
                    'audio_link'=>'required',
                ]);
            }else{
                $req->validate([
                    'video_link'=>'required',
                ]);
            }
            
        
        }elseif($req->file_or_link == 2){
            if ($req->file_type == 2) {
               $req->validate([
                    'video'=>'required|mimes:mp4,ogx,oga,ogv,ogg,webm,mkv',
                ]);
            }
            if ($req->file_type == 1) {
               $req->validate([
                    'audio'=>'required|mimes:mp3,mp4,mpeg',
                ]);
            }
        }
        
        
        if ($req->file_or_link == 1){
            $dt->file_link_type =$req->file_link_type;
            if ($req->file_link_type == 1) {
                $dt->audio_link = $req->audio_link;
            }elseif($req->file_link_type == 2){
                $dt->video_link = $req->video_link;
            }

        }elseif($req->file_or_link == 2){
            if ($req->file_type == 2) {
                $path = 'video/';
                $file = $req->file('video');
                $file_name = time().'_'.$file->getClientOriginalName();
                $upload = $file->move('public/video_file', $file_name);
                 
                if($upload){
                    $dt->video = $file_name;
                }        
            }
            if ($req->file_type == 1) {
                $path = 'audio/';
                $file = $req->file('audio');
                $file_name = time().'_'.$file->getClientOriginalName();
                $upload = $file->move('public/audio_file', $file_name);
                if($upload){
                    $dt->audio = $file_name;
                } 

            }

        }
        
        $dt->file_or_link = $req->file_or_link;  
        $dt->month = $req->month; 
        $dt->date = $req->date; 
        $dt->speaker_id = $req->speaker_id; 
        $dt->title = $req->title;
        $dt->file_type = $req->file_type;
        

        if ($dt->save()) {
            return Redirect::to('add-farbrengen-file')->with(['success' => 'File Upload Successful']);
        }else{
            return Redirect::to('add-farbrengen-file')->with(['success' => 'File Upload Unsuccessful']);
        }
    }

    public function daily_shiurim()
    {
        $chumas = SubCategory::where('subcategory_name', 'Chumash')->first();
        $rambam = SubCategory::where('subcategory_name', 'Rambam')->first();
        $dof_yomi = SubCategory::where('subcategory_name', 'Dof yomi')->first();
        $tanaya = Content::where('content_name', 'Tanya')->first();
        $hayom_yom = Content::where('content_name', 'Hayom yom')->first();
        $inyonei_geulah = TopicsCategory::where('category_name', 'Inyonei geulah umoshiach')->first();

        return view('home.daily_shiurim', compact('chumas', 'rambam', 'dof_yomi', 'tanaya', 'hayom_yom', 'inyonei_geulah'));

    }

    public function daily_shiurim_date($id, $name)
    {
        if($name == 'Chumash' || $name == 'Rambam' || $name == 'Dof yomi'){
            $data = DB::table('upload_files as fl')->select(
                'fl.id as id',
                'fl.subcategory_id as subcategory_id',
                'fl.title',
                'fl.date',
                'fl.month',
                'c.subcategory_name',
                )
                ->leftjoin('sub_categories as c', 'fl.subcategory_id', '=', 'c.id')
                ->where('fl.subcategory_id', $id)
                ->get();
            return view('home.daily_shiurim_set_date', compact('data'));

        }elseif($name == 'Tanya' || $name == 'Hayom yom'){
            $data = DB::table('upload_files as fl')->select(
                'fl.id as id',
                'fl.content_id as content_id',
                'fl.title',
                'fl.date',
                'fl.month',
                'c.content_name',
                )
                ->leftjoin('contents as c', 'fl.content_id', '=', 'c.id')
                ->where('fl.content_id', $id)
                ->get();
            return view('home.daily_shiurim_set_date_for_content', compact('data'));

        }elseif($name == 'Inyonei geulah umoshiach'){
            $data = DB::table('parshiyos_files as fl')->select(
                'fl.id as id',
                'fl.category_id as category_id',
                'fl.title',
                'fl.date',
                'fl.month',
                'c.category_name',
                )
                ->leftjoin('topics_categories as c', 'fl.category_id', '=', 'c.id')
                ->where('fl.category_id', $id)
                ->get();
            return view('home.daily_shiurim_set_date_for_inyonei', compact('data'));

        }
        
    }

    public function daily_seuirm_val_update(Request $req)
    {
        $id = $req->id;
        $dt = UploadFile::find($id);
        $dt->date = $req->date;
        $dt->month = $req->month;
        if ($dt->save()) {
            return response()->json(['error'=>false, 'msg'=>'Date Added successful']);
        }
    }

    public function daily_seuirm_val_update_for_inyonei_geulah(Request $req)
    {
        $id = $req->id;
        $dt = ParshiyosFile::find($id);
        $dt->date = $req->date;
        $dt->month = $req->month;
        if ($dt->save()) {
            return response()->json(['error'=>false, 'msg'=>'Date Added successful']);
        }
    }
}
