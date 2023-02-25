<!-- <div class="slider">
    <header class="header-main"><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <div class="header-top">
            <div class="container">
                <div class="row">
                    <div class="col-sm-9 col-md-9">
                        <div class="country_bx">
                            <span><b>Free Call</b>  (073) 123-12-12</span>
                        </div>
                        <div class="country_bx country_bx_last">
                            <span><b>Opening Hours:</b>  Mn-Fr: 10 am-8 pm</span>
                        </div>
                    </div>
                    <div class="col-sm-3 col-md-3">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 pull-right">
                                <div class="social-icon">
                                    @if(session()->has('user_id'))
                                    <a href="{{URL::to('/logout')}}">Logout</a>
                                    <a href="{{URL::to('users/dashboard')}}">My Account</a>
                                    @else 
                                    <a href="{{URL::to('login')}}">Login</a>
                                    <a href="{{URL::to('register')}}">SignUp</a>
                                    @endif
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
                    <a class="navbar-brand" href="{!! HTTP_PATH !!}"> {{HTML::image(LOGO_PATH, SITE_TITLE, array('alt'=>'Logo'))}}</a>
                    <div class="collapse navbar-collapse menus_design" id="navbarSupportedContent">
                        <ul class="nav navbar-nav navbar-left">
                            <li class="nav-item"><a class="nav-link" href="{{url('/about')}}">About</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{url('/services')}}">Services</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{url('/experts')}}">Our Experts</a></li>
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                            <li class="nav-item"><a class="nav-link" href="{{url('/blog')}}">Blog</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{url('/testimonial')}}">Testimonial</a></li>
                            <li class="nav-item"><a class="nav-link" href="#">Contacts</a></li>

                            @if(session()->has('user_id'))
                            <li class="nav-item mobile-show"><a class="nav-link" href="{{URL::to('Logout')}}">Login</a></li>
                            <li class="nav-item mobile-show"><a class="nav-link" href="{{URL::to('users/dashboard')}}">My Account</a></li>
                            @else 
                            <li class="nav-item mobile-show"><a class="nav-link" href="{{URL::to('login')}}">Login</a></li>
                            <li class="nav-item mobile-show"><a class="nav-link" href="{{URL::to('register')}}">Signup</a></li>
                            @endif
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </header>
    <div class="slider_cover">
        <div class="container">
            <div class="row">
                <div class="col-md-10 col-sm-10 col-lg-8 col-xl-8">
                    <div class="banner-txt">
                        <h2>Professional Beauty <span>Care for Everyone</span></h2>
                        <p>Glory Beauty Salon offers the latest and highest quality services for you and all your family members.
                            We specialize in all beauty treatments and our team is fully professional and innovative, from makeup 
                            services to hairstyling.</p>
                        <a href="#">READ MORE</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> -->
<div class="slider">
    <header class="header-main">
        <div class="header-top">
            <div class="container">
                <div class="row">
                    <div class="col-sm-9 col-md-9">
                        <div class="country_bx">
                            <span><b>Free Call</b>  (073) 123-12-12</span>
                        </div>
                        <div class="country_bx country_bx_last">
                            <span><b>Opening Hours:</b>  Mn-Fr: 10 am-8 pm</span>
                        </div>
                    </div>
                    <div class="col-sm-3 col-md-3">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 pull-right">
                                <div class="social-icon header-icon">
                                    @if(session()->has('user_id'))
                                    <a href="{{URL::to('/logout')}}">Logout</a>
                                    <a href="{{URL::to('users/dashboard')}}">My Account</a>
                                    @else 
                                    <a href="{{URL::to('login')}}">Login</a>
                                    <a href="{{URL::to('register')}}">SignUp</a>
                                    @endif
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
                    <a class="navbar-brand" href="{!! HTTP_PATH !!}">{{HTML::image(LOGO_PATH, SITE_TITLE, array('alt'=>'Logo'))}}</a>
                    <div class="collapse navbar-collapse menus_design" id="navbarSupportedContent">
                        <ul class="nav navbar-nav mr-auto">
                            <li class="nav-item"><a class="nav-link" href="{{url('/about')}}">About</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{url('/services')}}">Services</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{url('/experts')}}">Our Experts</a></li>
                        </ul>
                        <ul class="nav navbar-nav ml-auto">
                            <li class="nav-item"><a class="nav-link" href="{{url('/blog')}}">Blog</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{url('/testimonial')}}">Testimonial</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{url('/contact')}}">Contacts</a></li>
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
                                    @foreach($notifications as $notification)
                                    <a class="dropdown-item" href="{{ URL::to( 'users/myrequests')}}">
                                    <?php
                                    $startTimeStamp = strtotime($notification->created_at);
                                    $date = date('Y-m-d');
                                    $endTimeStamp = strtotime($date);
                                    $timeDiff = abs($endTimeStamp - $startTimeStamp);

                                    $numberDays = $timeDiff/86400;  
                                    $numberDays = intval($numberDays);
                                    $months = floor($numberDays/30);
                                    ?>
                                        <h3>{{$notification->message}}</h3>
                                        <div class="job-tatle">{{$notification->from_name}}<span> {{$months}} month ago</span></div>
                                    </a>
                                    @endforeach
                                </div>
                            </li>

                            <li class="nav-item mobile-show"><a class="nav-link" href="{{URL::to('login')}}">Login</a></li>
                            <li class="nav-item mobile-show"><a class="nav-link" href="{{URL::to('register')}}">SignUp</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </header>
    <div class="slider_cover">
        <div class="container">
            <div class="row">
                <div class="col-md-10 col-sm-10 col-lg-8 col-xl-8">
                    <div class="banner-txt">
                        <h2>Professional Beauty <span>Care for Everyone</span></h2>
                        <p>Glory Beauty Salon offers the latest and highest quality services for you and all your family members.
                            We specialize in all beauty treatments and our team is fully professional and innovative, from makeup 
                            services to hairstyling.</p>
                        <a href="{{url::to('/services')}}">READ MORE</a>
                        <a class="btn btn-default scrollTo" href="#section2">BOOK NOW</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('#booknow').click(function () {
        $.scrollTo($('#booknow'), 1000);
    });
</script>

<script>
    $(document).ready(function () {
        // Add smooth scrolling to all links
        $(".banner-txt a.scrollTo").on('click', function (event) {

            // Make sure this.hash has a value before overriding default behavior
            if (this.hash !== "") {
                // Prevent default anchor click behavior
                event.preventDefault();

                // Store hash
                var hash = this.hash;

                // Using jQuery's animate() method to add smooth page scroll
                // The optional number (800) specifies the number of milliseconds it takes to scroll to the specified area
                $('html, body').animate({
                    scrollTop: $(hash).offset().top
                }, 800, function () {

                    // Add hash (#) to URL when done scrolling (default click behavior)
                    window.location.hash = hash;
                });
            } // End if
        });
    });
</script>
<script>
@if(Session::get('user_id') && Session::get('user_id') > 0)
$(document).ready(function() { 
    getmessage();
});
@endif
function getmessage(){
    $.ajax({
        url: "{!! HTTP_PATH !!}/check-new-notification",
        type: "GET",
        success: function (result) {
            if(result == 1){

            }else{
                $('#checkunreadmsg').removeClass('displaynone');
                $('#msgcontaine').removeClass('displaynonenot');
                $("#msgcontaine").html('');
                servers = $.parseJSON(result);
                $.each(servers, function(index, value) {
                    $("#msgcontaine").append('<li><a href="{{HTTP_PATH}}/users/myrequests"><h3>'+value.message+'</h3><div class="job-tatle">'+value.from_name+'<span> '+value.timeago+'</span></div></a></li>');
                });
            }
        }
    });
}
setInterval(function() { getmessage(); }, 30000);

</script>