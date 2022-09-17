@extends('layouts.master')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 pd0">
            <div class="col-md-5 pd0 float-left">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4><i class="fa fa-list"></i> Write Your Question</h4>
                        <!-- <a href="{{ url('question-list') }}" class="float-right">
                            <i class="fa fa-list"></i> Show Question List
                        </a> -->
                    </div>
                    <div class="card-body">
                        <div class="form-group row nShow mb-1">
                            <label class="col-md-4">Type</label>
                            <div class="col-md-8">
                                <label><input class="required" type="radio" name="examType" value="exam" onclick="showQuestionList()"> Exam</label>
                                <!-- <label><input class="required" type="radio" name="examType" value="test" onclick="showQuestionList()"> Test</label> -->
                            </div>
                        </div>
                        <div class="form-group row dnone nShow mb-1">
                            <label class="col-md-4">Select Exam</label>
                            <div class="col-md-8 dnone examsd">
                                <div class="input-group">
                                    <select class="form-control" name="exam" id="exam" onchange="showQuestionList()">
                                        <option value="">Select</option>
                                        @if(Auth::user()->inst_status == 1)
                                            @foreach($data['exams'] as $exams)
                                            <option value="{{ $exams->id }}">{{ $exams->name }} {{ $exams->year }}</option>
                                            @endforeach
                                        @else
                                            @foreach($data['exams'] as $exams)
                                            <option value="{{ $exams->id }}">{{ $exams->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @if(Auth::user()->inst_status != 1)
                                    <a href="javascript:" class="input-group-append AddExam">
                                        <span class="btn btn-primary"><i class="fa fa-plus"></i></span>
                                    </a>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-8 dnone testsd">
                                <div class="input-group">
                                    <select class="form-control" name="test" id="test" onchange="showQuestionList()">
                                        <option value="">Select</option>
                                        @foreach($data['tests'] as $tests)
                                        <option value="{{ $tests->id }}">{{ $tests->name }}</option>
                                        @endforeach
                                    </select>
                                    <a href="javascript:" class="input-group-append AddTest">
                                        <span class="btn btn-primary"><i class="fa fa-plus"></i></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row dnone nShow mb-1">
                            <label class="col-md-4">Select Year</label>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <!-- <input type="text" class="yearpicker form-control" id="year" name="year" onchange="showQuestionList()"> -->
                                    <select class="form-control" id="year" name="year" required="" onchange="showQuestionList()">
                                        <option value="">Select</option>
                                        <option value="2019">2019</option>
                                        <option value="2020">2020</option>
                                        <option value="2021">2021</option>
                                        <option value="2022">2022</option>
                                        <option value="2023">2023</option>
                                        <option value="2024">2024</option>
                                        <option value="2025">2025</option>
                                        <option value="2026">2026</option>
                                        <option value="2027">2027</option>
                                        <option value="2028">2028</option>
                                        <option value="2029">2029</option>
                                        <option value="2030">2030</option>

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group dnone row nShow mb-1">
                            <label class="col-md-4">Select Level</label>
                            <div class="col-md-8">
                                <label><input class="required" type="radio" name="level" value="Primary-School-Intermediate" > Primary, School, Intermediate</label>
                                <!-- <label><input class="required" type="radio" name="level" value="Heigh-School" > High School</label> -->
                                <label><input class="required" type="radio" name="level" value="University" > University</label>
                            </div>
                        </div>
                        <div class="form-group row  dnone nShow mb-1">
                            <label class="col-md-4">Select Class</label>
                            <div class="col-md-8 dnone Primary-School-Intermediate">
                                <div class="input-group">
                                    <select class="form-control" name="pclass" id="psi" required="" onchange="showQuestionList()">
                                        <option value="">Select</option>
                                        @foreach($data['classes_psi'] as $cls)
                                        <option value="{{ $cls->id }}">{{ $cls->name }}</option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>

                            <div class="col-md-8 dnone University">
                                <div class="input-group">
                                    <select class="form-control" name="iclass" id="uni" required="" onchange="showQuestionList()" >
                                        <option value="">Select</option>
                                        @foreach($data['classes_uni'] as $cls)
                                        <option value="{{ $cls->id }}">{{ $cls->name }}</option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>
                        </div>
                        <!-- <div class="form-group row dnone nShow mb-1">
                            <label class="col-md-4">Select Class</label>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <select class="form-control stdClass" onchange="showQuestionList()">
                                        <option value="">Select</option>
                                        @foreach($data['classes_uni'] as $cls)
                                        <option value="{{ $cls->id }}">{{ $cls->name }}</option>
                                        @endforeach
                                    </select>
                                    <a href="javascript:" class="input-group-append add_class">
                                        <span class="btn btn-primary"><i class="fa fa-plus"></i></span>
                                    </a>
                                </div>
                            </div>
                        </div> -->
                        <div class="form-group row dnone nShow mb-1">
                            <label class="col-md-4">Select Subject</label>
                            <div class="col-md-8">
                                <div class="input-group">
                                @if(Auth::user()->inst_status == 1)
                                <select class="form-control subj" id="subject_sh" onchange="showQuestionList()">
                                
                                        <option value="">Select</option>
                                        <!-- @foreach($data['subjects'] as $sbj)
                                            <option value="{{ $sbj->subject_id }}">{{ $sbj->subject_name }}</option>
                                            @endforeach -->
                                        
                                           
                                       
                                    </select>
                                    @else
                                    <select class="form-control subj" id="subject_sh" onchange="showQuestionList()">
                                        <option value="">Select</option>
                                      
                                    </select>
                                    @endif
                                    @if(Auth::user()->inst_status != 1)
                                  <!-- <a href="javascript:" class="input-group-append add_subj">
                                    <span class="btn btn-primary"><i class="fa fa-plus"></i></span>
                                  </a> -->
                                  @endif
                                </div>
                            </div>
                        </div>

                        <!-- <div class="form-group row dnone nShow mb-1">
                            <label class="col-md-4">Select Subject</label>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <select class="form-control subj" onchange="showQuestionList()">
                                        <option value="">Select</option>
                                        @if(Auth::user()->inst_status == 1)
                                            @foreach($data['subjects'] as $sbj)
                                            <option value="{{ $sbj->subject_id }}">{{ $sbj->subject_name }}</option>
                                            @endforeach
                                        @else
                                            @foreach($data['subjects'] as $sbj)
                                            <option value="{{ $sbj->id }}">{{ $sbj->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @if(Auth::user()->inst_status != 1)
                                  <a href="javascript:" class="input-group-append add_subj">
                                    <span class="btn btn-primary"><i class="fa fa-plus"></i></span>
                                  </a>
                                  @endif
                                </div>
                            </div>
                        </div> -->

                        

                        



                        <div class="form-group row dnone nShow mb-1">
                            <label class="col-md-12"> Write a Question </label>
                            <div class="col-md-12">
                                <textarea class="form-control inpQC" placeholder="Write the question"></textarea>
                            </div>
                        </div>

                        <div class="form-group row dnone nShow mb-1 aType">
                            <label class="col-md-4"> Answer type </label>
                            <div class="col-md-8">
                                <select class="form-control" id="qType">
                                    <option value="">Select</option>
                                    <option value="text">Character Upto 255</option>
                                    <option value="number">Digit Only</option>
                                    <option value="textarea">Unlimited Character</option>
                                    <option value="checkbox">Multiple Choice</option>
                                    <option value="radio">Single Choice</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row DqType dnone nShow mb-1 aType">
                            <div class="col-md-12 qType"></div>

                            <label class="col-md-4 mb-1">Marks</label>
                            <div class="col-md-8 mb-1">
                                <input type="text" id="mark" class="form-control" placeholder="Marks">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12 sErr"></div>
                        </div>

                        <div class="form-group row dnone DqType mb-3 dSubmit">
                            <label class="col-md-4"></label>
                            <div class="col-md-8">
                                <button type="button" id="data_submit" class="btn btn-primary float-right">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-7 float-left" style="padding-right: 0">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4><i class="fa fa-list"></i> Question List Preview</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-12 qList"></div>
                        </div>
                        <table class="table table-bordered datatable">
                            <thead>
                                <tr>
                                    <th>Question</th>
                                    <th>Options</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody class="list"></tbody>
                        </table>
                        <!-- <div class="form-group row preview" style="display: none;">
                            <label class="col-md-12 previewHTML"></label>
                        </div>
                        <div class="form-group row preview" style="display: none;">
                            <div class="col-md-12 previewVal"></div>
                        </div> -->
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="SubjectModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="SubjectModalTitle">Add Subject</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group row mb-3 mt-3">
            <div class="col-md-12 sErr"></div>
        </div>
        <div class="form-group row mb-3 mt-3">
            <div class="col-md-12">
                <div class="input-group">
                    <input id="SubjectName" type="text" class="form-control required" placeholder="Write Subject name *">
                    <p class="err d-none"></p>
                    <a href="javascript:" class="input-group-append subject_submit">
                        <span class="btn btn-primary">Submit</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="form-group row mb-3 mt-3">
            <div class="col-md-12">
                <table class="table table-bordered table-striped table-hover subjData">
                    @foreach($data['subjects'] as $dt)
                    <tr>
                        <td>{{ $dt->name }}</td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="ClassModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ClassModalTitle">Add Class</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group row mb-3 mt-3">
            <div class="col-md-12 sErr"></div>
        </div>
        <div class="form-group row mb-3 mt-3">
            <div class="col-md-12">
                <div class="input-group">
                    <input id="className" type="text" class="form-control required" placeholder="Write Class name *">
                    <p class="err d-none"></p>
                    <a href="javascript:" class="input-group-append class_submit">
                        <span class="btn btn-primary">Submit</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="form-group row mb-3 mt-3">
            <div class="col-md-12">
                <table class="table table-bordered table-striped table-hover stdClsData">
                    @foreach($data['classes_uni'] as $dt)
                    <tr>
                        <td>{{ $dt->name }}</td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="ModalForAll" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ModalForAllTitle"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group row">
            <div class="col-md-12 sErr"></div>
        </div>
        <div class="ModalContent"></div>
      </div>
    </div>
  </div>
</div>
@endsection

@section("styles")
<style type="text/css">
.table td, .table th{
    padding: 3px 10px;
}
</style>
@endsection

@section("scripts")
<script type="text/javascript">
$(document).on("change", "#psi", function(){
  

  var class_id = $(this).val(); 
  //alert(class_id);
  $.ajax({
    url: "{{ url('selected_subject') }}",
    method: "post",
    dataType: 'json', 
    data: {class_id: class_id},
  
    success: function(rsp){
      var len = 0;

      if (rsp.data1 != null) {
        $("#subject_sh").empty();
        $("#subject_sh").append('<option value="">Select</option>');

        $(rsp.data1).each(function(i){
            var id = rsp.data1[i].subject_id;

            var name = rsp.data1[i].subject_name;

            var option = "<option value='"+id+"'>"+name+"</option>"; 

            $("#subject_sh").append(option);
        });

        }

      if (rsp.data != null) {
        $("#subject_sh").empty();
        $("#subject_sh").append('<option value="">Select</option>');
        $(rsp.data).each(function(i){
            var id = rsp.data[i].id;

            var name = rsp.data[i].name;

            var option = "<option value='"+id+"'>"+name+"</option>"; 

            $("#subject_sh").append(option);
        });


      }


      

  
      
    
     
    },
  
  });
});

$(document).on("change", "#uni", function(){
  

  var class_id = $(this).val(); 
  //alert(class_id);
  $.ajax({
    url: "{{ url('selected_subject') }}",
    method: "post",
    dataType: 'json', 
    data: {class_id: class_id},
  
    success: function(rsp){
     

      if (rsp.data1 != null) {
        $(rsp.data1).each(function(i){
            var id = rsp.data1[i].subject_id;

            var name = rsp.data1[i].subject_name;

            var option = "<option value='"+id+"'>"+name+"</option>"; 

            $("#subject_sh").append(option);
        });

        }

      if (rsp.data != null) {

        $(rsp.data).each(function(i){
            var id = rsp.data[i].id;

            var name = rsp.data[i].name;

            var option = "<option value='"+id+"'>"+name+"</option>"; 

            $("#subject_sh").append(option);
        });

      }

     
    },
  
  });
});



$(document).on("click", ".exam_test_submit", function(){
    var ts = $(this);
    var url = $(this).attr("url");
    var val = ts.parent(".input-group").find("input").val();
    if(val == ""){
        ts.parents(".modal-body").find(".sErr").html("<div class='alert alert-danger text-center'><i class='fa fa-warning'></i> Input field cannot left empty</div>");
        return;
    }

    $.ajax({
        url: url,
        method: "post",
        data: {val: val},
        beforeSend: function(){
            ts.parents(".modal-body").find(".sErr").html("<div class='alert alert-warning text-center'><i class='fa fa-spinner fa-spin'></i> Processing...</div>");
        },
        success: function(rsp){
            if(rsp.error == false){
                ts.parents(".modal-body").find(".sErr").html("<div class='alert alert-success text-center'><i class='fa fa-check'></i> "+rsp.msg+"</div>");
                setTimeout(function(){
                    ts.parent(".input-group").find("input").val("");
                    ts.parents(".modal-body").find(".sErr").html("");


                        if(rsp.data.exams==undefined){
                            var cData = rsp.data.tests;
                            var sData = tData = "";
                            $(cData).each(function(){
                                sData += "<option value='"+this.id+"'>"+this.name+"</option>";
                                tData += "<tr><td>"+this.name+"</td></tr>";
                            });

                            $("#test").html(sData);
                            ts.parents(".modal-body").find("tbody").html(tData);

                        }else{
                            var cData = rsp.data.exams;
                            var sData = tData = "";
                            $(cData).each(function(){
                                sData += "<option value='"+this.id+"'>"+this.name+"</option>";
                                tData += "<tr><td>"+this.name+"</td></tr>";
                            });
                            $("#exam").html(sData);
                            ts.parents(".modal-body").find("tbody").html(tData);
                        }


                }, 1500);

                setTimeout(function(){
                    $("#exam option:last").attr("selected", true);
                    $("#exam").change();
                    $("#ModalForAll").modal("hide");
                }, 2000);
            } else {
                ts.parents(".modal-body").find(".sErr").html("<div class='alert alert-danger text-center'><i class='fa fa-warning'></i> "+rsp.msg+"</div>");
            }
        },
        error: function(err, txt, sts){
            console.log(err);
            console.log(txt);
            console.log(sts);
        }
    });
});

$(document).on("click", ".AddExam", function(){
    var mContent =
    '<div class="form-group row">'+
        '<div class="col-md-12">'+
            '<div class="input-group">'+
                '<input type="text" class="form-control required" placeholder="Write name of the Exam *">'+
                '<a href="javascript:" class="input-group-append exam_test_submit" url="{{ url('exam-create') }}" tbl="exam">'+
                    '<span class="btn btn-primary">Submit</span>'+
                '</a>'+
            '</div>'+
        '</div>'+
    '</div>';
    $("#ModalForAll .modal-title").html("Add Exam");
    $("#ModalForAll .ModalContent").html(mContent);
    $("#ModalForAll").modal("show");

    $.ajax({
        url: "{{ url('ExamTestListAjax') }}",
        method: "get",
        beforeSend: function(){

        },
        success: function(rsp){
            var data = rsp.data.exams;
            var dContent = "<table class='table table-bordered'>";
            dContent += "<tr><th>Title</th></tr>";
            $(data).each(function(){
                dContent += "<tr><td>"+this.name+"</td></tr>";
            });
            $("#ModalForAll .ModalContent").append(dContent);
        },
        error: function(err, txt, sts){
            console.log(err);
            console.log(txt);
            console.log(sts);
        }
    });
});

$(document).on("click", ".AddTest", function(){
    var mContent =
    '<div class="form-group row">'+
        '<div class="col-md-12">'+
            '<div class="input-group">'+
                '<input type="text" class="form-control required" placeholder="Write name of the Test *">'+
                '<a href="javascript:" class="input-group-append exam_test_submit" url="{{ url('test-create') }}" tbl="test">'+
                    '<span class="btn btn-primary">Submit</span>'+
                '</a>'+
            '</div>'+
        '</div>'+
    '</div>';
    $("#ModalForAll .modal-title").html("Add Test");
    $("#ModalForAll .ModalContent").html(mContent);
    $("#ModalForAll").modal("show");
    $.ajax({
        url: "{{ url('ExamTestListAjax') }}",
        method: "get",
        beforeSend: function(){

        },
        success: function(rsp){
            var data = rsp.data.tests;
            var dContent = "<table class='table table-bordered'>";
            dContent += "<tr><th>Title</th></tr>";
            $(data).each(function(){
                dContent += "<tr><td>"+this.name+"</td></tr>";
            });
            $("#ModalForAll .ModalContent").append(dContent);
        },
        error: function(err, txt, sts){
            console.log(err);
            console.log(txt);
            console.log(sts);
        }
    });
});

$(document).on("click", "#data_submit", function(){
    var level = $("input[name='level']:checked").val();
    var ts = $(this);
    var exType = $("input[name=examType]:checked").val();
    if (exType == 'test') {
        var exID = $("#test option:selected").val();
    }else{
    var exID = $("#exam option:selected").val();
    }
    console.log(exType)
    console.log(exID)

    var classID;
    if(level == 'Primary-School-Intermediate'){
        classID = $('#psi :selected').val();
    }
    if(level == 'University'){
        classID = $('#uni :selected').val();
    }

    var subjID = $(".subj option:selected").val();
    var qType = $("#qType option:selected").val();
    var qCont = $(".inpQC").val();
    var mark = $("#mark").val() ? $("#mark").val() : 1;
    var year = $("#year option:selected").val();
    var options = [];
    $(".options").each(function(){
        if($(this).val()){
            options.push($(this).val());
        }
    });

    $.ajax({
        url: "{{ url('question/create') }}",
        method: "post",
        data: {exType: exType, exID: exID, classID: classID, subj: subjID, qType: qType, qCont: qCont, options: options, mark: mark, level:level, year: year},
        beforeSend: function(){
            ts.hide();
            $(".sErr").html("<div class='alert alert-warning text-center'><i class='fa fa-spinner fa-spin'></i> Processing...</div>");
        },
        success: function(rsp){
            if(rsp.error == false){
                $(".sErr").html("<div class='alert alert-success text-center'>"+rsp.msg+"</div>");
                setTimeout(function(){
                    $(".sErr").html(
                        "<div class='col-md-6'>"+
                            "<button type='button' class='btn btn-primary addNewQuestion btn-block'>Add New Question</button>"+
                        "</div>"+
                        "<div class='col-md-6'>"+
                            "<button type='button' class='btn btn-info showAllQuestion btn-block'>Show all Questions</button>"+
                        "</div>"
                    );
                    showQuestionList();
                }, 1500);
            } else if (rsp.error == true) {
                ts.show();
                $(".sErr").html("<div class='alert alert-danger'>"+rsp.msg+"</div>");
            }
        },
        error: function(err, txt, sts){
            ts.show();
            console.log(err);
            console.log(txt);
            console.log(sts);
        }
    });
});

$(document).on("click", ".addNewQuestion", function(){
    $(".inpQC").val("");
    $("#qType").val("");
    $(".qType").html("");
    $(".aType, .dSubmit").hide();
    $(".aType").find("select").val("").change();
    $(".sErr").html("");
});

$(document).on("click", ".showAllQuestion", function(){
    window.location = "{{ url('question-list') }}";
});

function showQuestionList(){
    //alert('rgfh');
    console.log("showQuestionList");
    var level = $("input[name='level']:checked").val();
    var class_id;
    if(level == 'Primary-School-Intermediate'){
        class_id = $('#psi :selected').val();
    }
    if(level == 'University'){
        class_id = $('#uni :selected').val();
    }
   // var class_id = $(".stdClass option:selected").val();
    var subject_id = $(".subj option:selected").val();
    //var exam_id = $("#exam option:selected").val();
    var year = $("#year option:selected").val();
    var exam_type = $("input[name=examType]:checked").val();
    if (exam_type == 'test') {
        var exam_id = $("#test option:selected").val();
    }else{

        var exam_id = $("#exam option:selected").val();
    }
    console.log()
    console.log(exam_id);
    console.log(exam_type);
    if(exam_type && exam_id && class_id && subject_id){
        $.ajax({
            url: "{{ url('showQuestionList') }}/"+class_id+"/"+subject_id+"/"+exam_id+"/"+exam_type+"/"+year,
            method: "get",
            beforeSend: function(){

            },
            success: function(rsp){
                var data = rsp.data;
                console.log(data);
                $(".qList").html(
                    "<b>Exam/Test:</b> "+$("#exam option:selected").text()+
                    ", <b>Class:</b> "+$(".stdClass option:selected").text()+
                    ", <b>Subject:</b> "+$(".subj option:selected").text()+"<hr>"
                );
                if(data.length == 0){
                    $("tbody").html("<tr><td colspan='3'><div class='alert alert-danger text-center'>No question found based on your query</div></td></tr>");
                } else {
                    var cont = "";
                    var sl = 0;
                    $(data.data).each(function(){
                        var opts = $.parseJSON(this.options);
                        console.log(opts);
                        var iCont = "";
                        if(this.type == 'radio'){
                            $(opts).each(function(){
                                iCont += " <label><input type='radio' name='opt"+sl+"'> "+this+" </label>";
                            });
                        } else if(this.type == 'checkbox'){
                            $(opts).each(function(){
                                iCont += " <label><input type='checkbox' name='opt"+sl+"'> "+this+" </label><br>";
                            });
                        } else if(this.type == 'textarea'){
                            iCont += "<textarea class='form-control' placeholder='Answer will go here...'></textarea>";
                        } else {
                            iCont += "<input type='"+this.type+"' class='form-control' placeholder='Answer will go here...'>";
                        }
                        cont +=
                        "<tr>"+
                            "<td>"+this.content+"</td>"+
                            "<td>"+iCont+"</td>"+
                            "<td class='text-center text-wrap'>"+
                                '<a id="'+this.id+'" href="{{ url('questions-edit')}}-'+this.id+'" class="btn btn-info btn-sm"> <i class="fa fa-edit"></i> </a> <a id="'+this.id+'" href="javascript:" class="btn btn-danger btn-sm dlt"> <i class="fa fa-trash"></i> </a>';
                            "</td>"+
                        "</tr>";
                        sl++;
                    });
                    $("tbody").html(cont);
                }
            },
            error: function(err, txt, sts){
              console.log(err);
              console.log(txt);
              console.log(sts);
            }
        });
    }
}

$(document).on("click", ".class_submit", function(){
    var val = $("#className").val();
    var ts = $(this);
    if(!val){
        alert("Class name cannot left empty");
        $("#className").focus();
        return;
    }

    $.ajax({
        url: "{{ url('addContent') }}",
        method: "post",
        data: {val: val, tbl: 'classes'},
        beforeSend: function(){
            ts.find("span").removeClass("btn-primary btn-danger")
            .addClass("btn-warning")
            .html("<i class='fa fa-spinner fa-spin'></i>");
        },
        success: function(rsp){
            if(rsp.error == false){
                ts.find("span").removeClass("btn-warning")
                .addClass("btn-success")
                .html("<i class='fa fa-check'></i>");
                $(".modal-body .sErr").html("<div class='alert alert-success'>"+rsp.msg+"</div>");
                setTimeout(function(){
                    ts.find("span").removeClass("btn-success")
                    .addClass("btn-primary")
                    .html("Submit");
                    $("#SubjectName").val("");
                    $(".modal-body .sErr").html("");
                    var cData = rsp.data.StdClass;
                    var stdClassHTML = stdClassTblHTML = "";
                    $(cData).each(function(){
                        stdClassHTML += "<option value='"+this.id+"'>"+this.name+"</option>";
                    });
                    $(cData).each(function(){
                        stdClassTblHTML += "<tr><td>"+this.name+"</td></tr>";
                    });
                    $(".stdClass").html(stdClassHTML);
                    $(".stdClsData").html(stdClassTblHTML);
                }, 1500);

                setTimeout(function(){
                    $(".stdClass option:last").attr("selected", true);
                    $(".stdClass").change();
                    $("#ClassModal").modal("hide");
                }, 2000);
            } else if (rsp.error == true) {
                ts.find("span").removeClass("btn-warning").addClass("btn-danger").html("<i class='fa fa-danger'></i>");
                $(".modal-body .sErr").html("<div class='alert alert-success'>"+rsp.msg+"</div>");
            }
        },
        error: function(err, txt, sts){
          console.log(err);
          console.log(txt);
          console.log(sts);
        }
    });
});

$(document).on("click", ".subject_submit", function(){
    var val = $("#SubjectName").val();
    var ts = $(this);
    if(!val){
        alert("Subject name cannot left empty");
        $("#SubjectName").focus();
        return;
    }

    $.ajax({
        url: "{{ url('addContent') }}",
        method: "post",
        data: {val: val, tbl: 'subjects'},
        beforeSend: function(){
            ts.find("span").removeClass("btn-primary btn-danger")
            .addClass("btn-warning")
            .html("<i class='fa fa-spinner fa-spin'></i>");
        },
        
        success: function(rsp){
            
            
            if(rsp.error == false){
                ts.find("span").removeClass("btn-warning")
                .addClass("btn-success")
                .html("<i class='fa fa-check'></i>");
                $(".modal-body .sErr").html("<div class='alert alert-success'>"+rsp.msg+"</div>");
                setTimeout(function(){
                    ts.find("span").removeClass("btn-success")
                    .addClass("btn-primary")
                    .html("Submit");
                    $("#SubjectName").val("");
                    $(".modal-body .sErr").html("");
                    var cData = rsp.data;
                    var stdSubjHTML = stdSubjTblHTML = "";
                    $(cData).each(function(){
                        stdSubjHTML += "<option value='"+this.id+"'>"+this.name+"</option>";
                    });
                    $(cData).each(function(){
                        stdSubjTblHTML += "<tr><td>"+this.name+"</td></tr>";
                    });
                    $(".subj").html(stdSubjHTML);
                    $(".subjData").html(stdSubjTblHTML);
                }, 1500);

                setTimeout(function(){
                    $(".subj option:last").attr("selected", true);
                    $(".subj").change();
                    $("#SubjectModal").modal("hide");
                }, 2000);
            } else if (rsp.error == true) {
                ts.find("span").removeClass("btn-warning").addClass("btn-danger").html("<i class='fa fa-danger'></i>");
                $(".modal-body .sErr").html("<div class='alert alert-success'>"+rsp.msg+"</div>");
            }
            location.reload();
            
        },
        error: function(err, txt, sts){
          console.log(err);
          console.log(txt);
          console.log(sts);
        }
    });
});

$(document).on("click", ".add_subj", function(){
    $("#SubjectModal").modal("show");
});

$(document).on("click", ".add_class", function(){
    $("#ClassModal").modal("show");
});

$(document).on("click", "input[type=radio]:checked", function(){
    $(this).parents('.nShow').next('.nShow').show('slow').css("display", "flex");
});

$(document).on("change", "select", function(){
    $(this).parents('.nShow').next('.nShow').show('slow').css("display", "flex");
});

$(document).on("keyup", "input[type=text], input[type=number], textarea", function(){
    $(this).parents('.nShow').next('.nShow').show('slow').css("display", "flex");
});

$(document).on("click", "input[name=examType]:checked", function(){
    var val = $(this).val();
    if(val == 'exam'){
        $(".examsd").show("slow").css("display", "flex");
        $(".testsd").hide("slow");
    } else if(val == 'test'){
        $(".testsd").show("slow").css("display", "flex");
        $(".examsd").hide("slow");
    }
});

$(document).on("change", "#qType, #section", function(){
    var val = $(this, "option:selected").val();
    if(val){
        $(this).css("border","1px solid #DDD");
    } else {
        $(this).css("border","1px solid #F00");
    }
});

$(document).on("blur", ".inpQC", function(){
    var val = $(this).val();
    if(val){
        $(this).css("border","1px solid #DDD");
    } else {
        $(this).css("border","1px solid #F00");
    }
});
$(document).on("change", "#qType", function(){
    var val = $(this, "option:selected").val();
    if(val == 'text'){
        $(".qType, .previewVal").html("<div class='col-md-12 pd0 mb-3'><input readonly type='text' name='txt_<?=time();?>' id='txt_<?=time();?>' class='form-control options' placeholder='Write content here...'></div>");
        $(".DqType").show("slow").css("display", "flex");
        $("#data_submit").show();
    } else if(val == 'number'){
        $(".qType, .previewVal").html("<div class='col-md-12 pd0 mb-3'><input readonly type='number' name='nmb_<?=time();?>' id='nmb_<?=time();?>' class='form-control options' placeholder='Write number here...'></div>");
        $(".DqType").show("slow").css("display", "flex");
        $("#data_submit").show();
    } else if(val == 'checkbox'){
        $(".qType").html('<label class="col-md-12 pd0 mb-3">Choices</label><div class="col-md-12 pd0"><div class="addMoreClone"><div class="input-group mb-3"><div class="input-group-prepend"> <span class="input-group-text"><input type=\'checkbox\'></span> </div> <input type="text" class="form-control options" placeholder="Label"> <div class="input-group-append d"> <a href="javascript:" class="btn btn-primary" id="add_more"><i class="fa fa-plus"></i></a> </div> </div></div></div>');
        $(".DqType").show("slow").css("display", "flex");
        $("#data_submit").show();
    } else if(val == 'radio'){
        $(".qType").html('<label class="col-md-12 pd0 mb-3">Choices</label><div class="col-md-12 pd0"><div class="addMoreClone"><div class="input-group mb-3"><div class="input-group-prepend"> <span class="input-group-text"><input type=\'radio\' name="radio"></span> </div> <input type="text" class="form-control options" placeholder="Label"> <div class="input-group-append d"> <a href="javascript:" class="btn btn-primary" id="add_more"><i class="fa fa-plus"></i></a> </div> </div></div></div>');
        $(".DqType").show("slow").css("display", "flex");
        $("#data_submit").show();
        CheckRadioHTML(<?=time();?>);
    } else if(val == 'textarea'){
        $(".qType, .previewVal").html("<div class='col-md-12 pd0 mb-3'><textarea readonly name='txta_<?=time();?>' id='txta_<?=time();?>' class='form-control options' placeholder='Write content here...'></textarea></div>");
        $(".DqType").show("slow").css("display", "flex");
        $("#data_submit").show();
    } else {
        $(".DqType").hide();
        $("#data_submit").hide();
    }
});

$(document).on("click", "#add_more", function(){
    var cln = $(this).parents(".addMoreClone").clone().html();
    var cont = "<div class='addMoreClone'>"+cln+"</div>";
    $(".qType").append(cont);
    var lng = $('.qType').find(".addMoreClone").length;
    if(lng > 1) {
        $(".d").html('<a href="javascript:" class="btn btn-danger" id="do_less"><i class="fa fa-minus"></i></a>');
        $(".d:last").html('<a href="javascript:" class="btn btn-danger" id="do_less"><i class="fa fa-minus"></i></a> <a href="javascript:" class="btn btn-primary" id="add_more"><i class="fa fa-plus"></i></a>');
    }
    CheckRadioHTML(<?=time();?>);
});

$(document).on("click", "#do_less", function(){
    $(this).parents(".addMoreClone").remove();
    var lng = $('.qType').find(".addMoreClone").length;
    if(lng < 2) {
        $(".d").html('<a href="javascript:" class="btn btn-primary" id="add_more"><i class="fa fa-plus"></i></a>');
    } else {
        $(".d:last").html('<a href="javascript:" class="btn btn-danger" id="do_less"><i class="fa fa-minus"></i></a> <a href="javascript:" class="btn btn-primary" id="add_more"><i class="fa fa-plus"></i></a>');
    }
    CheckRadioHTML(<?=time();?>);
});

$(document).on("keyup", ".inpQC", function(){
    //$(".preview").show();
    var val = $(this).val();
    //$(".previewHTML").html(val);
    CheckRadioHTML(<?=time();?>);
    var plc = $(".qType .form-control").attr("placeholder");//.length;
    if(plc != 'Label'){
        var lng = $(".qType .form-control").length;
        if(lng == 1){
            var cln = $(".qType").clone().html();
            //$(".previewVal").html(cln);
        }
    }
});

$(document).on("keyup", ".addMoreClone input[type=text]", function(){
    CheckRadioHTML(<?=time();?>);
});

function CheckRadioHTML(rand){
    var cont = "";
    var sl = rand + 1;

    $("input[type=radio]").each(function(){
        var lbl = $(this).parents(".addMoreClone").find("input[type=text]").val();
        if(lbl){
            cont += " <input type='radio' name='radios_"+rand+"' id='radios_"+sl+"' value='"+lbl+"'> <label for='radios_"+sl+"'>"+lbl+"</label>";
            sl++;
        }
    });

    $("input[type=checkbox]").each(function(){
        var lbl = $(this).parents(".addMoreClone").find("input[type=text]").val();
        if(lbl){
            cont += " <input type='checkbox' name='checkboxs_"+rand+"' id='checkbox_"+sl+"' value='"+lbl+"'> <label for='checkbox_"+sl+"'>"+lbl+"</label><br>";
            sl++;
        }
    });

    $(".previewVal").html(cont);
}

$(function(){
    $(".datatable").dataTable();
    $('.yearpicker').yearpicker()
});

$(document).on("click", ".dlt", function(){
    var id = $(this).attr("id");

    var ts =$(this);
    $.ajax({
        url: "{{ url('delete_question') }}",
        method: "post",
        data: {id: id},
        beforeSend: function(){
            ts.find("span").removeClass("btn-primary btn-danger")
            .addClass("btn-warning")
            .html("<i class='fa fa-spinner fa-spin'></i>");
        },
        success: function(rsp){
            if(rsp.error == false){

                setTimeout(function(){
                    location.reload();
                }, 1500);

                setTimeout(function(){

                }, 2000);
            } else if (rsp.error == true) {

            }
        },
        error: function(err, txt, sts){
          console.log(err);
          console.log(txt);
          console.log(sts);
        }
    });
});

$(document).on("click", "input[name=level]:checked", function(){
    var val = $(this).val();
    console.log(val);
    if(val == 'Primary-School-Intermediate'){
        $(".Primary-School-Intermediate").removeClass("dnone");
        $(".Primary-School-Intermediate").show("slow").css("display", "flex");
       // $(".Heigh-School").hide("slow");
        $(".University").hide("slow");
    }
    // else if(val == 'Heigh-School'){
    //     //$(".Heigh_School").removeClass("dnone");
    //     $(".Heigh-School").show("slow").css("display", "flex");
    //     $(".Primary-School").hide("slow");
    //     $(".Intermediate").hide("slow");
    // }
    else if(val == 'University'){
        $(".University").show("slow").css("display", "flex");
        $(".Primary-School-Intermediate").hide("slow");
       // $(".Heigh-School").hide("slow");

    }
});
</script>

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-36251023-1']);
  _gaq.push(['_setDomainName', 'jqueryscript.net']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
@endsection