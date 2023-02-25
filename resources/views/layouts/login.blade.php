<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <link rel="shortcut icon" type="image/x-icon" href="{!! FAVICON_PATH !!}">
        <title>{{$title.TITLE_FOR_LAYOUT}}</title>
        <!-- Bootstrap -->
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        {{ HTML::style('public/css/front/bootstrap.min.css')}}
        {{ HTML::style('public/css/front/style.css')}}
        {{ HTML::style('public/css/front/font-awesome.css')}}
        {{ HTML::style('public/css/front/owl.theme.default.min.css')}}
        {{ HTML::style('public/css/front/owl.carousel.min.css')}}
        {{ HTML::style('public/css/front/effects.min.css')}}
        {{ HTML::style('public/css/front/aos.css')}}
        {{ HTML::script('public/js/front/jquery.min.js')}}
        {{ HTML::script('public/js/jquery.validate.js')}}  
    </head>
    <body>

        @yield('content') 



        <div id="toTop">
            {{HTML::image('public/img/front/arrow-top.png', SITE_TITLE, array('alt' => 'top'))}}
        </div>
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        
        {{ HTML::script('public/js/front/bootstrap.min.js')}}
        {{ HTML::script('public/js/front/owl.carousel.js')}}
        {{ HTML::script('public/js/front/aos.js')}}
        <script type="text/javascript">
            AOS.init({
                duration: 1200, once: true
            });
        </script>
        <script>
            $(window).scroll(function () {
                if ($(this).scrollTop() > 5) {
                    $(".nevication-bar").addClass("fixed-me");
                } else {
                    $(".nevication-bar").removeClass("fixed-me");
                }
            });
        </script>
        <script type="text/javascript">
            $(window).scroll(function () {
                if ($(this).scrollTop() > 0) {
                    $('#toTop').fadeIn();
                } else {
                    $('#toTop').fadeOut();
                }
            });
            $('#toTop').click(function () {
                $('body,html').animate({scrollTop: 0}, 800);
            });
        </script>
        <script type="text/javascript">
            $(document).ready(function () {
                $('.ftdrop1').click(function () {
                    if ($('.ftdrop1').hasClass('ftopen1')) {
                        $('.ftdrop1').removeClass('ftopen1');
                    } else {
                        $('.ftdrop1').addClass('ftopen1');
                    }
                    $(".ftblock1").slideToggle();
                });
                $('.ftdrop2').click(function () {
                    if ($('.ftdrop2').hasClass('ftopen2')) {
                        $('.ftdrop2').removeClass('ftopen2');
                    } else {
                        $('.ftdrop2').addClass('ftopen2');
                    }
                    $(".ftblock2").slideToggle();
                });
                $('.ftdrop3').click(function () {
                    if ($('.ftdrop3').hasClass('ftopen3')) {
                        $('.ftdrop3').removeClass('ftopen3');
                    } else {
                        $('.ftdrop3').addClass('ftopen3');
                    }
                    $(".ftblock3").slideToggle();
                });
                $('.ftdrop4').click(function () {
                    if ($('.ftdrop4').hasClass('ftopen4')) {
                        $('.ftdrop4').removeClass('ftopen4');
                    } else {
                        $('.ftdrop4').addClass('ftopen4');
                    }
                    $(".ftblock4").slideToggle();
                });
            });
        </script>

    </body>
</html>
