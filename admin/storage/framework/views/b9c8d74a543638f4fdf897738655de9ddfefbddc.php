
<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="col-md-12 pd0">
        <div class="row justify-content-center">
            <div class="col-md-12 pd0 float-left mb-4">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4><i class="fa fa-list"></i> Uploded Video File List</h4>
                    </div>                
                </div>
            </div>

          
            <div class="col-md-12 box-shadow pd50 rds5">
              <table class="table table-bordered dataTable">
                  <thead class="thead-light">
                      <tr>
                          <th>SL</th> 
                          <th>Category</th>
                          <th>Type Name</th>
                          <th>Subcategory</th>
                          <th>Content</th>
                          <th>Sub Content</th>
                          <th>Lecture</th>
                          <th>Speakes</th>
                          <th>Title</th>
                          <th>Video</th>
                      </tr>
                      </thead>
                        <tbody>
                            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $dt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($key+1); ?></td>
                                <td class="name"><?php echo e($dt->name); ?></td>
                                <td class="type_name"><?php echo e($dt->type_name); ?></td>
                                <td class="subcategory_name"><?php echo e($dt->subcategory_name); ?></td>
                                <td class="content_name"><?php echo e($dt->content_name); ?></td>
                                <td class="subcontent_name"><?php echo e($dt->subcontent_name); ?></td>
                                <td class="lecture_name"><?php echo e($dt->lecture_name); ?></td>
                                <td class="speaker_name"><?php echo e($dt->speaker_name); ?></td>
                                <td class="title"><?php echo e($dt->title); ?></td>
                                <?php if($dt->file_link_type == 2): ?>
                                <td class="video"><video width="100px" height="80" muted><source src="<?php echo e($dt->video_link); ?>" type="video/mp4"></video></td>
                                <?php else: ?> 
                                <td class="video"><video width="100px" height="80" muted><source src="<?php echo e(asset('/storage/video/'.$dt->video)); ?>" type="video/mp4"></video></td>   
                                <?php endif; ?>    
                                
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

<?php $__env->stopSection(); ?>




<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\xamp\htdocs\laravel\admin\resources\views/home/file_list.blade.php ENDPATH**/ ?>