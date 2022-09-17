@extends('layouts.master')
@section('content')
<style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}
</style>

<div class="container">
    <div class="col-md-12 pd0">
        <div class="row justify-content-center">
            <div class="col-md-12 pd0 float-left mb-4">
                <div class="card card-primary">
                    <div class="card-header">
                        <div class="row justify-content-between">
                            <div class="col-md-10">
                                <h4><i class="fa fa-list"></i> Set Date </h4>
                            </div>
                            <div class="col-md-2 float-right">
                                <!-- <a href="{{url('all-file-list')}}" class="btn btn-primary float-right">Show File List</a> -->
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
                        <!-- <form method="post" action="{{ url('save-farbrengen-file') }}" enctype="multipart/form-data">
                        @csrf -->
                              
                            <div class="row justify-content-between mb-4">
                                <table>
                                  <tr>
                                    <th>Category Name</th>
                                    <th>File Title</th>
                                    <th>Select Date</th>
                                    <th>Select Month</th>
                                    <th>Action</th>
                                  </tr>
                                  @foreach($data as $dt)
                                  <tr>
                                    <td>{{$dt->category_name}}</td>
                                    <td>{{$dt->title}}</td>
                                    <td>
                                        <select class="form-control" name="date" id="date" class="date">
                                                <option>--Select Date--</option>
                                                <option {{ ($dt->date) == "01" ? 'selected' : '' }} value="01">01</option>
                                                <option {{ ($dt->date) == "02" ? 'selected' : '' }} value="02">02</option>
                                                <option {{ ($dt->date) == "03" ? 'selected' : '' }} value="03">03</option>
                                                <option {{ ($dt->date) == "04" ? 'selected' : '' }} value="04">04</option>
                                                <option {{ ($dt->date) == "05" ? 'selected' : '' }} value="05">05</option>
                                                <option {{ ($dt->date) == "06" ? 'selected' : '' }} value="06">06</option>
                                                <option {{ ($dt->date) == "07" ? 'selected' : '' }} value="07">07</option>
                                                <option {{ ($dt->date) == "08" ? 'selected' : '' }} value="08">08</option>
                                                <option {{ ($dt->date) == "09" ? 'selected' : '' }} value="09">09</option>
                                                <option {{ ($dt->date) == "10" ? 'selected' : '' }} value="10">10</option>
                                                <option {{ ($dt->date) == "11" ? 'selected' : '' }} value="11">11</option>
                                                <option {{ ($dt->date) == "12" ? 'selected' : '' }} value="12">12</option>
                                                <option {{ ($dt->date) == "13" ? 'selected' : '' }} value="13">13</option>
                                                <option {{ ($dt->date) == "14" ? 'selected' : '' }} value="14">14</option>
                                                <option {{ ($dt->date) == "15" ? 'selected' : '' }} value="15">15</option>
                                                <option {{ ($dt->date) == "16" ? 'selected' : '' }} value="16">16</option>
                                                <option {{ ($dt->date) == "17" ? 'selected' : '' }} value="17">17</option>
                                                <option {{ ($dt->date) == "18" ? 'selected' : '' }} value="18">18</option>
                                                <option {{ ($dt->date) == "19" ? 'selected' : '' }} value="19">19</option>
                                                <option {{ ($dt->date) == "20" ? 'selected' : '' }} value="20">20</option>
                                                <option {{ ($dt->date) == "21" ? 'selected' : '' }} value="21">21</option>
                                                <option {{ ($dt->date) == "22" ? 'selected' : '' }} value="22">22</option>
                                                <option {{ ($dt->date) == "23" ? 'selected' : '' }} value="23">23</option>
                                                <option {{ ($dt->date) == "24" ? 'selected' : '' }} value="24">24</option>
                                                <option {{ ($dt->date) == "25" ? 'selected' : '' }} value="25">25</option>
                                                <option {{ ($dt->date) == "26" ? 'selected' : '' }} value="26">26</option>
                                                <option {{ ($dt->date) == "27" ? 'selected' : '' }} value="27">27</option>
                                                <option {{ ($dt->date) == "28" ? 'selected' : '' }} value="28">28</option>
                                                <option {{ ($dt->date) == "29" ? 'selected' : '' }} value="29">29</option>
                                                <option {{ ($dt->date) == "30" ? 'selected' : '' }} value="30">30</option>
                                                <option {{ ($dt->date) == "31" ? 'selected' : '' }} value="31">31</option>
                                            </select>
                                    </td>
                                    <td>
                                        <select class="form-control" name="month" id="month" class="month">
                                                <option>--Select Month--</option>
                                                <option {{ ($dt->month) == "01" ? 'selected' : '' }} value="01">January</option>
                                                <option {{ ($dt->month) == "02" ? 'selected' : '' }} value="02">February </option>
                                                <option {{ ($dt->month) == "03" ? 'selected' : '' }} value="03">March</option>
                                                <option {{ ($dt->month) == "04" ? 'selected' : '' }} value="04">April </option>
                                                <option {{ ($dt->month) == "05" ? 'selected' : '' }} value="05">May</option>
                                                <option {{ ($dt->month) == "06" ? 'selected' : '' }} value="06">June </option>
                                                <option {{ ($dt->month) == "07" ? 'selected' : '' }} value="07">July </option>
                                                <option {{ ($dt->month) == "08" ? 'selected' : '' }} value="08">August </option>
                                                <option {{ ($dt->month) == "09" ? 'selected' : '' }} value="09">September </option>
                                                <option {{ ($dt->month) == "10" ? 'selected' : '' }} value="10">October</option>
                                                <option {{ ($dt->month) == "11" ? 'selected' : '' }} value="11">November</option>
                                                <option {{ ($dt->month) == "12" ? 'selected' : '' }} value="12">December</option>
                                                
                                            </select>
                                    </td>
                                    <td><a id="{{$dt->id}}" category_name="{{$dt->category_name}}" category_id="{{$dt->category_id}}" href="javascript:" class="btn btn-info btn-sm edit"> <i class='fa fa-save'></i> Submit </a></td>
                                  </tr>
                                  @endforeach

                                </table>
                                
                           </div>

                          
                            <div class="form-group row">
                                <div class="col-md-12 sErr"></div>
                            </div>

                            <!-- <div class="form-group row DqType mb-3 dSubmit">
                                <label class="col-md-4"></label>
                                <div class="col-md-8">
                                    <input type="submit" class="btn btn-primary btn-sm btn-block data_sub" value="Save">
                                </div>
                            </div> -->
                       <!--  </form> -->
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
      var date = $(this).parents("tr").find('#date').find(":selected").val();
      var month = $(this).parents("tr").find('#month').find(":selected").val();
      var id = $(this).attr("id");
      var category_id = $(this).attr("category_id");
      var category_name = $(this).attr("category_name");
      console.log(date)
      console.log(id)
      $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "{{ url('daily-seuirm-date-sate-for-inyonei-geulah') }}",
            method: "post",
            data: {
                id: id,
                date: date,
                month: month,
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
                            window.location = "{{ url('set-daily-shurim-date') }}"+"/"+category_id+"/"+category_name;
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



