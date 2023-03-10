<header class="main-header">
    <a href="<?php echo e(URL::to( 'admin/admins/dashboard')); ?>" class="logo">
        <span class="logo-mini"><b>G</b></span>
        <span class="logo-lg"><?php echo e(HTML::image(LOGO_PATH, SITE_TITLE)); ?></span>
    </a>
    <nav class="navbar navbar-static-top">
        <a href="javascript:void(0);" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu">
                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
                        <span class="hidden-xs"><?php echo e(Session::get('admin_username')); ?></span>
                    </a>
                </li>
                <li><a href="<?php echo e(URL::to( 'admin/admins/logout')); ?>" class=""><i class="fa fa-sign-out fa-lg"></i></a></li>
            </ul>
        </div>
    </nav>
</header>

<script>
            $(window).scroll(function () {
                if ($(this).scrollTop() > 5) {
                    $(".main-header").addClass("fixed-me");
                } else {
                    $(".main-header").removeClass("fixed-me");
                }
            });
        </script><?php /**PATH /home/lscouponslogicsp/public_html/resources/views/elements/admin/header.blade.php ENDPATH**/ ?>