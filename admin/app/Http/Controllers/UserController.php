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

class UserController extends Controller
{
    public function login()
    {
        return view('user.login');
    }

    public function registration()
    {
        return view('user.registration');
    }

    public function registration_save(Request $req)
    {
        $dt = new User;
        $dt->phone = $req->phone;
        $dt->name = $req->name;
        $dt->email = $req->email;
        $dt->username = $req->email;
        $dt->password = Hash::make($req->password);
        $username = $req->email;
        if($dt->save()){
            if(Auth::attempt(['username' => $username, 'password' => $req->password])){
                    $user = Auth::user();
                }
            return $this->success_error(false, 'Registration successful, please wait, redirecting you to dashboard.', $user);    
        }
    }

    public function admin_dashboard()
    {
       
        return view('admin.dashboard');
    }

     public function myLogin(Request $req){
        if(Auth::attempt(['username' => $req->username, 'password' => $req->Password])){
           $user =  Auth::user();
            return $this->success_error(false, 'Login Successful, please wait, redirecting you to dashboard.', $user);
        } else {
            return $this->success_error(true, "Invalid Credentials", "");
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
