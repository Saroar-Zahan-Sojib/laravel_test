
<?php $__env->startSection('content'); ?>
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
                                <!-- <a href="<?php echo e(url('all-file-list')); ?>" class="btn btn-primary float-right">Show File List</a> -->
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
                        <!-- <form method="post" action="<?php echo e(url('save-farbrengen-file')); ?>" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?> -->
                              
                            <div class="row justify-content-between mb-4">
                                <table>
                                  <tr>
                                    <th>Category Name</th>
                                    <th>Action</th>
                                  </tr>
                                  <?php $__currentLoopData = $category; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                  <tr>
                                    <td><?php echo e($dt->category_name); ?></td>
                                    <td><a href="set-feature-of-kol-rabeinu/<?php echo e($dt->id); ?>" class="btn btn-primary">Set feature</a> </td>
                                  </tr>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                  
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




<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\xampp\htdocs\laravel\admin\resources\views/home/kol_rabeinu_feature_add.blade.php ENDPATH**/ ?>