
<?php $__env->startSection('content'); ?>
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
                                <a href="<?php echo e(url('all-file-list')); ?>" class="btn btn-primary float-right">Show File List</a>
                            </div>
                       </div>
                    </div>
                    <?php if(session()->has('success')): ?>
                      <div class="alert alert-success">
                        <?php echo e(session()->get('success')); ?>

                      </div>
                    <?php elseif(session()->has('error')): ?>
                      <div class="alert alert-danger">
                        <?php echo e(session()->get('error')); ?>

                      </div>
                    <?php endif; ?>

                     <?php if($errors->any()): ?>
                     <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                         <div class="alert alert-danger"><?php echo e($error); ?></div>
                     <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                     <?php endif; ?>
                    <div class="card-body">
                        <form method="post" action="<?php echo e(url('save-kol-rabeinu-file')); ?>" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                              <input type="hidden" class="form-control" id="main_cat_id" name="main_cat_id" value="<?php echo e($main_cat->id); ?>">
                            <div class="row justify-content-between mb-4">
                                <div class="col-md-6">
                                    <div class="form-group row nShow mb-1">
                                        <label class="col-md-4">Select Category</label>
                                        <div class="col-md-7">
                                            <select class="form-control" name="category_id" id="category_id">
                                                <option>--Select--</option>
                                                <?php $__currentLoopData = $category; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($val->id); ?>"><?php echo e($val->category_name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 ">
                                    <div class="form-group row nShow mb-1 subcategory">
                                        <label class="col-md-4">Select SubCategory</label>
                                        <div class="col-md-7">
                                            <select class="form-control" name="subcategory_id" id="subcategory_id">
                                                
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="year_month row justify-content-between mb-4 d-none">
                              
                                
                                <div class="col-md-6">
                                    <div class="form-group row nShow mb-1">
                                        <label class="col-md-4">Select Year</label>
                                        <div class="col-md-7">
                                            <select class="form-control" name="year" id="year">
                                                <option>--Select--</option>
                                                <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($val->year); ?>"><?php echo e($val->year); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                               
                                <div class="col-md-6">
                                    <div class="form-group row nShow mb-1">
                                        <label class="col-md-4">Select Month</label>
                                        <div class="col-md-7">
                                            <select class="form-control" name="month" id="month">
                                                <option>--Select--</option>
                                                <?php $__currentLoopData = $months; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($val->month); ?>"><?php echo e($val->month); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                         

                           <div class="row justify-content-between mb-4">
                                <div class="col-md-6"> 
                                     <div class="event form-group row nShow mb-1 d-none">
                                        <label class="col-md-4">Select Event</label>
                                        <div class="col-md-7">
                                            <select class="form-control" name="event" id="event">
                                                <option>--Select--</option>
                                                <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($val->id); ?>"><?php echo e($val->event_name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6"> 
                                    <div id="for-title" class="form-group row mb-1">
                                        <label class="col-md-4">File Title</label>
                                        <div class="col-md-7">
                                            <input type="text" name="title" class="form-control" id="title">
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

<?php $__env->stopSection(); ?>

<?php $__env->startSection("styles"); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection("scripts"); ?>
<script type="text/javascript">
    $(document).ready(function () {

        
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

        $('#category_id').on('change', function () {
            var cat = $('#category_id :selected').text();
            console.log(cat)
            if (cat == 'Sichos kodesh') {
               $(".subcategory").addClass("d-none");
                $(".year_month").removeClass("d-none");
                $(".event").removeClass("d-none");
            }
            else if(cat == 'Mammar'){
                $(".year_month").removeClass("d-none");
                $(".subcategory").addClass("d-none");
                $(".event").addClass("d-none");
            }
            else{
                 $(".year_month").addClass("d-none");
                 $(".event").addClass("d-none");
                 $(".subcategory").removeClass("d-none");
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
<?php $__env->stopSection(); ?>




<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\xampp\htdocs\laravel\admin\resources\views/home/kol_rabeinu_file_upload.blade.php ENDPATH**/ ?>