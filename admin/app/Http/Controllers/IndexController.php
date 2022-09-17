<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Rules\MatchOldPassword;
use Mail;
use Auth;
use File;
use DB;
use App\Models\User;
use App\Models\Client;

class IndexController extends Controller
{
    public function index()
    {
        return view('frontend.index');
    }

    public function blog()
    {
        return view('frontend.blog');
    }

    public function save_client(Request $req)
    {
        $dt = new Client;
        $dt->name = $req->name;
        $dt->email = $req->email;
        $dt->phone = $req->phone;
        $dt->created_at = date('Y-m-d H:i:s');
        $dt->updated_at = date('Y-m-d H:i:s');

        if($dt->save()){
            return $this->success_error(false, 'Your Information Save Successful', "");
        }else{
            return $this->success_error(true, "Data Save Unsuccessful", "");
        }

    }

     public function success_error($err, $msg, $data){
        return response()->json([
            "error" => $err,
            "msg" => $msg,
            "data" => $data
        ]);
    }
}
