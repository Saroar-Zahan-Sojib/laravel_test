
<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="col-md-12 pd0">
        <div class="row justify-content-center">
            <div class="col-md-10 pd0 float-left">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4><i class="fa fa-list"></i> Set Holiday date</h4>
                        
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
                        <form method="post" action="<?php echo e(url('save-current-parsha')); ?>" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                            <input type="hidden" class="form-control" id="main_cat_id" name="main_cat_id" value="<?php echo e($main_cat->id); ?>">
                            <input type="hidden" class="form-control" id="cat_id" name="cat_id" value="<?php echo e($cat->id); ?>">
                            
                            
                            <div class="Bereshit form-group row nShow mb-1">
                                <label class="col-md-1"></label>
                                <div class="col-md-10">
                                
                                    <table class="table table-bordered dataTable">
                                      <thead class="thead-light">
                                       
                                            <tbody>
                                                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                  <tr>
                                                    <td style="white-space: nowrap;" class="holiday_id"><?php echo e($val->holiday_name); ?></td>
                                                    <td class="date_form"><input type="text" name="date_form" id="date_from" class="form-control" placeholder="YY-MM-DD" value="<?php echo e($val->date_from); ?>"></td>
                                                    <td class="date_to"><input type="text" name="date_to" id="date_to" class="form-control" placeholder="YY-MM-DD" value="<?php echo e($val->date_to); ?>">
                                                    </td>
                                                    <td><a id="<?php echo e($val->id); ?>" href="javascript:" class="btn btn-info btn-sm edit"> <i class='fa fa-save'></i> Submit </a></td>
                                                  </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>    
                                         
                                            </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <div class="col-md-12 sErr"></div>
                            </div>

                           <!--  <div class="form-group row DqType mb-3 dSubmit">
                                <label class="col-md-4"></label>
                                <div class="col-md-8">
                                    <input type="submit" class="btn btn-primary btn-sm btn-block data_sub" value="Save">
                                </div>
                            </div> -->
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
     $(document).on("click", ".edit", function(){
      var name = $(this).parents("tr").find(".holiday_id").text();
      var date_from = $(this).parents("tr").find("#date_from").val();
      var date_to = $(this).parents("tr").find("#date_to").val()
      var id = $(this).attr("id");
      var cat_id = $(this).attr("cat_id");
      console.log(name)
      console.log(date_from)
      console.log(date_to)
      $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "<?php echo e(url('save-upcoming-holiday')); ?>",
            method: "post",
            data: {
                id: id,
                date_from: date_from,
                date_to: date_to,
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
                            window.location = "<?php echo e(url('set-upcoming-holiday')); ?>";
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
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\xampp\htdocs\laravel\admin\resources\views/home/upcoming_holiday.blade.php ENDPATH**/ ?>