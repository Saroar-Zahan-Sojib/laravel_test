@extends('layouts.master')
@section('content')
<div class="container">
    <div class="col-md-12 pd0">
        <div class="row justify-content-center">
            <div class="col-md-12 pd0 float-left mb-4">
                <div class="card card-primary">
                    <div class="card-header">
                        <div class="row justify-content-between">
                            <div class="col-md-10">
                                <h4><i class="fa fa-list"></i> Upload Your File</h4>
                            </div>
                            <div class="col-md-2 float-right">
                                <a href="{{url('all-file-list')}}" class="btn btn-primary float-right">Show File List</a>
                            </div>
                       </div>
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
                        <form method="post" action="{{ url('save-nigunim-file') }}" enctype="multipart/form-data">
                        @csrf
                            <div class="row justify-content-between mb-4">
                                <div class="col-md-6">
                                    <div class="form-group row nShow mb-1">
                                        <label class="col-md-4">Select Category</label>
                                        <div class="col-md-7">
                                            <select class="form-control" name="nigunim_category_id" id="nigunim_category_id">
                                                <option>--Select--</option>
                                                @foreach($nigcat as $val)
                                                <option value="{{$val->id}}">{{$val->nigunim_category_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" value="{{$nig->id}}" class="form-control" id="nigunim_id" name="nigunim_id">
                                <div class="col-md-6">
                                    <div class="form-group row nShow mb-1">
                                        <label class="col-md-4">Select Albam</label>
                                        <div class="col-md-7">
                                            <select class="form-control" name="albam_id" id="albam_id">
                                                
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                           

                           <div class="row justify-content-between mb-4">
                                <div class="col-md-6">    
                                    <div class="form-group row nShow mb-1">
                                        <label class="col-md-4">File Title</label>
                                        <div class="col-md-7">
                                            <input type="text" name="title" class="form-control" id="title">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6"> 
                                    <div class="form-group row nShow mb-1">
                                        <label class="col-md-4">Topics</label>
                                        <div class="col-md-7">
                                            <textarea name="topics" class="form-control" id="topics" rows="4"></textarea>
                                        </div>
                                    </div>
                                </div>
                                
                           </div>
                         

                           <div class="row justify-content-between mb-4 for-file">
                                <div class="col-md-6"> 
                                    <div id="for-audio" class="form-group row mb-1">
                                        <label class="col-md-4">Audio File</label>
                                        <div class="col-md-7">
                                            <input type="file" name="audio" class="form-control" id="audio">
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="col-md-6"> 
                                    
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
           
        </div>
    </div>
</div>

@endsection

@section("styles")

@endsection

@section("scripts")
<script type="text/javascript">
    $(document).ready(function () {

        
        $('#nigunim_category_id').on('change', function () {
            let id = $(this).val();
            $('#albam_id').empty();
            $('#albam_id').append(`<option value="0" disabled selected>Processing...</option>`);
            $.ajax({
                type: 'GET',
                url: 'get-albam-list-depends-on-cat/' + id,
                success: function (response) {
                var response = JSON.parse(response);
                console.log(response);   
                $('#albam_id').empty();
                $('#albam_id').append(`<option value="0" disabled selected>Select Albam*</option>`);
                response.forEach(element => {
                    $('#albam_id').append(`<option value="${element['id']}">${element['albam_name']}</option>`);
                    });
                }
            });
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



