@extends('layouts.master')
@section('content')
<div class="container">
    <div class="col-md-12 pd0">
        <div class="row justify-content-center">
            <div class="col-md-10 pd0 float-left">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4><i class="fa fa-list"></i> Set Holiday date</h4>
                        
                    </div>
                    @if(session()->has('success'))
                      <div class="alert alert-success">
                        {{ session()->get('success') }}
                      </div>
                    @elseif(session()->has('error'))
                      <div class="alert alert-danger">
                        {{ session()->get('error') }}
                      </div>
                    @endif

                     @if ($errors->any())
                     @foreach ($errors->all() as $error)
                         <div class="alert alert-danger">{{$error}}</div>
                     @endforeach
                     @endif
                    <div class="card-body">
                        <form method="post" action="{{ url('save-current-parsha') }}" enctype="multipart/form-data">
                        @csrf
                            <input type="hidden" class="form-control" id="main_cat_id" name="main_cat_id" value="{{$main_cat->id}}">
                            <input type="hidden" class="form-control" id="cat_id" name="cat_id" value="{{$cat->id}}">
                            
                            
                            <div class="Bereshit form-group row nShow mb-1">
                                <label class="col-md-1"></label>
                                <div class="col-md-10">
                                
                                    <table class="table table-bordered dataTable">
                                      <thead class="thead-light">
                                       
                                            <tbody>
                                                @foreach($data as $key => $val)
                                                  <tr>
                                                    <td style="white-space: nowrap;" class="holiday_id">{{$val->holiday_name}}</td>
                                                    <td class="date_form"><input type="text" name="date_form" id="date_from" class="form-control" placeholder="YY-MM-DD" value="{{$val->date_from}}"></td>
                                                    <td class="date_to"><input type="text" name="date_to" id="date_to" class="form-control" placeholder="YY-MM-DD" value="{{$val->date_to}}">
                                                    </td>
                                                    <td><a id="{{$val->id}}" href="javascript:" class="btn btn-info btn-sm edit"> <i class='fa fa-save'></i> Submit </a></td>
                                                  </tr>
                                                @endforeach    
                                         
                                            </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <div class="col-md-12 sErr"></div>
                            </div>

                           <!--  <div class="form-group row DqType mb-3 dSubmit">
                                <label class="col-md-4"></label>
                                <div class="col-md-8">
                                    <input type="submit" class="btn btn-primary btn-sm btn-block data_sub" value="Save">
                                </div>
                            </div> -->
                        </form>
                    </div>
                </div>
            </div>
           
        </div>
    </div>
</div>

@endsection

@section("styles")

@endsection

@section("scripts")
<script type="text/javascript">
     $(document).on("click", ".edit", function(){
      var name = $(this).parents("tr").find(".holiday_id").text();
      var date_from = $(this).parents("tr").find("#date_from").val();
      var date_to = $(this).parents("tr").find("#date_to").val()
      var id = $(this).attr("id");
      var cat_id = $(this).attr("cat_id");
      console.log(name)
      console.log(date_from)
      console.log(date_to)
      $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "{{ url('save-upcoming-holiday') }}",
            method: "post",
            data: {
                id: id,
                date_from: date_from,
                date_to: date_to,
                "_token": "{{ csrf_token() }}"
            },
            beforeSend: function(){
                $(this).removeClass("btn-info").addClass("btn-warning").html("<i class='fa fa-spinner fa-spin'></i>")
            },
            success: function(rsp){
                console.log(rsp);
                if(rsp.error == false){
                    $(".card").html("<div class='alert alert-success text-center'>"+rsp.msg+"</div>");
                    $("input").val("");
                    $(this).removeClass("btn-warning").addClass("btn-primary").html("<i class='fa fa-save'></i> Submit");
                    setTimeout(function(){
                            window.location = "{{ url('set-upcoming-holiday') }}";
                    }, 2000);
                } else {
                    $(".sErr").html("<div class='alert alert-danger'>"+rsp.msg+"</div>");
                    $(this).removeClass("btn-warning").addClass("btn-primary").html("<i class='fa fa-save'></i> Submit");
                }
            },
            error: function(err, txt, sts){
                console.log(err);
                console.log(txt);
                console.log(sts);
            }
    });

     });    


</script>
@endsection