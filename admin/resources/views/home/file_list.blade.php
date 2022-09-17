@extends('layouts.master')
@section('content')
<div class="container">
    <div class="col-md-12 pd0">
        <div class="row justify-content-center">
            <div class="col-md-12 pd0 float-left mb-4">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4><i class="fa fa-list"></i> Uploded Video File List</h4>
                    </div>                
                </div>
            </div>

          
            <div class="col-md-12 box-shadow pd50 rds5">
              <table class="table table-bordered dataTable">
                  <thead class="thead-light">
                      <tr>
                          <th>SL</th> 
                          <th>Category</th>
                          <th>Type Name</th>
                          <th>Subcategory</th>
                          <th>Content</th>
                          <th>Sub Content</th>
                          <th>Lecture</th>
                          <th>Speakes</th>
                          <th>Title</th>
                          <th>Video</th>
                      </tr>
                      </thead>
                        <tbody>
                            @foreach($data as $key => $dt)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td class="name">{{$dt->name}}</td>
                                <td class="type_name">{{$dt->type_name}}</td>
                                <td class="subcategory_name">{{$dt->subcategory_name}}</td>
                                <td class="content_name">{{$dt->content_name}}</td>
                                <td class="subcontent_name">{{$dt->subcontent_name}}</td>
                                <td class="lecture_name">{{$dt->lecture_name}}</td>
                                <td class="speaker_name">{{$dt->speaker_name}}</td>
                                <td class="title">{{$dt->title}}</td>
                                @if($dt->file_link_type == 2)
                                <td class="video"><video width="100px" height="80" muted><source src="{{$dt->video_link}}" type="video/mp4"></video></td>
                                @else 
                                <td class="video"><video width="100px" height="80" muted><source src="{{asset('/video_file/'.$dt->video)}}" type="video/mp4"></video></td>   
                                @endif    
                                
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

@endsection



