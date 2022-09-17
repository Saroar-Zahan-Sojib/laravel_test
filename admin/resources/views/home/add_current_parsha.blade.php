@extends('layouts.master')
@section('content')
<div class="container">
    <div class="col-md-12 pd0">
        <div class="row justify-content-center">
            <div class="col-md-8 pd0 float-left">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4><i class="fa fa-list"></i> Add Current Parsha</h4>
                        
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
                            <div class="form-group row nShow mb-1">
                                <label class="col-md-4">Select Parshiyos Type</label>
                                <div class="col-md-8">
                                    <select class="form-control" name="type_id" id="type_id">
                                        <option>--Select--</option>
                                        @foreach($type as $val)
                                            <option value="{{$val->id}}">{{$val->type_name}}</option>
                                        @endforeach
                                       
                                    </select>
                                </div>
                            </div>
                            <br>
                            <div class="Bereshit form-group row nShow mb-1 d-none">
                                <label class="col-md-4"></label>
                                <div class="col-md-8">
                                    <ul class="list-group">
                                        @foreach($contents as $val)
                                            @if($val->type_name == 'Bereshit')
                                                <li class="list-group-item"><input type="checkbox" name="current[]" value="{{$val->id}}" {{  ($val->content_id == $val->id ? ' checked' : '') }}>  {{$val->content_name}}</li>
                                          
                                           @endif
                                      @endforeach
                                    </ul>
                                </div>
                            </div>
                            <div class="Shemot form-group row nShow mb-1 d-none">
                                <label class="col-md-4"></label>
                                <div class="col-md-8">
                                    <ul class="list-group">
                                      @foreach($contents as $val)
                                            @if($val->type_name == 'Shemot')
                                                <li class="list-group-item"><input type="checkbox" name="current[]" value="{{$val->id}}"{{  ($val->content_id == $val->id ? ' checked' : '') }} >  {{$val->content_name}}</li>
                                          
                                           @endif
                                      @endforeach
                                    </ul>
                                </div>
                            </div>
                            <div class="Vayikra form-group row nShow mb-1 d-none">
                                <label class="col-md-4"></label>
                                <div class="col-md-8">
                                    <ul class="list-group">
                                     @foreach($contents as $val)
                                            @if($val->type_name == 'Vayikra')
                                                <li class="list-group-item"><input type="checkbox" name="current[]" value="{{$val->id}}" {{  ($val->content_id == $val->id ? ' checked' : '') }}>  {{$val->content_name}}</li>
                                          
                                           @endif
                                      @endforeach                                    </ul>
                                </div>
                            </div>
                            <div class="Bamidbar form-group row nShow mb-1 d-none">
                                <label class="col-md-4"></label>
                                <div class="col-md-8">
                                    <ul class="list-group">
                                      @foreach($contents as $val)
                                            @if($val->type_name == 'Bamidbar')
                                                <li class="list-group-item"><input type="checkbox" name="current[]" value="{{$val->id}}" {{  ($val->content_id == $val->id ? ' checked' : '') }}>  {{$val->content_name}}</li>
                                          
                                           @endif
                                      @endforeach
                                    </ul>
                                </div>
                            </div>
                            <div class="Devarim form-group row nShow mb-1 d-none">
                                <label class="col-md-4"></label>
                                <div class="col-md-8">
                                    <ul class="list-group">
                                      @foreach($contents as $val)
                                            @if($val->type_name == 'Devarim')
                                                <li class="list-group-item"><input type="checkbox" name="current[]" value="{{$val->id}}" {{  ($val->content_id == $val->id ? ' checked' : '') }}>  {{$val->content_name}}</li>
                                          
                                           @endif
                                      @endforeach                                    </ul>
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
        $('#type_id').on('change', function () {
            var type = $('#type_id :selected').text();
           
            if (type == 'Bereshit') {
                $(".Bereshit").removeClass("d-none"); 
                $(".Shemot").addClass("d-none");
                $(".Vayikra").addClass("d-none");
                $(".Bamidbar").addClass("d-none");
                $(".Devarim").addClass("d-none");
                
            }
            else if(type == 'Shemot') {
                 $(".Bereshit").addClass("d-none"); 
                $(".Shemot").removeClass("d-none");
                $(".Vayikra").addClass("d-none");
                $(".Bamidbar").addClass("d-none");
                $(".Devarim").addClass("d-none");
            }
            else if(type == 'Vayikra') {
                 $(".Bereshit").addClass("d-none"); 
                $(".Shemot").addClass("d-none");
                $(".Vayikra").removeClass("d-none");
                $(".Bamidbar").addClass("d-none");
                $(".Devarim").addClass("d-none");
            }
            else if(type == 'Bamidbar') {
                 $(".Bereshit").addClass("d-none"); 
                $(".Shemot").addClass("d-none");
                $(".Vayikra").addClass("d-none");
                $(".Bamidbar").removeClass("d-none");
                $(".Devarim").addClass("d-none");
            }
            else if(type == 'Devarim') {
                 $(".Bereshit").addClass("d-none"); 
                $(".Shemot").addClass("d-none");
                $(".Vayikra").addClass("d-none");
                $(".Bamidbar").addClass("d-none");
                $(".Devarim").removeClass("d-none");
            }
        });

     });
</script>
@endsection