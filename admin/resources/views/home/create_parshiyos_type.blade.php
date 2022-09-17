@extends('layouts.master')
@section('content')
<div class="container">
    <div class="col-md-12 pd0">
        <div class="row justify-content-center">
            <div class="col-md-6 pd0 float-left">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4><i class="fa fa-list"></i> Write Your Parshiyos Type</h4>
                        
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
                        <form method="post" action="{{ url('save-parshiyos-type') }}" enctype="multipart/form-data">
                        @csrf
                            <div class="form-group row nShow mb-1">
                                <label class="col-md-4">Select Category</label>
                                <div class="col-md-8">
                                    <select class="form-control" name="category_id" id="category_id" readonly>
                                       
                                        <option value="{{$cat->id}}">{{$cat->category_name}}</option>
                                       
                                    </select>
                                  
                                    <input type="hidden" class="form-control" id="id" name="id">
                                    <input type="hidden" class="form-control" id="main_cat_id" name="main_cat_id" value="{{$main_cat->id}}">

                                </div>
                            </div>
                             <div class="form-group row nShow mb-1">
                                <label class="col-md-4">Type Name</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="type_name" placeholder="Type Name" id="type_name">
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <div class="col-md-12 sErr"></div>
                            </div>

                            <div class="form-group row DqType mb-3 dSubmit">
                                <label class="col-md-4"></label>
                                <div class="col-md-8">
                                    <input type="submit" class="btn btn-primary btn-sm btn-block data_sub" value="Save">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
          
            <div class="col-md-6 box-shadow pd50 rds5">
              <table class="table table-bordered dataTable">
                  <thead class="thead-light">
                      <tr>
                          <th>SL</th> 
                          <th>Type Name</th>
                          <th>Action</th>
                      </tr>
                      </thead>
                        <tbody>
                            @foreach($data as $key => $dt)
                              <tr>
                                <td>{{$key+1}}</td>
                                <td class="type_name">{{$dt->type_name}}</td>
                                <td style="white-space: nowrap;">
                                    <a id="{{$dt->id}}" href="javascript:" class="btn btn-info btn-sm edit"> <i class="fa fa-edit"></i> </a> <a id="{{$dt->id}}" onclick="return confirm('Are you sure to delete this Category?')" href="{{ url('delete-parshiyos-type/'.$dt->id) }}" class="btn btn-danger btn-sm"> <i class="fa fa-trash"></i> </a>
                                </td>
                              </tr>
                            @endforeach    
                     
                        </tbody>
                </table>
            </div>
           
        </div>
    </div>
</div>

@endsection

@section("styles")

@endsection

@section("scripts")
<script type="text/javascript">
      $(function(){
          tinymceLoad("");

          $(".dataTable").dataTable();
        });
    $(document).on("click", "#data_submit", function(){
        alert("test");
        var ts = $(this);
        var name = $("#name").val();

        console.log(name);

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "{{ url('save-category') }}",
            method: "post",
            data: {
                name: name,
                "_token": "{{ csrf_token() }}"
            },
            beforeSend: function(){
                ts.removeClass("btn-primary").addClass("btn-warning").html("<i class='fa fa-spinner fa-spin'></i>")
            },
            success: function(rsp){
                console.log(rsp);
                if(rsp.error == false){
                    $(".card").html("<div class='alert alert-success text-center'>"+rsp.msg+"</div>");
                    $("input").val("");
                    ts.removeClass("btn-warning").addClass("btn-primary").html("<i class='fa fa-save'></i> Submit");
                    setTimeout(function(){
                            window.location = "{{ url('admin/dashboard') }}";
                    }, 2000);
                } else {
                    $(".sErr").html("<div class='alert alert-danger'>"+rsp.msg+"</div>");
                    ts.removeClass("btn-warning").addClass("btn-primary").html("<i class='fa fa-save'></i> Submit");
                }
            },
            error: function(err, txt, sts){
                console.log(err);
                console.log(txt);
                console.log(sts);
            }
        });
    });

    $(document).on("click", ".edit", function(){
      var nigumin_category_name = $(this).parents("tr").find(".nigumin_category_name").text();
      var id = $(this).attr("id");
      $("#nigumin_category_name").val(nigumin_category_name);
      $("#id").val(id);
      $(".data_sub").val("Update");
    });
</script>
@endsection