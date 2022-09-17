@extends('layouts.master')
@section('content')
<div class="container">
    <div class="col-md-12 pd0">
        <div class="row justify-content-center">
            <div class="col-md-6 pd0 float-left">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4><i class="fa fa-list"></i> Write Your Subcategory</h4>
                        
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
                        <form method="post" action="{{ url('save-subcategory') }}" enctype="multipart/form-data">
                        @csrf
                            <div class="form-group row nShow mb-1">
                                <label class="col-md-4">Select Category</label>
                                <div class="col-md-8">
                                    <select class="form-control" name="category_id" id="category_id">
                                        <option>--Select--</option>
                                        @foreach($cat as $val)
                                        <option value="{{$val->id}}">{{$val->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row nShow mb-1">
                                <label class="col-md-4">Select Category Type</label>
                                <div class="col-md-8">
                                    <select class="form-control" name="type_id" id="type_id">
                                        
                                    </select>
                                </div>
                            </div>
                            <div class="subcategory">
                                <div class="form-group row mb-1">
                                    <label class="col-md-4">Type Name</label>
                                    <div class="col-md-7">
                                        <input type="text" class="form-control" name="subcategory[]" placeholder="Subcategory Name" id="subcategory">
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-sm btn-primary add"><i class="fa fa-plus-square" aria-hidden="true"></i></button>
                                    </div>

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
                          <th>Category</th>
                          <th>Type Name</th>
                          <th>Subcategory</th>
                      </tr>
                      </thead>
                        <tbody>
                            @foreach($data as $key => $dt)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td class="name">{{$dt->name}}</td>
                                <td class="type_name">{{$dt->type_name}}</td>
                                <td class="subcategory_name">{{$dt->subcategory_name}}</td>
                                
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

    $(document).ready(function () {
                $('#category_id').on('change', function () {
                let id = $(this).val();
                $('#type_id').empty();
                $('#type_id').append(`<option value="0" disabled selected>Processing...</option>`);
                $.ajax({
                type: 'GET',
                url: 'get-type-list-depends-on-cat/' + id,
                success: function (response) {
                var response = JSON.parse(response);
                console.log(response);   
                $('#type_id').empty();
                $('#type_id').append(`<option value="0" disabled selected>Select Category Type*</option>`);
                response.forEach(element => {
                    $('#type_id').append(`<option value="${element['id']}">${element['type_name']}</option>`);
                    });
                }
            });
        });
    });

    $(document).on("click", ".add", function(){
      var html_field = ''
      // html_field += '<div class="form-group row mb-1"><label class="col-md-4">Type Name</label><div class="col-md-7"><input type="text" class="form-control" name="subcategory" placeholder="Subcategory Name" id="subcategory"></div><div class="col-md-1"><button class="btn btn-sm btn-primary add"><i class="fa fa-plus-square" aria-hidden="true"></i></button> </div></div>'
      html_field += '<div class="form-group row mb-1 addSubCat">';
      html_field += '<label class="col-md-4">Type Name</label>';
      html_field += '<div class="col-md-7">';
      html_field += '<input type="text" class="form-control" name="subcategory[]" placeholder="Subcategory Name" id="subcategory">';
      html_field += '</div>';
      html_field += '<div class="col-md-1">';
      html_field += '<button type="button" class="btn btn-sm btn-danger remove"><i class="fa fa-minus-square" aria-hidden="true"></i></button>';
      html_field += '</div>'
      html_field += '</div>'
      $('.subcategory').append(html_field);          
    });
    $(document).on("click", ".remove", function(){
        $(this).closest('.addSubCat').remove();
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
      var type_name = $(this).parents("tr").find(".type_name").text();
      var id = $(this).attr("id");
      var category_id = $(this).attr("category_id");
      $("#type_name").val(type_name);
      $("#category_id").val(category_id);
      $("#id").val(id);
      $(".data_sub").val("Update");
    });


</script>
@endsection