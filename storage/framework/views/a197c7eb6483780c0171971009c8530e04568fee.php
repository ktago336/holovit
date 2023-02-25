<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo e($title.TITLE_FOR_LAYOUT); ?></title>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="shortcut icon" href="<?php echo FAVICON_PATH; ?>" type="image/x-icon"/>
        <link rel="icon" href="<?php echo FAVICON_PATH; ?>" type="image/x-icon"/>

        <?php echo e(HTML::style('public/css/bootstrap.min.css')); ?>

        <?php echo e(HTML::style('public/css/AdminLTE.min.css')); ?>

        <?php echo e(HTML::style('public/css/front/font-awesome.css')); ?>


        <?php echo e(HTML::script('public/js/jquery-2.1.0.min.js')); ?>

        <?php echo e(HTML::script('public/js/jquery.validate.js')); ?>

        <?php echo e(HTML::script('public/js/front/bootstrap.min.js')); ?>

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="hold-transition login-page">
        <div class="login-box">
            <?php echo $__env->yieldContent('content'); ?>
        </div>
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
</html><?php /**PATH /home/lscouponslogicsp/public_html/resources/views/layouts/adminlogin.blade.php ENDPATH**/ ?>