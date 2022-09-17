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
  
  <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> -->

   
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
              <span class="menu-title">Add Speaker</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo e(url('create-lecture')); ?>">
              <i class="mdi mdi-window-restore menu-icon"></i>
              <span class="menu-title">Section</span>
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
          <li class="nav-item">
            <a class="nav-link" href="<?php echo e(url('feature-file-upload')); ?>">
              <i class="mdi mdi-window-restore menu-icon"></i>
              <span class="menu-title">Feature File Upload</span>
            </a>
          </li>
         <!--  <li class="nav-item">
            <a class="nav-link" href="<?php echo e(url('nigunim')); ?>">
              <i class="mdi mdi-window-restore menu-icon"></i>
              <span class="menu-title">Nigunim</span>
            </a>
          </li> -->
         <!--  <li class="nav-item">
            <a class="nav-link" href="<?php echo e(url('muggidei-shiurim')); ?>">
              <i class="mdi mdi-window-restore menu-icon"></i>
              <span class="menu-title">Maggidei Shiurim</span>
            </a>
          </li> -->



          <li class="nav-item has-treeview">
                <a href="#" class="nav-link" data-toggle="collapse" data-target=".nav-treeview" aria-expanded="false">
                    <i class="mdi mdi-window-restore menu-icon"></i>
                    <span class="menu-title">Nigunim</span>
                </a>
                <ul class="nav nav-treeview" style="display: none;">
                    <li class="nav-item">
                        <a href="<?php echo e(url('create-nigunim-category')); ?>" class="nav-link">
                            <i class="mdi mdi-window-restore menu-icon"></i>
                    <span class="menu-title">Add Category</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(url('create-nigunim-album')); ?>" class="nav-link">
                            <i class="mdi mdi-window-restore menu-icon"></i>
                    <span class="menu-title">Nigunim Album</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(url('create-nigunim-file')); ?>" class="nav-link">
                            <i class="mdi mdi-window-restore menu-icon"></i>
                    <span class="menu-title">Nigunim File </span>
                        </a>
                    </li>
                    
                </ul>
            </li>

            <li class="nav-item has-treeview">
                <a href="#" class="nav-link" data-toggle="collapse" data-target=".nav-treeview" aria-expanded="false">
                    <i class="mdi mdi-window-restore menu-icon"></i>
                    <span class="menu-title">Story</span>
                </a>
                <ul class="nav nav-treeview" style="display: none;">
                    <li class="nav-item">
                        <a href="<?php echo e(url('create-story-category')); ?>" class="nav-link">
                            <i class="mdi mdi-window-restore menu-icon"></i>
                    <span class="menu-title">Add Story Category</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(url('add-main-story-file')); ?>" class="nav-link">
                            <i class="mdi mdi-window-restore menu-icon"></i>
                    <span class="menu-title">Add Story File</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?php echo e(url('add-feature-for-story')); ?>" class="nav-link">
                            <i class="mdi mdi-window-restore menu-icon"></i>
                    <span class="menu-title">Add Feature Story </span>
                        </a>
                    </li>
                    
                    
                </ul>
            </li>

            <li class="nav-item has-treeview">
                <a href="#" class="nav-link" data-toggle="collapse" data-target=".nav-treeview" aria-expanded="false">
                    <i class="mdi mdi-window-restore menu-icon"></i>
                    <span class="menu-title">Farbrengens</span>
                </a>
                <ul class="nav nav-treeview" style="display: none;">
                    <li class="nav-item">
                        <a href="<?php echo e(url('create-farbrengen-month')); ?>" class="nav-link">
                            <i class="mdi mdi-window-restore menu-icon"></i>
                    <span class="menu-title">Add Month</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(url('create-farbrengen-date')); ?>" class="nav-link">
                            <i class="mdi mdi-window-restore menu-icon"></i>
                    <span class="menu-title">Add Date</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?php echo e(url('add-farbrengen-file')); ?>" class="nav-link">
                            <i class="mdi mdi-window-restore menu-icon"></i>
                    <span class="menu-title">Add Farbengens File</span>
                        </a>
                    </li>
                    
                    
                </ul>
            </li>

            <li class="nav-item has-treeview">
                <a href="#" class="nav-link" data-toggle="collapse" data-target=".nav-treeview" aria-expanded="false">
                    <i class="mdi mdi-window-restore menu-icon"></i>
                    <span class="menu-title">Topics</span>
                </a>
                <ul class="nav nav-treeview" style="display: none;">
                    <li class="nav-item">
                        <a href="<?php echo e(url('create-topics-category')); ?>" class="nav-link">
                            <i class="mdi mdi-window-restore menu-icon"></i>
                    <span class="menu-title">Add Category</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?php echo e(url('create-parshioys-type')); ?>" class="nav-link">
                            <i class="mdi mdi-window-restore menu-icon"></i>
                    <span class="menu-title">Add Parshioys Type</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(url('create-parshioys-content')); ?>" class="nav-link">
                            <i class="mdi mdi-window-restore menu-icon"></i>
                    <span class="menu-title">Add Parshioys Content</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(url('create-parshioys-group')); ?>" class="nav-link">
                            <i class="mdi mdi-window-restore menu-icon"></i>
                    <span class="menu-title">Add Parshioys Group</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?php echo e(url('parshioys-file-upload')); ?>" class="nav-link">
                            <i class="mdi mdi-window-restore menu-icon"></i>
                    <span class="menu-title">Parshioys File Upload</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?php echo e(url('yomim-tovim-holiday')); ?>" class="nav-link">
                            <i class="mdi mdi-window-restore menu-icon"></i>
                    <span class="menu-title">Add Yomim Tovim Holiday </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(url('add-current-parsha')); ?>" class="nav-link">
                            <i class="mdi mdi-window-restore menu-icon"></i>
                    <span class="menu-title">Add Current Parsha </span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?php echo e(url('set-upcoming-holiday')); ?>" class="nav-link">
                            <i class="mdi mdi-window-restore menu-icon"></i>
                    <span class="menu-title">Set Upcoming Holiday </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(url('add-parshas-hashavua-feature')); ?>" class="nav-link">
                            <i class="mdi mdi-window-restore menu-icon"></i>
                    <span class="menu-title">Parshas Hashavua Feature </span>
                        </a>
                    </li>


                </ul>
            </li>

            <li class="nav-item has-treeview">
                <a href="#" class="nav-link" data-toggle="collapse" data-target=".nav-treeview" aria-expanded="false">
                    <i class="mdi mdi-window-restore menu-icon"></i>
                    <span class="menu-title">Kol Rabeinu</span>
                </a>
                <ul class="nav nav-treeview" style="display: none;">
                    <li class="nav-item">
                        <a href="<?php echo e(url('kol-rabeinu')); ?>" class="nav-link">
                            <i class="mdi mdi-window-restore menu-icon"></i>
                    <span class="menu-title">Add Category</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?php echo e(url('month')); ?>" class="nav-link">
                            <i class="mdi mdi-window-restore menu-icon"></i>
                    <span class="menu-title">Add Month</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(url('year')); ?>" class="nav-link">
                            <i class="mdi mdi-window-restore menu-icon"></i>
                    <span class="menu-title">Add Year</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(url('create-event')); ?>" class="nav-link">
                            <i class="mdi mdi-window-restore menu-icon"></i>
                    <span class="menu-title">Add Event</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?php echo e(url('niggun-category')); ?>" class="nav-link">
                            <i class="mdi mdi-window-restore menu-icon"></i>
                    <span class="menu-title">Add Niggun Category</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?php echo e(url('create-stories-category')); ?>" class="nav-link">
                            <i class="mdi mdi-window-restore menu-icon"></i>
                    <span class="menu-title">Add Stories Category </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(url('topics-of-sichos')); ?>" class="nav-link">
                            <i class="mdi mdi-window-restore menu-icon"></i>
                    <span class="menu-title">Add topics of sichos </span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?php echo e(url('kol-rabeinu-file-upload')); ?>" class="nav-link">
                            <i class="mdi mdi-window-restore menu-icon"></i>
                    <span class="menu-title">Kol Rabeinu File Upload </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(url('add-feature-for-kol-rabeinu')); ?>" class="nav-link">
                            <i class="mdi mdi-window-restore menu-icon"></i>
                    <span class="menu-title">Add Feature Kol Rabeinu </span>
                        </a>
                    </li>


                </ul>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="<?php echo e(url('daily-shiurim')); ?>">
              <i class="mdi mdi-window-restore menu-icon"></i>
              <span class="menu-title">Daily Shiurim</span>
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

  const hasTree  = $('.has-treeview');

$.each(hasTree,function(){
    $(this).click(function(){
        $(this).children('.nav-treeview').slideToggle();
    });
});

}); 
</script>
<?php echo $__env->yieldContent('scripts'); ?>
<?php /**PATH F:\xampp\htdocs\laravel\admin\resources\views/layouts/master.blade.php ENDPATH**/ ?>