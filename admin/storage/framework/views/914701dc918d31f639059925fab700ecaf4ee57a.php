<!doctype html>
<html lang="en">
  <head>
  	<title>Login 06</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	
	<link rel="stylesheet" href="<?php echo e(asset('login/css/style.css')); ?>">
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.2.0/css/font-awesome.min.css">

	</head>
	<body>
	<section class="ftco-section">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-12 col-lg-7">
					<div class="login-wrap card">
						<div class="sErr text-center"></div>
						<form action="#" class="signin-form d-md-flex">
							<div class="half p-4 py-md-5">
			      		<div class="w-100">
			      			<h3 class="mb-4">Sign In</h3>
			      		</div>
			      		<div class="form-group mt-3">
			      			<label class="label" for="name">Username</label>
			      			<input type="text" class="form-control" id="username" placeholder="Username" required>
			      		</div>
		            <div class="form-group">
		            	<label class="label" for="password">Password</label>
		              <input id="password-field" type="password" class="form-control" placeholder="Password" required>
		              <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
		            </div>
		          </div>
							<div class="half p-4 py-md-5 bg-primary">
		            <div class="form-group">
		            	<button type="button" class=" data_submit form-control btn btn-secondary rounded submit px-3">Sign me in now</button>
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

	<script src="<?php echo e(asset('login/js/jquery.min.js')); ?>"></script>
  <script src="<?php echo e(asset('login/js/popper.js')); ?>"></script>
  <script src="<?php echo e(asset('login/js/bootstrap.min.js')); ?>"></script>
  <script src="<?php echo e(asset('login/js/main.js')); ?>"></script>

	</body>
</html>

<script type="text/javascript" src="<?php echo e(url('js/jquery.min.js')); ?>"></script>

<script type="text/javascript">

$(document).on("click", ".data_submit", function(){
    var ts = $(this);
    var username = $("#username").val();
    var password = $("#password-field").val();

    console.log(username);
    console.log(password);

    

    if(!username && !password){
        required();
        return false;
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: "<?php echo e(url('myLogin')); ?>",
        method: "post",
        data: {
            username: username,
            Password: password,
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

<?php /**PATH C:\laragon\www\admin\resources\views/user/login.blade.php ENDPATH**/ ?>