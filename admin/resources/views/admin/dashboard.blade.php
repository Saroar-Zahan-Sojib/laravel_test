@extends('layouts.master')
@section('content')
<!-- partial -->
<div class="main-panel">
  <div class="content-wrapper">
    
    <div class="row">
      <div class="col-md-12 grid-margin">
        <div class="d-flex justify-content-between flex-wrap">
          <div class="d-flex align-items-end flex-wrap">
            <div class="me-md-3 me-xl-5">
              <h2>Welcome back,</h2>
              <p class="mb-md-0">Your analytics dashboard template.</p>
            </div>
            <div class="d-flex">
              <i class="mdi mdi-home text-muted hover-cursor"></i>
              <p class="text-muted mb-0 hover-cursor">&nbsp;/&nbsp;Dashboard&nbsp;/&nbsp;</p>
              <p class="text-primary mb-0 hover-cursor">Analytics</p>
            </div>
          </div>
        
        </div>
      </div>
    </div>
    
   
    <div class="row">
      <div class="col-md-12 stretch-card">
        <div class="card">
          <div class="card-body">
            <p class="card-title">Recent Registered User</p>
            <div class="table-responsive">
              <table id="recent-purchases-listing" class="table DataTable">
                <thead>
                  <tr>
                    <th>SL</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Name</th>
                      <th>Date</th>
                  </tr>
                </thead>
                <tbody>
                
                  
                </tbody>
              </table>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- content-wrapper ends -->
  <!-- partial:partials/_footer.html -->
  <footer class="footer">
  <div class="d-sm-flex justify-content-center justify-content-sm-between">
    <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © <a href="https://www.bootstrapdash.com/" target="_blank">bootstrapdash.com </a>2021</span>
    <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Only the best <a href="https://www.bootstrapdash.com/" target="_blank"> Bootstrap dashboard  </a> templates</span>
  </div>
  </footer>
  <!-- partial -->
</div>
<!-- main-panel ends -->
@endsection     
@section('script')
@endsection 
   

