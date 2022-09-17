
<?php $__env->startSection('content'); ?>

<section class="ftco-section">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-12 col-lg-7">
				<div class="login-wrap card">
					<div class="sErr text-center"></div>
					<form action="#" class="signin-form d-md-flex">
						<div class="half p-4 py-md-5">
				      		<div class="w-100">
				      			<h3 class="mb-4">Registration</h3>
				      		</div>
				      		<div class="form-group mt-3">
				      			<label class="label" for="name">Full Name <span class="text-red">*</span></label>
				      			<input type="text" class="form-control" id="name" placeholder="Full Name" required>
				      		</div>
				      		<div class="form-group">
				      			<label class="label" for="email">Email <span class="text-red">*</span></label>
				      			<input type="email" class="form-control" id="email" placeholder="Email" required>
				      		</div>
				      		<div class="form-group">
				      			<label class="label" for="phone">Phone <span class="text-red">*</span></label>
				      			<input type="text" class="form-control" id="phone" placeholder="Phone" required>
				      		</div>
				      		
				            <div class="form-group">
				            	<label class="label" for="password">Password <span class="text-red">*</span></label>
				              <input id="password-field" type="password" class="form-control" placeholder="Password" required>
				              <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
				            </div>
				            <div class="form-group mt-3">
				      			<label class="label" for="name"></label>
				      			Already have an account? <a href="<?php echo e(url('admin/login')); ?>" style="color: #3490dc !important;"> Login</a>
				      		</div>
			            </div>
						<div class="half p-4 py-md-5 bg-primary">
				            <div class="form-group">
				            	<button type="button" class=" data_submit form-control btn btn-secondary rounded submit px-3">Submit</button>
				            </div>
				            <div class="form-group d-md-flex">
				            	<div class="w-50 text-left">
						            <label class="checkbox-wrap checkbox-primary mb-0">Remember Me
									 <input type="checkbox" checked>
									 <span class="checkmark"></span>
									</label>
								</div>
								<div class="w-50 text-md-right">
									<a href="#">Forgot Password</a>
								</div>
				            </div>
				            <p class="w-100 text-center" style="color:white;">&mdash; Or Sign In With &mdash;</p>
					        <div class="w-100">
								<p class="social-media d-flex justify-content-center">
									<a href="#" class="social-icon d-flex align-items-center justify-content-center"><span class="fa fa-facebook"></span></a>
									<a href="#" class="social-icon d-flex align-items-center justify-content-center"><span class="fa fa-twitter"></span></a>
								</p>
							</div>
				        </div>
                    </form>
                </div>
             </div>
		</div>
	</div>
</section>

<?php $__env->stopSection(); ?>
<?php $__env->startSection("scripts"); ?>

	<script type="text/javascript">

	$(document).on("click", ".data_submit", function(){
	    var ts = $(this);
	    var name = $("#name").val();
	    var email = $("#email").val();
	    var phone = $("#phone").val();
	    var password = $("#password-field").val();

	    console.log(name);
	    console.log(email);

	    // if(!username && !password){
	    //     required();
	    //     return false;
	    // }

	    $.ajaxSetup({
	        headers: {
	            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	        }
	    });

	    $.ajax({
	        url: "<?php echo e(url('registration-save')); ?>",
	        method: "post",
	        data: {
	            name: name,
	            email: email,
	            phone: phone,
	            password: password,
	            "_token": "<?php echo e(csrf_token()); ?>"
	        },
	        beforeSend: function(){
	            ts.removeClass("btn-secondary").addClass("btn-warning").html("<i class='fa fa-spinner fa-spin'></i>")
	        },
	        success: function(rsp){
	            console.log(rsp);
	            if(rsp.error == false){
	                $(".card").html("<div class='alert alert-success text-center'>"+rsp.msg+"</div>");
	                $("input").val("");
	                ts.removeClass("btn-warning").addClass("btn-secondary").html("<i class='fa fa-save'></i> Submit");
	                setTimeout(function(){
	                        window.location = "<?php echo e(url('admin/dashboard')); ?>";
	                }, 2000);
	            } else {
	                $(".sErr").html("<div class='alert alert-danger'>"+rsp.msg+"</div>");
	                ts.removeClass("btn-warning").addClass("btn-secondary").html("<i class='fa fa-save'></i> Submit");
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
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\xamp\htdocs\laravel\admin\resources\views/user/registration.blade.php ENDPATH**/ ?>