<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo e($title.TITLE_FOR_LAYOUT); ?></title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
  <link rel="shortcut icon" href="<?php echo FAVICON_PATH; ?>" type="image/x-icon"/>
  <link rel="icon" href="<?php echo FAVICON_PATH; ?>" type="image/x-icon"/>


  <?php echo e(HTML::style('public/css/bootstrap3.min.css')); ?>

  <?php echo e(HTML::style('public/css/AdminLTE.min.css')); ?>

  <?php echo e(HTML::style('public/css/all-skins.min.css')); ?>

  <?php echo e(HTML::style('public/css/admin.css')); ?>

  <?php echo e(HTML::style('public/css/font-awesome.css')); ?>




  <?php echo e(HTML::script('public/js/jquery-2.1.0.min.js')); ?>

  <?php echo e(HTML::script('public/js/bootstrap3.min.js')); ?>

  <?php echo e(HTML::script('public/js/jquery.validate.js')); ?>

  <?php echo e(HTML::script('public/js/app.min.js')); ?>

  <?php echo e(HTML::script('public/js/ajaxsoringpagging.js')); ?>

  <?php echo e(HTML::script('public/js/listing.js')); ?>



  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <![endif]-->
      <style type="text/css">
        .fc-past{pointer-events: none;opacity: 0.5;cursor: not-allowed;}
        .fc-offday{pointer-events: none;cursor: not-allowed;background:#ecdddb;}

        .fc-past:hover {cursor: not-allowed!important;}
        #space-div{display:none;height:155px;}
        .fc-unthemed th, .fc-unthemed td, .fc-unthemed thead, .fc-unthemed tbody, .fc-unthemed .fc-divider, .fc-unthemed .fc-row, .fc-unthemed .fc-content, .fc-unthemed .fc-popover, .fc-unthemed .fc-list-view, .fc-unthemed .fc-list-heading td{border-color: #cec9c9!important;}
      </style>
      
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
      <div class="wrapper">
        <?php echo $__env->make('elements.admin.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php echo $__env->make('elements.admin.left_menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
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

    </html><?php /**PATH /home/lscouponslogicsp/public_html/resources/views/layouts/admin.blade.php ENDPATH**/ ?>