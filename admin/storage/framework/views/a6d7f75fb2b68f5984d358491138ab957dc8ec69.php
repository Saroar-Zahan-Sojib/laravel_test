<!DOCTYPE html>
<html>
    <head>
        <title>SoftCar</title>
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="<?php echo e(asset('front/styles.css')); ?>">
        <link rel="stylesheet" href="<?php echo e(asset('front/ezytheme.css')); ?>">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.2.0/css/font-awesome.min.css">
        <meta name="viewport" content="width=device-width, initial scale=1.0">
    </head>
    <body id="upp">
        <div class="navigation" id="navigation">
            <div class="brand" onclick="location.href='#'">
                SoftCar
            </div>
            <div class="links">
            <a href="<?php echo e(asset('blog')); ?>" class="link link0">Blog</a>
                <a href="#section8" class="link link1"><i class="material-icons blue nav-icon">play_arrow</i>How It Works</a>
                <div onclick="openModal();">
                    <a class="link link2">Register Now</a>
                </div>
            </div>
        </div>

        <div class="mod-container" id="modal">
            <div class="modal">
                <div class="modal-title">
                    Welcome to SoftCar
                </div>
                <div class="modal-form">
                    <form action="get" name="rForm">
                        <div class="form-input">
                            <span class="label label1">Name</span>
                            <input placeholder="Enter your name" class="input" type="text" name="name" id="name" required onkeydown="ValidateName();">
                        </div>
                        <div class="form-input">
                            <span class="label label2">Email</span>
                            <input placeholder="Enter your email" class="input" type="email" name="email" id="email" required onkeydown="ValidateEmail();">
                        </div>
                        <div class="form-input">
                            <span class="label label3">Phone Number</span>
                            <input placeholder="Enter phone number" class="input" type="number" name="phoneNumber" id="phone" required>
                        </div>
                        <input class="form-btn data_submit" type="button" value="Submit">
                    </form>
                </div>
                <div class="modal-sub">
                    *One of our agent will contact you shortly.
                </div>
                <div onclick="closeModal();" class="modal-close">
                    <i class="material-icons">close</i>
                </div>
            </div>
        </div>

        <div class="fd12"></div>

        <div class="section1 flex">
            <div class="first first1">
                <h1>
                    Easily manage your car rent service with SoftCar
                </h1>
                <p>
                    SoftCar gives you a platform to make your cars available for renting & manage the overall service.<br> Its easy, secure & cost-efficient.
                </p>
            </div>
            <div class="first first2">
                <div class="pic1"></div>
            </div>
        </div>

        <div id="section2" class="container1 flex">
            <div class="section2 sec1">
                <div class="flex absolute double-img">
                    <div id="image1" class="image1"></div>
                    <div id="image2" class="image2"></div>
                </div>
                <div class="image-box absolute"></div>
                <div class="dummy2"></div>
            </div>
            <div class="section2 sec2">
                <h2>
                    Trouble managing with many tools? Struggling to keep track of Everything?
                </h2>
            </div>
        </div>

        <div class="fd2t3"></div>

        <div id="section3" class="container1 flex">
            <div class="section3 sec1">
                <h2>
                    SoftCar has one Platform to do it all
                </h2>
            </div>
            <div class="section3 sec2">
                <div class="flex absolute double-img2">
                    <div id="image11" class="image11"></div>
                    <div id="image12" class="image12"></div>
                </div>
                <div class="image-box2 absolute"></div>
            </div>
        </div>

        <div class="fd34"></div>


        <div class="container70 c-sm-90" id="s4">
            <div class="section4" id="section4">
                <div id="item1" class="tooltip item1">
                    <div>Keep track of reserved & available cars</div>
                </div>
                <div id="item2" class="tooltip item2">
                    <div>Have records of payment information</div>
                </div>
                <div id="item3" class="tooltip item3">
                    <div>Identify who damaged the car</div>
                </div>
                <div id="item4" class="tooltip item4">
                    <div>See statistics of reservations</div>
                </div>
            </div>
        </div>

        <div class="fd45"></div>

        <div id="section5" class="container80 flex tbc90">
            <div id="sec51" class="section5 sec1">
                <div class="content pad">
                    <h1>
                        Manage Everything from SoftCar Panel
                    </h1>
                    <p>
                        Register now and have your own panel to manage everything.<br> you don't need any other tools.
                    </p>
                    <br><br>
                    <button onclick="location.href='https://google.com';" class="btn grad grad1">
                        Register Now
                    </button>
                </div>
            </div>
            <div id="sec52" class="section5 sec2">
                <div class="sec2sec">
                    <img class="sec5pic" src="<?php echo e(asset('front/pic/pic7.png')); ?>" alt="">
                </div>
            </div>
        </div>


        <div id="section6" class="container80 flex section6 c-sm-90">
            <div class="sec61">
                <div id="ss1" class="ss ss1"></div>
                <div id="ss2" class="ss ss2"></div>
            </div>
            <div class="sec62">
                <div class="content2">
                    <h1>
                        SoftCar gives your customers a mobile app to reserve cars                    </h1>
                    <p>
                        Using the app customers can reserve cars, find pickup<br> location and make payment.                    </p>
                    <br>
                    <button onclick="location.href='https://google.com';" class="btn grad grad1">
                        Check The App
                    </button>
                </div>
            </div>
        </div>


        <div id="section7" class="container1 flex">
            <div class="section7 sec1">
                <div class="content7">
                    <h1>
                        Your cars are secured with SoftCar
                    </h1>
                    <p>
                        Your customer will submit photos of the car before they use. Thus, you can identify who damages your car.
                    </p>
                    <br><br>
                    <button onclick="location.href='https://google.com';" class="btn grad grad1">
                        Register Now
                    </button>
                </div>
            </div>
            <div class="section7 sec2">
                <div class="flex absolute double-img7">
                    <div id="image71" class="image71"></div>
                    <div id="image72" class="image72"></div>
                </div>
                <div class="image-box7 absolute"></div>
                <div class="dummy7"></div>
            </div>
        </div>
        
        <div class="fd78"></div>

        <div class="container70 flex section8 c-sm-90" id="section8">
            <h2 class="hITw">
                See How It Works
            </h2>
            <div class="video">
                <div class="frame">
                    <video class="main-video" id="video">
                        <source class="src" src="<?php echo e(asset('front/video.mp4')); ?>" type="video/mp4">
                    </video>
                </div>
                <div class="ctrl" id="ctrl" onclick="playpause();"><i class="material-icons" id="icon">play_arrow</i></div>
            </div>
            <h1>
                <span class="sec8h11">Want to know more?</span>
                <span class="sec8h12">Let's get connected.</span>
            </h1>
            <div>
                <div class="cntc-btn">
                    <button class="btn cntc" onclick="location.href='mailto:xyz@yourapplicationdomain.com?subject=SoftCar&body=Hello!'">
                        Contact Us
                    </button>
                </div>
            </div>
        </div>


        <div id="up" class="up" onclick="location.href='#upp';">
            <i class="material-icons">arrow_upward</i>
        </div>

        <script src="<?php echo e(asset('front/script.js')); ?>"></script>
    </body>
</html>
<script type="text/javascript" src="<?php echo e(url('front/js/jquery.min.js')); ?>"></script>
<script type="text/javascript">

$(document).on("click", ".data_submit", function(){
    var ts = $(this);
    var name = $("#name").val();
    var email = $("#email").val();
    var phone = $("#phone").val();

    console.log(name);
    console.log(email);
    console.log(phone);

    

    if(!name && !phone){
        required();
        return false;
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: "<?php echo e(url('client-data')); ?>",
        method: "post",
        data: {
            name: name,
            email: email,
            phone: phone,
            "_token": "<?php echo e(csrf_token()); ?>"
        },
        beforeSend: function(){
            ts.html("<i class='fa fa-spinner fa-spin'></i>")
        },
        success: function(rsp){
            console.log(rsp);
            if(rsp.error == false){
                $(".sErr").html("<div class='alert alert-success text-center'>"+rsp.msg+"</div>");
                
                ts.removeClass("btn-warning").addClass("btn-secondary").html("<i class='fa fa-save'></i> Submit");
                setTimeout(function(){
                       triggerModal();

                       $("#modal").modal('hide'); 
                       window.location.reload(true); 
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
</script><?php /**PATH F:\xampp\htdocs\laravel\admin\resources\views/frontend/index.blade.php ENDPATH**/ ?>