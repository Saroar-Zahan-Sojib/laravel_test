
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
                                    <th>File Title</th>
                                    <th>Set Feature</th>
                                    <th>Action</th>
                                  </tr>
                                  <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                  <tr>
                                    <td><?php echo e($dt->story_category_name); ?></td>
                                    <td><?php echo e($dt->title); ?></td>
                                    <td>
                                        <!-- <input type="radio" <?php echo e(($dt->feature_status=="1")? "checked" : ""); ?>  id="html" name="feature_status" class="feature_status" value="1"> -->
                                        <input name="feature_status" <?php echo e(($dt->feature_status=="1")? "checked" : ""); ?> id="ad_Checkbox3" class="ads_Checkbox" type="checkbox" value="<?php echo e($dt->id); ?>" />
                                    </td>
                                    <td><a id="<?php echo e($dt->id); ?>" category_id="<?php echo e($dt->category_id); ?>" href="javascript:" class="btn btn-info btn-sm edit"> <i class='fa fa-save'></i> Submit </a></td>
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
     $(document).on("click", ".edit", function(){
      var status = $(this).parents("tr").find("input[name='feature_status']:checked").val();;
      var category_id = $(this).attr("category_id");
      var id = $(this).attr("id");

      console.log(status)
      console.log(id)
      $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "<?php echo e(url('story-feature-update')); ?>",
            method: "post",
            data: {
                id: id,
                status: status,
                category_id: category_id,
                "_token": "<?php echo e(csrf_token()); ?>"
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
                            window.location = "<?php echo e(url('set-feature-story')); ?>"+"/"+category_id;
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
<?php $__env->stopSection(); ?>




<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\xampp\htdocs\laravel\admin\resources\views/home/story_feature_update.blade.php ENDPATH**/ ?>