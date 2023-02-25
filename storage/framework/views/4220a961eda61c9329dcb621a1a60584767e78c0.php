<div class="slider-inner">
    <header class="header-main"><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <div class="header-top">
            <div class="container">
                <div class="row">
                    <div class="col-sm-7 col-md-7" >
                        <div class="country_bx">
                            <span><b>Free Call</b>  (073) 123-12-12</span>
                        </div>
                        <div class="country_bx country_bx_last">
                            <span><b>Opening Hours:</b>  Mn-Fr: 10 am-8 pm</span>
                        </div>
                    </div>
                    <div class="col-sm-5 col-md-5">
                        <div class="row">
                            <div class="col-md-6 col-sm-6 pull-right">
                                <div class="social-icon header-icon">
                                    <a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                                    <a href="#"><i class="fa fa-google" aria-hidden="true"></i></a>
                                    <a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                                    <a href="#"><i class="fa fa-youtube-play" aria-hidden="true"></i></a>
                                    <a href="#"><i class="fa fa-pinterest-p" aria-hidden="true"></i></a>
                                    <a href="#"><i class="fa fa-vimeo" aria-hidden="true"></i></a>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 pull-right">
                                <div class="social-icon header-icon">
                                    <?php if(session()->has('user_id')): ?>
                                    <a href="<?php echo e(URL::to('/logout')); ?>">Logout</a>
                                    <a href="<?php echo e(URL::to('users/dashboard')); ?>">My Account</a>
                                    <?php else: ?> 
                                    <a href="<?php echo e(URL::to('login')); ?>">Login</a>
                                    <a href="<?php echo e(URL::to('register')); ?>">SignUp</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="header">
            <nav class="navbar navbar-expand-lg navbar-light feedart-menu nevication-bar navbar-me">
                <div class="container">
                    <div class="navbar-toggler collapsed" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-label="Toggle navigation">
                        <div class="toggle position-relative">
                            <div class="line top position-absolute"></div>
                            <div class="line middle cross1 position-absolute"></div>
                            <div class="line middle cross2 position-absolute"></div>
                            <div class="line bottom position-absolute"></div>
                        </div>
                    </div>
                    <a class="navbar-brand" href="<?php echo HTTP_PATH; ?>"><?php echo e(HTML::image(LOGO_PATH, SITE_TITLE, array('alt'=>'Logo'))); ?></a>
                    <div class="collapse navbar-collapse menus_design" id="navbarSupportedContent">
                        <ul class="nav navbar-nav mr-auto">
<!--                            <li class="nav-item"><a class="nav-link" href="<?php echo e(url('/')); ?>">Home</a></li>-->
                            <li class="nav-item"><a class="nav-link" href="<?php echo e(url('/about')); ?>">About</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?php echo e(url('/services')); ?>">Services</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?php echo e(url('/experts')); ?>">Our Experts</a></li>
                        </ul>
                        <ul class="nav navbar-nav ml-auto">
                            <li class="nav-item"><a class="nav-link" href="<?php echo e(url('/blog')); ?>">Blog</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?php echo e(url('/testimonial')); ?>">Testimonial</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?php echo e(url('/contact')); ?>">Contacts</a></li>
                            <!-- <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Notifications</a>
                                <div class="dropdown-menu dropdown-menu-abs">
                                    <a class="dropdown-item" href="#">
                                        <h3>hello</h3>
                                        <div class="job-tatle">Lara Lara David<span> 1 month ago</span></div>
                                    </a>
                                    <a class="dropdown-item" href="#">
                                        <h3>hello</h3>
                                        <div class="job-tatle">Lara Lara David<span> 1 month ago</span></div>
                                    </a>
                                    <a class="dropdown-item" href="#">
                                        <h3>hello</h3>
                                        <div class="job-tatle">Lara Lara David<span> 1 month ago</span></div>
                                    </a>
                                    <a class="dropdown-item" href="#">
                                        <h3>hello</h3>
                                        <div class="job-tatle">Lara Lara David<span> 1 month ago</span></div>
                                    </a>
                                </div>
                            </li> -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Notifications<span id="checkunreadmsg" class="green-dots displaynone"></span></a>
                                <div class="dropdown-menu dropdown-menu-abs" id = "msgcontaine">
                                    <ul class="notification displaynonenot" id="msgcontaine">
                                </ul>
                                     <?php 
                                    $notifications = DB::table('notifications')->where('user_id',Session::get('user_id'))->orderBy('id','DESC')->get();
                                    ?> 
                                    <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <a class="dropdown-item" href="<?php echo e(URL::to( 'users/myrequests')); ?>">
                                    <?php
                                    $startTimeStamp = strtotime($notification->created_at);
                                    $date = date('Y-m-d');
                                    $endTimeStamp = strtotime($date);
                                    $timeDiff = abs($endTimeStamp - $startTimeStamp);

                                    $numberDays = $timeDiff/86400;  
                                    $numberDays = intval($numberDays);
                                    $months = floor($numberDays/30);
                                    ?>
                                        <h3><?php echo e($notification->message); ?></h3>
                                        <div class="job-tatle"><?php echo e($notification->from_name); ?><span> <?php echo e($months); ?> month ago</span></div>
                                    </a>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </li>
                            <li class="nav-item mobile-show"><a class="nav-link" href="<?php echo e(URL::to('login')); ?>">Login</a></li>
                            <li class="nav-item mobile-show"><a class="nav-link" href="<?php echo e(URL::to('register')); ?>">SignUp</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </header>
</div>

<script>
<?php if(Session::get('user_id') && Session::get('user_id') > 0): ?>
$(document).ready(function() { 
    getmessage();
});
<?php endif; ?>
function getmessage(){
    $.ajax({
        url: "<?php echo HTTP_PATH; ?>/check-new-notification",
        type: "GET",
        success: function (result) {
            if(result == 1){

            }else{
                $('#checkunreadmsg').removeClass('displaynone');
                $('#msgcontaine').removeClass('displaynonenot');
                $("#msgcontaine").html('');
                servers = $.parseJSON(result);
                $.each(servers, function(index, value) {
                    $("#msgcontaine").append('<li><a href="<?php echo e(HTTP_PATH); ?>/users/myrequests"><h3>'+value.message+'</h3><div class="job-tatle">'+value.from_name+'<span> '+value.timeago+'</span></div></a></li>');
                });
            }
        }
    });
}
setInterval(function() { getmessage(); }, 30000);

</script><?php /**PATH /home/lscouponslogicsp/public_html/resources/views/elements/newheader.blade.php ENDPATH**/ ?>