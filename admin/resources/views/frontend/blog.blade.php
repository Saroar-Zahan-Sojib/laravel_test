<!DOCTYPE html>
<html>
    <head>
        <title>SoftCar</title>
        <link rel="stylesheet" href="{{ asset('front/styles.css') }}">
        <link rel="stylesheet" href="{{ asset('front/ezytheme.css') }}">
        <link rel="stylesheet" href="{{ asset('front/css/blog.css') }}">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <meta name="viewport" content="width=device-width, initial scale=1.0">
    </head>
    <body id="upp">
        <div class="navigation" id="navigation">
            <div class="brand" onclick="location.href='../'">
                SoftCar
            </div>
            <div class="links">
                <a href="#" class="link link0">Blog</a>
                <a href="../#section8" class="link link1"><i class="material-icons blue nav-icon">play_arrow</i>How It Works</a>
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
                            <input placeholder="Enter your name" class="input" type="text" name="name" id="name" required>
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

        <div class="blog1">
            <h1>
                Easily manage your car rent service with SoftCar
            </h1>
            <p class="date">
                January 25, 2022 <span style="color:#5D5DFF;margin:0 2px 0 10px">&bull;</span>By John Doe
            </p>
            <div class="blog-image"></div>
            <p class="blog-text">
                Cras finibus eget risus ut venenatis. Phasellus ante dolor, ultricies vel porttitor id, pretium facilisis libero. Proin neque ante, tincidunt nec porta vel, aliquam maximus dolor. Ut aliquet et dui et tempor. Sed posuere consectetur faucibus. Mauris dui erat, tempus vel venenatis aliquet, faucibus id nisl. Donec bibendum a orci ut accumsan. Praesent placerat condimentum semper.
            </p>
            <p class="blog-text">
                Nunc dignissim commodo tortor. Interdum et malesuada fames ac ante ipsum primis in faucibus. Donec varius mauris sed ex sodales consequat. In sed nisl vestibulum, molestie elit at, ullamcorper turpis. Integer facilisis felis et lectus venenatis convallis. Curabitur pulvinar accumsan mi, ut tristique lectus sodales vitae. Phasellus diam turpis, ultricies non libero ut, faucibus commodo magna. Etiam vulputate rhoncus iaculis. Maecenas commodo dolor in aliquam pellentesque. Cras sit amet mi interdum, imperdiet arcu vel, aliquet lectus. Integer scelerisque ex augue, sed facilisis lectus hendrerit eget. Ut nec faucibus lectus, sed blandit odio.
            </p>
            <p class="blog-text">
                Phasellus ante dolor, ultricies vel porttitor id, pretium facilisis libero. Proin neque ante, tincidunt nec porta vel, aliquam maximus dolor. Ut aliquet et dui et tempor. Sed posuere consectetur faucibus. Mauris dui erat, tempus vel venenatis aliquet, faucibus id nisl. Donec bibendum a orci ut accumsan.
            </p>
            <p class="blog-text">
                Nunc dignissim commodo tortor. Interdum et malesuada fames ac ante ipsum primis in faucibus. Donec varius mauris sed ex sodales consequat. In sed nisl vestibulum, molestie elit at, ullamcorper turpis. Integer facilisis felis et lectus venenatis convallis. Curabitur pulvinar accumsan mi, ut tristique lectus sodales vitae. Phasellus diam turpis, ultricies non libero ut, faucibus commodo magna. Etiam vulputate rhoncus iaculis. Maecenas commodo dolor in aliquam pellentesque. Cras sit amet mi interdum, imperdiet arcu vel, aliquet lectus. Integer scelerisque ex augue, sed facilisis lectus hendrerit eget. Ut nec faucibus lectus, sed blandit odio.
            </p>
            
        </div>

        <div class="footer">
            <div>
                <h2>
                    SoftCar
                </h2>
                <p>
                    Cras finibus eget risus ut venenatis. Phasellus ante dolor, ultricies vel porttitor id,
                    pretium facilisis libero. Proin neque ante, tincidunt nec porta vel, aliquam maximus
                    dolor. Ut aliquet et dui et tempor. Sed posuere consectetur faucibus. Mauris dui erat,
                    tempus vel venenatis aliquet, faucibus id nisl.
                </p>
            </div>
            <div>
                <h3>
                    Contact Us
                </h3>
                <p>
                    Email: <a href="mailto:softcar@gmail.com?subject=SoftCar&body=Hello!">softcar@gmail.com</a><br>
                    Phone: <a href="tel:+17865200000//">+1 78652 00000</a>
                </p>
            </div>
        </div>

        

        <script src="{{ asset('front/js/blog.js')}}"></script>
    </body>
</html>