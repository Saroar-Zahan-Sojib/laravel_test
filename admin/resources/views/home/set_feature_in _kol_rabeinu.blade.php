@extends('layouts.master')
@section('content')
<div class="container">
    <div class="col-md-12 pd0">
        <div class="row justify-content-center">
            <div class="col-md-8 pd0 float-left">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4><i class="fa fa-list"></i> Add Feature</h4>
                        
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
                            <div class="form-group row nShow mb-1">
                                <label class="col-md-4">Select Category</label>
                                <div class="col-md-8">
                                    <select class="form-control" name="category_id" id="category_id">
                                        <option>--Select--</option>
                                        @foreach($category as $val)
                                        <option value="{{$val->id}}">{{$val->category_name}}</option>
                                        @endforeach
                                       
                                    </select>
                                </div>
                            </div>
                            <br>

                            <!-- <div class="form-group row nShow mb-1 subcategory d-none">
                                <label class="col-md-4">Select Subcategory</label>
                                <div class="col-md-8">
                                    <select class="form-control" name="subcategory_id" id="subcategory_id">
                                                
                                    </select>
                                </div>
                            </div>
                            <br>

                            <div class="form-group row nShow mb-1 year_month d-none">
                                <label class="col-md-4">Select Year</label>
                                <div class="col-md-8">
                                    <select class="form-control" name="year" id="year">
                                        <option>--Select--</option>
                                        @foreach($years as $val)
                                        <option value="{{$val->year}}">{{$val->year}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <br>

                            <div class="form-group row nShow mb-1 year_month d-none">
                                <label class="col-md-4">Select Month</label>
                                <div class="col-md-8">
                                    <select class="form-control" name="month" id="month">
                                        <option>--Select--</option>
                                        @foreach($months as $val)
                                        <option value="{{$val->month}}">{{$val->month}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row nShow mb-1 event d-none">
                                <label class="col-md-4">Select Event</label>
                                <div class="col-md-8">
                                    <select class="form-control" name="event" id="event">
                                        <option>--Select--</option>
                                        @foreach($events as $val)
                                        <option value="{{$val->id}}">{{$val->event_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> -->

                            <div class="niggun form-group row nShow mb-1 d-none">
                                <label class="col-md-1"></label>
                                <div class="col-md-10">
                                
                                    <table class="table table-bordered dataTable">
                                      <thead class="thead-light">
                                        <th>Category Name</th>
                                        <th>Subcategory Name</th>
                                        <th>Title</th>
                                        <th>Status</th>
                                        <th>Action</th>

                                       </thead>
                                            <tbody>
                                                @foreach($niggun_file as $key => $val)
                                                  <tr>
                                                    <td style="white-space: nowrap;" class="holiday_id">{{$val->category_name}}</td>
                                                    <td style="white-space: nowrap;" class="holiday_id">{{$val->kol_rebeinu_sub_cat_name}}</td>
                                                    <td style="white-space: nowrap;" class="holiday_id">{{$val->title}}</td>
                                                    <td>@if($val->feature_status == 1)<h5> Featured</h5>@endif</td>
                                                    @if($val->feature_status == 1)
                                                    <td></td>
                                                    @else
                                                    <td><a id="{{$val->id}}" cat_id="{{$val->category_id}}" href="javascript:" class="btn btn-info btn-sm edit"> <i class='fa fa-save'></i> Submit </a></td>
                                                    @endif
                                                  </tr>
                                                @endforeach    
                                         
                                            </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="story form-group row nShow mb-1 d-none">
                                <label class="col-md-1"></label>
                                <div class="col-md-10">
                                
                                    <table class="table table-bordered dataTable">
                                      <thead class="thead-light">
                                        <th>Category Name</th>
                                        <th>Subcategory Name</th>
                                        <th>Title</th>
                                        <th>Status</th>
                                        <th>Action</th>

                                       </thead>
                                            <tbody>
                                                @foreach($story_file as $key => $val)
                                                  <tr>
                                                    <td style="white-space: nowrap;" class="holiday_id">{{$val->category_name}}</td>
                                                    <td style="white-space: nowrap;" class="holiday_id">{{$val->kol_rebeinu_sub_cat_name}}</td>
                                                    <td style="white-space: nowrap;" class="holiday_id">{{$val->title}}</td>
                                                    <td>@if($val->feature_status == 1)<h5> Featured</h5>@endif</td>
                                                    @if($val->feature_status == 1)
                                                    <td></td>
                                                    @else
                                                    <td><a id="{{$val->id}}" cat_id="{{$val->category_id}}" href="javascript:" class="btn btn-info btn-sm edit"> <i class='fa fa-save'></i> Submit </a></td>
                                                    @endif
                                                  </tr>
                                                @endforeach    
                                         
                                            </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="Bereshit form-group row nShow mb-1 d-none">
                                <label class="col-md-1"></label>
                                <div class="col-md-10">
                                
                                    <table class="table table-bordered dataTable">
                                      <thead class="thead-light">
                                       
                                            <tbody>
                                                   
                                         
                                            </tbody>
                                    </table>
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

        // $('#category_id').on('change', function () {
        //     var cat = $('#category_id :selected').text();
        //     console.log(cat)
        //     if (cat == 'Sichos kodesh') {
        //        $(".subcategory").addClass("d-none");
        //         $(".year_month").removeClass("d-none");
        //         $(".event").removeClass("d-none");
        //     }
        //     else if(cat == 'Mammar'){
        //         $(".year_month").removeClass("d-none");
        //         $(".subcategory").addClass("d-none");
        //         $(".event").addClass("d-none");
        //     }
        //     else{
        //          $(".year_month").addClass("d-none");
        //          $(".event").addClass("d-none");
        //          $(".subcategory").removeClass("d-none");
        //     }
        // });

        $('#category_id').on('change', function () {
            var cat = $('#category_id :selected').text();
            console.log(cat)
            if (cat == 'Niggun') {
                $(".niggun").removeClass("d-none");
                $(".story").addClass("d-none");
            }else if(cat == 'Story'){
                $(".niggun").addClass("d-none");
                $(".story").removeClass("d-none");
            }
          
        });

        $('#category_id').on('change', function () {
            let id = $(this).val();
            $('#subcategory_id').empty();
            $('#subcategory_id').append(`<option value="0" disabled selected>Processing...</option>`);
            $.ajax({
                type: 'GET',
                url: 'get-kol-rabeinu_subcat-list-depends-on-cat/' + id,
                success: function (response) {
                var response = JSON.parse(response);
                console.log(response);   
                $('#subcategory_id').empty();
                $('#subcategory_id').append(`<option value="0" disabled selected>Select Content*</option>`);
                response.forEach(element => {
                    $('#subcategory_id').append(`<option value="${element['id']}">${element['kol_rebeinu_sub_cat_name']}</option>`);
                    });
                }
            });
        });

    $(document).on("click", ".edit", function(){
      var id = $(this).attr("id");
      var category_id = $(this).attr("cat_id");
      console.log(id)
      console.log(category_id)
      $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "{{ url('save-feature-status') }}",
            method: "post",
            data: {
                id: id,
                category_id: category_id,
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
                            window.location = "{{ url('add-feature-for-kol-rabeinu') }}";
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

        

     });
</script>
@endsection