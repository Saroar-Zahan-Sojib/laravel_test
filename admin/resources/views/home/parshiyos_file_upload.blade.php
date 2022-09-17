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
                        <form method="post" action="{{ url('save-parshiyos-file') }}" enctype="multipart/form-data">
                        @csrf
                              <input type="hidden" class="form-control" id="main_cat_id" name="main_cat_id" value="{{$main_cat->id}}">
                            <div class="row justify-content-between mb-4">
                                <div class="col-md-6">
                                    <div class="form-group row nShow mb-1">
                                        <label class="col-md-4">Select Category</label>
                                        <div class="col-md-7">
                                            <select class="form-control" name="category_id" id="category_id">
                                                <option>--Select--</option>
                                                @foreach($category as $val)
                                                <option value="{{$val->id}}">{{$val->category_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row nShow mb-1">
                                        <label class="col-md-4">Inside Or Outside</label>
                                        <div class="col-md-7">
                                            <div class="form-check form-check-inline">
                                              <input class="form-check-input" type="radio" name="inside_or_outside" id="inside_or_outside" value="1">
                                              <label class="form-check-label" for="inside_or_outside">Inside</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                              <input class="form-check-input" type="radio" name="inside_or_outside" id="inside_or_outside" value="2">
                                              <label class="form-check-label" for="inside_or_outside">Outside</label>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="parshiyos row justify-content-between mb-4 d-none">
                              
                                <!-- <input type="hidden" class="form-control" id="category_id" name="category_id" value="{{$cat->id}}"> -->
                                <div class="col-md-6">
                                    <div class="form-group row nShow mb-1">
                                        <label class="col-md-4">Select Type</label>
                                        <div class="col-md-7">
                                            <select class="form-control" name="type_id" id="type_id">
                                                <option>--Select--</option>
                                                @foreach($type as $val)
                                                <option value="{{$val->id}}">{{$val->type_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                               
                                <div class="col-md-6">
                                    <div class="form-group row nShow mb-1">
                                        <label class="col-md-4">Select Content</label>
                                        <div class="col-md-7">
                                            <select class="form-control" name="content_id" id="content_id">
                                                
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                           

                           <div class="row justify-content-between mb-4">
                                <div class="col-md-6">
                                    <div class="form-group row nShow mb-1">
                                        <label class="col-md-4">Select Group</label>
                                        <div class="col-md-7">
                                            <select class="form-control" name="group_id" id="group_id">
                                                <option>--Select--</option>
                                                @foreach($groups as $val)
                                                <option value="{{$val->id}}">{{$val->group_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>    
                                <div class="col-md-6"> 
                                    <div class="form-group row nShow mb-1">
                                        <label class="col-md-4">Select Speaker</label>
                                        <div class="col-md-7">
                                            <select class="form-control" name="speaker_id" id="speaker_id">
                                                <option>--Select Speaker--</option>
                                                @foreach($speaker as $val)
                                                <option value="{{$val->id}}">{{$val->speaker_name}}</option>
                                                @endforeach
                                            
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                           </div>
                         

                           <div class="row justify-content-between mb-4">
                                <div class="col-md-6"> 
                                    <div id="for-title" class="form-group row mb-1">
                                        <label class="col-md-4">File Title</label>
                                        <div class="col-md-7">
                                            <input type="text" name="title" class="form-control" id="title">
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="col-md-6"> 
                                     <div class="yomim_yovim form-group row nShow mb-1 d-none">
                                        <label class="col-md-4">Select Holiday</label>
                                        <div class="col-md-7">
                                            <select class="form-control" name="holiday_id" id="holiday_id">
                                                <option>--Select--</option>
                                                @foreach($holiday as $val)
                                                <option value="{{$val->id}}">{{$val->holiday_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div> 
                           </div>

                           <div class="row justify-content-between mb-4">
                                <div class="col-md-6"> 
                                    <div class="form-group row nShow mb-1">
                                        <label class="col-md-4">File Or Link</label>
                                        <div class="col-md-7">
                                            <div class="form-check form-check-inline">
                                              <input class="form-check-input" type="radio" name="file_or_link" id="file_or_link1" value="1">
                                              <label class="form-check-label" for="file_or_link1">Link</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                              <input class="form-check-input" type="radio" name="file_or_link" id="file_or_link2" value="2">
                                              <label class="form-check-label" for="file_or_link2">File</label>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6"> 
                                   
                                </div> 
                           </div>

                           <div class="row justify-content-between mb-4 for-link d-none">
                                <div class="col-md-6"> 
                                    <div class="form-group row nShow mb-1">
                                        <label class="col-md-4">File Link Type</label>
                                        <div class="col-md-7">
                                            <div class="form-check form-check-inline">
                                              <input class="form-check-input" type="radio" name="file_link_type" id="file_link_type1" value="1">
                                              <label class="form-check-label" for="file_link_type1">Audio Link</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                              <input class="form-check-input" type="radio" name="file_link_type" id="file_link_type2" value="2">
                                              <label class="form-check-label" for="file_link_type2">Video Link</label>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6"> 
                                    <div id="for-audio-link" class="form-group row mb-1 d-none">
                                        <label class="col-md-4">Audio File Link</label>
                                        <div class="col-md-7">
                                            <input type="text" name="audio_link" class="form-control" id="audio_link">
                                        </div>
                                    </div>
                                    <div id="for-video-link" class="form-group row nShow mb-1 d-none">
                                        <label class="col-md-4">Video File Link</label>
                                        <div class="col-md-7">
                                            <input type="text" name="video_link" class="form-control" id="video_link">

                                        </div>
                                    </div>
                                </div>
                                
                           </div>

                           <div class="row justify-content-between mb-4 d-none for-file">
                                <div class="col-md-6"> 
                                    <div class="form-group row nShow mb-1">
                                        <label class="col-md-4">File Type</label>
                                        <div class="col-md-7">
                                            <div class="form-check form-check-inline">
                                              <input class="form-check-input" type="radio" name="file_type" id="inlineRadio1" value="1">
                                              <label class="form-check-label" for="inlineRadio1">Audio</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                              <input class="form-check-input" type="radio" name="file_type" id="inlineRadio2" value="2">
                                              <label class="form-check-label" for="inlineRadio2">Video</label>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6"> 
                                    <div id="for-audio" class="form-group row mb-1 d-none">
                                        <label class="col-md-4">Audio File</label>
                                        <div class="col-md-7">
                                            <input type="file" name="audio" class="form-control" id="audio">
                                        </div>
                                    </div>
                                    <div id="for-video" class="form-group row nShow mb-1 d-none">
                                        <label class="col-md-4">Video File</label>
                                        <div class="col-md-7">
                                            <input type="file" name="video" class="form-control" id="video">

                                        </div>
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
            let id = $(this).val();
            $('#content_id').empty();
            $('#content_id').append(`<option value="0" disabled selected>Processing...</option>`);
            $.ajax({
                type: 'GET',
                url: 'get-content-list-depends-on-type/' + id,
                success: function (response) {
                var response = JSON.parse(response);
                console.log(response);   
                $('#content_id').empty();
                $('#content_id').append(`<option value="0" disabled selected>Select Content*</option>`);
                response.forEach(element => {
                    $('#content_id').append(`<option value="${element['id']}">${element['content_name']}</option>`);
                    });
                }
            });
        });

        $('#category_id').on('change', function () {
            var cat = $('#category_id :selected').text();
            console.log(cat)
            if (cat == 'Yomim Tovim') {
               $(".parshiyos").addClass("d-none");
                $(".yomim_yovim").removeClass("d-none");
            }
            else if(cat == 'Parshiyos') {
                 $(".yomim_yovim").addClass("d-none");
                 $(".parshiyos").removeClass("d-none");
            }
        });


        $('input:radio[name="file_or_link"]').change(function(){

            if ($(this).val() == '1') {
                $(".for-link").removeClass("d-none");
                $(".for-file").addClass("d-none");
                // $("#audio").show();
            }
            else if($(this).val() == '2') {
                 $(".for-file").removeClass("d-none");
                 $(".for-link").addClass("d-none");
            }
        });

        $('input:radio[name="file_link_type"]').change(function(){

            if ($(this).val() == '1') {
                $("#for-audio-link").removeClass("d-none");
                $("#for-video-link").addClass("d-none");
                // $("#audio").show();
            }
            else if($(this).val() == '2') {
                 $("#for-video-link").removeClass("d-none");
                 $("#for-audio-link").addClass("d-none");
            }
        });

        $('input:radio[name="file_type"]').change(function(){

            if ($(this).val() == '1') {
                $("#for-audio").removeClass("d-none");
                $("#for-video").addClass("d-none");
                // $("#audio").show();
            }
            else if($(this).val() == '2') {
                 $("#for-video").removeClass("d-none");
                 $("#for-audio").addClass("d-none");
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



