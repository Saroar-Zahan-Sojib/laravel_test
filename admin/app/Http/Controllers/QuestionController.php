<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StdClass;
use App\Models\Subject;
use App\Models\Exam;
use App\Models\Test;
use App\Models\User;
use App\Models\Question;
use Mail;
use Auth;
use File;
use DB;

class QuestionController extends Controller
{
    public function create(){
       
        $data['exams'] = Exam::where("user_id", Auth::id())->get();
        
        $data['tests'] = Test::where("user_id", Auth::id())->get();
        $data['classes_psi'] = StdClass::where("status", 'admin')->where('level', 'Primary-School-Intermediate')->get();
        $data['classes_uni'] = StdClass::where("status", 'admin')->where('level', 'University')->get();
        $data['subjects'] = Subject::where("user_id", Auth::id())->get();
        
        return view('questions.create', compact('data'));
    }
}
