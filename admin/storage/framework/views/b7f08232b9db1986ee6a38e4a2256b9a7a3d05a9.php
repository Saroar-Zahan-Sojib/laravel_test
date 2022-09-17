<?php $__empty_1 = true; $__currentLoopData = $spk; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $dt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
<tr>
    <td><?php echo e($key+1); ?></td>
    <td class="name"><?php echo e($dt->speaker_name); ?></td>
    <td class="type_name"><img src="<?php echo e(asset('files/'.$dt->speaker_image)); ?>" alt="Image" class="d-flex rounded mr-3"></td>
                            
</tr>

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
<code>No Speaker Found</code>
<?php endif; ?><?php /**PATH F:\xampp\htdocs\laravel\admin\resources\views/home/speaker_list.blade.php ENDPATH**/ ?>