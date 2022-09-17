<!doctype html>
<html lang="en">
  <head>
  	<title>Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	
	<link rel="stylesheet" href="<?php echo e(asset('login/css/style.css')); ?>">
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.2.0/css/font-awesome.min.css">
	<style type="text/css">
		.text-red {
    color: #dc3545;
    }
	</style>

	</head>
	<body>
	<?php echo $__env->yieldContent('content'); ?>

	<script src="<?php echo e(asset('login/js/jquery.min.js')); ?>"></script>
  <script src="<?php echo e(asset('login/js/popper.js')); ?>"></script>
  <script src="<?php echo e(asset('login/js/bootstrap.min.js')); ?>"></script>
  <script src="<?php echo e(asset('login/js/main.js')); ?>"></script>

	</body>

	<script type="text/javascript">


		function required(){
		    $(".required").each(function(){
		        if ($(this).val() == "") {
		            if ($(this).next("p").hasClass("err")) {

		            } else {
		                $(this).next("p").html("<span class='text-red'>This field is required</span>");
		            }
		        } else if ($(this).val() != "") {
		            if ($(this).next("p").hasClass("err")) {
		                $(this).next("p").remove();
		            }
		        }
		    });
		}
	</script>
</html>
<?php echo $__env->yieldContent('scripts'); ?><?php /**PATH F:\xamp\htdocs\laravel\admin\resources\views/layouts/app.blade.php ENDPATH**/ ?>