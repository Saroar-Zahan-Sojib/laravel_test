
<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="col-md-12 pd0">
        <div class="row justify-content-center">
            <div class="col-md-6 pd0 float-left">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4><i class="fa fa-list"></i> Write Your Category Name</h4>
                        
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
                        <form method="post" action="<?php echo e(url('save-nigunim-album')); ?>" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                            <div class="form-group row nShow mb-1">
                                <label class="col-md-4">Select Category</label>
                                <div class="col-md-8">
                                    <select class="form-control" name="nigunim_category_id" id="nigunim_category_id" readonly>
                                        <option>--Select--</option>
                                        <?php $__currentLoopData = $nigcat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($val->id); ?>" ><?php echo e($val->nigunim_category_name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                  
                                    <input type="hidden" class="form-control" id="id" name="id">
                                    <input type="hidden" value="<?php echo e($nig->id); ?>" class="form-control" id="nigunim_id" name="nigunim_id">

                                </div>
                            </div>
                             <div class="form-group row nShow mb-1">
                                <label class="col-md-4">Albam Name</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="albam_name" placeholder="Albam Name" id="albam_name">
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
          
            <div class="col-md-6 box-shadow pd50 rds5">
              <table class="table table-bordered dataTable">
                  <thead class="thead-light">
                      <tr>
                          <th>SL</th> 
                          <th>Category Name</th>
                          <th>Albam Name</th>
                          <th>Action</th>
                      </tr>
                      </thead>
                        <tbody>
                            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $dt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <tr>
                                <td><?php echo e($key+1); ?></td>
                                <td class="nigumin_category_name"><?php echo e($dt->nigunim_category_name); ?></td>
                                <td class="albam_name"><?php echo e($dt->albam_name); ?></td>
                                <td style="white-space: nowrap;">
                                    <a id="<?php echo e($dt->id); ?>" nigunim_category_id="<?php echo e($dt->nigunim_category_id); ?>" href="javascript:" class="btn btn-info btn-sm edit"> <i class="fa fa-edit"></i> </a> <a id="<?php echo e($dt->id); ?>" onclick="return confirm('Are you sure to delete this Albam?')" href="<?php echo e(url('delete-nigunim-albam/'.$dt->id)); ?>" class="btn btn-danger btn-sm"> <i class="fa fa-trash"></i> </a>
                                </td>
                              </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>    
                     
                        </tbody>
                </table>
            </div>
           
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection("styles"); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection("scripts"); ?>
<script type="text/javascript">
      $(function(){
          tinymceLoad("");

          $(".dataTable").dataTable();
        });
    $(document).on("click", "#data_submit", function(){
        alert("test");
        var ts = $(this);
        var name = $("#name").val();

        console.log(name);

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "<?php echo e(url('save-category')); ?>",
            method: "post",
            data: {
                name: name,
                "_token": "<?php echo e(csrf_token()); ?>"
            },
            beforeSend: function(){
                ts.removeClass("btn-primary").addClass("btn-warning").html("<i class='fa fa-spinner fa-spin'></i>")
            },
            success: function(rsp){
                console.log(rsp);
                if(rsp.error == false){
                    $(".card").html("<div class='alert alert-success text-center'>"+rsp.msg+"</div>");
                    $("input").val("");
                    ts.removeClass("btn-warning").addClass("btn-primary").html("<i class='fa fa-save'></i> Submit");
                    setTimeout(function(){
                            window.location = "<?php echo e(url('admin/dashboard')); ?>";
                    }, 2000);
                } else {
                    $(".sErr").html("<div class='alert alert-danger'>"+rsp.msg+"</div>");
                    ts.removeClass("btn-warning").addClass("btn-primary").html("<i class='fa fa-save'></i> Submit");
                }
            },
            error: function(err, txt, sts){
                console.log(err);
                console.log(txt);
                console.log(sts);
            }
        });
    });

    $(document).on("click", ".edit", function(){
      var albam_name = $(this).parents("tr").find(".albam_name").text();
      var id = $(this).attr("id");
      var nigunim_category_id = $(this).attr("nigunim_category_id");
      $("#albam_name").val(albam_name);
      $("#id").val(id);
      $("#nigunim_category_id").val(nigunim_category_id);
      $(".data_sub").val("Update");
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\xampp\htdocs\laravel\admin\resources\views/home/create_nigunim_album.blade.php ENDPATH**/ ?>