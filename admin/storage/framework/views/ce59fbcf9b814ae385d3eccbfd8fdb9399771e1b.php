<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Majestic Admin</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="<?php echo e(asset('admin/vendors/mdi/css/materialdesignicons.min.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(asset('admin/vendors/base/vendor.bundle.base.css')); ?>">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <link rel="stylesheet" href="<?php echo e(asset('admin/vendors/datatables.net-bs4/dataTables.bootstrap4.css')); ?>">
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="<?php echo e(asset('admin/css/style.css')); ?>">
  <!-- endinject -->
  <link rel="shortcut icon" href="<?php echo e(asset('admin/images/favicon.png')); ?>" />
</head>
<body>
  <div class="container-scroller">
    
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="navbar-brand-wrapper d-flex justify-content-center">
        <div class="navbar-brand-inner-wrapper d-flex justify-content-between align-items-center w-100">  
          <a class="navbar-brand brand-logo" href="index.html"><img src="images/logo.svg" alt="logo"/></a>
          <a class="navbar-brand brand-logo-mini" href="index.html"><img src="images/logo-mini.svg" alt="logo"/></a>
          <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-sort-variant"></span>
          </button>
        </div>  
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <ul class="navbar-nav mr-lg-4 w-100">
          <li class="nav-item nav-search d-none d-lg-block w-100">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text" id="search">
                  <i class="mdi mdi-magnify"></i>
                </span>
              </div>
              <input type="text" class="form-control" placeholder="Search now" aria-label="search" aria-describedby="search">
            </div>
          </li>
        </ul>
        <ul class="navbar-nav navbar-nav-right">
          
         
          <li class="nav-item nav-profile dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" id="profileDropdown">
              
              <span class="nav-profile-name">Hi <?php echo e(Auth::user()->name); ?></span>
            </a>
            
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
             
              <a class="dropdown-item"  href="<?php echo e(url('logout')); ?>">
                <i class="mdi mdi-logout text-primary"></i>
                Logout
              </a>
            </div>
          </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span class="mdi mdi-menu"></span>
        </button>
      </div>
    </nav>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_sidebar.html -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item">
            <a class="nav-link" href="index.html">
              <i class="mdi mdi-home menu-icon"></i>
              <span class="menu-title">Dashboard</span>
            </a>
          </li>
        </ul>
      </nav>
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
                        <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$dt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($key+1); ?></td>
                            <td><?php echo e($dt->name); ?></td>
                            <td><?php echo e($dt->email); ?></td>
                            <td><?php echo e($dt->phone); ?></td>
                            <td><?php echo e(date('Y-m-d', strtotime($dt->created_at))); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        
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
          <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright ?? <a href="https://www.bootstrapdash.com/" target="_blank">bootstrapdash.com </a>2021</span>
          <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Only the best <a href="https://www.bootstrapdash.com/" target="_blank"> Bootstrap dashboard  </a> templates</span>
        </div>
        </footer>
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->

  <!-- plugins:js -->
  <script src="<?php echo e(asset('admin/vendors/base/vendor.bundle.base.js')); ?>"></script>
  <!-- endinject -->
  <!-- Plugin js for this page-->
  <script src="<?php echo e(asset('admin/vendors/chart.js/Chart.min.js')); ?>"></script>
  <script src="<?php echo e(asset('admin/vendors/datatables.net/jquery.dataTables.js')); ?>"></script>
  <script src="<?php echo e(asset('admin/vendors/datatables.net-bs4/dataTables.bootstrap4.js')); ?>"></script>
  <!-- End plugin js for this page-->
  <!-- inject:js -->
  <script src="<?php echo e(asset('admin/js/off-canvas.js')); ?>"></script>
  <script src="<?php echo e(asset('admin/js/hoverable-collapse.js')); ?>"></script>
  <script src="<?php echo e(asset('admin/js/template.js')); ?>"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <script src="<?php echo e(asset('admin/js/dashboard.js')); ?>"></script>
  <script src="<?php echo e(asset('admin/js/data-table.js')); ?>"></script>
  <script src="<?php echo e(asset('admin/js/jquery.dataTables.js')); ?>"></script>
  <script src="<?php echo e(asset('admin/js/dataTables.bootstrap4.js')); ?>"></script>
  <!-- End custom js for this page-->

  <script src="<?php echo e(asset('js/jquery.cookie.js')); ?>" type="text/javascript"></script>
</body>

</html>
<script type="text/javascript">
   $(function(){
  $(".DataTable").dataTable();

}); 
</script>

<?php /**PATH C:\laragon\www\admin\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>