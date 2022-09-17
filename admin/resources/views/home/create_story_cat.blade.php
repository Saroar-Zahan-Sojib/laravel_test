@extends('layouts.master')
@section('content')
<div class="container">
    <div class="col-md-12 pd0">
        <div class="row justify-content-center">
            <div class="col-md-6 pd0 float-left">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4><i class="fa fa-list"></i> Write Story Category</h4>
                        
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
                        <form method="post" action="{{ url('save-story-category') }}" enctype="multipart/form-data">
                        @csrf
                            <div class="form-group row nShow mb-1">
                                <label class="col-md-4">Story Category Name</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="kol_rebeinu_sub_cat_name" placeholder="Category Name" id="kol_rebeinu_sub_cat_name">
                                    <input type="hidden" class="form-control" id="category_id" name="category_id" value="{{$category->id}}">
                                    <input type="hidden" class="form-control" id="main_cat_id" name="main_cat_id" value="{{$cat->id}}">
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
                          <th>Category Name</th>
                          <th>Action</th>
                      </tr>
                      </thead>
                        <tbody>
                            @foreach($data as $key => $dt)
                              <tr>
                                <td>{{$key+1}}</td>
                                <td class="name">{{$dt->kol_rebeinu_sub_cat_name}}</td>
                                <td style="white-space: nowrap;">
                                    <a id="{{$dt->id}}" href="javascript:" class="btn btn-info btn-sm edit"> <i class="fa fa-edit"></i> </a> <a id="{{$dt->id}}" onclick="return confirm('Are you sure to delete this topics Category?')" href="{{ url('delete-topics-category/'.$dt->id) }}" class="btn btn-danger btn-sm"> <i class="fa fa-trash"></i> </a>
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
      var name = $(this).parents("tr").find(".name").text();
      var id = $(this).attr("id");
      $("#name").val(name);
      $("#category_id").val(id);
      $(".data_sub").val("Update");
    });
</script>
@endsection