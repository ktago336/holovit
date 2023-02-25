<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <link rel="shortcut icon" type="image/x-icon" href="<?php echo FAVICON_PATH; ?>">
<!--        <title><?php echo e($title.TITLE_FOR_LAYOUT); ?></title>-->
        <title>Holovit - Daily Deal Software </title>
         <meta name = "keywords" content = "best groupon clone, Groupon Clone, Groupon Clone Script, Groupon Clone Software, daily deal script, groupon script, groupon clone app, daily deal software, coupon script, coupon software, coupon management software" />
        <meta name="description" content="lscoupons is a daily deal software for listing deals. Start your own online coupon software by using our readymade groupon clone script.">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
        <?php echo e(HTML::style('public/css/front/bootstrap.min.css')); ?>

        <?php echo e(HTML::style('public/css/front/owl.carousel.min.css')); ?>

        <?php echo e(HTML::style('public/css/front/font-awesome.css')); ?>

        <?php echo e(HTML::style('public/css/front/style.css')); ?>

        <?php echo e(HTML::style('public/css/front/responsive.css')); ?>

        <?php echo e(HTML::script('public/js/front/jquery-1.12.4.min.js')); ?>

		<?php echo e(HTML::script('public/js/jquery.cookie.js')); ?>

        <?php echo e(HTML::script('public/js/jquery.validate.js')); ?> 
		<?php echo e(HTML::script('public/js/front/popper.min.js')); ?>

    </head>
    <body>
        <?php echo $__env->make('elements.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php echo $__env->yieldContent('content'); ?> 
        <?php echo $__env->make('elements.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <div id="toTop"><?php echo e(HTML::image('public/img/front/arrow-top.png', SITE_TITLE, array('alt' => 'top'))); ?></div>
        <?php echo e(HTML::script('public/js/front/bootstrap.min.js')); ?>

        <?php echo e(HTML::script('public/js/front/owl.carousel.js')); ?>

        <?php echo e(HTML::script('public/js/front/script.js')); ?> 
        <script>
            $(document).ready(function () {
                $('.owl-carousel').owlCarousel({
                    loop: true,
                    margin: 10,
                    responsiveClass: true,
                    autoplay: true,
                    autoplayTimeout: 5000,
                    responsive: {
                        0: {items: 1, nav: true},
                        600: {items: 2, nav: false},
                        1000: {items: 3, nav: true, loop: true, margin: 20
                        }
                    }
                })
            })
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
            });
        </script>
        <script>
            $(window).scroll(function () {
                if ($(this).scrollTop() > 5) {
                    $(".menu_header").addClass("fixed-me");
                } else {
                    $(".menu_header").removeClass("fixed-me");
                }
            });
        </script>

        <script type="text/javascript">
            $(document).ready(function () {
<?php /*if (!(Session::get('never')) && ((!(Session::get('countryName')) || Session::get('countryName') == "") || (!(Session::get('regionName')) || Session::get('regionName') == ""))) { ?>
                    setTimeout(function () {
                        var html = '<div id="geoLocPopUp" class="userPrompt animate"><div><p class="caption">Share your location with us for more relevant jobs</p><p class="desc">You can turn them off anytime from browser settings</p></div><span id="block" onclick="notNow()" class="fr geoLocBtn later">Later</span><span id="allow" onclick="yesnow()" class="fr geoLocBtn sure">Sure</span></div>'
                        $(html).appendTo('body');
                    }, 1000);
<?php }*/ ?>


//                $(".dev_menu").click(function () {
//                    $("nav").toggle();
//                });
            });

            function notNow() {
                $('#geoLocPopUp').remove();
                $.ajax({
                    type: 'POST',
                    url: "<?php echo HTTP_PATH; ?>/never/",
                    cache: false,
                    data: {},
                    beforeSend: function () {
                        $('#geoLocPopUp').remove();
                    },
                    complete: function () {
                        $('#geoLocPopUp').remove();
                    },
                    success: function (result) {
                        $('#geoLocPopUp').remove();
                    }
                });
            }
            function yesnow() {
                $.ajax({
                    type: 'POST',
                    url: "<?php echo HTTP_PATH; ?>/setLocationInSession/",
                    cache: false,
                    data: {},
                    beforeSend: function () {
                        $('#geoLocPopUp').remove();
                    },
                    complete: function () {
                        $('#geoLocPopUp').remove();
                    },
                    success: function (result) {
                        if (result == 2) {
                            alert("We cannot able to get deals for your current location for now, please try again later");
                            $('#geoLocPopUp').remove(); }
                        else if (result == 0) {
                            alert("We cannot able to track you location for now, please try again later");
                            $('#geoLocPopUp').remove();
                        } else {
                            $('#geoLocPopUp').remove();
                        }

                    }
                });

            }
        </script>

    </body>
</html>
<?php /**PATH /home/holovitr/domains/holovit.ru/public_html/resources/views/layouts/home.blade.php ENDPATH**/ ?>