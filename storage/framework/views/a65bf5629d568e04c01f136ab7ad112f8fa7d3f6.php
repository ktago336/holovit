<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <link rel="shortcut icon" type="image/x-icon" href="<?php echo FAVICON_PATH; ?>">
<!--        <title><?php echo e($title.TITLE_FOR_LAYOUT); ?></title>-->
         <title>LS Coupon - Daily Deal Software | Groupon Clone Script</title>
         <meta name = "keywords" content = "best groupon clone, Groupon Clone, Groupon Clone Script, Groupon Clone Software, daily deal script, groupon script, groupon clone app, daily deal software, coupon script, coupon software, coupon management software" />
        <meta name="description" content="lscoupons is a daily deal software for listing deals. Start your own online coupon software by using our readymade groupon clone script.">
        <link rel="canonical" href="<?php echo HTTP_PATH; ?>" />
        <!-- Bootstrap -->
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <?php echo e(HTML::style('public/css/front/bootstrap.min.css')); ?>

        <?php echo e(HTML::style('public/css/front/style.css')); ?>

        <?php echo e(HTML::style('public/css/front/font-awesome.css')); ?>

   <?php echo e(HTML::style('public/css/front/owl.theme.default.min.css')); ?>

        <?php echo e(HTML::style('public/css/front/owl.carousel.min.css')); ?>

        <?php echo e(HTML::style('public/css/front/responsive.css')); ?> 
        <?php echo e(HTML::style('public/css/front/aos.css')); ?>

        
        <?php echo e(HTML::script('public/js/front/jquery-2.1.0.min.js')); ?>

        <!--<?php echo e(HTML::script('public/js/front/jquery.min.js')); ?>-->
		<?php echo e(HTML::script('public/js/jquery.cookie.js')); ?>

        <?php echo e(HTML::script('public/js/jquery.validate.js')); ?> 
         <?php echo e(HTML::script('public/js/front/popper.min.js')); ?>

        <?php echo e(HTML::script('public/js/front/owl.carousel.js')); ?>

        <?php echo e(HTML::script('public/js/front/custom.min.js')); ?>

       
        <?php echo e(HTML::script('public/js/front/bootstrap.min.js')); ?>

        <?php echo e(HTML::script('public/js/front/ajaxsoringpagging.js')); ?>

<?php echo e(HTML::script('public/js/front/listing.js')); ?>

    </head>
    <body>
        <?php echo $__env->make('elements.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php echo $__env->yieldContent('content'); ?> 
        <?php echo $__env->make('elements.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <div id="toTop">
            <?php echo e(HTML::image('public/img/front/arrow-top.png', SITE_TITLE, array('alt' => 'top'))); ?>

        </div>
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        
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
       
        <script>
            $('.menu-toggle').on('click', function () {
                $(this).toggleClass('toggled-on');
            });
            var flip = 0;
            $('.menu-toggle').click(function () {
                $('.test').toggle(flip++ % 2 === 0);
            });
        </script>
        <script>
            $(document).on('click', function () {
                $('.collapse').collapse('hide');
            });
        </script>
        <?php echo e(HTML::script('public/js/front/aos.js')); ?>

        <!-- <script src="js/aos.js"></script> -->
        <script>
            AOS.init({
                duration: 1200, once: true
            });
        </script>
        <script type="text/javascript">window.$zopim || (function (d, s) {
        var z = $zopim = function (c) {
            z._.push(c)
        }, $ = z.s =
                d.createElement(s), e = d.getElementsByTagName(s)[0];
        z.set = function (o) {
            z.set.
                    _.push(o)
        };
        z._ = [];
        z.set._ = [];
        $.async = !0;
        $.setAttribute("charset", "utf-8");
        $.src = "https://v2.zopim.com/?4toXhVRHXOtCLes7sRNCMItG7HdblsBt";
        z.t = +new Date;
        $.
                type = "text/javascript";
        e.parentNode.insertBefore($, e)
    })(document, "script");</script>
<script>
    $zopim(function () {
        $zopim.livechat.bubble.setColor('#ee534e');
    });
</script>
    </body>
</html>
<?php /**PATH /home/lscouponslogicsp/public_html/resources/views/layouts/inner.blade.php ENDPATH**/ ?>