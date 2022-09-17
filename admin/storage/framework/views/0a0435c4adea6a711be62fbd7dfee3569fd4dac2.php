<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
  <title>Heichel Menachem Admin</title>
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
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.2.0/css/font-awesome.min.css">
  <?php echo $__env->yieldContent("styles"); ?>
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
          <li class="nav-item">
            <a class="nav-link" href="<?php echo e(url('create-category')); ?>">
              <i class="mdi mdi-pencil-box menu-icon"></i>
              <span class="menu-title">Category</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo e(url('create-category-type')); ?>">
              <i class="mdi mdi-window-restore menu-icon"></i>
              <span class="menu-title">Category Type</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo e(url('create-subcatagory')); ?>">
              <i class="mdi mdi-window-restore menu-icon"></i>
              <span class="menu-title">Subcategory</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo e(url('create-content')); ?>">
              <i class="mdi mdi-window-restore menu-icon"></i>
              <span class="menu-title">Content</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo e(url('create-sub-content')); ?>">
              <i class="mdi mdi-window-restore menu-icon"></i>
              <span class="menu-title">Sub Content</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo e(url('create-lecture')); ?>">
              <i class="mdi mdi-window-restore menu-icon"></i>
              <span class="menu-title">lecture</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo e(url('create-speaker')); ?>">
              <i class="mdi mdi-window-restore menu-icon"></i>
              <span class="menu-title">Speaker</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="<?php echo e(url('file-upload')); ?>">
              <i class="mdi mdi-window-restore menu-icon"></i>
              <span class="menu-title">File Upload</span>
            </a>
          </li>
        </ul>
      </nav>
      <?php echo $__env->yieldContent('content'); ?>
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

  <script src="<?php echo e(asset('admin/js/jquery.cookie.js')); ?>" type="text/javascript"></script>
</body>

</html>
<script type="text/javascript">
   $(function(){
  $(".DataTable").dataTable();

}); 
</script>
<?php echo $__env->yieldContent('scripts'); ?>
<?php /**PATH F:\xamp\htdocs\laravel\admin\resources\views/layouts/master.blade.php ENDPATH**/ ?>