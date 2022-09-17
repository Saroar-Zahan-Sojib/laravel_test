<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Password;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
//use Illuminate\Support\Facades\Auth;

//use Auth;
use Illuminate\Support\Facades\Auth;
use Validator;
use DB;
use File;
use Storage;
use Mail;
use Carbon\Carbon;
use App\User;
use App\Medicine;
use App\DrugInteraction;
use App\Covid19;
use App\GeneralVital;
use App\Appointment;
use App\Doctor;
use App\TakingMedicineLog;
use App\DoctorNote;
use App\Prescription;
use App\UserTest;
use App\RequestTherapist;
use App\TrialNotification;
use App\TrialProfile;
use App\Session;
use App\UserSession;
use App\SessionLive;
use App\MindBody;
use App\CallDoctor;
use App\RequestDoctor;
use App\UserFeedback;
use App\DoctorAppointment;
use App\ProvisinalDiagnosis;
use App\ProvisinalDiagnosisName;
use App\DrgIntRequest;
use App\AppointmentSpecialist;
use App\VitalStatusSetting;
use App\TaskList;
use App\UnderObservation;
use App\RequestNutritionist;
use Kreait\Firebase\Factory;
use App\HealthPlanFeature;
use App\PlanService;
use App\OrderLabTest;
use App\PlanSection;
use App\Subscription;
use App\GeneralSetting;
use App\FinalEmergency;
use App\LocetEmergency;
use App\ForecSession;
use App\Device;
use App\PhysiciansScheduleSet;
use App\HealthTarget;
use App\ProgressReport;
use App\CalorieItem;
use App\MasterNutrition;
use App\Nutrition;
use App\FoodUnit;
use App\FoodUnitSize;
use App\NutritionistComment;
use App\MemberFood;
use App\SpecialistAssessment;
use App\UsersTokenList;
use App\UserRole;

class ApiController extends BaseController{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, SendsPasswordResetEmails;
    // public function __construct()
    // {
    //     $this->middleware('auth:api', ['except' => ['login','register']]);
    // }

    public function request_for_therapist(){
        $data['personal'] = Session::where("therapy_status", "personal")->where("starttds", ">", date("Y-m-d H:i:s"))->get();
        $data['group'] = Session::where("therapy_status", "group")->where("starttds", ">", date("Y-m-d H:i:s"))->get();
        return success_error(false, "Success ", $data, 200);
    }

    public function existing_medecine_list(){
        $result = DB::table('ha_medicines')
          ->select('name')
          ->groupBy('name')
          ->get();

        $arr = [];
        foreach($result as $val){
            $arr[] = array(
                "name" => $val->name
            );
        }
        return success_error(false, "Success ", $arr, 200);
    }


    public function RequestForTherapist(Request $req){

        $user_id= $this->guard()->user()->id;

        $cq = RequestTherapist::where('user_id', $user_id)->where('status', 'waiting')->where('type',$req->type)->where('session_type',$req->session_type)->exists();/*->where('type', $type)*/
        if($cq){
            return success_error(true, "You already have an appointment for ". ucwords($req->type) ." therapist in this time", "", 200);
        }

        $dt = new RequestTherapist;
        $dt->user_id = $user_id;
        $dt->type = $req->type;
        $dt->session_type = $req->session_type;
        $dt->session_id = $req->id  ?? null;
        $dt->tds = date("Y-m-d H:i:s");
         if($dt->save()){
            return success_error(false, "Thanks for your request for ". ucwords($dt->type) ." Therapy. Our office will contact you soon to facilitate the appointment.", "", 200);
        }else{
            return success_error(true, "Unable to save data.", "", 400);
        }
    }

    public function request_for_nutritionist(Request $req){
        $cq = RequestNutritionist::where('user_id', $req->user_id)->where('status', 'waiting')->exists();
        if($cq){
            return success_error(true, "You already have an appointment waiting for nutritionist.", "", 200);
        }

        $dt = new RequestNutritionist;
        $dt->user_id = $req->user_id;
        $dt->type = $req->type;//online, chamber
        $dt->request_tds = date("Y-m-d H:i:s");
         if($dt->save()){
            return success_error(false, "Thanks for your request for Nutritionist. Our office will contact you soon to facilitate the appointment.", "", 200);
        }else{
            return success_error(true, "Unable to save data.", "", 400);
        }
    }

     /*public function request_for_personal_therapist(Request $req)
    {
        $dt = new RequestTherapist;
        $cq = RequestTherapist::where('user_id', $req->user_id)->where('status', 'waiting')->where('type', 'personal')->first();
        if(empty($cq)){
            $dt->user_id = $req->user_id;
            $dt->type = 'personal';
            $dt->tds = date("Y-m-d H:i:s");
             if($dt->save()){
                return success_error(false, "Thanks you for your request for personal therapist. Our office will contact you soon to facilitate the appointment/ doctor visit.", "", 200);
            }else{
                return success_error(true, "Unable to save data.", "", 400);
            }
        }else{
            return success_error(true, "You have already an appoinment for personal therapist", "", 200);
        }

    }

    public function request_for_group_therapist(Request $req)
    {
        $dt = new RequestTherapist;
        $cq = RequestTherapist::where('user_id', $req->user_id)->where('status', 'waiting')->where('type', 'group')->first();
        if(empty($cq)){
            $dt->user_id = $req->user_id;
            $dt->type = 'group';
            $dt->tds = date("Y-m-d H:i:s");
             if($dt->save()){
                return success_error(false, "Thanks you for your request for group therapist. Our office will contact you soon to facilitate the appointment/ doctor visit.", "", 200);
            }else{
                return success_error(true, "Unable to save data.", "", 400);
            }
        }else{
            return success_error(true, "You have already an appoinment for group therapist", "", 200);
        }
    }*/

    public function firebase_update(){
        $data = User::where("type", "app_user")->get();
        $ttl = count($data);
        $inp = 0;

        /*require 'public/firebase-php/vendor/autoload.php';
        $factory = (new Factory)
            ->withServiceAccount('public/firebase-php/crid-6fa32-firebase-adminsdk-8kb2n-58371e7332.json')
            ->withDatabaseUri('https://crid-6fa32.firebaseio.com');
        $database = $factory->createDatabase();
        foreach($data as $udt){
            $database->getReference('vital_status/'.$udt->id.'/observation_end')->set(0);
            $database->getReference('vital_status/'.$udt->id.'/observation_note')->set(0);
            $database->getReference('vital_status/'.$udt->id.'/doctor_id')->set(0);
            $inp++;
        }*/

        return success_error(false, "Success ", ['total' => $ttl, 'inp' => $inp], 200);
    }

    public function observation_note(Request $req){
        require base_path().'/public/firebase-php/vendor/autoload.php';
        $factory = (new Factory)
            ->withServiceAccount(base_path().'/public/firebase-php/crid-6fa32-firebase-adminsdk-8kb2n-58371e7332.json')
            ->withDatabaseUri('https://crid-6fa32.firebaseio.com');
        $database = $factory->createDatabase();
        $database->getReference('vital_status/'.$req->userID.'/observation_end')->set($req->end_date);
        $database->getReference('vital_status/'.$req->userID.'/observation_note')->set($req->note);
        $database->getReference('vital_status/'.$req->userID.'/doctor_id')->set($req->doctor_id);

        $dt = new UnderObservation;
        $dt->patient_id = $req->userID;
        $dt->doctor_id = $req->doctor_id;
        $dt->note = $req->note;
        $dt->end_date = $req->end_date;

        if ($dt->save()) {
            return success_error(false, "Success ", "", 200);
        }else{
            return success_error(true, "Error", "", 400);
        }
    }
    public function authorized_member_update(Request $req){
        $store_data=['name'=>$req->firstname.' '.$req->lastname,'firstname'=>$req->firstname,'lastname'=>$req->lastname,'mobile'=>$req->mobile,'other_relation'=>$req->other];
        if($req->password!=null){
          $store_data['password']=bcrypt($req->password);
        }
        if($req->other!=null){
         $store_data['other_relation']=$req->other;
        }
        if($req->relation!=null){
         $store_data['emergency_contact_relation']=$req->relation;
        }

        $user=User::where('authorized_by',$req->auth_id)->where('id',$req->id)->update($store_data);
        if ($user) {
            return success_error(false, "Success ", "", 200);
        }else{
            return success_error(true, "Error", "", 400);
        }
    }
    public function AuthorizedMemberStore(Request $req){
        //$cq = User::where("mobile", $req->mobile)->where("authorized_by", $req->auth_id)->where("id", "<>", $req->id)->exists();
        $cq = User::where("mobile", $req->mobile)->where("id", "<>", $req->id)->exists();

        if($req->auth_id == ""){
            return success_error(true, "Please send User ID/Auth ID", "", 200);
        }

        if($cq){
            return success_error(true, $req->mobile." already enlisted", "", 200);
        }

        if($req->id > 0){
            $dt = User::find($req->id);
        } else {
            $dt = new User;
        }

        $dt->name = $req->firstname.' '.$req->lastname;
        $dt->firstname = $req->firstname;
        $dt->lastname = $req->lastname;
        $dt->username = $req->mobile;
        $dt->mobile = $req->mobile;
        $dt->emergency_contact_relation = $req->relation;
        $dt->other_relation = $req->other;
        $dt->type = 'authorized_member';
        $dt->authorized_by = $req->auth_id;
        if($req->password){
          $dt->password = Hash::make($req->password);
        }

        if ($dt->save()) {
            return success_error(false, "Action is successful", "", 200);
        } else {
            return success_error(true, "Action is unsuccessful", "", 200);
        }
    }

    public function authorized_member_list(Request $req){
        $id = $req->user_id;
        $dt = User::where("authorized_by", $id)->where("type", "authorized_member")->get();
        $arr = [];
        foreach($dt as $val){
            $arr[] = array(
                "id" => $val->id,
                "name" => $val->name,
                "firstname" => $val->firstname,
                "lastname" => $val->lastname,
                "username" => $val->username,
                "mobile" => $val->mobile,
                "emergency_contact_relation" => $val->emergency_contact_relation,
                "other_relation" => $val->other_relation,
                "type" => $val->type,
                "authorized_by" => $val->authorized_by,
            );
        }

        return success_error(false, "", $arr, 200);

    }

    public function weekly_report_pdf_list($id){
        $data = DB::table( 'weekly_report_pdf_lists' )
            	->join( 'users', 'users.id', '=', 'weekly_report_pdf_lists.created_by' )
            	->where('weekly_report_pdf_lists.user_id', $id)
            	->select( 'weekly_report_pdf_lists.*', 'users.name' )->orderBy('weekly_report_pdf_lists.id', 'DESC')->get();
        $arr = [];
        foreach($data as $val){
            $arr[] = array(
                "provider_name" => $val->name,
                "tds" => $val->tds,
                "pdf" => "public/weekly-report-pdf/".$val->pdf,
                "id" => $val->id,
            );
        }
        if($arr){
           return success_error(false, "success", $arr, 200);
        }else{
            return success_error(false, "data not found", '', 400);
        }

    }

     public function request_drug_interaction(Request $req)
    {
        $id = $req->user_id;
        $dt = new DrgIntRequest;
        $dt->user_id = $req->user_id;
        $dt->tds = date("Y-m-d H:i:s");

        if ($dt->save()) {
            return success_error(false, "Thank you for your request, someone from our office will contact you soon. ", "", 200);
        }else{
             return success_error(true, "Request cannot accept", "", 200);
        }
    }

    public function store_point(Request $req)
    {
        $id = $req->user_id;
        $dt = User::find($id);
        $arr = [];

        if ($dt->point != NULL) {
            $ppoint = $dt->point;
            $data = $ppoint+$req->point;
            $dt->point = $data;

            if ($dt->save()) {
                $pt = User::find($id);
                $arr[] = array(
                "point" => $pt->point,
                "id" => $pt->id,
            );
                return success_error(false, "Data successfully saved", $arr, 200);
            }else{
                return success_error(true, "Unable to save data.", "", 400);
            }
        }else{
          $dt->point = $req->point;
          if ($dt->save()) {
            $pt = User::find($id);
                $arr[] = array(
                "point" => $pt->point,
                "id" => $pt->id,
            );
                return success_error(false, "Data successfully saved", $arr, 200);
            }else{
                return success_error(true, "Unable to save data.", "", 400);
            }
        }
    }



    public function doctor_appointment_request(Request $req){
        $cq = DoctorAppointment::where('user_id', $req->user_id)->where('status', 'waiting');
        if($cq->exists()){
            return success_error(true, "You have already an appoinment", "", 200);
        }else{
            $dt = new DoctorAppointment;
            $dt->user_id = $req->user_id;
            $dt->doctor_id = $req->doctor_id;
            $dt->reason = $req->reason;
            $dt->tds = date("Y-m-d H:i:s");
             if($dt->save()){
                return success_error(false, "Thanks for your request for appointment. Our back office will contact you soon to facilitate the appointment/ doctor visit.", "", 200);
            }else{
                return success_error(true, "Unable to save data.", dd($dt), 400);
            }
        }
    }

    public function appointment_request_specialist(Request $req){
        $cq = AppointmentSpecialist::where('user_id', $req->user_id)->where('status', 'waiting');
        if($cq->exists()){
            return success_error(true, "You have already an appoinment", "", 200);
        }else{
            $dt = new AppointmentSpecialist;
            $dt->user_id = $req->user_id;
            $dt->reason = $req->reason;
            $dt->tds = date("Y-m-d H:i:s");
             if($dt->save()){
                return success_error(false, "Thanks for your request for appointment. Our back office will contact you soon to facilitate the appointment/ doctor visit.", "", 200);
            }else{
                return success_error(true, "Unable to save data.", dd($dt), 400);
            }
        }
    }


    public function GetAllSessions(){
        $data['live_session'] = Session::where("starttds", "<", date("Y-m-d H:i:s"))->where("endtds", ">", date("Y-m-d H:i:s"))->get();
        $data['previous_session'] = Session::where("endtds", "<", date("Y-m-d H:i:s"))->get();
        $data['upcoming_session'] = Session::where("starttds", ">", date("Y-m-d H:i:s"))->get();
        return success_error(false, "Records", $data, 200);
    }

    public function GetUserSessions($id){
        $arr = UserSession::where("user_id", $id)->where("status", "joined")->get();
        $data = [];
        $url = 'http://'.$_SERVER['SERVER_NAME'].'/public/session/thumbnail/';
        foreach ($arr as $val) {
            $sInfo = Session::find($val->session_id);
            $is_live = 0;
            if($sInfo['starttds'] < date("Y-m-d H:i:s") && $sInfo['endtds'] > date("Y-m-d H:i:s")){
                $is_live = 1;
            }
            $data[] = [
                "id" => $val->id,
                //"session_info" => $sInfo,
                "status" => $val->status,
                "is_live" => $is_live,
                "session_id" => $val->session_id,
                "heading" => $sInfo['heading'],
                "tutor" => $sInfo['tutor'],
                "zoom_url" => $sInfo['zoom_url'],
                "video_link" => $sInfo['video_link'],
                "duration" => $sInfo['duration'],
                "starttds" => $sInfo['starttds'],
                "endtds" => $sInfo['endtds'],
                "thumbnail" => ($sInfo['thumbnail'] ? $url.$sInfo['thumbnail'] : "")
            ];
        }
        return success_error(false, "Records", $data, 200);
    }

    public function JoinLeaveSession(Request $req){

        if($req->status == 'leave'){
            $cq = UserSession::where("user_id", $req->user_id)->where("session_id", $req->session_id)->exists();
            if($cq){
                //$info = UserSession::where("user_id", $req->user_id)->where("session_id", $req->session_id)->first();
                UserSession::where("user_id", $req->user_id)->where("session_id", $req->session_id)->update([
                    "status" => "left"
                ]);
                //$dt->status = 'left';
                //$dt->save();
                //UserSession::where("user_id", $req->user_id)->where("session_id", $req->session_id)->delete();
                return success_error(false, "You successfully leaved from the session", $this->GetUserSessions($req->user_id), 200);
            } else {
                return success_error(false, "We cannot find your session", "", 200);
            }
        }

        $dt = new UserSession;
        $dt->user_id = $req->user_id;
        $dt->session_id = $req->session_id;
        $dt->status = 'joined';
        if($dt->save()){
            return success_error(false, "Data successfully saved", $this->GetUserSessions($req->user_id), 200);
        }else{
            return success_error(true, "Unable to save data.", "", 400);
        }
    }

    public function GetMindBodySoulData(){

        $data = MindBody::get();

        return success_error(false, "Mind Body Soul Data", $data, 200);

    }

    public function test_list(Request $req,$id=false){

        $id=$req->user_id ?? $id;
        $arr = [];

        $data = UserTest::where("user_id", $id);
        $user_id=auth('api')->user()->id ?? 0;
        if($user_id==$id){
            $data =$data->get();
        }else{
            $data =$data->selectRaw("id,testname,tds,user_id,images")->paginate(10)->appends($_GET);
            //return success_error(false, "", $data, 200);
        }

        foreach($data as $val){
            $Imgs = json_decode($val->images);
            $images = [];
            foreach($Imgs as $ival){
                $images[] = ["img" => $ival];
            }
            $arr[] = array(
                "testname" => $val->testname,
                "tds" => $val->tds,
                "images" => $images,
                "id" => $val->id,
            );
         }

        return success_error(false, "Records", $arr, 200);
    }

    public function all_lab_test_list()
    {

        $data = OrderLabTest::all();
        $arr = [];

        foreach($data as $val){
            $arr[] = array(
                "id" => $val->id,
                "testname" => $val->test_name,
            );
        }
        return success_error(false, "", $arr, 200);
    }

    public function test_create(Request $req){
        $dt = new UserTest;
        $dt->tds =date("Y-m-d");
        $id=isset($req->user_id) ? $req->user_id:auth('api')->user()->id;
        $dt->user_id = $id;
        $dt->testname = $req->testname;

        //multiple image insert
       // dd($req->images);

        $dtarr= $this->ImageDecode($req->images);

        $dt->images = json_encode($dtarr);

        if($dt->save()){
            //$data = UserTest::where("user_id", $req->user_id)->get();
            return success_error(false, "Data successfully saved", $dt->all(), 200);
        }else{
            return success_error(true, "Unable to save data.", "", 400);
        }
    }

    function ImageDecode($images){


        $decode_images = array();

        foreach($images as $b64){

        $bin = base64_decode($b64);
        //dd($bin);
            // Gather information about the image using the GD library
        $size = getImageSizeFromString($bin);
            // Check the MIME type to be sure that the binary data is an image
        if (empty($size['mime']) || strpos($size['mime'], 'image/') !== 0) {
            return success_error(false, "Base64 value is not a valid image", "", 200);
            }
            // Mime types are represented as image/gif, image/png, image/jpeg, and so on
            // Therefore, to extract the image extension, we subtract everything after the “image/” prefix
            $ext = substr($size['mime'], 6);
            // Make sure that you save only the desired file extensions
            if (!in_array($ext, ['png', 'gif', 'jpeg'])) {
            return success_error(false, "Unsupported image type", "", 200);
            }
            // Specify the location where you want to save the image
            $photo_name = date('d_m_Y_H_i_s').uniqid().".".$ext;
            $img_file = public_path ("lab_test/".$photo_name);
            file_put_contents($img_file, $bin);
            $photo_name1 = "public/lab_test/".$photo_name;

            array_push($decode_images,$photo_name1);
        }
            return $decode_images;

    }
     public function store_feedback(Request $req)
    {
        $dt = new UserFeedback;
        $dt->user_id = $req->user_id;
        $dt->message = $req->message;
        $dt->tds = date("Y-m-d H:i:s");
        if($dt->save()){
            return success_error(false, "Data successfully saved", "", 200);
        }else{
            return success_error(true, "Unable to save data.", "", 400);
        }
    }

    public function test_edit(Request $req){

        $dtarr = array();
        //multiple image insert
        // dd($req->images);

        $imagearr = $req->images;
        foreach($imagearr as $b64){
            // Obtain the original content (usually binary data)
            $bin = base64_decode($b64);
            //dd($bin);
                // Gather information about the image using the GD library
             $size = getImageSizeFromString($bin);
                // Check the MIME type to be sure that the binary data is an image
             if (empty($size['mime']) || strpos($size['mime'], 'image/') !== 0) {
                  return success_error(false, "Base64 value is not a valid image", "", 200);
                }
                // Mime types are represented as image/gif, image/png, image/jpeg, and so on
                // Therefore, to extract the image extension, we subtract everything after the “image/” prefix
                $ext = substr($size['mime'], 6);
                // Make sure that you save only the desired file extensions
                if (!in_array($ext, ['png', 'gif', 'jpeg'])) {
                  return success_error(false, "Unsupported image type", "", 200);
                }
                // Specify the location where you want to save the image
                $photo_name = date('d_m_Y_H_i_s').uniqid().".".$ext;
                $img_file = public_path ("lab_test/".$photo_name);
                file_put_contents($img_file, $bin);
                $photo_name1 = "public/lab_test/".$photo_name;
                //$dt->images = $photo_name;

                $dtarr[] = $photo_name1;
        }



        $dt = UserTest::find($req->test_id);
        $dt->tds =isset($req->tds)? $req->tds : date("Y-m-d H:i:s");;
        $dt->user_id = $req->user_id;
        $dt->testname = $req->testname;
        $dt->reason = isset($req->reason)? $req->reason:$dt->reason;
        $dt->images = json_encode($dtarr);

        if ($dt->save()) {
            return success_error(false, "Data successfully updated.", "", 200);
        }else{
            return success_error(true, "Unable to update data.", "", 400);
        }
    }

    public function test_delete(Request $req)
    {
        $cq = UserTest::where("id", $req->test_id)->exists();
        if (!$cq) {
            return success_error(false, 'This record not found in server.', "", 404);
        }

        $delete = UserTest::find($req->test_id)->delete();
        if ($delete) {
            return success_error(false, 'Record successfully deleted.', "", 200);
        } else {
            return success_error(true, 'Unable to delete the record', "", 403);
        }
    }


    public function prescription_list(Request $req,$id=false){
        $id=$req->user_id ?? $id;
        $arr = [];
        $url = 'http://'.$_SERVER['SERVER_NAME'].'/public/prescription/';
        $data = Prescription::where("user_id", $id);
        $user_id=auth('api')->user()->id ?? 0;
        if($user_id==$id){
            $data =$data->get();
        }else{

            $data =$data->selectRaw("id,heading,dr_name,user_id,CONCAT('$url',image) as imageurl,image")->paginate(10)->appends($_GET);

            return success_error(false, "", $data, 200);
        }

        foreach($data as $val){
           // $dinfo = $val->doctor_id > 0 ? User::find($val->doctor_id) : "";
            $arr[] = array(
                "id" => $val->id,
                "heading" => $val->heading,
                "doctor_name" => $val->dr_name,
                "doctor_id" => $val->doctor_id,
              //  "dname" => $dinfo ? $dinfo->name : "",
                "user_id" => $val->user_id,
                "image" => $val->image,
                "imageurl" => $url.$val->image,
            );
        }
        return success_error(false, "", $arr, 200);
    }
    public function providerSchedule(Request $req){


        $Loged_user_info=DB::table('users')->select('id','type','user_sub_category')->where('id', $req->user_id)->first();

             $date=Carbon::now()->format('Y-m-d');

                 if(!$req->schedule_type){
                     $req->schedule_type='present';
                 }

                 $query=DB::table('physicians_schedule_sets')
                      ->select('physicians_schedule_sets.id as schedule_id',
                          'physicians_schedule_sets.physician_id as doctor_id',
                          'physicians_schedule_sets.member_id as patient_id',
                          DB::raw("(select users.name from users where users.id=physicians_schedule_sets.physician_id) as doctor_name"),
                          'users.name as patient_name',
                          'users.mobile as user_mobile',
                          'users.age as user_age',
                          'users.gender as user_gender',
                          'users.photo as user_photo',
                          'users.date_of_birth as patient_birth_date',
                         'physicians_schedule_sets.remarks as status',
                         'physicians_schedule_sets.meeting_url',
                         'physicians_schedule_sets.type as doctor_type',
                         'physicians_schedule_sets.note as reason',
                         'physicians_schedule_sets.stime as schedule_time',
                          DB::raw('DATE_FORMAT(physicians_schedule_sets.sdate, "%d-%b-%Y") as shedule_date'),
                          DB::raw('DATE_FORMAT(physicians_schedule_sets.created_at, "%d-%b-%Y") as created_at')
               )->rightjoin('users','users.id','physicians_schedule_sets.member_id');

                 if(isset($req->search_keyword) && $req->search_keyword!=null && !empty($req->search_keyword)){
                    $query=$query->where('users.name', 'LIKE', "%{$req->search_keyword}%")
                    ->orWhere('users.mobile', 'LIKE', "%{$req->search_keyword}%")
                    ->orWhere('users.username', 'LIKE', "%{$req->search_keyword}%");
                 }
                 if($req->schedule_type=='present'){
                    $query= $query->whereDate('physicians_schedule_sets.sdate',$date);
                }elseif($req->schedule_type=='previous'){
                    $query= $query->whereDate('physicians_schedule_sets.sdate','<',$date);
                }elseif($req->schedule_type=='upcoming'){
                    $query= $query->whereDate('physicians_schedule_sets.sdate','>',$date);
                }else{
                   $query= $query->whereDate('physicians_schedule_sets.sdate','<','1980-01-01');
                }
                 if($Loged_user_info->type!="doctor" && $Loged_user_info->user_sub_category>1){

                    $data= $query->where("users.user_sub_category",$Loged_user_info->user_sub_category)
                                 ->groupBy("physicians_schedule_sets.id")
                                 ->orderBy("physicians_schedule_sets.sdate", "desc")->get();
                     return success_error(true, 'Shedule List', $data, 200);

                 }elseif($Loged_user_info->type=="doctor"){

                     $data=$query->where('physicians_schedule_sets.physician_id',$Loged_user_info->id)->groupBy("physicians_schedule_sets.sdate")->orderBy("physicians_schedule_sets.sdate", "desc")->get();
                     return success_error(false, 'Shedule List', $data, 200);
                 }else{
                     return success_error(true, 'No Shedule Found', [], 400);
                 }


     }

     public function SetProviderSchedule(Request $req){


            $dc = new PhysiciansScheduleSet;
            $dc->physician_id = $req->doctor_id;
            $dc->type = User::select('type')->where("id",$req->doctor_id)->first()->type;
            $dc->member_id = $req->patient_id;
            $dc->sdate = date("Y-m-d",strtotime($req->scheduled_date));
            $dc->stime = $req->scheduled_time;
            $dc->note = $req->reason;
            $dc->remarks = "Pending";
            $dc->meeting_url = "meeting_url";
            $dc->created_by=auth('api')->user()->id ?? 0;

            try {
                $dc->save();
                return success_error(false, "Schedule Set Successfully", [],200);
            } catch (\Throwable $th) {
                return success_error(true, $th->getMessage(),[],400);
            }
    }
     public function get_prescription_by_doctor($id)
    {
        $data = DB::table('prescriptions')
        ->join('users', 'users.id', '=', 'prescriptions.user_id')
        ->select('prescriptions.*', 'users.firstname as pfname', 'users.lastname  as plname', 'users.mobile as patient_mobile', 'users.name as pname', 'users.id as patient_id')
        ->where('prescriptions.user_id', $id)
        //->limit(10)
        ->orderBy('id', 'DESC')
        ->get();

        foreach($data as $val){
            $mdc = json_decode($val->medicine);
            $vts = json_decode($val->vitals);

            $arr[] = array(
                "patient_mobile" => $val->patient_mobile,
                "p_firstname" => $val->pfname,
                "p_lastname" => $val->plname,
                "p_name" =>$val->pname,
                "prescription_id" => $val->prescription_id,
                "visit_date" => $val->visit_date,
                "chief_complain" => $val->chief_complain,
                "existing_medical_condition" => $val->existing_medical_condition,
                "past_medical_history" => $val->past_medical_history,
                "family_history" => $val->family_history,

                "inv" => $val->inv,
                "pathological_test" => $val->pathological_test,
                "diagnosis" => $val->diagnosis,
                "adv" => $val->adv,
                "next_meet" => $val->next_meet,
                "tds" => $val->tds,
                "vitals" =>$vts,
                "medicine" =>$mdc,

            );
         }

       return success_error(false, "", $arr, 200);
    }

    public function get_prescription_list_by_doctor(Request $req,$id=false)
    {

        $id=$req->user_id ?? $id;
        $arr = [];
        $url = 'http://'.$_SERVER['SERVER_NAME'].'/public/prescription/';
        $data = DB::table('prescriptions')
        ->join('users', 'prescriptions.doctor_id', '=', 'users.id')
        ->selectRaw("prescriptions.id as prescription_id,prescriptions.tds as date,prescriptions.user_id as patient_id,prescriptions.prescription_id as visit_id,users.name as doc_name,users.mobile as d_mobile,users.id as doc_id,CONCAT('$url',prescriptions.image) as imageurl,prescriptions.image as image");

        $user_id=auth('api')->user()->id ?? 0;
        if($user_id==$id){
            $data =$data->where('prescriptions.user_id', $id)->orderBy('prescriptions.id', 'DESC')->get();
        }else{

            $data =$data->where('prescriptions.doctor_id',$user_id)->orderBy('prescriptions.id', 'DESC')->paginate(10)->appends($_GET);
        }

        foreach($data as $val){
            $patient=DB::table('users')->where("id",$val->patient_id)->first();
            $arr[] = array(
                "doc_name" =>$val->doc_name,
                "patient_id" =>$patient->id??"",
                "patient_name" =>$patient->name??"",
                "prescription_id" => $val->prescription_id,
                "visit_id" => $val->visit_id,
                "date" => $val->date,
                "image" => $val->image,
            );
         }

       return success_error(false, "", $arr, 200);
    }

    public function sp_doctor_prescription_records()
    {

        $arr = [];
        $doctor_id=auth('api')->user()->id ?? 0;
        $data = DB::table('prescriptions')
        ->join('users', 'prescriptions.doctor_id', '=', 'users.id')
        ->selectRaw("prescriptions.id as prescription_id,prescriptions.tds as date,prescriptions.user_id as patient_id,prescriptions.prescription_id as visit_id,users.name as doc_name,users.mobile as d_mobile,users.id as doc_id")
        ->where('prescriptions.doctor_id',$doctor_id)->orderBy('prescriptions.id', 'DESC')->paginate(10)->appends($_GET);

        foreach($data as $val){
            $patient=DB::table('users')->where("id",$val->patient_id)->first();
            $arr[] = array(
                "doc_name" =>$val->doc_name,
                "patient_id" =>$patient->id??"",
                "patient_name" =>$patient->name??"",
                "prescription_id" => $val->prescription_id,
                "visit_id" => $val->visit_id,
                "date" => $val->date,
            );
         }

       return success_error(false, "", $arr, 200);
    }

    public function Sp_app_prescription_view(Request $request){

        if(isset($request->patient_id) && isset($request->visit_id)){
        $arry=[];
        $arry['pcp_note_summery']=[];
        $arry['provisinal_diagnosis']=[];
        $spdata=SpecialistAssessment::where("patient_id",$request->patient_id)->where("visit_id",$request->visit_id)->orderBy('created_at','DESC')->get();

        foreach($spdata as $val){
            $dinfo = $val->doctor_id > 0 ? User::find($val->doctor_id) : "";
            $spinfo = $val->specilest_id > 0 ? User::find($val->specilest_id) : "";
            $arry['pcp_note_summery'][] = array(
                "id" => $val->id,
                "doctor_id" => $val->doctor_id,
                "dname" => $dinfo ? $dinfo->name : "",
                "specilest_id" => $val->specilest_id,
                "spdoctorname" => $spinfo ? $spinfo->name : "",
                "patient_id" => $val->patient_id,
                "note" => $val->note,
                "created_at" => $val->created_at,
            );
        }
        $pro_data = ProvisinalDiagnosis::where("patient_id",$request->patient_id)->where("visit_id",$request->visit_id)->orderBy('created_at','DESC')->get();
        foreach($pro_data as $val){
            $dinfo = $val->doctor_id > 0 ? User::find($val->doctor_id) : "";
            $arry['provisinal_diagnosis'][] = array(
                "id" => $val->id,
                "doctor_id" => $val->doctor_id,
                "dname" => $dinfo ? $dinfo->name : "",
                "patient_id" => $val->patient_id,
                "provisinal_note" => $val->provisinal_note,
                "provisinal_diagnosis" => $val->provisinal_diagnosis,
                "created_at" => $val->created_at,
            );
        }
        $arry['specialist_assessment']=DoctorNote::where("user_id",$request->patient_id)->where("visit_id",$request->visit_id)->orderBy('created_at','DESC')->get();
        $arry['OrderLabTest']=OrderLabTest::where("user_id",$request->patient_id)->where("visit_id",$request->visit_id)->orderBy('created_at','DESC')->get();
        $arry['old_medicine']= Medicine::where("user_id", $request->patient_id)->where("medicine_status","continue")->orderBy('created_at','DESC')->get();
        $arry['New_medicine']= Medicine::where("user_id", $request->patient_id)->where("visit_id", $request->visit_id)->orderBy('created_at','DESC')->get();
        $arry['user_info']=PhysiciansScheduleSet::selectRaw('physicians_schedule_sets.remarks,physicians_schedule_sets.note as reason,users.id as user_id,users.name as name, users.mobile as mobile,users.gender as gender,users.age as age,users.patient_id as patient_id')->join('users','users.id','physicians_schedule_sets.member_id')->where('physicians_schedule_sets.physician_id',auth('api')->user()->id)->first();
        return success_error(false, "Prescription View",$arry, 200);
       }else{
            return success_error(true, "Invalid Perameter",[], 200);
        }
    }
    public function Sp_app_prescription_submit(Request $request){

        if(isset($request->patient_id) && isset($request->visit_id)){

        $pro_data = ProvisinalDiagnosis::where("patient_id",$request->patient_id)->where("visit_id",$request->visit_id)->orderBy('created_at','DESC')->pluck('provisinal_diagnosis')->toArray();

        $testarray=OrderLabTest::where("user_id",$request->patient_id)->where("visit_id",$request->visit_id)->orderBy('created_at','DESC')->pluck('test_name')->toArray();

        $medicines_data= Medicine::where("user_id", $request->patient_id)->where("visit_id", $request->visit_id)->orderBy('created_at','DESC')->get();

        function dosage($val){
            if($val=="yes"){
                return 1;
            }else{
                return 0;
            }
        }
        $medicines=[];
        foreach($medicines_data as $val){
            $medicines[]= array(
                "type" =>$val->type,
                "name" =>$val->medicine_name??"",
                "measurement" =>$val->measurement_unit,
                "suffix" => $val->prescription_id,
                "generic" => $val->generic_name,
                "days" => $val->how_many_days,
                "continue" => 1,
                "medStatus" => $val->medicine_status,
                "dosage" => dosage($val->morning)."+".dosage($val->afternoon)."+".dosage($val->night),
                "use_of_medicine" => $val->borameal,
                "special_inst" => "",
                "medicine_reason" => "",
            );
         }

        $pres = new Prescription;
        $pres->user_id = $request->patient_id;
        $pres->prescription_id = $request->visit_id;
        $pres->heading = "Specialest";
        $pres->visit_date = date("Y-m-d");
        $pres->diagnosis = implode(",",$pro_data);
        $pres->pathological_test = implode(",",$testarray);
        $pres->order_new_labs = implode(",",$testarray);
        $pres->patient_pr_in_own_lang = $request->patient_talk??"";//$pres->latest()->whereNotNull('patient_pr_in_own_lang')->where('user_id',$request->patient_id)->pluck('patient_pr_in_own_lang')??"";
        //$pres->chief_complain = $req->chief_complain;
        //$pres->existing_medical_condition = $req->existing_medical_condition;
        //$pres->past_medical_history = $req->past_medical_history;
        //$pres->family_history = $req->family_history;
        $vitals=$pres->whereNotNull('vitals')->where('user_id',$request->patient_id)->pluck('vitals')??[""];
        $pres->vitals =(!empty($vitals[0]) ? $vitals[0] : "");
        //$pres->inv = $req->inv;
        //$pres->pathological_test = $req->pathological_test;
        //$pres->diagnosis = $req->diagnosis;
       $pres->medicine = (!empty($medicines) ? json_encode($medicines) : "");
        $pres->adv = $request->advice;
        //$pres->mental_note = $req->mental_note;
        //$pres->dental_note = $req->dental_note;
        //$pres->next_meet = $req->next_meet;
        $pres->tds = date("Y-m-d");
        $pres->doctor_id = $request->doctor_id;
        //$pres->confidential_information = $req->confidential_information;
        //$pres->exposed = $req->exposed;
        //$pres->other_confidential_information = $req->other_confidential_information;
        //$pres->patient_view = $req->patient_view;
        $qry = $pres->save();

        $dt = PhysiciansScheduleSet::where("member_id", $request->patient_id)->where("physician_id",$request->doctor_id)->whereDate("sdate", date("Y-m-d"));
            if($dt->exists()){
                $dt = PhysiciansScheduleSet::find($dt->first()->id);
                $dt->remarks = "Complete";
                $dt->update();
            }
        return success_error(false, "Prescription Submited Succesfully",[], 200);
       }else{
            return success_error(true, "Invalid Perameter",[], 200);
        }
    }

    public function prescription_view_for_app($id)
    {
        $arr = url('prescription-view-for-app/'.$id);

       return success_error(false, "", $arr, 200);
    }

     public function MedicalHistory_view_for_app(Request $req,$id)
    {
        $user_id=auth('api')->user()->id ?? 0;
        if($user_id==$id){
           $arr = url('MedicalHistory_view_for_app/'.$id);
        }else{
           $arr = url('MedicalHistory_view_for_app/'.$id.'/'.$user_id);
        }


       return success_error(false, "", $arr, 200);
    }

    public function prescription_create(Request $req){
        /*// Define the Base64 value you need to save as an image
        $b64 = $req->image;
        // Obtain the original content (usually binary data)
        $bin = base64_decode($b64);
        // Gather information about the image using the GD library
        $size = getImageSizeFromString($bin);
        // Check the MIME type to be sure that the binary data is an image
        if (empty($size['mime']) || strpos($size['mime'], 'image/') !== 0) {
          return success_error(false, "Base64 value is not a valid image", "", 200);
        }
        // Mime types are represented as image/gif, image/png, image/jpeg, and so on
        // Therefore, to extract the image extension, we subtract everything after the “image/” prefix
        $ext = substr($size['mime'], 6);
        // Make sure that you save only the desired file extensions
        if (!in_array($ext, ['png', 'gif', 'jpeg'])) {
          return success_error(false, "Unsupported image type", "", 200);
        }
        // Specify the location where you want to save the image
        $photo_name = time().".".$ext;
        $img_file = "public/prescription/".$photo_name;*/
        // Define the Base64 value you need to save as an image
        $b64 = $req->image;
        // Obtain the original content (usually binary data)
        $bin = base64_decode($b64);
        // Gather information about the image using the GD library
        $size = getImageSizeFromString($bin);
        // Check the MIME type to be sure that the binary data is an image
        if (empty($size['mime']) || strpos($size['mime'], 'image/') !== 0) {
          return success_error(false, "Base64 value is not a valid image", "", 200);
        }
        // Mime types are represented as image/gif, image/png, image/jpeg, and so on
        // Therefore, to extract the image extension, we subtract everything after the “image/” prefix
        $ext = substr($size['mime'], 6);
        // Make sure that you save only the desired file extensions
        if (!in_array($ext, ['png', 'gif', 'jpeg'])) {
          return success_error(false, "Unsupported image type", "", 200);
        }
        // Specify the location where you want to save the image
        $photo_name = time().".".$ext;
        $img_file = public_path ("prescription/".$photo_name);
        // Save binary data as raw data (that is, it will not remove metadata or invalid contents)
        // In this case, the PHP backdoor will be stored on the server
        file_put_contents($img_file, $bin);

        $dt = new Prescription;
        $dt->heading = $req->heading;
        $dt->user_id = $req->user_id;
        //$dt->doctor_id = $req->doctor_id;
        $dt->image = $photo_name;
        $dt->dr_name = $req->doctor_name;
        if($dt->save()){
            //$this->prescription_list($req->user_id);
            //$data = Prescription::where("user_id", $req->user_id)->get();
            return success_error(false, "Data successfully saved", $this->prescription_list($req,$req->user_id), 200);
        }else{
            return success_error(true, "Unable to save data.", "", 400);
        }
    }

    public function prescription_edit(Request $req)
    {
        // Define the Base64 value you need to save as an image
            $b64 = $req->image;
            // Obtain the original content (usually binary data)
            $bin = base64_decode($b64);
            // Gather information about the image using the GD library
            $size = getImageSizeFromString($bin);
            // Check the MIME type to be sure that the binary data is an image
            if (empty($size['mime']) || strpos($size['mime'], 'image/') !== 0) {
              return success_error(false, "Base64 value is not a valid image", "", 200);
            }
            // Mime types are represented as image/gif, image/png, image/jpeg, and so on
            // Therefore, to extract the image extension, we subtract everything after the “image/” prefix
            $ext = substr($size['mime'], 6);
            // Make sure that you save only the desired file extensions
            if (!in_array($ext, ['png', 'gif', 'jpeg'])) {
              return success_error(false, "Unsupported image type", "", 200);
            }
            // Specify the location where you want to save the image
            $photo_name = time().".".$ext;
            $img_file = public_path ("prescription/".$photo_name);
            file_put_contents($img_file, $bin);

            $dt = Prescription::find($req->prescription_id);
            $dt->heading = $req->heading;
            $dt->user_id = $req->user_id;
          //  $dt->doctor_id = $req->doctor_id;
            $dt->image = $photo_name;
            $dt->dr_name = $req->doctor_name;

            if ($dt->save()) {
                return success_error(false, "Data successfully updated.", "", 200);
            }else{
                return success_error(true, "Unable to update data.", "", 400);
            }
    }

    public function prescription_delete(Request $req)
    {
         $cq = Prescription::where("id", $req->prescription_id)->exists();
        if (!$cq) {
            return success_error(false, 'This record not found in server.', "", 404);
        }

        $delete = Prescription::find($req->prescription_id)->delete();
        if ($delete) {
            return success_error(false, 'Record successfully deleted.', "", 200);
        } else {
            return success_error(true, 'Unable to delete the record', "", 403);
        }
    }

    public function doctor_note_list($id){
        $data = DoctorNote::where("user_id", $id)->get();
        $arr = [];

        foreach($data as $val){
            $dinfo = $val->doctor_id > 0 ? User::find($val->doctor_id) : "";
            $arr[] = array(
                "id" => $val->id,
                "doctor_id" => $val->doctor_id,
                "dname" => $dinfo ? $dinfo->name : "",
                "user_id" => $val->user_id,
                "note" => $val->note,
                "created_at" => date("Y-m-d H:i:s", strtotime($val->created_at)),
            );
        }
        return success_error(false, "Records", $arr, 200);
    }
   public function doctor_account_summery($doctor_id){

     $data=[
           "last_week"=>Prescription::whereBetween('created_at',[Carbon::now()->subDays(7)->format("Y-m-d"),Carbon::now()->format("Y-m-d")])->where('prescriptions.doctor_id',$doctor_id)->count(),
           "last_30_days"=>Prescription::whereBetween('created_at',[Carbon::now()->subDays(30)->format("Y-m-d"),Carbon::now()->format("Y-m-d")])->where('prescriptions.doctor_id',$doctor_id)->count(),
           "total_visit"=>Prescription::where('prescriptions.doctor_id',$doctor_id)->count(),
           "last_visited_patients"=>Prescription::select("users.name" ,"users.mobile","prescriptions.visit_date")
                                   ->join("users","users.id","prescriptions.user_id")
                                   ->whereBetween('prescriptions.created_at',[Carbon::now()->subDays(30)->format("Y-m-d"),Carbon::now()->format("Y-m-d")])
                                   ->where('prescriptions.doctor_id',$doctor_id)
                                   ->limit(10)->get()
     ];

     return success_error(false, "Account Summery Data", $data, 200);

   }
    public function doctor_note_create(Request $req){
        $dt = new DoctorNote;
        $dt->doctor_id = $req->doctor_id;
        $dt->user_id = $req->user_id;
        $dt->note = $req->note;
        $dt->visit_id = $req->visit_id??0;
        $dt->tds = date('Y-m-d H:i:s');
        if($dt->save()){
            $data = DoctorNote::where("user_id", $req->user_id)->get();
            return success_error(false, "Data successfully saved", $data, 200);
        }else{
            return success_error(true, "Unable to save data.", "", 400);
        }
    }

    public function edit_doctor_note(Request $req){
        $dt = DoctorNote::find($req->note_id);
        $dt->doctor_id = $req->doctor_id;
        $dt->user_id = $req->user_id;
        $dt->note = $req->note;
        $dt->tds = date('Y-m-d H:i:s');

        if($dt->save()){

            return success_error(false, "Data successfully saved", "", 200);
        }else{
            return success_error(true, "Unable to save data.", "", 400);
        }
    }

    public function doctor_note_delete(Request $req){

        $dn = DoctorNote::where("id", $req->note_id)->exists();
        if (!$dn) {
            return success_error(false, 'This record not found in server.', "", 404);
        }

        $delete = DoctorNote::find($req->note_id)->delete();
        if ($delete) {
            return success_error(false, 'Record successfully deleted.', "", 200);
        } else {
            return success_error(true, 'Unable to delete the record', "", 403);
        }
    }

    public function default_provisinal_diagnosis_list(){
        $data=ProvisinalDiagnosisName::pluck('name');

        return success_error(false, "Provisinal Diagnosis Name", $data, 200);

    }

    public function provisinal_diagnosis_create(Request $req){
        $dt = new ProvisinalDiagnosis;
        $dt->doctor_id = $req->doctor_id;
        $dt->patient_id = $req->patient_id;
        $dt->provisinal_note = $req->provisinal_note;
        $dt->provisinal_diagnosis = $req->provisinal_diagnosis;
        $dt->terget_doctor_id = $req->target_doctor_id??"";
        $dt->visit_id = $req->visit_id??0;
        if($dt->save()){
            $data = ProvisinalDiagnosis::where("patient_id", $req->patient_id)->get();
            return success_error(false, "Data successfully saved", $data, 200);
        }else{
            return success_error(true, "Unable to save data.", "", 400);
        }
    }

    public function provisinal_diagnosis($id){
        $data = ProvisinalDiagnosis::where("patient_id", $id)->get();
        $arr = [];

        foreach($data as $val){
            $dinfo = $val->doctor_id > 0 ? User::find($val->doctor_id) : "";
            $arr[] = array(
                "id" => $val->id,
                "doctor_id" => $val->doctor_id,
                "dname" => $dinfo ? $dinfo->name : "",
                "patient_id" => $val->patient_id,
                "provisinal_note" => $val->provisinal_note,
                "provisinal_diagnosis" => $val->provisinal_diagnosis,
                "created_at" => $val->created_at,
            );
        }
        return success_error(false, "Records", $arr, 200);
    }
    public function edit_provisinal_diagnosis(Request $req){
        $dt = ProvisinalDiagnosis::find($req->diagnosis_id);
        $dt->provisinal_note = $req->provisinal_note;
        $dt->provisinal_diagnosis = $req->provisinal_diagnosis;
        $dt->terget_doctor_id = $req->target_doctor_id??"";

        if($dt->update()){
            return success_error(false, "Data updated successfully", "", 200);
        }else{
            return success_error(true, "Unable to save data.", "", 400);
        }
    }


    public function provisinal_diagnosis_delete($diagnosis_id){

        $dn = ProvisinalDiagnosis::where("id", $diagnosis_id)->exists();
        if (!$dn) {
            return success_error(false, 'This record not found in server.', "", 404);
        }

        $delete = ProvisinalDiagnosis::find($diagnosis_id)->delete();
        if ($delete) {
            return success_error(false, 'Record successfully deleted.', "", 200);
        } else {
            return success_error(true, 'Unable to delete the record', "", 403);
        }
    }


    public function specialist_assessment_create(Request $req){

        if(isset($req->specilest_id) && isset($req->doctor_id) && isset($req->patient_id) && isset($req->note)){
            $dt = new SpecialistAssessment;
            $dt->doctor_id = $req->doctor_id;
            $dt->specilest_id = $req->specilest_id;
            $dt->patient_id = $req->patient_id;
            $dt->note = $req->note;
            $dt->visit_id = $req->visit_id??0;
            if($dt->save()){
                $data = SpecialistAssessment::where("patient_id", $req->patient_id)->get();
                return success_error(false, "Data successfully saved", $data, 200);
            }else{
                return success_error(true, "Unable to save data.", "", 400);
            }
       }else{
        return success_error(true, "please check your input data.", "", 400);
       }
    }

    public function specialist_assessment($id){
        $data = SpecialistAssessment::where("patient_id", $id)->get();
        $arr = [];

        foreach($data as $val){
            $dinfo = $val->doctor_id > 0 ? User::find($val->doctor_id) : "";
            $spinfo = $val->specilest_id > 0 ? User::find($val->specilest_id) : "";
            $arr[] = array(
                "id" => $val->id,
                "doctor_id" => $val->doctor_id,
                "dname" => $dinfo ? $dinfo->name : "",
                "specilest_id" => $val->specilest_id,
                "spdoctorname" => $spinfo ? $spinfo->name : "",
                "patient_id" => $val->patient_id,
                "note" => $val->note,
                "created_at" => $val->created_at,
            );
        }
        return success_error(false, " Specialist Assessment Records", $arr, 200);
    }

    public function edit_specialist_assessment(Request $req){
        $dt = SpecialistAssessment::find($req->assesment_id);
        $dt->note = $req->note;
        $dt->doctor_id = $req->doctor_id??$dt->doctor_id;

        if($dt->update()){
            return success_error(false, "Data updated successfully", "", 200);
        }else{
            return success_error(true, "Unable to save data.", "", 400);
        }
    }

    public function specialist_assessment_delete($assesment_id){

        $dn = SpecialistAssessment::where("id", $assesment_id)->exists();
        if (!$dn) {
            return success_error(false, 'This record not found in server.', "", 404);
        }

        $delete = SpecialistAssessment::find($assesment_id)->delete();
        if ($delete) {
            return success_error(false, 'Record successfully deleted.', "", 200);
        } else {
            return success_error(true, 'Unable to delete the record', "", 403);
        }
    }

    public function update_taking_medicine(Request $req){
        if($req->medicine_id && $req->user_id && $req->taking_period && $req->taken_time){
            $pDate = date("Y-m-d", strtotime($req->taken_time));
            $tPeriod = $req->taking_period;
            $dt = new TakingMedicineLog;
            $dt->medicine_id = $req->medicine_id;
            $dt->user_id = $req->user_id;
            $dt->taking_period = $tPeriod;
            $dt->tds = $req->taken_time;
            $dt->original_tds = $pDate." ".User::find($req->user_id)->$tPeriod.":00";
            if($dt->save()){
                return success_error(false, "Data saved successfully", "", 200);
            } else {
                return success_error(true, "Unable to save data.", "", 400);
            }
        } else {
            return success_error(true, "Please make sure medicine id, user id, taking period, date & time are properly sending.", "", 400);
        }

        /*$dt = new TakingMedicineLog;
        $dt->medicine_id = $req->medicine_id;
        $dt->user_id = $req->user_id;
        $dt->taking_period = $req->taking_period;
        $dt->tds = $req->taken_time;
        if($dt->save()){
            return success_error(false, "Data successfully saved", "", 200);
        }else{
            return success_error(true, "Unable to save data.", "", 400);
        }*/
    }

    public function add_doctor(Request $req){
       $cq = User::where("mobile", $req->mobile_number)->exists();
       // return success_error(false, "found", $cq, 200);
        if($cq){
            $data = User::all();
            return success_error(false, "Records", $data, 200);
        }

        $dt = new User;
        $dt->name = $req->doc_name;
        $dt->speciality = $req->doc_spaciality;
        $dt->email = $req->doc_email ;
        $dt->mobile = $req->mobile_number;
        $dt->office_number = $req->office_number;
        $dt->emergency_number = $req->emergency_number;
        $dt->address = $req->address;
        $dt->type = $req->type;
        if($dt->save()){
            $data = User::all();
            return success_error(false, "Data successfully saved", $data, 200);
        }else{
            return success_error(true, "Unable to save data.", "", 400);
        }
    }
    public function doctor_category(){

            $data = User::where('type', 'Doctor')->whereNotNull('speciality')->distinct()->pluck('speciality');

        return success_error(false, "Records Found", $data, 200);
    }

    public function doctor_list($Search_keyword=null){

        if(isset($Search_keyword)){
            $data = User::where('type', 'Doctor')
                    ->where('name', 'LIKE', "%{$Search_keyword}%")
                    ->orWhere('speciality', 'LIKE', "%{$Search_keyword}%")
                    ->orWhere('username', 'LIKE', "%{$Search_keyword}%")->get();

        }else{
            $data = User::where('type', 'Doctor')->get();
        }

        return success_error(false, "Records", $data, 200);
    }

    public function appointment_entry(Request $req){
        $dt = new Appointment;
        $dt->heading = $req->heading;
        $dt->doctor_id = $req->doctor_id;
        $dt->tds = $req->datentime;
        $dt->location = $req->location;
        $dt->note = $req->note??"";
        $dt->problem = $req->reason??"";
        $dt->patient_id = $req->user_id;
        if($dt->save()){
            $data = Appointment::where("patient_id", $req->user_id)->get();
            return success_error(false, "Data successfully saved", $data, 200);
        }else{
            return success_error(true, "Unable to save data.", "", 400);
        }
    }

    public function appointment_list($id){
        $data = Appointment::where("patient_id", $id)->get();
        return success_error(false, "Records", $data, 200);
    }

    public function TrialNotification(Request $req){
        if(!$req->times && !$req->ampm){
            $data = TrialNotification::all();
            return success_error(false, "Record Found", $data, 200);
        }
        $dt = new TrialNotification;
        $dt->times = $req->times;
        $dt->ampm = $req->ampm;
        if ($dt->save()) {
            $data = TrialNotification::all();
            return success_error(false, "Data successfully saved", $data, 200);
        }else{
            return success_error(true, "Unable to save data.", "", 400);
        }
    }

    public function TrialProfile(Request $req){
        $dt = new TrialProfile;
        $dt->emergency_contact_name = $req->emergency_contact_name;
        $dt->primary_care_contact_number = $req->primary_care_contact_number;
        $dt->primary_care_contact_email = $req->primary_care_contact_email;
        $dt->current_address = $req->current_address;
        $dt->allow_current_location_finder = $req->allow_current_location_finder;
        $dt->drug_alergy = $req->drug_alergy;
        $dt->food_alergy = $req->food_alergy;
        $dt->allergy = $req->allergy;
        if ($dt->save()) {
            return success_error(false, "Data successfully saved", "", 200);
        }else{
            return success_error(true, "Unable to save data.", "", 400);
        }
    }

    public function ShowGeneralVital(Request $req,$id=false){
        $id=$req->user_id ?? auth()->user()->id;
        if(!$id){
            return success_error(true, "Please pass user ID", [], 400);
        }

        $cq = GeneralVital::where("user_id", $id)->exists();
        if(!$cq){
            return success_error(true, "User ID not found", [], 400);
        }

        $start_date_end_date=GeneralVital::CalculationStartdateEnddate($req);

        $data['fdate']=$start_date_end_date[0];
        $data['tdate']=$start_date_end_date[1];

        $selected_activity=["activity_mode","activity_state","activity_step","activity_calorie","activity_calorie_unit","activity_distance","activity_distance_unit","workout_start","workout_end","workout_duration","workout_duration_unit","workout_altitude","workout_altitude_unit","workout_airPressure","workout_airPressure_unit","workout_spm","workout_mode","workout_step","workout_distance","workout_distance_unit","workout_calorie","workout_speed",
                "workout_speed_unit","workout_pace","workout_pace_unit"
            ];
        $select_raw= $selected_activity;
        if($req->activity_fileld){
         $selected_activity=["$req->activity_fileld"];
         $select_raw=["$req->activity_fileld".' as val'];
        }


       $type=false;
       if( $req->type==null){
         $type=true;
        }

        if($req->type=='pulse'){
         if($type){
            $_GET['type']='pulse';
         }
          $data['pulse'] = GeneralVital::GeneralVitalData($req,$id,'pulse',['pulse as val']);
        }


        if($req->type=='temperature_reading' || $req->type==null){
        if($type){
            $_GET['type']='temperature_reading';
         }
          $data['temperature_reading'] = GeneralVital::GeneralVitalData($req,$id,'temperature_reading',['temperature_reading as val'])->appends($_GET);

        }


        if($req->type=='weight_reading' || $req->type==null){
        if($type){
            $_GET['type']='weight_reading';
         }
          $data['weight_reading'] = GeneralVital::GeneralVitalData($req,$id,'weight_reading',['weight_reading as val'])->appends($_GET);
        }


        if($req->type=='sleeps'){
        if($type){
            $_GET['type']='sleeps';
         }
          $data['sleeps'] = GeneralVital::GeneralVitalData($req,$id,'sleeps',['sleeps as val'])->appends($_GET);
        }


        if($req->type=='sugar_reading'){
        if($type){
            $_GET['type']='sugar_reading';
         }
          $data['sugar_reading'] = GeneralVital::GeneralVitalData($req,$id,'sugar_reading',['sugar_reading as val'])->appends($_GET);
        }


        if($req->type=='heart_rate' || $req->type==null){
         if($type){
            $_GET['type']='heart_rate';
         }
          $data['heart_rate'] = GeneralVital::GeneralVitalData($req,$id,'pulse',['pulse as val'])->appends($_GET);
        }



        if($req->type=='blood_presure' || $req->type==null){
         if($type){
            $_GET['type']='blood_presure';
         }
          $data['blood_presure'] = GeneralVital::GeneralVitalData($req,$id,'systolic',['systolic as systolic','diastolic as diastolic'],'blood_presure')->appends($_GET);
        }


        if($req->type=='activity_reading'){
          if($type){
            $_GET['type']='activity_reading';
         }
          $data['activity_reading'] =GeneralVital::GeneralVitalActivityData($req,$id,$selected_activity,$select_raw)->appends($_GET);
        }


        //$data = GeneralVital::where("user_id", $id)->limit(100)->get();

        if(empty($data)){
            return success_error(true, "No data available", [], 400);
        }
        return success_error(false, "Record found", $data, 200);
    }

    public function GeneralVitalInput(Request $req){
        //pulse - Systolic, Diastolic, Pulse, Temperature, Weight
        if(!$req->userID){
            return success_error(true, "userID field cannot left empty", "", 400);
        }

        $tempRd = "";
        if($req->temperatureReading != "" && $req->temperatureReading !=null){
            $tempRd = number_format($req->temperatureReading,1);
        }

        //$pulse_status = "Emergency";
        $pulseStCq = VitalStatusSetting::where("section", "pulse")->whereRaw('? between minimum_val and maximum_val', [$req->pulseReading])->first();
        $pulse_status = $pulseStCq ? $pulseStCq->status : "Emergency";
        /*foreach($pulseStCq as $pscq){
            if ( in_array($req->pulseReading, range($pscq->minimum_val,$pscq->maximum_val)) ) {
                 $pulse_status = $pscq->status;
            }
        }*/

        $TempStCq = VitalStatusSetting::where("section", "temperature")->whereRaw('? between minimum_val and maximum_val', [$req->temperatureReading])->first();
        $temp_status = $TempStCq ? $TempStCq->status : "Emergency";

        $DysStCq = VitalStatusSetting::where("section", "dystolic")->whereRaw('? between minimum_val and maximum_val', [$req->diastolicReading])->first();
        $dys_status = $DysStCq ? $DysStCq->status : "Emergency";

        $SysStCq = VitalStatusSetting::where("section", "systolic")->whereRaw('? between minimum_val and maximum_val', [$req->systolicReading])->first();
        $sys_status = $SysStCq ? $SysStCq->status : "Emergency";



        $uinfo = User::find($req->userID);

        if($uinfo->patient_id == ""){
            $patient_id_is_unique = false;
            $patient_id = false;
            while(!$patient_id_is_unique){
                $patient_id = rand(100000,999999);
                $patient_idqry = User::where("patient_id",$patient_id)->first();
                if(empty($patient_idqry)){
                    $patient_id_is_unique = true;
                }
            }
            $uinfo->patient_id = $patient_id;
            $uinfo->save();
            $uinfo = User::find($req->userID);
        }

        require base_path().'/public/firebase-php/vendor/autoload.php';
        $factory = (new Factory)
            ->withServiceAccount(base_path().'/public/firebase-php/crid-6fa32-firebase-adminsdk-8kb2n-58371e7332.json')
            ->withDatabaseUri('https://crid-6fa32.firebaseio.com');
        $database = $factory->createDatabase();
        $database->getReference('vital_status/'.$req->userID.'/name')->set($uinfo->name);
        $database->getReference('vital_status/'.$req->userID.'/user_id')->set($uinfo->id);
        $database->getReference('vital_status/'.$req->userID.'/patient_id')->set($uinfo->patient_id);
        $database->getReference('vital_status/'.$req->userID.'/mobile')->set($uinfo->mobile);
        $database->getReference('vital_status/'.$req->userID.'/weightReading')->set($req->weightReading);
        $database->getReference('vital_status/'.$req->userID.'/weightUnit')->set($req->weightUnit);
        $database->getReference('vital_status/'.$req->userID.'/sugerReading')->set($req->sugerReading);
        $database->getReference('vital_status/'.$req->userID.'/sugerUnit')->set($req->sugerUnit);
        $database->getReference('vital_status/'.$req->userID.'/status')->set($req->status);
        $database->getReference('vital_status/'.$req->userID.'/heart_rate')->set($req->heart_rate);
        $database->getReference('vital_status/'.$req->userID.'/sleeps')->set($req->sleeps);
        $database->getReference('vital_status/'.$req->userID.'/tds')->set($req->tds);

        //$database->getReference('vital_status/'.$req->userID.'/observation_end')->set("");
        //$database->getReference('vital_status/'.$req->userID.'/observation_note')->set("");
        //$database->getReference('vital_status/'.$req->userID.'/doctor_id')->set("");

        if($req->pulseReading != ""){
           $database->getReference('vital_status/'.$req->userID.'/pulse')->set($req->pulseReading);
           $database->getReference('vital_status/'.$req->userID.'/pulse_status')->set($pulse_status);
        }

        if($req->temperatureReading != ""){
            $database->getReference('vital_status/'.$req->userID.'/temp_rd')->set($tempRd);
            $database->getReference('vital_status/'.$req->userID.'/temp_unit')->set($req->temperatureUnit);
            $database->getReference('vital_status/'.$req->userID.'/temperature_status')->set($temp_status);

        }

        if($req->diastolicReading != ""){
            $database->getReference('vital_status/'.$req->userID.'/dys_rd')->set($req->diastolicReading);
            $database->getReference('vital_status/'.$req->userID.'/dystolic_status')->set($dys_status);
        }

        if($req->systolicReading != ""){
            $database->getReference('vital_status/'.$req->userID.'/sys_rd')->set($req->systolicReading);
            $database->getReference('vital_status/'.$req->userID.'/systolic_status')->set($sys_status);
        }

        if($temp_status != 'Emergency' && $sys_status != 'Emergency' && $dys_status != 'Emergency' && $pulse_status != 'Emergency'){
            $database->getReference('vital_status/'.$req->userID.'/observation_end')->set(0);
            $database->getReference('vital_status/'.$req->userID.'/observation_note')->set(0);
            $database->getReference('vital_status/'.$req->userID.'/doctor_id')->set(0);
        }

        $dt = $req->id > 0 ? GeneralVital::find($req->id) : new GeneralVital;
        $dt->pulse = $req->pulseReading;
        $dt->temperature_reading = $tempRd;
        $dt->temperature_unit = $req->temperatureUnit;
        $dt->systolic = $req->systolicReading;
        $dt->diastolic = $req->diastolicReading;
        $dt->weight_reading = $req->weightReading;
        $dt->weight_unit = $req->weightUnit;
        $dt->sugar_reading = $req->sugerReading;
        $dt->sugar_unit = $req->sugerUnit;
        $dt->note = $req->note;
        $dt->user_id = $req->userID;
        $dt->status = $req->status;
        $dt->machine_type = $req->machine_type;
        $dt->heart_rate = $req->heart_rate;
        $dt->sleeps = $req->sleeps;
        $dt->tds = $req->tds;

        $dt->activity_mode = $req->activity_mode;
        $dt->activity_state = $req->activity_state;
        $dt->activity_step = $req->activity_step;
        $dt->activity_calorie = empty($req->activity_calorie) ? $req->activity_calorie : $req->activity_calorie/10000;
        $dt->activity_calorie_unit = $req->activity_calorie_unit;
        $dt->activity_distance = empty($req->activity_distance) ? $req->activity_distance : $req->activity_distance/10000;
        $dt->activity_distance_unit = $req->activity_distance_unit;
        $dt->workout_start = $req->workout_start;
        $dt->workout_end  = $req->workout_end ;
        $dt->workout_duration = $req->workout_duration;
        $dt->workout_duration_unit = $req->workout_duration_unit;
        $dt->workout_altitude = $req->workout_altitude;
        $dt->workout_altitude_unit  = $req->workout_altitude_unit;
        $dt->workout_airPressure = $req->workout_airPressure;
        $dt->workout_airPressure_unit = $req->workout_airPressure_unit;
        $dt->workout_spm = $req->workout_spm;
        $dt->workout_mode = $req->workout_mode;
        $dt->workout_step = $req->workout_step;
        $dt->workout_distance = $req->workout_distance;
        $dt->workout_distance_unit = $req->workout_distance_unit;
        $dt->workout_calorie = $req->workout_calorie;
        $dt->workout_speed = $req->workout_speed;
        $dt->workout_speed_unit = $req->workout_speed_unit;
        $dt->workout_pace = $req->workout_pace;
        $dt->workout_pace_unit = $req->workout_pace_unit;

        $msg = $req->id > 0 ? "updated" : "saved";

        if ($dt->save()) {
            return success_error(false, "Data successfully ".$msg.".", "", 200);
        }else{
            return success_error(true, "Unable to ".$msg." data.", "", 400);
        }
    }

    public function GeneralVitalInputarray(Request $req){
        //pulse - Systolic, Diastolic, Pulse, Temperature, Weight
        if(!$req->userID){
            return success_error(true, "userID field cannot left empty", "", 400);
        }

        $tempRd = "";
        if($req->temperatureReading != ""){
            $tempRd = number_format($req->temperatureReading,1);
        }

        //$pulse_status = "Emergency";
        $pulseStCq = VitalStatusSetting::where("section", "pulse")->whereRaw('? between minimum_val and maximum_val', [$req->pulseReading])->first();
        $pulse_status = $pulseStCq ? $pulseStCq->status : "Emergency";
        /*foreach($pulseStCq as $pscq){
            if ( in_array($req->pulseReading, range($pscq->minimum_val,$pscq->maximum_val)) ) {
                 $pulse_status = $pscq->status;
            }
        }*/

        $TempStCq = VitalStatusSetting::where("section", "temperature")->whereRaw('? between minimum_val and maximum_val', [$req->temperatureReading])->first();
        $temp_status = $TempStCq ? $TempStCq->status : "Emergency";

        $DysStCq = VitalStatusSetting::where("section", "dystolic")->whereRaw('? between minimum_val and maximum_val', [$req->diastolicReading])->first();
        $dys_status = $DysStCq ? $DysStCq->status : "Emergency";

        $SysStCq = VitalStatusSetting::where("section", "systolic")->whereRaw('? between minimum_val and maximum_val', [$req->systolicReading])->first();
        $sys_status = $SysStCq ? $SysStCq->status : "Emergency";



        $uinfo = User::find($req->userID);

        if($uinfo->patient_id == ""){
            $patient_id_is_unique = false;
            $patient_id = false;
            while(!$patient_id_is_unique){
                $patient_id = rand(100000,999999);
                $patient_idqry = User::where("patient_id",$patient_id)->first();
                if(empty($patient_idqry)){
                    $patient_id_is_unique = true;
                }
            }
            $uinfo->patient_id = $patient_id;
            $uinfo->save();
            $uinfo = User::find($req->userID);
        }

        require base_path().'/public/firebase-php/vendor/autoload.php';
        $factory = (new Factory)
            ->withServiceAccount(base_path().'/public/firebase-php/crid-6fa32-firebase-adminsdk-8kb2n-58371e7332.json')
            ->withDatabaseUri('https://crid-6fa32.firebaseio.com');
        $database = $factory->createDatabase();
        $database->getReference('vital_status/'.$req->userID.'/name')->set($uinfo->name);
        $database->getReference('vital_status/'.$req->userID.'/user_id')->set($uinfo->id);
        $database->getReference('vital_status/'.$req->userID.'/patient_id')->set($uinfo->patient_id);
        $database->getReference('vital_status/'.$req->userID.'/mobile')->set($uinfo->mobile);
        $database->getReference('vital_status/'.$req->userID.'/weightReading')->set($req->weightReading);
        $database->getReference('vital_status/'.$req->userID.'/weightUnit')->set($req->weightUnit);
        $database->getReference('vital_status/'.$req->userID.'/sugerReading')->set($req->sugerReading);
        $database->getReference('vital_status/'.$req->userID.'/sugerUnit')->set($req->sugerUnit);
        $database->getReference('vital_status/'.$req->userID.'/status')->set($req->status);
        $database->getReference('vital_status/'.$req->userID.'/heart_rate')->set($req->heart_rate);
        $database->getReference('vital_status/'.$req->userID.'/sleeps')->set($req->sleeps);
        $database->getReference('vital_status/'.$req->userID.'/tds')->set($req->tds);

        //$database->getReference('vital_status/'.$req->userID.'/observation_end')->set("");
        //$database->getReference('vital_status/'.$req->userID.'/observation_note')->set("");
        //$database->getReference('vital_status/'.$req->userID.'/doctor_id')->set("");

        if($req->pulseReading != ""){
           $database->getReference('vital_status/'.$req->userID.'/pulse')->set($req->pulseReading);
           $database->getReference('vital_status/'.$req->userID.'/pulse_status')->set($pulse_status);
        }

        if($req->temperatureReading != ""){
            $database->getReference('vital_status/'.$req->userID.'/temp_rd')->set($tempRd);
            $database->getReference('vital_status/'.$req->userID.'/temp_unit')->set($req->temperatureUnit);
            $database->getReference('vital_status/'.$req->userID.'/temperature_status')->set($temp_status);

        }

        if($req->diastolicReading != ""){
            $database->getReference('vital_status/'.$req->userID.'/dys_rd')->set($req->diastolicReading);
            $database->getReference('vital_status/'.$req->userID.'/dystolic_status')->set($dys_status);
        }

        if($req->systolicReading != ""){
            $database->getReference('vital_status/'.$req->userID.'/sys_rd')->set($req->systolicReading);
            $database->getReference('vital_status/'.$req->userID.'/systolic_status')->set($sys_status);
        }

        if($temp_status != 'Emergency' && $sys_status != 'Emergency' && $dys_status != 'Emergency' && $pulse_status != 'Emergency'){
            $database->getReference('vital_status/'.$req->userID.'/observation_end')->set(0);
            $database->getReference('vital_status/'.$req->userID.'/observation_note')->set(0);
            $database->getReference('vital_status/'.$req->userID.'/doctor_id')->set(0);
        }

        // $dt = $req->id > 0 ? GeneralVital::find($req->id) : new GeneralVital;
        // $dt->pulse = $req->pulseReading;
        // $dt->temperature_reading = $tempRd;
        // $dt->temperature_unit = $req->temperatureUnit;
        // $dt->systolic = $req->systolicReading;
        // $dt->diastolic = $req->diastolicReading;
        // $dt->weight_reading = $req->weightReading;
        // $dt->weight_unit = $req->weightUnit;
        // $dt->sugar_reading = $req->sugerReading;
        // $dt->sugar_unit = $req->sugerUnit;
        // $dt->note = $req->note;
        // $dt->user_id = $req->userID;
        // $dt->status = $req->status;
        // $dt->heart_rate = $req->heart_rate;
        // $dt->sleeps = $req->sleeps;
        // $dt->tds = $req->tds;

        // $dt->activity_mode = $req->activity_mode;
        // $dt->activity_state = $req->activity_state;
        // $dt->activity_step = $req->activity_step;
        // $dt->activity_calorie = empty($req->activity_calorie) ? $req->activity_calorie : $req->activity_calorie/10000;
        // $dt->activity_calorie_unit = $req->activity_calorie_unit;
        // $dt->activity_distance = empty($req->activity_distance) ? $req->activity_distance : $req->activity_distance/10000;
        // $dt->activity_distance_unit = $req->activity_distance_unit;
        // $dt->workout_start = $req->workout_start;
        // $dt->workout_end  = $req->workout_end ;
        // $dt->workout_duration = $req->workout_duration;
        // $dt->workout_duration_unit = $req->workout_duration_unit;
        // $dt->workout_altitude = $req->workout_altitude;
        // $dt->workout_altitude_unit  = $req->workout_altitude_unit;
        // $dt->workout_airPressure = $req->workout_airPressure;
        // $dt->workout_airPressure_unit = $req->workout_airPressure_unit;
        // $dt->workout_spm = $req->workout_spm;
        // $dt->workout_mode = $req->workout_mode;
        // $dt->workout_step = $req->workout_step;
        // $dt->workout_distance = $req->workout_distance;
        // $dt->workout_distance_unit = $req->workout_distance_unit;
        // $dt->workout_calorie = $req->workout_calorie;
        // $dt->workout_speed = $req->workout_speed;
        // $dt->workout_speed_unit = $req->workout_speed_unit;
        // $dt->workout_pace = $req->workout_pace;
        // $dt->workout_pace_unit = $req->workout_pace_unit;

        // $msg = $req->id > 0 ? "updated" : "saved";

        //start array

        foreach ($req->uservitaldata as $val) {
            $pv_data = new GeneralVital([
                'pulse' => $val['pulseReading'],
                'temperature_reading' => $tempRd,
                'temperature_unit' => $val['temperatureUnit'],
                'systolic' => $val['systolicReading'],
                'diastolic' => $val['diastolicReading'],
                'weight_reading' => $val['weightReading'],
                'weight_unit' => $val['weightUnit'],
                'sugar_reading' => $val['sugerReading'],
                'sugar_unit' => $val['sugerUnit'],
                'note' => $val['note'],
                'user_id' => $val['userID'],
                'status' => $val['status'],
                'heart_rate' => $val['heart_rate'],
                'sleeps' => $val['sleeps'],
                'tds' => $val['tds'],
                'activity_mode' => $val['activity_mode'],
                'activity_state' => $val['activity_state'],
                'activity_step' => $val['activity_step'],
                'activity_calorie' => empty($val['activity_calorie']) ? $val['activity_calorie'] : $val['activity_calorie']/10000,
                'activity_calorie_unit' => $val['activity_calorie_unit'],
                'activity_distance' => empty($val['activity_distance']) ? $val['activity_distance'] : $val['activity_distance']/10000,
                'activity_distance_unit' => $val['activity_distance_unit'],
                'workout_start' => $val['workout_start'],
                'workout_end'  => $val['workout_end '],
                'workout_duration' => $val['workout_duration'],
                'workout_duration_unit' => $val['workout_duration_unit'],
                'workout_altitude' => $val['workout_altitude'],
                'workout_altitude_unit'  => $val['workout_altitude_unit'],
                'workout_airPressure' => $val['workout_airPressure'],
                'workout_airPressure_unit' => $val['workout_airPressure_unit'],
                'workout_spm' => $val['workout_spm'],
                'workout_mode' => $val['workout_mode'],
                'workout_step' => $val['workout_step'],
                'workout_distance' => $val['workout_distance'],
                'workout_distance_unit' => $val['workout_distance_unit'],
                'workout_calorie' => $val['workout_calorie'],
                'workout_speed' => $val['workout_speed'],
                'workout_speed_unit' => $val['workout_speed_unit'],
                'workout_pace' => $val['workout_pace'],
                'workout_pace_unit' => $val['workout_pace_unit'],
            ]);
            if ($pv_data->save()) {
                return success_error(false, "Data successfully saved", "", 200);
            }else{
                return success_error(true, "Unable to saved data.", "", 400);
            }
        }

        //endarray

        // if ($dt->save()) {
        //     return success_error(false, "Data successfully ".$msg.".", "", 200);
        // }else{
        //     return success_error(true, "Unable to ".$msg." data.", "", 400);
        // }
    }

    public function update_medc_schd(Request $req)
    {
        $dt = User::find($req->id);
        $dt->morning = date("H:i", strtotime($req->morning));
        $dt->afternoon = date("H:i", strtotime($req->afternoon));
        $dt->night = date("H:i", strtotime($req->night));

        if ($dt->save()) {
            return success_error(false, "Data successfully updated.", "", 200);
        }else{
            return success_error(true, "Unable to update data.", "", 400);
        }
    }

    public function profile_update(Request $req){
        $emailCq = User::where("email", $req->email)->whereNotNull('email')->where("id", "!=",$req->user_id)->exists();

        $mobileCq = User::where("mobile", $req->phone)->where("id", "!=",$req->user_id)->exists();
        $cq = User::where("username", $req->phone)->where("id", "!=", $req->user_id)->exists();



            $bmi = "";

            if($req->height_ft && $req->weight){
              $mtr = ($req->height_ft*12+$req->height_inch ?? 0) * 0.0254;
              $meter_sq = $mtr*$mtr;
              $bmi = $req->weight / $meter_sq;
              $bmi = (float) $bmi;
            }

            $dt = User::find($req->user_id);
            if($req->first_name || $req->last_name){
             $dt->name = ($req->first_name ?? '') .' '.($req->last_name ?? '');
            }
            if($req->first_name){
             $dt->firstname = $req->first_name;
            }
            if($req->last_name){
             $dt->lastname = $req->last_name;
            }
            if($req->email){

                if($emailCq){
                return success_error(true, $req->email." already exist in server.", "", 200);
                }
                $dt->email = $req->email;
            }
            if($req->phone){
            if($mobileCq){
              return success_error(true, $req->phone." already exist in server.", "", 200);
            }
            if ($cq) {
            return success_error(true, $req->phone." already exist in server.", "", 200);
            }
             $dt->mobile = $req->phone;
             //$dt->username = $req->phone;
            }
            if($req->address){
             $dt->address = $req->address;
            }
            if($req->date_of_birth){
             $dt->date_of_birth = $req->date_of_birth;
            }
            if($req->gender){
             $dt->gender = $req->gender;
            }
            if($req->height){
             $dt->height = $req->height;
            }
            if($req->height_unit){
             $dt->height_unit = $req->height_unit;
            }
            if($req->height_ft){
             $dt->height_ft = $req->height_ft;
            }
            if($req->height_inch){
             $dt->height_inch = $req->height_inch;
            }
             if($req->weight){
             $dt->weight = $req->weight;
            }

            $dt->weight_unit = "Kg";//$req->weight_unit ? $req->weight_unit : $dt->weight_unit;
            if($bmi){
             $dt->bmi = $bmi;
            }
            if($req->note){
             $dt->note = $req->note;
            }
            if($req->age){
                $dt->age = $req->age;
               }
            if($req->city){
             $dt->city = $req->city;
            }
            if($req->state){
             $dt->state = $req->state;
            }
            if($req->country){
             $dt->country = $req->country;
            }
            if($req->zip_code){
             $dt->zip_code = $req->zip_code;
            }

            if($req->emergency_contact_number){
             $dt->emergency_contact_number = $req->emergency_contact_number;
            }
            if($req->emergency_contact_relation){
             $dt->emergency_contact_relation = $req->emergency_contact_relation;
            }
           if($req->primary_care_giver_contact_info){
             $dt->primary_care_giver_contact_info = $req->primary_care_giver_contact_info;
            }
            if($req->emergency_contact_person_name){
             $dt->emergency_contact_person_name = $req->emergency_contact_person_name;
            }
            if($req->bmdc){
                $dt->bmdc = $req->bmdc;
            }
            if($req->degree){
                $dt->degree = $req->degree;
            }
            if($req->degree){
                $dt->degree = $req->degree;
            }
            if($req->zoom_id){
                $dt->zoom_id = $req->zoom_id;
            }
            if(isset($req->image)){
                 // Define the Base64 value you need to save as an image
                $b64 = $req->image;
                // Obtain the original content (usually binary data)
                $bin = base64_decode($b64);
                // Gather information about the image using the GD library
                $size = getImageSizeFromString($bin);
                // Check the MIME type to be sure that the binary data is an image
                if (empty($size['mime']) || strpos($size['mime'], 'image/') !== 0) {
                  return success_error(false, "Base64 value is not a valid image", "", 200);
                }
                // Mime types are represented as image/gif, image/png, image/jpeg, and so on
                // Therefore, to extract the image extension, we subtract everything after the “image/” prefix
                $ext = substr($size['mime'], 6);
                // Make sure that you save only the desired file extensions
                if (!in_array($ext, ['png', 'gif', 'jpeg'])) {
                  return success_error(false, "Unsupported image type", "", 200);
                }
                // Specify the location where you want to save the image
                $photo_name = time().".".$ext;
                $dt->photo = $photo_name;
                $img_file = public_path ("profile_pic/".$photo_name);
                file_put_contents($img_file, $bin);
            }

            if(isset($req->bmdc_certificate)){
                // Define the Base64 value you need to save as an image
               $b64 = $req->bmdc_certificate;
               // Obtain the original content (usually binary data)
               $bin = base64_decode($b64);
               // Gather information about the image using the GD library
               $size = getImageSizeFromString($bin);
               // Check the MIME type to be sure that the binary data is an image
               if (empty($size['mime']) || strpos($size['mime'], 'image/') !== 0) {
                 return success_error(false, "Base64 value is not a valid image", "", 200);
               }
               // Mime types are represented as image/gif, image/png, image/jpeg, and so on
               // Therefore, to extract the image extension, we subtract everything after the “image/” prefix
               $ext = substr($size['mime'], 6);
               // Make sure that you save only the desired file extensions
               if (!in_array($ext, ['png', 'gif', 'jpeg'])) {
                 return success_error(false, "Unsupported image type", "", 200);
               }
               // Specify the location where you want to save the image
               $photo_name = time().".".$ext;
               $dt->bmdc_certificate = $photo_name;
               $img_file = public_path ("BMDC_certificate/".$photo_name);
               file_put_contents($img_file, $bin);
           }

            if($req->password){
              $dt->password = Hash::make($req->password);
            }
            if ($dt->save()) {

                $user =  User::find($dt->id);
                $user_device=Device::where('user_id',$user->id)->where('status',1)->with('device_type')->get();
                $err["id"] = $user->id;
                $fname = $user->name != "" ? $user->name : '';
                $err["name"] = $fname != "" ? $fname : $user->firstname;
                $err["firstname"] = $user->firstname != "" ? $user->firstname : '';
                $err["lastname"] = $user->lastname != "" ? $user->lastname : '';
                $err["type"] = $user->type != "" ? $user->type : '';
                $err["user_category"] = $user->user_category;
                $err["user_sub_category"] = $user->user_sub_category;
                $err["email"] = $user->email != "" ? $user->email : '';
                $err["username"] = $user->username != "" ? $user->username : '';
                $err["mobile"] = $user->mobile != "" ? $user->mobile : '';
                $err["address"] = $user->address != "" ? $user->address : '';
                $err["date_of_birth"] = $user->date_of_birth != "" ? $user->date_of_birth : '';
                $err["gender"] = $user->gender != "" ? $user->gender : '';
                $err["height"] = $user->height != "" ? $user->height : '';
                $err["height_ft"] = $user->height_ft != "" ? $user->height_ft : '';
                $err["height_inch"] = $user->height_inch != "" ? $user->height_inch : '';
                // $err["height_unit"] = $user->height_unit != "" ? $user->height_unit : '';
                $err["weight"] = $user->weight != "" ? $user->weight : '';
                $err["weight_unit"] = $user->weight_unit != "" ? $user->weight_unit : '';
                $err["city"] = $user->city != "" ? $user->city : '';
                $err["state"] = $user->state != "" ? $user->state : '';
                $err["country"] = $user->country != "" ? $user->country : '';
                $err["zip_code"] = $user->zip_code != "" ? $user->zip_code : '';
                $url = 'http://'.$_SERVER['SERVER_NAME'].'/public/profile_pic/';
                $err["photo"] = $user->photo != null ? $url.$user->photo : '';
                if( $user->type=='doctor'){
                    $url = 'http://'.$_SERVER['SERVER_NAME'].'/public/BMDC_certificate/';
                    $err["bmdc_certificate"] = $user->bmdc_certificate != null ? $url.$user->bmdc_certificate : '';
                    $err["bmdc"] = $user->bmdc != null ? $user->bmdc : '';
                    $err["zoom_id"] = $user->zoom_id !=null ? $user->zoom_id : '';

                }

            $err["emergency_contact_number"] =  $user->emergency_contact_number != "" ? $user->emergency_contact_number : '';
              $err["is_external"] = $user->is_external ?? 0;

                return success_error(false, "Data successfully updated.", $err, 200);
            }else{
                return success_error(true, "Unable to update data.", "", 400);
            }

    }

    public function update_profile(Request $req, $id){
        if($req->username){
            $username = $req->username;
        }else{
            $username = $req->mobile;
        }

        if(!($req->email)){
            return success_error(true, 'Email must not be empty.', '', 200);
        }

        if(!($req->mobile)){
            return success_error(true, 'Mobile must not be empty.', '', 200);
        }
        $cq = User::where("username", $username)->where("id", "!=", $id)->exists();

        $emailCq = User::where("email", $req->email)->whereNotNull('email')->where("id", "<>", $id)->exists();
        $mobileCq = User::where("mobile", $req->mobile)->where("id", "<>", $id)->exists();

        if($emailCq){
            return success_error(true, $req->email.' exists in server.', '', 200);
        }
        elseif ($cq) {
            return success_error(true, $username.' exists in server.', '', 200);
        }
        elseif ($mobileCq) {
            return success_error(true, $req->mobile.' exists in server.', '', 200);
        } else {
            $dt = User::find($id);
            $dt->name = $req->fullname;
            $dt->email = $req->email;
            $dt->mobile = $req->mobile;
            $dt->username = $req->mobile;
            if($req->password){
              $dt->password = Hash::make($req->password);
            }
            $dt->type = $req->usertype;
            $dt->project = 'covid19';
            if ($dt->save()) {
                return success_error(false, "Data successfully updated.", "", 200);
            }else{
                return success_error(true, "Unable to update data.", "", 400);
            }
        }
    }

    public function all_orders(){
        $url = 'http://'.$_SERVER['SERVER_NAME'].'/public/uploads/covid-19/';
        $dt = Covid19::all();
        $arr = [];
        foreach($dt as $key => $val){
            $usr = User::where("mobile", $val->mobile)->first();
            $arr[] = array(
                "sl" => $key+1,
                "id" => $val->id,
                "what_u_need" => $val->what_u_need,
                "how_soon_do_u_need_it" => $val->how_soon_do_u_need_it,
                "what_u_supply" => $val->what_u_supply,
                "url" => $url.$val->product_pic,
                "product_pic" => $val->product_pic ? $val->product_pic : "",
                "what_u_supply_other" => $val->what_u_supply_other,
                "how_soon_can_u_supply" => $val->how_soon_can_u_supply,
                "hospital" => $val->hospital,
                "location" => $val->location,
                "name" => $usr->name,
                "mobile" => $val->mobile,
                "email" => $usr->email,
                "type" => $usr->type,
            );
        }
        return success_error(false, 'Record found', $arr, 200);
    }

    public function all_user(){
        $dt = User::where("project", "covid19")->get();
        $arr = [];
        foreach($dt as $key => $val){
            $arr[] = array(
                "sl" => $key+1,
                "id" => $val->id,
                "name" => $val->name,
                "mobile" => $val->mobile,
                "email" => $val->email,
                "type" => $val->type,
            );
        }
        return success_error(false, 'Record found', $arr, 200);
    }

    public function delete($id){
        $cq = Covid19::where("id", $id)->exists();
        if (!$cq) {
            return success_error(false, 'This record not found in server.', "", 404);
        }

        $delete = Covid19::find($id)->delete();
        if ($delete) {
            return success_error(false, 'Record successfully deleted.', "", 200);
        } else {
            return success_error(true, 'Unable to delete the record', "", 403);
        }
    }

    public function show_my_data($id){
        $cq = Covid19::where("mobile", $id)->exists();
        if (!$cq) {
            return success_error(false, 'This mobile number not found in server.', "", 404);
        } else {
            $data = Covid19::where("mobile", $id)->get();
            return success_error(false, 'Data found', $data, 200);
        }
    }

    public function clogin(Request $req){
        if ($req->mobile == "" || $req->password == "") {
            $nullmsg = "";
            if (!$req->mobile) {
                $nullmsg .= 'Mobile, ';
            }
            if (!$req->password) {
                $nullmsg .= 'Password, ';
            }
            $nullmsg = rtrim($nullmsg, ", \t\n");
            return success_error(true, $nullmsg." cannot left empty", '', 400);
        }

        if (Auth::attempt(['username' => request('mobile'), 'password' => request('password')])) {
            $user = Auth::user();
            //$data = Covid19::where("mobile", $req->mobile)->get();
            $err["id"] = $user->id != "" ? $user->id : '-';
            $err["name"] = $user->name != "" ? $user->name.' '.$user->lastname : '-';
            $err["usertype"] = $user->type != "" ? $user->type : '-';
            $err["email"] = $user->email != "" ? $user->email : '-';
            $err["mobile"] = $user->mobile != "" ? $user->mobile : '-';
            return success_error(false, 'Login Successfull!', $err, 200);
        } else {
            return success_error(true, "Invalid Credentials.", "", 401);
        }
    }

    public function update_what_u_need(Request $req, $id){

        $photo_name = "";
        if($req->product_pic){
            // Define the Base64 value you need to save as an image
            $b64 = $req->product_pic;
            // Obtain the original content (usually binary data)
            $bin = base64_decode($b64);
            // Gather information about the image using the GD library
            $size = getImageSizeFromString($bin);
            // Check the MIME type to be sure that the binary data is an image
            if (empty($size['mime']) || strpos($size['mime'], 'image/') !== 0) {
              return success_error(false, "Base64 value is not a valid image", "", 200);
            }
            // Mime types are represented as image/gif, image/png, image/jpeg, and so on
            // Therefore, to extract the image extension, we subtract everything after the “image/” prefix
            $ext = substr($size['mime'], 6);
            // Make sure that you save only the desired file extensions
            if (!in_array($ext, ['png', 'gif', 'jpeg', 'jpg'])) {
              return success_error(false, "Unsupported image type", "", 200);
            }
            // Specify the location where you want to save the image
            $photo_name = time().".".$ext;
            $img_file = public_path ("uploads/covid-19/".$photo_name);
            // Save binary data as raw data (that is, it will not remove metadata or invalid contents)
            // In this case, the PHP backdoor will be stored on the server
            file_put_contents($img_file, $bin);
        }

        $dt = Covid19::find($id);
        $dt->what_u_need = $req->what_u_need ? $req->what_u_need : $dt->what_u_need;
        $dt->how_soon_do_u_need_it = $req->how_soon_do_u_need_it ? $req->how_soon_do_u_need_it : $dt->how_soon_do_u_need_it;
        $dt->what_u_supply = $req->what_u_supply ? $req->what_u_supply : $dt->what_u_supply;
        $dt->product_pic = $photo_name ? $photo_name : $dt->product_pic;
        $dt->what_u_supply_other = $req->what_u_supply_other ? $req->what_u_supply_other : $dt->what_u_supply_other;
        $dt->how_soon_can_u_supply = $req->how_soon_can_u_supply ? $req->how_soon_can_u_supply : $dt->how_soon_can_u_supply;
        $dt->location = $req->location ? $req->location : $dt->location;
        $dt->hospital = $req->hospital ? $req->hospital : $dt->hospital;

        if ($dt->save()) {
            $data = Covid19::where("mobile", $req->mobile)->get();
            return success_error(false, "Data successfully saved.", $data, 200);
        }else{
            return success_error(true, "Unable to store data.", "", 400);
        }
    }

    public function what_u_need(Request $req){

        $photo_name = "";
        if($req->product_pic){
            // Define the Base64 value you need to save as an image
            $b64 = $req->product_pic;
            // Obtain the original content (usually binary data)
            $bin = base64_decode($b64);
            // Gather information about the image using the GD library
            $size = getImageSizeFromString($bin);
            // Check the MIME type to be sure that the binary data is an image
            if (empty($size['mime']) || strpos($size['mime'], 'image/') !== 0) {
              return success_error(false, "Base64 value is not a valid image", "", 200);
            }
            // Mime types are represented as image/gif, image/png, image/jpeg, and so on
            // Therefore, to extract the image extension, we subtract everything after the “image/” prefix
            $ext = substr($size['mime'], 6);
            // Make sure that you save only the desired file extensions
            if (!in_array($ext, ['png', 'gif', 'jpeg', 'jpg'])) {
              return success_error(false, "Unsupported image type", "", 200);
            }
            // Specify the location where you want to save the image
            $photo_name = time().".".$ext;
            $img_file = public_path ("uploads/covid-19/".$photo_name);
            // Save binary data as raw data (that is, it will not remove metadata or invalid contents)
            // In this case, the PHP backdoor will be stored on the server
            file_put_contents($img_file, $bin);
        }

        $pv_data = new Covid19([
            'mobile' => $req->mobile,
            'what_u_need' => $req->what_u_need,
            'how_soon_do_u_need_it' => $req->how_soon_do_u_need_it,
            'what_u_supply' => $req->what_u_supply,
            'product_pic' => $photo_name,
            'what_u_supply_other' => $req->what_u_supply_other,
            'how_soon_can_u_supply' => $req->how_soon_can_u_supply,
            'location' => $req->location,
            'hospital' => $req->hospital,
        ]);
        if ($pv_data->save()) {
            $UserCq = User::where("username", $req->mobile)->exists();
            if ($UserCq) {

            } else {
                $is_unique = false;
                $token = false;
                while(!$is_unique){
                    $token = rand(100000,999999);
                    $qry = User::where("token",$token)->first();
                    if(empty($qry)){
                        $is_unique = true;
                    }
                }
                $pv_data2 = new User([
                    'name' => $req->fullname,
                    'email' => $req->email,
                    'mobile' => $req->mobile,
                    'username' => $req->mobile,
                    'password' => Hash::make($req->password),
                    'type' => $req->usertype,
                    'project' => 'covid19',
                    'token' => $token
                ]);
                $pv_data2->save();
            }
            $data = Covid19::where("mobile", $req->mobile)->get();
            return success_error(false, "Data successfully saved.", $data, 200);
        }else{
            return success_error(true, "Unable to store data.", "", 400);
        }
    }

    public function show_hospitals(){
        $data = Covid19::whereNotNull("hospital")->get();
        $arr = [];
        foreach($data as $data){
            $arr[] = [
                "id" => $data->id,
                "name" => $data->hospital,
                "address" => $data->location
            ];
        }
        return success_error(false, "Data return successful", $arr, 200);
    }

    public function register(Request $req){
        if($req->is_external && ($req->firstname == "" ||
            $req->mobile == "" ||
            $req->password == "" ||
            $req->type == "")){
             $nullmsg = "";
            if (!$req->firstname) {
                $nullmsg .= 'Firstname, ';
            }
            if (!$req->mobile) {
                $nullmsg .= 'Mobile, ';
            }
            if (!$req->password) {
                $nullmsg .= 'Password, ';
            }
            if (!$req->type) {
                $nullmsg .= 'Usertype, ';
            }


        }
        elseif (!$req->is_external && (
            $req->firstname == "" ||
            $req->mobile == "" ||
            $req->password == "" ||
            $req->type == "" ||
            $req->emergency_contact_number == "" ||
            $req->emergency_contact_relation == "" ||
            $req->emergency_contact_person_name == "")
        ) {
            $nullmsg = "";
            if (!$req->firstname) {
                $nullmsg .= 'Firstname, ';
            }
            if (!$req->mobile) {
                $nullmsg .= 'Mobile, ';
            }
            if (!$req->password) {
                $nullmsg .= 'Password, ';
            }
            if (!$req->type) {
                $nullmsg .= 'Usertype, ';
            }

            if (!$req->emergency_contact_number) {
                $nullmsg .= 'Emergency Contact Number, ';
            }
            if (!$req->emergency_contact_relation) {
                $nullmsg .= 'Emergency Contact Relation, ';
            }
             if (!$req->emergency_contact_person_name) {
                $nullmsg .= 'Emergency Contact Person Name, ';
            }
            $nullmsg = rtrim($nullmsg, ", \t\n");
            return success_error(true, $nullmsg." cannot left empty", 400);
        }

        $enmpty_data = (object) null;

        //if(is_numeric($req->mobile)){
        if(!ctype_digit($req->mobile)){
            return success_error(true, 'Mobile number must be numeric.', $enmpty_data, 400);
        }
         if(!($req->email)){
            return success_error(true, 'Email must not be empty.', $enmpty_data, 400);
        }

        $UserCq = User::where("username", $req->mobile)->exists();
        $emailCq = User::where("email", $req->email)->whereNotNull('email')->exists();
        $mobileCq = User::where("mobile", $req->mobile)->exists();

        if($emailCq){

            return success_error(true, $req->email.' exists in server.', $enmpty_data, 400);
        }
        elseif ($UserCq) {
            return success_error(true, $req->mobile.' exists in server.', $enmpty_data, 400);
        }
        elseif ($mobileCq) {
            return success_error(true, $req->mobile.' exists in server.', $enmpty_data, 400);
        }

        else {

            $is_unique = false;
            $patient_id_is_unique = false;
            $token = false;
            $patient_id = false;
            while(!$is_unique){
                $token = rand(100000,999999);
                $patient_id = rand(100000,999999);
                $qry = User::where("token",$token)->first();
                $patient_idqry = User::where("patient_id",$patient_id)->first();
                if(empty($qry)){
                    $is_unique = true;
                }
                if(empty($patient_idqry)){
                    $patient_id_is_unique = true;
                }
            }

            $pv_data = new User;
            $pv_data->name = $req->firstname." ".$req->lastname;
            $pv_data->firstname = $req->firstname;
            $pv_data->firstname = $req->lastname;
            $pv_data->email = $req->email;
            $pv_data->username = $req->mobile;
            $pv_data->mobile = $req->mobile;
            $pv_data->password = Hash::make($req->password);
            $pv_data->type = $req->type;
            $pv_data->is_permitted = 1; //0 or 1;
            $pv_data->token = $token;
            $pv_data->emergency_contact_number = $req->emergency_contact_number;
            $pv_data->emergency_contact_relation = $req->emergency_contact_relation;
            $pv_data->emergency_contact_person_name = $req->emergency_contact_person_name;
            $pv_data->patient_id = $patient_id;
            if($req->is_external){
              $pv_data->is_external = $req->is_external;
            }

            if($req->user_category == 1){
                $pv_data->user_sub_category = $req->user_sub_category;
                $pv_data->user_category = $req->user_category;
            }


            if ($pv_data->save()) {

                $user = $pv_data;
                DB::table('user_roles')->insert(['user_id'=>$user->id,'role_id'=>11]); //11 is app_user Role;
                $err["id"] = $user->id != "" ? $user->id : '-';
                $err["firstname"] = $user->name != "" ? $user->name : '-';
                $err["lastname"] = $user->lastname != "" ? $user->lastname : '-';
                $err["type"] = $user->type != "" ? $user->type : '-';
                $err["username"] = $user->username != "" ? $user->username : '-';
                $err["mobile"] = $user->mobile != "" ? $user->mobile : '-';
                $err["token"] = $user->token != "" ? $user->token : '-';
                //Auth::login($user);
                if (env('APP_IS_LIVE')==true) {
                    if($req->email != null){
                        $name = $user->firstname." ".$user->lastname;
                        $email = $req->email;
                        $info = "Username - ".$req->mobile.", Password: ".$req->password;
                        $data = array('name' => $name, 'code' => '', 'info' => $info, 'type' => $user->type);
                        sendmail_sendgrid('email_content.general-registration',$data,$email,'KambaiiHealth - Registration');

                    }

                    $mobile = substr($req->mobile, -10);
                    $mobile = "880".$mobile;
                    $mbl_txt = "Your Kambaii Health login information is: LoginID -  ".$req->username.", Pswd: ".$req->password;//"Verify your mobile at Kambaii Health using - ".$randM;
                    $rslt =send_sms($mobile, $mbl_txt);
                    $err["sms"] = $rslt;

                 }
            }
            return success_error(false, 'Registration Successful!', $err, 200);
        }
    }

    public function hospital_patients_register(Request $req){

        $validator =Validator::make($req->all(),[
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'mobile' => 'required|numeric|min:11|unique:users',
            'gender' => 'required|string',
            'address' => 'required|string',
            'date_of_birth' => 'required',
            'emergency_contact_name' => 'required|string',
            'emergency_contact_number' => 'required|numeric',
            'emergency_contact_relation' => 'required|string',
        ]);

    $UserCq = User::where("username", $req->mobile)->exists();
    $emailCq = User::where("email", $req->email)->whereNotNull('email')->exists();
    $mobileCq = User::where("mobile", $req->mobile)->exists();
    $errorString = implode(",",$validator->messages()->all());
    if ($validator->fails()) {
        return success_error(true,$errorString, (object)[], 400);
    }elseif($emailCq){

        return success_error(true, $req->email.' exists in server.', (object)[], 400);
    }
    elseif ($UserCq) {
        return success_error(true, $req->mobile.' exists in server.', (object)[], 400);
    }
    elseif ($mobileCq) {
        return success_error(true, $req->mobile.' exists in server.', (object)[], 400);
    }

    else {

        $is_unique = false;
        $patient_id_is_unique = false;
        $token = false;
        $patient_id = false;
        while(!$is_unique){
            $token = rand(100000,999999);
            $patient_id = rand(100000,999999);
            $qry = User::where("token",$token)->first();
            $patient_idqry = User::where("patient_id",$patient_id)->first();
            if(empty($qry)){
                $is_unique = true;
            }
            if(empty($patient_idqry)){
                $patient_id_is_unique = true;
            }
        }
        $password = rand(100000,999999);

        $pv_data = new User;
        $pv_data->name = $req->firstname." ".$req->lastname;
        $pv_data->firstname = $req->firstname;
        $pv_data->lastname = $req->lastname;
        $pv_data->email = $req->email?? "-";
        $pv_data->username = empty($req->mobile)?$patient_id:$req->mobile;
        $pv_data->mobile = $req->mobile??"-";
        $pv_data->password = Hash::make($password);
        $pv_data->type = "app_user";
        $pv_data->is_permitted = 1; //0 or 1;
        $pv_data->is_external = 0; //0 or 1;
        $pv_data->token = $token;
        $pv_data->address = $req->address;
        $pv_data->gender = $req->gender;

        $dateOfBirth = date("Y-m-d",strtotime($req->date_of_birth));
        $today = date("Y-m-d");
        $diff = date_diff(date_create($dateOfBirth), date_create($today));

        $pv_data->date_of_birth=$dateOfBirth;
        $pv_data->age =$diff->format('%y');
        $pv_data->emergency_contact_number = $req->emergency_contact_number;
        $pv_data->emergency_contact_relation = $req->emergency_contact_relation;
        $pv_data->emergency_contact_person_name = $req->emergency_contact_name;
        $pv_data->patient_id = $patient_id;

        $pv_data->user_sub_category = auth('api')->user()->user_sub_category;
        $pv_data->user_category = auth('api')->user()->user_category;

            if(isset($req->image)){
            // Define the Base64 value you need to save as an image
            $b64 = $req->image;
            // Obtain the original content (usually binary data)
            $bin = base64_decode($b64);

            $photo_name = time().".png";

            $img_file = public_path ("profile_pic/".$photo_name);
            // Save binary data as raw data (that is, it will not remove metadata or invalid contents)
            // In this case, the PHP backdoor will be stored on the server
            file_put_contents($img_file, $bin);
            $pv_data->photo=$photo_name;
            }

        if ($pv_data->save()) {

            $user = $pv_data;
            DB::table('user_roles')->insert(['user_id'=>$user->id,'role_id'=>11]); //11 is app_user Role;
            $err["id"] = $user->id != "" ? $user->id : '-';
            $err["name"] = $user->name != "" ? $user->name : '-';
            $err["username"] = $user->username != "" ? $user->username : '-';
            $err["mobile"] = $user->mobile != "" ? $user->mobile : '-';
            //Auth::login($user);
                if (env('APP_IS_LIVE')==true) {
                    if($req->email != null && isset($req->email)){
                        $name = $user->firstname." ".$user->lastname;
                        $email = $req->email;
                        $info = "Username - ".$user->username.", Password: ".$password;
                        $data = array('name' => $name, 'code' => '', 'info' => $info, 'type' => $user->type);
                        sendmail_sendgrid('email_content.general-registration',$data,$email,'KambaiiHealth - Registration');

                    }

                    $mobile = substr($req->mobile, -10);
                    $mobile = "880".$mobile;
                    $mbl_txt = "Your Kambaii Health login information is: LoginID -  ".$user->username.", Pswd: ".$password;//"Verify your mobile at Kambaii Health using - ".$randM;
                    $rslt =send_sms($mobile, $mbl_txt);
                }
            return success_error(false, 'Registration Successful!', $err, 200);
        }else{
            return success_error(false, 'Registration Successful!',(object)[], 400);
        }

     }
    }
    public function forgotpassword(Request $req){
        if ($req->mobile == "" || $req->password == "") {
            $nullmsg = "";
            if (!$req->mobile) {
                $nullmsg .= 'Mobile, ';
            }
            if (!$req->password) {
                $nullmsg .= 'Password, ';
            }
            $nullmsg = rtrim($nullmsg, ", \t\n");
            return success_error(true, $nullmsg." cannot left empty", "", 400);
        }

        if(!ctype_digit($req->mobile)){
            return success_error(true, 'Mobile number must be numeric.', '', 400);
        }

        $UserCq = User::where("username", $req->mobile)->exists();

        if (!$UserCq) {
            return success_error(true, $req->mobile.' not exists in server.', '', 400);
        } else {
            //need to send code to mobile
            User::where("username", $req->mobile)->update([
                'password' => Hash::make($req->password)
            ]);
            return success_error(false, 'Password successfully updated.', '', 200);
        }
    }

    public function login(Request $req){

        if ($req->password == "") {
            $nullmsg = "";
            if (!$req->password) {
                $nullmsg .= 'Password, ';
            }
            $nullmsg = rtrim($nullmsg, ", \t\n");
            return success_error(true, $nullmsg." cannot left empty", (object) [], 400);
        }
        // $credentials = $req->only('mobile', 'password');
        //  if ($token = $this->guard()->attempt($credentials)) {

        //  }


        if($req->is_external){
          $token = $this->guard()->attempt(['username'=>($req->username ?? $req->mobile),'password'=>$req->password,'is_external'=>1,'is_permitted' => 1]);
        }else if(!is_numeric($req->username)){
          $token = $this->guard()->attempt(['username'=>($req->username ?? $req->mobile),'password'=>$req->password,'is_permitted' => 1]);
        }else{
          $token = $this->guard()->attempt(['mobile'=>($req->username ?? $req->mobile),'password'=>$req->password,'is_permitted' => 1]);
        }

        if ($token) {
            //Auth::login(['username' => request('mobile'), 'password' => request('password')]);
            $user =  auth('api')->user();
            $user_device=Device::where('user_id',$user->id)->where('status',1)->with('device_type')->get();
            $err["id"] = $user->id;
            $fname = $user->name != "" ? $user->name : '';
            $err["name"] = $fname != "" ? $fname : $user->firstname;
            $err["firstname"] = $user->firstname != "" ? $user->firstname : '';
            $err["lastname"] = $user->lastname != "" ? $user->lastname : '';
            $err["type"] = $user->type != "" ? $user->type : '';
            $err["user_category"] = $user->user_category;
            $err["user_sub_category"] = $user->user_sub_category;
            $err["email"] = $user->email != "" ? $user->email : '';
            $err["username"] = $user->username != "" ? $user->username : '';
            $err["mobile"] = $user->mobile != "" ? $user->mobile : '';
            $err["address"] = $user->address != "" ? $user->address : '';
            $err["date_of_birth"] = $user->date_of_birth != "" ? $user->date_of_birth : '';
            $err["gender"] = $user->gender != "" ? $user->gender : '';
            $err["height"] = $user->height != "" ? $user->height : '';
            $err["height_ft"] = $user->height_ft != "" ? $user->height_ft : '';
            $err["height_inch"] = $user->height_inch != "" ? $user->height_inch : '';
            // $err["height_unit"] = $user->height_unit != "" ? $user->height_unit : '';
            $err["weight"] = $user->weight != "" ? $user->weight : '';
            $err["weight_unit"] = $user->weight_unit != "" ? $user->weight_unit : '';
            //$err["note"] = $user->note != "" ? $user->note : '';
            $err["city"] = $user->city != "" ? $user->city : '';
            $err["state"] = $user->state != "" ? $user->state : '';
            $err["country"] = $user->country != "" ? $user->country : '';
            $err["zip_code"] = $user->zip_code != "" ? $user->zip_code : '';

            //$err["photo"] = $user->photo != "" ? $user->photo : '-';
            $url = 'http://'.$_SERVER['SERVER_NAME'].'/public/profile_pic/';
            $err["photo"] = $user->photo != null ? $url.$user->photo : '';
            if( $user->type=='doctor'){
                $url = 'http://'.$_SERVER['SERVER_NAME'].'/public/BMDC_certificate/';
                $err["bmdc_certificate"] = $user->bmdc_certificate != null ? $url.$user->bmdc_certificate : '';
                $err["bmdc"] = $user->bmdc != null ? $user->bmdc : '';
                $err["zoom_id"] = $user->zoom_id !=null ? $user->zoom_id : '';

            }
            $spdata=User::select("name")->where("id",$user->user_sub_category)->first();
            $err["hospital_name"]= isset($spdata->name)?$spdata->name:"Kambaii EMR System";

            $err["emergency_contact_number"] =  $user->emergency_contact_number != "" ? $user->emergency_contact_number : '';
            //$err["token"] = $user->token != "" ? $user->token : '';
            //$err["primary_care_giver_contact_info"] = $user->primary_care_giver_contact_info != "" ? $user->primary_care_giver_contact_info : '';
            //$err["emergency_contact_relation"] = $user->emergency_contact_relation != "" ? $user->emergency_contact_relation : '';
            $err["is_external"] = $user->is_external ?? 0;

            // $err["morning"] = $user->morning != "" ? $user->morning : '';
            // $err["afternoon"] = $user->afternoon != "" ? $user->afternoon : '';
            // $err["night"] = $user->night != "" ? $user->night : '';
            // $err["emergency_contact_number"] = $user->emergency_contact_number != "" ? $user->emergency_contact_number : '';
             $err["fcm_token"] = $user->fcm_token != "" ? $user->fcm_token : '';
            // $err["point"] = $user->point != "" ? $user->point : '';
            $err["emergency_contact_person_name"] = $user->emergency_contact_person_name != "" ? $user->emergency_contact_person_name : '';
            // $err["authorized_by"] = $user->authorized_by != "" ? $user->authorized_by : '';
            // $err["height_ft"] = $user->height_ft != "" ? $user->height_ft : '';
            // $err["height_inch"] = $user->height_inch != "" ? $user->height_inch : '0';
            // $err["plan_id"] = $user->current_plan_id != "" ? $user->current_plan_id : '';
            // $err["general_info"] = GeneralSetting::first();
            // $err["device_info"] = $user_device;
            // $err["plan_name"] = "";
            // $err["plan_keys"] = [];
            // if($user->current_plan_id > 0){
            //     $err["plan_id"] = $user->current_plan_id != "" ? $user->current_plan_id : '';
            //     $plan_info = Subscription::find($user->current_plan_id);
            //     $err["plan_name"] = $plan_info->plan_name;
            //     $plan_keys = PlanSection::getPlanDetails($user->current_plan_id,true);
            // } else {
            //     $plan_keys = PlanSection::getPlanDetails(false,true);

            // }
            // //return $plan_keys;
            // foreach ($plan_keys as $key => $keyVal) {

            //     foreach ($keyVal->PlanService as $dts) {
            //         $key=$key+1;
            //         $status ='No';
            //         foreach ($dts->PackageDetail as $dtls) {
            //             if ($dtls->section_id == $dts->plan_section_id && $dtls->service_id == $dts->id) {
            //                 if($dtls->status == 1){
            //                     $status ='Yes';
            //                 }

            //             }

            //         }
            //         $err["plan_keys"][] = [
            //         "sl" => $key,
            //         "id" => $dts->id,
            //         "key_for_app" => $dts->access_code,
            //         "status" => $status,
            //         "unit_cost" => $dts->unit_cost > 0 ? $dts->unit_cost : "",
            //         "heading" => $dts->heading
            //         ];

            //         # code...
            //     }

            // }


            $authenticate_token=$this->respondWithToken($token);
            $err["jwt_token"]=$authenticate_token;

            return success_error(false, 'Login Successfull!', $err, 200);
        } else {
            return success_error(true, "Invalid Credentials.", (object) [], 401);
        }
    }

      /**
     * Get the authenticated User
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {       $user=$this->guard()->user();
            $user_device=Device::where('user_id',$user->id)->where('status',1)->with('device_type')->get();
             $err["id"] = $user->id;
            $fname = $user->name != "" ? $user->name : '';
            $err["name"] = $fname != "" ? $fname : $user->firstname;
            $err["firstname"] = $user->firstname != "" ? $user->firstname : '';
            $err["lastname"] = $user->lastname != "" ? $user->lastname : '';
            $err["type"] = $user->type != "" ? $user->type : '';
            $err["email"] = $user->email != "" ? $user->email : '';
            $err["username"] = $user->username != "" ? $user->username : '';
            $err["mobile"] = $user->mobile != "" ? $user->mobile : '';
            $err["address"] = $user->address != "" ? $user->address : '';
            $err["date_of_birth"] = $user->date_of_birth != "" ? $user->date_of_birth : '';
            $err["gender"] = $user->gender != "" ? $user->gender : '';
            $err["height"] = $user->height != "" ? $user->height : '';
            $err["height_ft"] = $user->height_ft != "" ? $user->height_ft : '';
            $err["height_inch"] = $user->height_inch != "" ? $user->height_inch : '';
            $err["is_external"] = $user->is_external ?? 0;
            $err["height_unit"] = $user->height_unit != "" ? $user->height_unit : '';
            $err["weight"] = $user->weight != "" ? $user->weight : '';
            $err["weight_unit"] = $user->weight_unit != "" ? $user->weight_unit : '';
            $err["note"] = $user->note != "" ? $user->note : '';
            $err["city"] = $user->city != "" ? $user->city : '';
            $err["state"] = $user->state != "" ? $user->state : '';
            $err["country"] = $user->country != "" ? $user->country : '';
            $err["zip_code"] = $user->zip_code != "" ? $user->zip_code : '';
            //$err["photo"] = $user->photo != "" ? $user->photo : '-';
            $url = 'http://'.$_SERVER['SERVER_NAME'].'/public/profile_pic/';
            $err["photo"] = $user->photo != "" ? $url.$user->photo : '';
            $err["token"] = $user->token != "" ? $user->token : '';
            $err["primary_care_giver_contact_info"] = $user->primary_care_giver_contact_info != "" ? $user->primary_care_giver_contact_info : '';
            $err["emergency_contact_relation"] = $user->emergency_contact_relation != "" ? $user->emergency_contact_relation : '';
            $err["morning"] = $user->morning != "" ? $user->morning : '';
            $err["afternoon"] = $user->afternoon != "" ? $user->afternoon : '';
            $err["night"] = $user->night != "" ? $user->night : '';
            $err["emergency_contact_number"] = $user->emergency_contact_number != "" ? $user->emergency_contact_number : '';
            $err["fcm_token"] = $user->fcm_token != "" ? $user->fcm_token : '';
            $err["point"] = $user->point != "" ? $user->point : '';
            $err["emergency_contact_person_name"] = $user->emergency_contact_person_name != "" ? $user->emergency_contact_person_name : '';
            $err["authorized_by"] = $user->authorized_by != "" ? $user->authorized_by : '';
            $err["height_ft"] = $user->height_ft != "" ? $user->height_ft : '';
            $err["height_inch"] = $user->height_inch != "" ? $user->height_inch : '0';
            $err["plan_id"] = $user->current_plan_id != "" ? $user->current_plan_id : '';
            $err["general_info"] = GeneralSetting::first();
            $err["device_info"] = $user_device;
            $err["plan_name"] = "";
            $err["plan_keys"] = [];
            if($user->current_plan_id > 0){
                $err["plan_id"] = $user->current_plan_id != "" ? $user->current_plan_id : '';
                $plan_info = Subscription::find($user->current_plan_id);
                $err["plan_name"] = $plan_info->plan_name;
                $plan_keys = PlanSection::getPlanDetails($user->current_plan_id,true);
            } else {
                $plan_keys = PlanSection::getPlanDetails(false,true);

            }
            //return $plan_keys;
            foreach ($plan_keys as $key => $keyVal) {

                foreach ($keyVal->PlanService as $dts) {
                    $key=$key+1;
                    $status ='No';
                    foreach ($dts->PackageDetail as $dtls) {
                        if ($dtls->section_id == $dts->plan_section_id && $dtls->service_id == $dts->id) {
                            if($dtls->status){
                              $status = 'Yes';
                            }

                        }

                    }
                    $err["plan_keys"][] = [
                    "sl" => $key,
                    "id" => $dts->id,
                    "key_for_app" => $dts->access_code,
                    "status" => $status,
                    "unit_cost" => $dts->unit_cost > 0 ? $dts->unit_cost : "",
                    "heading" => $dts->heading
                    ];

                    # code...
                }

            }
        return success_error(false, 'Login Successfull!', $err, 200);
    }

    /**
     * Log the user out (Invalidate the token)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
         User::where('id',auth('api')->user()->id)->update(['fcm_token'=>null]);

        $this->guard()->logout();

        return success_error(false, 'Logout Successfull!', [], 200);
        //return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken($this->guard()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60
        ]);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard()
    {
        return Auth::guard('api');
    }

    public function update_medicine(Request $req, $id){
        if (
            $req->user_id == "" ||
            $req->type == "" ||
            $req->medicine_name == "" ||
            $req->measurement == ""
        ) {
            $nullmsg = "";
            if (!$req->user_id) {
                $nullmsg .= 'User ID, ';
            }
            if (!$req->type) {
                $nullmsg .= 'Medicine Type, ';
            }
            if (!$req->medicine_name) {
                $nullmsg .= 'Medicine Name, ';
            }
            if (!$req->measurement) {
                $nullmsg .= 'Medicine Measurement, ';
            }

            $nullmsg = rtrim($nullmsg, ", \t\n");
            return success_error(true, $nullmsg." cannot left empty", "", 400);
        }

        // Define the Base64 value you need to save as an image
        $b64 = $req->photo;
        // Obtain the original content (usually binary data)
        $bin = base64_decode($b64);
        // Gather information about the image using the GD library
        $size = getImageSizeFromString($bin);
        // Check the MIME type to be sure that the binary data is an image
        if (empty($size['mime']) || strpos($size['mime'], 'image/') !== 0) {
          return success_error(false, "Base64 value is not a valid image", "", 200);
        }
        // Mime types are represented as image/gif, image/png, image/jpeg, and so on
        // Therefore, to extract the image extension, we subtract everything after the “image/” prefix
        $ext = substr($size['mime'], 6);
        // Make sure that you save only the desired file extensions
        if (!in_array($ext, ['png', 'gif', 'jpeg'])) {
          return success_error(false, "Unsupported image type", "", 200);
        }
        // Specify the location where you want to save the image
        $photo_name = time().".".$ext;
        $img_file = public_path ("uploads/".$photo_name);
        // Save binary data as raw data (that is, it will not remove metadata or invalid contents)
        // In this case, the PHP backdoor will be stored on the server
        file_put_contents($img_file, $bin);
        //name, type, measurement, photo, user_id, taking_period, qr_bar_code, before_after_meal,

        $dt = Medicine::find($id);
        $dt->photo = $photo_name;
        $dt->type = $req->type;
        $dt->medicine_name = $req->medicine_name;
        $dt->measurement = $req->measurement;
        $dt->measurement_unit = $req->measurement_unit;
        $dt->taking_period = '';
        $dt->morning = $req->morning;
        $dt->afternoon = $req->afternoon;
        $dt->night = $req->night;
        $dt->user_id = $req->user_id;
        $dt->bar_qr_code = $req->qr_bar_code;
        $dt->how_many_days = $req->how_many_days;
        $dt->how_much = $req->how_much;
        $dt->borameal = $req->before_or_after_meal;
        $dt->how_many_medicine_have = $req->how_many_medicine_have;
        if ($dt->save()) {
            return success_error(false, "Medicine successfully updated!", "", 200);
        }else{
            return success_error(true, "Unable to update medicine information.", "", 400);
        }
    }

    public function health_care_patient_and_doctor_list(Request $request){

        $receptionist_id =auth('api')->user()->id ?? 0;

        $hospital_id=DB::table('users')->where('id',$receptionist_id)->first()->user_sub_category;

        if($hospital_id>0){
           // $date = \Carbon\Carbon::today()->subDays(7);
            $list=DB::table('users')->select("id","name","mobile","age","gender","type",
                                    DB::raw('DATE_FORMAT(users.created_at, "%d-%b-%Y") as created_at'))
                                    ->where('user_sub_category',$hospital_id);

                    if($request->type=='doctor'){
                        $list= $list->whereIn('type',['doctor','specialist','us_specialist']);
                    }else if($request->type=='patients'){
                        $list=$list->whereIn('type',['app_user','patient']);
                    }

                    if(isset($request->search_keyword)&& $request->search_keyword!=null && !empty($request->search_keyword)){
                        $list=$list->where('name', 'LIKE', "%{$request->search_keyword}%")
                        ->orWhere('mobile', 'LIKE', "%{$request->search_keyword}%")
                        ->orWhere('username', 'LIKE', "%{$request->search_keyword}%");
                    }

                    $data=$list->orderBy('created_at',"DESC")->limit(30)->get();
            return success_error(false,"Patients Data", $data, 200);
        }else{
            return success_error(true, "Patient Not Found !", (object)[], 400);
        }



    }
    public function medicine_entry(Request $req){
        //return $req->all();
        if (
            $req->user_id == "" ||
            $req->type == "" ||
            $req->medicine_name == "" ||
            $req->measurement == ""
        ) {
            $nullmsg = "";
            if (!$req->user_id) {
                $nullmsg .= 'User ID, ';
            }
            if (!$req->type) {
                $nullmsg .= 'Medicine Type, ';
            }
            if (!$req->medicine_name) {
                $nullmsg .= 'Medicine Name, ';
            }
            if (!$req->measurement) {
                $nullmsg .= 'Medicine Measurement, ';
            }

            $nullmsg = rtrim($nullmsg, ", \t\n");
            return success_error(true, $nullmsg." cannot left empty", "", 400);
        }

        // Define the Base64 value you need to save as an image

        if($req->has($req->photo)){
        $b64 = $req->photo;
        // Obtain the original content (usually binary data)
        $bin = base64_decode($b64);
        // Gather information about the image using the GD library
        $size = getImageSizeFromString($bin);
        // Check the MIME type to be sure that the binary data is an image
        if (empty($size['mime']) || strpos($size['mime'], 'image/') !== 0) {
          return success_error(false, "Base64 value is not a valid image", "", 200);
        }
        // Mime types are represented as image/gif, image/png, image/jpeg, and so on
        // Therefore, to extract the image extension, we subtract everything after the “image/” prefix
        $ext = substr($size['mime'], 6);
        // Make sure that you save only the desired file extensions
        if (!in_array($ext, ['png', 'gif', 'jpeg'])) {
          return success_error(false, "Unsupported image type", "", 200);
        }
        // Specify the location where you want to save the image
        $photo_name = time().".".$ext;
        $img_file = public_path ("uploads/".$photo_name);
        // Save binary data as raw data (that is, it will not remove metadata or invalid contents)
        // In this case, the PHP backdoor will be stored on the server
        //move_uploaded_file($bin, $img_file);
        file_put_contents($img_file, $bin);
        //name, type, measurement, photo, user_id, taking_period, qr_bar_code, before_after_meal,
        }
        $pv_data = new Medicine([
            'photo' => $photo_name??'',
            'type' => $req->type,
            'medicine_name' => $req->medicine_name,
            'measurement' => $req->measurement,
            'measurement_unit' => $req->measurement_unit,
            'taking_period' => '',
            'morning' => $req->morning,
            'afternoon' => $req->afternoon,
            'night' => $req->night,
            'user_id' => $req->user_id,
            'bar_qr_code' => $req->qr_bar_code??'',
            'medicine_status'=>$req->medicine_status,
            'how_many_days' => $req->how_many_days,
            'how_much' => $req->how_much?? 0,
            'borameal' => $req->before_or_after_meal??0,
            'how_many_medicine_have' => $req->how_many_medicine_have??0,
            'prescribed_by' => $req->prescribed_by??0,
            'visit_id' => $req->visit_id??0,
        ]);

        if ($pv_data->save()) {
            return success_error(false, "Medicine successfully added!", "", 200);
        }else{
            return success_error(true, "Unable to store medicine information.", "", 400);
        }
    }

    public function all_medicine(){
        $data = DB::table('medicines')->groupBy('medicine_name')->get();
        $arr = [];
        foreach($data as $val){
            $arr[] = array(
                "id" => $val->id,
                "medicine_name" => $val->medicine_name
            );
        }
        return success_error(false, "", $arr, 200);
    }

    public function schedule_for($id, $type){
        if($type == 'today'){
            $arr = PhysiciansScheduleSet::where("member_id", $id)
            ->whereDate("sdate", date("Y-m-d"))
            ->get();

            $pcp_dt = [];
            foreach ($arr as $val) {
                $pcp_info = User::find($val->physician_id);
                $member_info = User::find($val->member_id);
                $visit_already=PhysiciansScheduleSet::whereNotIn('id',[$val->id])->where('member_id',$val->member_id)->count();
                $pcp_dt[] = [
                    "id" => $val->id,
                    "remarks" => $val->remarks,
                    "pcp" => $pcp_info ? $pcp_info->name : "",
                    "member" => $member_info ? $member_info->name : "",
                    "patient_id" => $member_info ? $member_info->patient_id : "",
                    "sdate" => $val->sdate,
                    "stime" => $val->stime,
                    "pcp_type" => $pcp_info ? ucwords($pcp_info->type) : "",
                    "meeting_url" => $val->meeting_url,
                    "visit_already"=>$visit_already ?? 0,
                    "date_time"=>$val->date_time,
                    "end_date_time"=>$val->end_date_time,

                ];
            }

              if ($pcp_dt) {
                return success_error(false, "data found", $pcp_dt, 200);
            }else{
                return success_error(true, "data not found", "", 400);
            }
        }

        if($type == 'upcoming'){
            $arr = PhysiciansScheduleSet::where("member_id", $id)
            ->whereDate("sdate", ">", date("Y-m-d"))
            ->get();

            $pcp_dt = [];
            foreach ($arr as $val) {
                $pcp_info = User::find($val->physician_id);
                $member_info = User::find($val->member_id);
                $visit_already=PhysiciansScheduleSet::whereNotIn('id',[$val->id])->where('member_id',$val->member_id)->count();
                $pcp_dt[] = [
                    "id" => $val->id,
                    "remarks" => $val->remarks,
                    "pcp" => $pcp_info ? $pcp_info->name : "",
                    "member" => $member_info ? $member_info->name : "",
                    "patient_id" => $member_info ? $member_info->patient_id : "",
                    "sdate" => $val->sdate,
                    "stime" => $val->stime,
                    "pcp_type" => $pcp_info ? ucwords($pcp_info->type) : "",
                    "meeting_url" => $val->meeting_url,
                    "visit_already"=>$visit_already ?? 0,
                    "date_time"=>$val->date_time,
                    "end_date_time"=>$val->end_date_time,
                ];
            }

              if ($pcp_dt) {
                return success_error(false, "data found", $pcp_dt, 200);
            }else{
                return success_error(true, "data not found", "", 400);
            }
        }

        if($type == 'previous'){
            $arr = PhysiciansScheduleSet::where("member_id", $id)
            ->whereDate("sdate", "<", date("Y-m-d"))
            ->get();

            $pcp_dt = [];
            foreach ($arr as $val) {
                $pcp_info = User::find($val->physician_id);
                $member_info = User::find($val->member_id);
                $visit_already=PhysiciansScheduleSet::whereNotIn('id',[$val->id])->where('member_id',$val->member_id)->count();
                $pcp_dt[] = [
                    "id" => $val->id,
                    "remarks" => $val->remarks,
                    "pcp" => $pcp_info ? $pcp_info->name : "",
                    "member" => $member_info ? $member_info->name : "",
                    "patient_id" => $member_info ? $member_info->patient_id : "",
                    "sdate" => $val->sdate,
                    "stime" => $val->stime,
                    "pcp_type" => $pcp_info ? ucwords($pcp_info->type) : "",
                    "meeting_url" => $val->meeting_url,
                    "visit_already"=>$visit_already ?? 0,
                    "date_time"=>$val->date_time,
                    "end_date_time"=>$val->end_date_time,
                ];
            }

              if ($pcp_dt) {
                return success_error(false, "data found", $pcp_dt, 200);
            }else{
                return success_error(true, "data not found", "", 400);
            }
        }


    }

    public function my_health_target($id){
        $arr = HealthTarget::where("user_id", $id);
    	$ht_data = $arr->exists() ? json_decode($arr->first()->health_data) : '[]';
    	if($ht_data != '[]'){
    	    $data = [];
    	    foreach ($ht_data as $val) {
    	        $data[]=[
    	            "label" => $val->label,
        	        "current_val" => $val->current_val,
        	        "ideal_val" => $val->ideal_val,
        	        "week1" => $val->week1,
        	        "week2" => $val->week2
    	       ];

    	    }
    	    return success_error(false, "data is found", $data, 200);

    	}else{
    	    return success_error(true, "data not found", "", 400);
    	}
    }

    public function my_request_for_doctor($id){
        $arr = RequestDoctor::where('user_id', $id)->where('status', 'done')->get();
        if($arr){
            $data = [];
    	    foreach ($arr as $val) {
    	        $doc_info = User::find($val->doctor_id);
    	        $data[]=[
    	            "tds" => date('Y-m-d h:i A', strtotime($val->tds)),
        	        "status" => $val->status,
        	        "pIssues" => $val->pIssues,
        	        "iSolutions" => $val->iSolutions,
        	        "doctor_name" => $doc_info ? $doc_info->name : "",
    	       ];

    	    }
    	    return success_error(false, "data is found", $data, 200);
        }else{
             return success_error(true, "data not found", "", 400);
        }
    }

    public function period_wise_medicine_show($id, $period){
        $dt = Medicine::where("user_id", $id)->where('medicine_status', 'continue')->get();
        $before_meal = $after_meal = 0;
        $b4mealMed = [];
        $afterMealMed = [];
        $period = strtolower($period);
        foreach ($dt as $val) {
            if($val->$period == 'yes' && $val->borameal == 'before'){
                $before_meal++;
                $b4mealMed[] = [
                    'id' => $val->id,
                    'type' => $val->type,
                    'medicine_name' => $val->medicine_name,
                    'measurement' => $val->measurement,
                    'measurement_unit' => $val->measurement_unit,
                    'how_many_days' => $val->how_many_days,
                    'how_much' => $val->how_much,
                ];
            }
            if($val->$period == 'yes' && $val->borameal == 'after'){
                $after_meal++;
                $afterMealMed[] = [
                    'id' => $val->id,
                    'type' => $val->type,
                    'medicine_name' => $val->medicine_name,
                    'measurement' => $val->measurement,
                    'measurement_unit' => $val->measurement_unit,
                    'how_many_days' => $val->how_many_days,
                    'how_much' => $val->how_much,
                ];
            }
        }

        $data['total_med_b4meal'] = $before_meal;
        $data['total_med_after_meal'] = $after_meal;
        $data['b4mealMedicines'] = $b4mealMed;
        $data['afterMealMedicines'] = $afterMealMed;
        return success_error(false, "", $data, 200);
    }

    public function view_medicines(Request $req){
        $arr = [];
        $url = 'http://'.$_SERVER['SERVER_NAME'].'/public/uploads/';

        if(isset($req->status)){
            $data = Medicine::where("user_id", $req->user_id)->where("medicine_status", $req->status)->orderBy('created_at','DESC');
        }elseif(isset($req->visit_id)){
            $data = Medicine::where("user_id", $req->user_id)->where("visit_id", $req->visit_id)->orderBy('created_at','DESC');
        }else{
            $data = Medicine::where("user_id", $req->user_id)->orderBy('created_at','DESC');
        }


        $user_id=auth('api')->user()->id ?? 0;
        if($user_id==$req->user_id){
            $data =$data->get();
        }else{

            $data =$data->selectRaw("id,type,medicine_name,generic_name,measurement,measurement_unit,morning,afternoon,night,is_notified,photo,CONCAT('$url',photo) as photourl,bar_qr_code,how_many_days,how_much,borameal as before_or_after_meal,medicine_status,how_many_medicine_have,prescribed_by,created_at")->paginate(10)->appends($_GET);

            return success_error(false, "", $data, 200);
        }



        ;
        foreach($data as $val){
            $arr[] = array(
                "id" => $val->id,
                "type" => $val->type,
                "medicine_name" => $val->medicine_name,
                "generic_name" => $val->generic_name,
                "measurement" => $val->measurement,
                "measurement_unit" => $val->measurement_unit,
                //"taking_period" => json_decode($val->taking_period),
                "morning" => $val->morning,
                "afternoon" => $val->afternoon,
                "night" => $val->night,
                "is_notified" => $val->is_notified,
                "photo" => $val->photo,
                "photourl" => $url.$val->photo,
                "bar_qr_code" => $val->bar_qr_code,
                "how_many_days" => $val->how_many_days,
                "how_much" => $val->how_much,
                "before_or_after_meal" => $val->borameal,
                "medicine_status" => $val->medicine_status,
                "how_many_medicine_have" => $val->how_many_medicine_have
            );
        }
        return success_error(false, "", $arr, 200);
    }

    public function medicine_status(Request $req){

        $data = Medicine::where("user_id", $req->user_id)->where('id', $req->medicine_id)->first();

        if($data){
            $status = $data->medicine_status;

            if($status == 'continue'){
                $data->medicine_status = 'discontinue';
                $data->save();
            }else{
                $data->medicine_status = 'continue';
                $data->save();
            }

        }
        return success_error(false, "Medicine Status Updated Successfully", "", 200);

    }



    public function drug_interaction(Request $req){
        $user = User::find($req->user_id);
        if($user->is_paid == 0 && !isset($req->sp_check)){
            return response()->json(["error" => true, "msg" => "To view Interaction of your drug, you have to pay Tk 100 through bKash No#", "data" => ""], 200);
        }
        $data = DB::table("drug_interactions as di")
            ->join("medicines as m1","di.medicine_one_id","m1.id")
            ->join("medicines as m2","di.medicine_two_id","m2.id")
            ->join("users as u","di.posted_by","u.id")
            ->select("di.*","m1.medicine_name as med1","m1.generic_name as gen1","m2.medicine_name as med2","m2.generic_name as gen2","u.name as inp_by")
            ->where("di.user_id",$req->user_id)
            ->get();
        $interactions = [];
        foreach ($data as $val) {
            $interactions[] = array(
                "med1" => $val->med1,
                "gen1" => $val->gen1,
                "med2" => $val->med2,
                "gen2" => $val->gen2,
                "details" => $val->details
            );
        }
        return response()->json(["error" => false, "msg" => "", "data" => $interactions], 200);
    }

    public function drug_side_effect(Request $req){
        $data = DrugSideEffect::where("user_id", $req->user_id)->get();
        return response()->json(["error" => false, "data" => $data], 200);
    }

    public function drug_similaritie(Request $req){
        $data = DrugSimilaritie::where("user_id", $req->user_id)->get();
        return response()->json(["error" => false, "data" => $data], 200);
    }

    public function drug_contraindication(Request $req){
        $data = DrugContraindication::where("user_id", $req->user_id)->get();
        return response()->json(["error" => false, "data" => $data], 200);
    }

    public function device_status_update(Request $req)
    {
        $dt = User::find($req->id);
        $dt->device_status = $req->device_status;

        if ($dt->save()) {
            return success_error(false, "Data successfully updated.", "", 200);
        }else{
            return success_error(true, "Unable to update data.", "", 400);
        }
    }

    public function fcm_token_update(Request $req)
    {
        $dt = User::find($req->id);
        $dt->fcm_token = $req->fcm_token;

        if ($dt->save()) {
            return success_error(false, "Data successfully updated.", "", 200);
        }else{
            return success_error(true, "Unable to update data.", "", 400);
        }
    }

     public function options_list()
    {
        $data = CallDoctor::get();
        $arr = [];
        foreach ($data as $val) {
            $arr[] = array(
                "id" => $val->id,
                "options" => $val->options,
            );
        }

         return success_error(false, "", $arr, 200);
    }

    public function store_call_doctor(Request $req)
    {
        $dt = new RequestDoctor;
        $cq = RequestDoctor::where('user_id', $req->user_id)->where('status', 'waiting')->first();
        if(empty($cq)){
            $dt->user_id = $req->user_id;
            $dt->options_id = $req->options_id;
            if($req->issues){
                $dt->pIssues = $req->issues;
            }
            $dt->tds = date("Y-m-d H:i:s");
            if($dt->save()){
                //$data = UserTest::where("user_id", $req->user_id)->get();
                return success_error(false, "Data successfully saved", "", 200);
            }else{
                return success_error(true, "Unable to save data.", "", 400);
            }
        }else{
            return success_error(true, "You are already in wating", "", 200);
        }


    }

    public function progress_report_view_for_app($id)
    {
          //$arr = url('progress-report-app/'.$id);
        $dt =  ProgressReport::where('user_id', $id)->orderBy("id", "desc")->limit(10)->get();
        $data = [];
            foreach ($dt as $val) {
              $dinfo = $val->doctor_id > 0 ? User::find($val->doctor_id) : "";
              $data[] = [
                "id" => $val->id,
                "bmdc" => $dinfo ? $dinfo->bmdc : "",
                "dname" => $dinfo ? $dinfo->name : "",
                "overall_summary" => $val->overall_summary,
                "areas_of_success" => $val->areas_of_success,
                "focus_area" => $val->focus_area,
                "tds" => date("Y-m-d", strtotime($val->tds)),
              ];
            }

       return success_error(false, "progress report", $data, 200);
    }

     public function health_target_view_for_app($id)
    {
         $arr = url('health_target_view_for_app/'.$id);

       return success_error(false, "", $arr, 200);
    }
    public function entry_today_taken_nutrition($id)
    {
    //      $arr = url('nutritution_entry_for_app/'.$id);

    //  return success_error(false, "", $arr, 200);
         $tds = date("Y-m-d");
        //$m = 0;
        $data['list'] = CalorieItem::all();
        $data['breakfast'] = [];
        $data['MorningSnacks'] = [];
        $data['lunch']=[];
        $data['dinner']=[];
        $data['EveningSnacks']=[];
        $data['BedTimeSnacks']=[];
        $all_items_id = MasterNutrition::pluck('item_id')->toArray();
        $user_info = User::find($id);
        $user_category = $user_info->user_category;
        $user_sub_category = $user_info->user_sub_category;

        $breakfast = MasterNutrition::where('period', 'Breakfast')->where('user_category', 0)->with('calories_item')->with(['Nutration'=>function($q) use ($id,$tds){
            $q->where('period', 'Breakfast')->where('user_id',$id)->where("tds", $tds);

        }])->get();

         if($user_category == 1 && $user_sub_category > 0){
             $breakfast = MasterNutrition::where('period', 'Breakfast')->where('user_category', 1)->where('user_sub_category', $user_sub_category)->with('calories_item')->with(['Nutration'=>function($q) use ($id,$tds){
                $q->where('period', 'Breakfast')->where('user_id',$id)->where("tds", $tds);

            }])->get();
         }

        $breakfast2 =Nutrition::where('period', 'Breakfast')->where('user_id',$id)->with('calories_item')->where("tds", $tds)->get();
        $breakfast_arr = [];
        if(count($breakfast2) == 0){

            foreach($breakfast as $key=>$value){
              $breakfast_arr[$value->main_lavel][] = $value;

            }

        }else{
             foreach($breakfast2 as $key=>$value){
              $breakfast_arr[$value->main_lavel][] = $value;
            }
        }


        if($breakfast_arr){
             $data['breakfast']=$breakfast_arr;
        }else{
            $data['breakfast']=(object)null;
        }


         $Lunch = MasterNutrition::where('period', 'Lunch')->where('user_category', 0)->with('calories_item')->with(['Nutration'=>function($q) use ($id,$tds){
            $q->where('period', 'Lunch')->where('user_id',$id)->where("tds", $tds);

        }])->get();

        if($user_category == 1 && $user_sub_category > 0){
            $Lunch = MasterNutrition::where('period', 'Lunch')->where('user_category', 1)->where('user_sub_category', $user_sub_category)->with('calories_item')->with(['Nutration'=>function($q) use ($id,$tds){
                $q->where('period', 'Lunch')->where('user_id',$id)->where("tds", $tds);

            }])->get();
        }
        $Lunch2 =Nutrition::where('period', 'Lunch')->where('user_id',$id)->with('calories_item')->where("tds", $tds)->get();
       $lunch_arr = [];
        if(count($Lunch2) == 0){
            foreach($Lunch as $key=>$value){
              $lunch_arr[$value->main_lavel][] = $value;
            }
        }else{
             foreach($Lunch2 as $key=>$value){
              $lunch_arr[$value->main_lavel][] = $value;
            }
        }


       if($lunch_arr){
           $data['lunch'] = $lunch_arr;
       }else{
           $data['lunch'] = (object)null;
       }


        $dinner = MasterNutrition::where('period', 'Dinner')->where('user_category', 0)->with('calories_item')->with(['Nutration'=>function($q) use ($id,$tds){
            $q->where('period', 'Dinner')->where('user_id',$id)->where("tds", $tds);

        }])->get();

        if($user_category == 1 && $user_sub_category > 0){
            $dinner = MasterNutrition::where('period', 'Dinner')->where('user_category', 1)->where('user_sub_category', $user_sub_category)->with('calories_item')->with(['Nutration'=>function($q) use ($id,$tds){
                $q->where('period', 'Dinner')->where('user_id',$id)->where("tds", $tds);

            }])->get();
        }
        $dinner2 =Nutrition::where('period', 'Dinner')->where('user_id',$id)->with('calories_item')->where("tds", $tds)->get();
        $dinner_arr = [];
        if(count($dinner2) == 0){
           foreach($dinner as $key=>$value){
              $dinner_arr[$value->main_lavel][] = $value;
            }
        }else{
            foreach($dinner2 as $key=>$value){
              $dinner_arr[$value->main_lavel][] = $value;
            }
        }


        if($dinner_arr){
            $data['dinner'] = $dinner_arr;
        }else{
            $data['dinner'] = (object)null;
        }




         $MorningSnacks = MasterNutrition::where('period', 'MorningSnacks')->where('user_category', 0)->with('calories_item')->with(['Nutration'=>function($q) use ($id,$tds){
            $q->where('period', 'MorningSnacks')->where('user_id',$id)->where("tds", $tds);

        }])->get();
        if($user_category == 1 && $user_sub_category > 0){
            $MorningSnacks = MasterNutrition::where('period', 'MorningSnacks')->where('user_category', 1)->where('user_sub_category', $user_sub_category)->with('calories_item')->with(['Nutration'=>function($q) use ($id,$tds){
                $q->where('period', 'MorningSnacks')->where('user_id',$id)->where("tds", $tds);

            }])->get();
        }
        $MorningSnacks2 =Nutrition::where('period', 'MorningSnacks')->where('user_id',$id)->with('calories_item')->where("tds", $tds)->get();
        $morning_arr = [];
        if(count($MorningSnacks2) == 0){
            foreach($MorningSnacks as $key=>$value){
              $morning_arr[$value->main_lavel][] = $value;
            }
        }else{
           foreach($MorningSnacks2 as $key=>$value){
              $morning_arr[$value->main_lavel][] = $value;
            }
        }


        if($morning_arr){
            $data['MorningSnacks'] = $morning_arr;
        }else{
            $data['MorningSnacks'] = (object)null;
        }

        $EveningSnacks = MasterNutrition::where('period', 'EveningSnacks')->where('user_category', 0)->with('calories_item')->with(['Nutration'=>function($q) use ($id,$tds){
            $q->where('period', 'EveningSnacks')->where('user_id',$id)->where("tds", $tds);

        }])->get();
        if($user_category == 1 && $user_sub_category > 0){
            $EveningSnacks = MasterNutrition::where('period', 'EveningSnacks')->where('user_category', 1)->where('user_sub_category', $user_sub_category)->with('calories_item')->with(['Nutration'=>function($q) use ($id,$tds){
            $q->where('period', 'EveningSnacks')->where('user_id',$id)->where("tds", $tds);

        }])->get();
        }
        $EveningSnacks2 =Nutrition::where('period', 'EveningSnacks')->where('user_id',$id)->with('calories_item')->where("tds", $tds)->get();
        $evening_arr = [];
        if(count($EveningSnacks2) == 0){
             foreach($EveningSnacks as $key=>$value){
              $evening_arr[$value->main_lavel][] = $value;
            }
        }else{
            foreach($EveningSnacks2 as $key=>$value){
              $evening_arr[$value->main_lavel][] = $value;
            }
        }


        if($evening_arr){
            $data['EveningSnacks'] = $evening_arr;
        }else{
            $data['EveningSnacks'] = (object)null;
        }


        // bedtime
        $BedTimeSnacks = MasterNutrition::where('period', 'BedTimeSnacks')->where('user_category', 0)->with('calories_item')->with(['Nutration'=>function($q) use ($id,$tds){
            $q->where('period', 'BedTimeSnacks')->where('user_id',$id)->where("tds", $tds);

        }])->get();
        if($user_category == 1 && $user_sub_category > 0){
            $BedTimeSnacks = MasterNutrition::where('period', 'BedTimeSnacks')->where('user_category', 1)->where('user_sub_category', $user_sub_category)->with('calories_item')->with(['Nutration'=>function($q) use ($id,$tds){
                $q->where('period', 'BedTimeSnacks')->where('user_id',$id)->where("tds", $tds);

            }])->get();
        }
        $BedTimeSnacks2 =Nutrition::where('period', 'BedTimeSnacks')->where('user_id',$id)->with('calories_item')->where("tds", $tds)->get();
        $bed_time_arr = [];
        if(count($BedTimeSnacks2) == 0){
            foreach($BedTimeSnacks as $key=>$value){
              $bed_time_arr[$value->main_lavel][] = $value;
            }
        }else{
            foreach($BedTimeSnacks2 as $key=>$value){
              $bed_time_arr[$value->main_lavel][] = $value;
            }
        }



        if($bed_time_arr){
            $data['BedTimeSnacks'] = $bed_time_arr;
        }else{
            $data['BedTimeSnacks'] = (object)null;
        }




        //return dump($breakfast);
         $bfm = 1;
         $lm = 1;
         $dm = 1;
         $msm = 1;

         $esm = 1;
         $btsm = 1;
         $unit_type = FoodUnit::all();
         $size = FoodUnitSize::all();



        $nutCom = NutritionistComment::where("patient_id", $id);
        $data['nutCom'] = $nutCom->exists() ? $nutCom->first() : "";
        $data['bfm'] = $bfm;
        $data['id'] = $id;
        $data['dm'] = $dm;
        $data['lm'] = $lm;
        $data['msm'] = $msm;
        $data['esm'] = $esm;
        $data['btsm'] = $btsm;
        $data['unit_type'] = $unit_type;
        $data['size'] = $size;

       return success_error(false, "what i eat today data", $data, 200);
    }

    public function order_lab_by_doctor(Request $req)
    {
        $id = $req->user_id;
        $data = DB::table('order_lab_tests')
        ->join('users', 'users.id', '=', 'order_lab_tests.doctor_id')
        ->select('order_lab_tests.*', 'order_lab_tests.id as orid', 'users.firstname as pfname', 'users.lastname  as plname', 'users.name as doctor_name', 'users.id as patient_id')
        ->where('order_lab_tests.user_id', $id)
        ->orderBy('order_lab_tests.id', 'DESC')
        ->get();
        $arr = [];
        foreach($data as $val){
            $arr[] = array(
                "test_name" => $val->test_name,
                "tds" => date('Y-m-d H:i:s', strtotime($val->tds)) ,
                "reason" => $val->reason,
                "doctor_name" => $val->doctor_name,
                "id" => $val->orid,
            );
         }

        return success_error(false, "Order Lab Test", $arr, 200);
    }

    public function store_lab_test(Request $req){

        $orlab = new OrderLabTest;

         $orlab->user_id = $req->user_id;
         $orlab->doctor_id =$req->specelist_id;
         $orlab->test_name = $req->test_name;
         $orlab->reason = $req->reason;
         $orlab->visit_id = $req->visit_id;
         $orlab->tds = date('Y-m-d H:i:s');

          if($orlab->save()){
            return success_error(false, "Task add successful", "", 200);
          } else {
              return success_error(true, "Unable to save data.", "", 400);
          }

    }
    public function delete_order_lab($id)
    {
      $dt = OrderLabTest::find($id);

      if($dt->delete()){
        return success_error(false, "Lab Test Deleted Successful", "", 200);
      } else {
          return success_error(true, "Unable to delete data.", "", 400);
      }
    }

    public function create_todo(Request $req)
    {
        $dt = new TaskList;
        $dt->title = $req->title;
        $dt->description = $req->description;
        $dt->todo_time = $req->todo_time;
        $dt->status = $req->status;
        $dt->tds = date("Y-m-d H:i:s");;
        $dt->user_id = $req->user_id;

        if($dt->save()){
                return success_error(false, "Task add successful", "", 200);
            }else{
                return success_error(true, "Unable to save data.", "", 400);
            }

    }

    public function get_user_todo_list($id)
    {
        $data = TaskList::where('user_id', $id)->get();
        $arr = [];
        foreach($data as $val){
            $arr[] = array(
                "id" => $val->id,
                "tds" => date('Y-m-d H:i:s', strtotime($val->tds)) ,
                "title" => $val->title,
                "description" => $val->description,
                "todo_time" => $val->todo_time,
                "status" => $val->status,
                "user_id" => $val->user_id,
                "remind" => $val->remind,
            );
         }

        return success_error(false, "Task List", $arr, 200);
    }

    public function update_taks_schedule(Request $req)
    {
        $user_id = $req->user_id;
        $id = $req->task_id;
        $dt = TaskList::find($id);
        if ($req->status == 'Completed') {
            $dt->status = $req->status;
            $dt->remind = $req->remind;
        }else{
            $dt->status = $req->status;
            $dt->remind = $req->remind;
            $dt->todo_time = $req->todo_time;
        }

        if($dt->save()){
                return success_error(false, "Task Update successful", "", 200);
            }else{
                return success_error(true, "Unable to save data.", "", 400);
            }
    }

    public function update_medicine_take_time(Request $req)
    {
        $user_id = $req->user_id;
        $dt = User::find($user_id);
        $medicine_alarm_for = $req->medicine_alarm_for;

        if ($medicine_alarm_for == "medicine_at_morning_before_meal" || $medicine_alarm_for == "medicine_at_morning_after_meal") {
            $dt->morning_temp = $req->time;
        }
        if ($medicine_alarm_for == "medicine_at_afternoon_before_meal" || $medicine_alarm_for == "medicine_at_afternoon_after_meal") {
            $dt->afternoon_temp = $req->time;
        }
        if ($medicine_alarm_for == "medicine_at_night_before_meal" || $medicine_alarm_for == "medicine_at_night_after_meal") {
            $dt->night_temp = $req->time;
        }

        if($req->medicine_alarm_for == 'medicine_at_morning_after_meal'){
            $dt->morning_type_temp = 'after';
        }

        if($req->medicine_alarm_for == 'medicine_at_afternoon_after_meal'){
            $dt->afternoon_type_temp = 'after';
        }

        if($req->medicine_alarm_for == 'medicine_at_night_after_meal'){
            $dt->night_type_temp = 'after';
        }

        if($dt->save()){
                return success_error(false, "Time Update successful", "", 200);
            }else{
                return success_error(true, "Unable to save data.", "", 400);
            }
    }

    public function current_food_habits($id)
    {
      // $arr = url('current_food_habit_overview/'.$id);
        $pid = $id;
        $rfs = MemberFood::where("member_id", $pid);
        $rfs = $rfs->exists() ? $rfs->first() : "";
        if($rfs){
            return success_error(false, "Food habit data", $rfs, 200);
        }else{
            return success_error(false, "Data are not available", '', 200);
        }



       //return view('nutrition.current_food_habit_app', compact('rfs'));

       //return success_error(false, "", $arr, 200);
    }

    public function recommended_diet_chart($id)
    {
        $pid = $id;
        $rfs = MemberFood::where("member_id", $pid);
        $rfs = $rfs->exists() ? $rfs->first() : "";
        if($rfs){
            return success_error(false, "success", $rfs, 200);
        }else{
            return success_error(false, "Data are not available", '', 400);
        }

        // $arr = url('recommended_diet_chart/'.$id);

        // return success_error(false, "", $arr, 200);
    }

    public function healthy_food_habit_target($id)
    {
        // $arr = url('healthy_food_habit_target/'.$id);
        // return success_error(false, "", $arr, 200);

        $pid = $id;
        $rfs = MemberFood::where("member_id", $pid);
        $rfs = $rfs->exists() ? $rfs->first() : "";
        if($rfs){

        return response()->json([
                'error' => false,
                'msg' => 'success',
                'data' => $rfs,
                'code' => 200,
            ]);
        }else{
            return response()->json([
                'error' => false,
                'msg' => 'data not found',
                'data' => $rfs,
                'code' => 400,
            ]);
        }
    }

    public function avoidance_list($id)
    {
        $pid = $id;
        $rfs = MemberFood::where("member_id", $pid);
        $rfs = $rfs->exists() ? $rfs->first() : "";

        if($rfs){
            return success_error(false, "success", $rfs, 200);
        }else{
            return success_error(false, "Data are not available", '', 400);
        }

        // $arr = url('avoidance_list/'.$id);
        // return success_error(false, "", $arr, 200);
    }

    public function recommended_daily_intake_food_list($id)
    {
        $pid = $id;
        $rfs = MemberFood::where("member_id", $pid);
        $rfs = $rfs->exists() ? $rfs->first() : "";
        if($rfs){
            return success_error(false, "success", $rfs, 200);
        }else{
            return success_error(false, "Data are not available", '', 400);
        }
        // $arr = url('recommended_daily_intake_food_list/'.$id);
        // return success_error(false, "", $arr, 200);
    }

    public function nutritionist_advice($id)
    {
        $pid = $id;
        $nutComment = NutritionistComment::where("patient_id", $pid);
        $rfs = $nutComment->exists() ? $nutComment->first()->nutritionist_advice : "";
        if($rfs){
            return success_error(false, "success", $rfs, 200);
        }else{
            return success_error(false, "Data are not available", '', 400);
        }
        // $arr = url('nutritionist_advice/'.$id);
        // return success_error(false, "", $arr, 200);
    }

    public function userInformation()
    {
        $data = User::where('type', 'app_user')->get();
        return success_error(false, "Records", $data, 200);
    }

    public function success_error($status, $msg, $data, $code){
        return response()->json([
            'error' => $status,
            'msg' => $msg,
            'data' => $data
        ], $code);
    }

    public function emergencyList(Request $req){

    $emergency = FinalEmergency::getEmergencyPatient($req);
    return success_error(false, "", $emergency, 200);

    }

    public function LocketEmergencyList(Request $req){

    $emergency = LocetEmergency::getEmergency($req);
    return success_error(false, "", $emergency, 200);

    }



    public function StoreLocetEmergency(Request $req){
    $merge_request=array_merge($req->all(),['patient_id'=>$this->guard()->user()->id]);

    $emergency = LocetEmergency::InsertEmergency($merge_request);

    if($emergency){
            $ids=[];
            $uinfo = User::whereNotNull('fcm_token')->whereIn('type',['doctor','admin'])->pluck('fcm_token');
            foreach($uinfo as $key =>$value){
            array_push($ids, $value);
            }

            $total_patient =1;
            $emergency_for ='MRN : PAT'.$this->guard()->user()->patient_id.'fall in an  emergency !!';
            $id1 = json_encode($ids);

            $firebase_info=firebase_info();
            $app_url=$firebase_info['app_url'];
            $authorization=$firebase_info['authorization'];
            $postman_token=$firebase_info['postman_token'];

            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => "$app_url",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\"data\":{\"notify_description\":\"$total_patient Patient in an emergency\",\"notify_type\":\"emergency_locket\",\"notify_img\":\"https://cdn.pixabay.com/photo/2017/05/13/23/05/img-src-x-2310895_960_720.png\",\"notify_title\":\"Patient in an emergency\",\"user_data\":\"Patient in an emergency\",\"emergency_for\":\"$emergency_for\",\"number_of_patient\":\"$total_patient\"},\"registration_ids\":$id1}",

              CURLOPT_HTTPHEADER => array(
                "authorization: $authorization",
                "cache-control: no-cache",
                "content-type: application/json",
                "postman-token: $postman_token"
               ),
            ));

          $response = curl_exec($curl);
          $err = curl_error($curl);

          curl_close($curl);

          if ($err) {
            echo "cURL Error #:" . $err;
          } else {
            echo $response;
          }
        }

    return success_error(false, "", $emergency, 200);

    }

    public function get_partner_hospital_list(){
        $data = User::where('type', 'partner_hospital')->get();
        $arr = [];
        if($data){
            foreach($data as $val){
                $arr[] = array(
                    "id" => $val->id,
                    "name" => $val->name
                );
            }
        }

        if($arr){
            return success_error(false, "success", $arr, 200);
        }else{
            return success_error(false, "Data are not available", '', 400);
        }
    }

    public function user_otp_token_varification(Request $req){
        $mobile = $req->mobile;
        $info = new UsersTokenList;
        $cq = User::where("mobile",$mobile)->orWhere('username', $mobile)->first();
        if($cq){
            return success_error(true, "Mobile number alredy exist", '', 200);
        }else{
            if($mobile){
                $is_unique = false;
                $token = false;
                while(!$is_unique){
                    $token = rand(100000,999999);
                    $qry = UsersTokenList::where("token",$token)->first();
                    if(empty($qry)){
                        $is_unique = true;
                    }
                }
                $mobile_qry = UsersTokenList::where("mobile",$req->mobile)->first();
                if($mobile_qry){
                    $mobile_qry->token = $token;
                    if($mobile_qry->save()){

                        $mobile = substr($req->mobile, -10);
                        $mobile = "880" . $mobile;
                        $mbl_txt = "Hi ".$req->name.", your Kambaii Health O-T-P is - ".$token;

                        $rslt = send_sms($mobile, $mbl_txt);
                        $msg = "Security code sent in your registered mobile - ".$info->mobile;

                        return success_error(false, $msg, '', 200);

                    }else{
                        return success_error(true, "Unsuccessful", '', 400);
                    }
                }else{
                    $info->mobile = $req->mobile;
                    $info->token = $token;

                    if($info->save()){
                        $mobile = substr($info->mobile, -10);
                        $mobile = "880" . $mobile;
                        $mbl_txt = "Hi ".$req->name.", your Kambaii Health O-T-P is - ".$token;

                        $rslt = send_sms($mobile, $mbl_txt);
                        $msg = "Security code sent in your registered mobile - ".$info->mobile;

                        return success_error(false, $msg, '', 200);
                    }else{
                        return success_error(true, "Unsuccessful", '', 400);
                    }
                }


            }

        }

    }

    public function user_send_otp_token_varification(Request $req){
        $mobile = $req->mobile;
        if($mobile){
            $mobile_qry = UsersTokenList::where("mobile",$req->mobile)->first();
            if($mobile_qry->token == $req->token){
                $msg ='Your token matched';
                return success_error(false, $msg, '', 200);
            }else{
                $msg ='Your token are not matched';
                return success_error(true, $msg, '', 400);
            }
        }
    }



}
