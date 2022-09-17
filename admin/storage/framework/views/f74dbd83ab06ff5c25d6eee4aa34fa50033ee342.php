
<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="col-md-12 pd0">
        <div class="row justify-content-center">
            <div class="col-md-6 pd0 float-left">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4><i class="fa fa-list"></i> Create Speaker</h4>
                        
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
                        <form method="post" action="<?php echo e(url('save-speaker')); ?>" enctype="multipart/form-data" id="form">
                        <?php echo csrf_field(); ?>
                            <div class="form-group row nShow mb-1">
                                <label class="col-md-4">Speaker Name</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="speaker_name" placeholder="Speaker Name" id="speaker_name">
                                    <input type="hidden" class="form-control" id="speaker_id" name="speaker_id">
                                    <span class="text-danger error-text speaker_name_error"></span>
                                </div>
                            </div>
                            <div class="form-group row nShow mb-1">
                                <label class="col-md-4">Short Description</label>
                                <div class="col-md-8">
                                    <textarea name="short_description" id="short_description" class="form-control"></textarea>
                                    <span class="text-danger error-text short_description_error"></span>
                                </div>
                            </div>
                            <div class="form-group row nShow mb-1">
                                <label class="col-md-4">Speaker Image</label>
                                <div class="col-md-8">
                                    <input type="file" name="speaker_image" class="form-control">
                                    <span class="text-danger error-text speaker_image_error"></span>
                                </div>
                            </div>
                            <div class="form-group row nShow mb-1">
                                <label class="col-md-4"></label>
                                <div class="col-md-8 image-holder"></div>
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
                          <th>Speaker Name</th>
                          <th>Image</th>
                          <th>Action</th>
                      </tr>
                      </thead>
                        <tbody id="allspeaker">
                             
                     
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

        $('#form').on('submit', function(e){
            e.preventDefault();
            var form = this;

            $.ajax({
                url:$(form).attr('action'),
                method:$(form).attr('method'),
                data:new FormData(form),
                processData:false,
                dataType:'json',
                contentType:false,
                beforeSend:function(){
                    $(form).find('span.error-text').text('');
                },
                success:function(data){
                    if(data.code==0){
                        $.each(data.error, function(prefix, val){
                            $(form).find('span.'+prefix+'_error').text(val[0]);
                        });
                    }else{
                        $(form)[0].reset();
                       speakerList()
                    }
                },
                error: function(err, txt, sts){
                   console.log(err);
                   console.log(txt);
                   console.log(sts);
                }
            });

        });
        speakerList()
        //all speaker list
        function speakerList() {
            $.get('<?php echo e(route("speaker-list")); ?>',{},function(data){
                $('#allspeaker').html(data.result);

            }, 'json');
        }
     });  

    $('input[type="file"][name="speaker_image"]').on('change', function(){
        var image_path = $(this)[0].value;
        var image_holder = $('.image-holder');
        var currentImagePath = $(this).data('value');
        var extension = image_path.substring(image_path.lastIndexOf('.')+1).toLowerCase();
        if (extension == 'jpg' || extension == 'jpeg' || extension == 'png') {
            if (typeof(FileReader) != 'undefined') {
                image_holder.empty();
                var reader = new FileReader();
                reader.onload = function(e){
                    $('<img/>', {'src':e.target.result, 'class':'img-fluid', 'style':'max-width:100px; margin-bottom:10px; margin-top:4px'}).appendTo(image_holder);
                }
                image_holder.show();
                reader.readAsDataURL($(this)[0].files[0]);
            }else{
                $(image_holder).html('This browser not support FileReader');
            }
        }else{
            $(image_holder).html(currentImagePath);
        }

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
      var name = $(this).parents("tr").find(".name").text();
      var id = $(this).attr("id");
      $("#name").val(name);
      $("#category_id").val(id);
      $(".data_sub").val("Update");
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\xampp\htdocs\laravel\admin\resources\views/home/create_speaker.blade.php ENDPATH**/ ?>