
<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="col-md-12 pd0">
        <div class="row justify-content-center">
            <div class="col-md-8 pd0 float-left">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4><i class="fa fa-list"></i> Add Current Parsha</h4>
                        
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
                            <div class="form-group row nShow mb-1">
                                <label class="col-md-4">Select Parshiyos Type</label>
                                <div class="col-md-8">
                                    <select class="form-control" name="type_id" id="type_id">
                                        <option>--Select--</option>
                                        <?php $__currentLoopData = $type; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($val->id); ?>"><?php echo e($val->type_name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                       
                                    </select>
                                </div>
                            </div>
                            <br>
                            <div class="Bereshit form-group row nShow mb-1 d-none">
                                <label class="col-md-4"></label>
                                <div class="col-md-8">
                                    <ul class="list-group">
                                        <?php $__currentLoopData = $contents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($val->type_name == 'Bereshit'): ?>
                                                <li class="list-group-item"><input type="checkbox" name="current[]" value="<?php echo e($val->id); ?>" <?php echo e(($val->content_id == $val->id ? ' checked' : '')); ?>>  <?php echo e($val->content_name); ?></li>
                                          
                                           <?php endif; ?>
                                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </div>
                            </div>
                            <div class="Shemot form-group row nShow mb-1 d-none">
                                <label class="col-md-4"></label>
                                <div class="col-md-8">
                                    <ul class="list-group">
                                      <?php $__currentLoopData = $contents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($val->type_name == 'Shemot'): ?>
                                                <li class="list-group-item"><input type="checkbox" name="current[]" value="<?php echo e($val->id); ?>"<?php echo e(($val->content_id == $val->id ? ' checked' : '')); ?> >  <?php echo e($val->content_name); ?></li>
                                          
                                           <?php endif; ?>
                                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </div>
                            </div>
                            <div class="Vayikra form-group row nShow mb-1 d-none">
                                <label class="col-md-4"></label>
                                <div class="col-md-8">
                                    <ul class="list-group">
                                     <?php $__currentLoopData = $contents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($val->type_name == 'Vayikra'): ?>
                                                <li class="list-group-item"><input type="checkbox" name="current[]" value="<?php echo e($val->id); ?>" <?php echo e(($val->content_id == $val->id ? ' checked' : '')); ?>>  <?php echo e($val->content_name); ?></li>
                                          
                                           <?php endif; ?>
                                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>                                    </ul>
                                </div>
                            </div>
                            <div class="Bamidbar form-group row nShow mb-1 d-none">
                                <label class="col-md-4"></label>
                                <div class="col-md-8">
                                    <ul class="list-group">
                                      <?php $__currentLoopData = $contents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($val->type_name == 'Bamidbar'): ?>
                                                <li class="list-group-item"><input type="checkbox" name="current[]" value="<?php echo e($val->id); ?>" <?php echo e(($val->content_id == $val->id ? ' checked' : '')); ?>>  <?php echo e($val->content_name); ?></li>
                                          
                                           <?php endif; ?>
                                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </div>
                            </div>
                            <div class="Devarim form-group row nShow mb-1 d-none">
                                <label class="col-md-4"></label>
                                <div class="col-md-8">
                                    <ul class="list-group">
                                      <?php $__currentLoopData = $contents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($val->type_name == 'Devarim'): ?>
                                                <li class="list-group-item"><input type="checkbox" name="current[]" value="<?php echo e($val->id); ?>" <?php echo e(($val->content_id == $val->id ? ' checked' : '')); ?>>  <?php echo e($val->content_name); ?></li>
                                          
                                           <?php endif; ?>
                                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>                                    </ul>
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
        $('#type_id').on('change', function () {
            var type = $('#type_id :selected').text();
           
            if (type == 'Bereshit') {
                $(".Bereshit").removeClass("d-none"); 
                $(".Shemot").addClass("d-none");
                $(".Vayikra").addClass("d-none");
                $(".Bamidbar").addClass("d-none");
                $(".Devarim").addClass("d-none");
                
            }
            else if(type == 'Shemot') {
                 $(".Bereshit").addClass("d-none"); 
                $(".Shemot").removeClass("d-none");
                $(".Vayikra").addClass("d-none");
                $(".Bamidbar").addClass("d-none");
                $(".Devarim").addClass("d-none");
            }
            else if(type == 'Vayikra') {
                 $(".Bereshit").addClass("d-none"); 
                $(".Shemot").addClass("d-none");
                $(".Vayikra").removeClass("d-none");
                $(".Bamidbar").addClass("d-none");
                $(".Devarim").addClass("d-none");
            }
            else if(type == 'Bamidbar') {
                 $(".Bereshit").addClass("d-none"); 
                $(".Shemot").addClass("d-none");
                $(".Vayikra").addClass("d-none");
                $(".Bamidbar").removeClass("d-none");
                $(".Devarim").addClass("d-none");
            }
            else if(type == 'Devarim') {
                 $(".Bereshit").addClass("d-none"); 
                $(".Shemot").addClass("d-none");
                $(".Vayikra").addClass("d-none");
                $(".Bamidbar").addClass("d-none");
                $(".Devarim").removeClass("d-none");
            }
        });

     });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\xampp\htdocs\laravel\admin\resources\views/home/add_current_parsha.blade.php ENDPATH**/ ?>