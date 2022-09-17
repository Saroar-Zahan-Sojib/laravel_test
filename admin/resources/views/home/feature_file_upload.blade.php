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
                        <form method="post" action="{{ url('save-feature-file') }}" enctype="multipart/form-data">
                        @csrf
                            

                            <div class="row justify-content-between mb-4">
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
                           <div class="row justify-content-between mb-4">
                                <div class="col-md-6"> 
                                    <div class="form-group row nShow mb-1">
                                        <label class="col-md-4">Short Description</label>
                                        <div class="col-md-7">
                                            <textarea name="short_description" class="form-control" id="short_description" rows="4"></textarea>
                                        </div>
                                    </div>
                                </div>
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
                                
                           </div>

                           <!-- <div class="row justify-content-between mb-4">
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
                                
                           </div> -->

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

        $('#type_id').on('change', function () {
            var type_id = $(this).val();
            var cat_id = $('#category_id :selected').val();
            console.log(type_id);
            $('#subcategory_id').empty();
            $('#subcategory_id').append(`<option value="0" disabled selected>Processing...</option>`);
            $.ajax({
                type: 'GET',
                url: 'get-subcat-list-depends-on-cat/'+type_id+'/'+cat_id,
                success: function (response) {
                var response = JSON.parse(response);
                console.log(response);   
                $('#subcategory_id').empty();
                $('#subcategory_id').append(`<option value="0" disabled selected>Select Sub Category*</option>`);
                response.forEach(element => {
                    $('#subcategory_id').append(`<option value="${element['id']}">${element['subcategory_name']}</option>`);
                    });
                }
            });
        });

         $('#subcategory_id').on('change', function () {
            var subcat_id = $(this).val();
            var cat_id = $('#category_id :selected').val();
            var type_id = $('#type_id :selected').val();
            console.log(type_id);
            $('#content_id').empty();
            $('#content_id').append(`<option value="0" disabled selected>Processing...</option>`);
            $.ajax({
                type: 'GET',
                url: 'get-content-list-depends-on-cat/'+type_id+'/'+cat_id+'/'+subcat_id,
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

          $('#content_id').on('change', function () {
            var content_id = $(this).val();
            var cat_id = $('#category_id :selected').val();
            var type_id = $('#type_id :selected').val();
            var subcategory_id = $('#subcategory_id :selected').val();
            console.log(type_id);
            $('#speaker_id').empty();
            $('#speaker_id').append(`<option value="0" disabled selected>Processing...</option>`);
            $.ajax({
                type: 'GET',
                url: 'get-speaker-list-depends-on-cat/'+type_id+'/'+cat_id+'/'+subcategory_id+'/'+content_id,
                success: function (response) {
                var response = JSON.parse(response);
                console.log(response);   
                $('#speaker_id').empty();
                $('#speaker_id').append(`<option value="0" disabled selected>Select Speaker*</option>`);
                response.forEach(element => {
                    $('#speaker_id').append(`<option value="${element['speaker_id']}">${element['speaker_name']}</option>`);
                    });
                }
            });
        });

         $('#speaker_id').on('change', function () {
            var speaker_id = $(this).val();
            var cat_id = $('#category_id :selected').val();
            var content_id = $('#content_id :selected').val();
            var type_id = $('#type_id :selected').val();
            var subcategory_id = $('#subcategory_id :selected').val();
            console.log(type_id);
            $('#lecture_id').empty();
            $('#lecture_id').append(`<option value="0" disabled selected>Processing...</option>`);
            $.ajax({
                type: 'GET',
                url: 'get-lecture-list-depends-on-cat/'+type_id+'/'+cat_id+'/'+subcategory_id+'/'+content_id+'/'+speaker_id,
                success: function (response) {
                var response = JSON.parse(response);
                console.log(response);   
                $('#lecture_id').empty();
                $('#lecture_id').append(`<option value="0" disabled selected>Select Section*</option>`);
                response.forEach(element => {
                    $('#lecture_id').append(`<option value="${element['id']}">${element['lecture_name']}</option>`);
                    });
                }
            });
        });
    });

    $(document).on("click", ".addlecture", function(){
      var html_field = ''
     
      html_field += '<div class="form-group row mb-1 remlecture">';
      html_field += '<label class="col-md-2">Lecture Name</label>';
      html_field += '<div class="col-md-3">';
      html_field += '<input type="text" class="form-control" name="lecture[]" placeholder=" Lecture Name" id="lecture">';
      html_field += '</div>';
      html_field += '<div class="col-md-1">';
      html_field += '<button type="button" class="btn btn-sm btn-danger removelecture"><i class="fa fa-minus-square" aria-hidden="true"></i></button>';
      html_field += '</div>'
      html_field += '</div>'
      $('.lecture').append(html_field);          
    });
    $(document).on("click", ".removelecture", function(){
        $(this).closest('.remlecture').remove();
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



