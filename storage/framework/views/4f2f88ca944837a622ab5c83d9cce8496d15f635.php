<?php $__env->startSection('content'); ?>
<!-- <section class="about-banner">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-md-6" data-aos="fade-right">
                <h1>Blog</h1>
            </div>
            <div class="col-sm-6 col-md-6" data-aos="fade-left">
                <div class="about-img"><?php echo e(HTML::image('public/img/front/banner-img3.png', SITE_TITLE)); ?></div>
            </div>
        </div>
    </div>
</section> -->
<section class="breadcrumb-section">
    <div class="container">
        <ol class="breadcrumb my_breadcum">
            <li><a href="<?php echo e(url('/')); ?>">Home</a></li> 
            <li class="active"><a href="<?php echo e(url('/blog')); ?>">Blog</a></li> 
        </ol>
    </div>
</section>
<section class="blog-section" data-aos="fade-up">
    <div class="container">
        <div class="blog-bx">
            <div class="row">
                <div class="col-sm-8 col-md-8">
                    <div class="blog-post">
                        <div class="thumbnail">
                            <div class="blog-img">
                                <?php echo e(HTML::image('public/img/front/blog-img2.jpg', SITE_TITLE)); ?>

                            </div>
                            <div class="caption">
                                <h3><a href="#">Perfect Treatment for Dry Skin</a></h3>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sit amet bibendum nisl. Etiam a quam pellentesque interdum vel at risus. Curabitur tempor porttitor egestas. </p>
                                <div class="date-by">
                                    <span>Jun 16, 2018</span>
                                    <div class="by-post">by <a href="#">Mary Lucas</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="blog-post">
                        <div class="thumbnail">
                            <div class="blog-img">
                                <?php echo e(HTML::image('public/img/front/blog-img1.jpg', SITE_TITLE)); ?>

                            </div>
                            <div class="caption">
                                <h3><a href="#">Five Easy Steps for Creating Gala Smoky Eyes</a></h3>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sit amet bibendum nisl. Etiam a quam pellentesque interdum vel at risus. Curabitur tempor porttitor egestas. </p>
                                <div class="date-by">
                                    <span>Jun 16, 2018</span>
                                    <div class="by-post">by <a href="#">Mary Lucas</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="blog-post">
                        <div class="thumbnail">
                            <div class="blog-img">
                                <?php echo e(HTML::image('public/img/front/blog-img3.jpg', SITE_TITLE)); ?>

                            </div>
                            <div class="caption">
                                <h3><a href="#">Natural Skin Care Products and Services</a></h3>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sit amet bibendum nisl. Etiam a quam pellentesque interdum vel at risus. Curabitur tempor porttitor egestas. </p>
                                <div class="date-by">
                                    <span>Jun 16, 2018</span>
                                    <div class="by-post">by <a href="#">Mary Lucas</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 col-md-4">
                    <div class="blog-right">
                        <div class="blog-right-bx">
                            <h2>Search</h2>
                            <div class="search-input">
                                <input type="text" placeholder="Search..." class="form-control">
                                <a href="#"><i class="fa fa-search" aria-hidden="true"></i></a>
                            </div>
                        </div>
                        <div class="blog-right-bx">
                            <h2>Archive</h2>
                            <div class="archive-bx">
                                <ul>
                                    <li><a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i><span>Jun 2018</span></a></li>
                                    <li><a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i><span>Nov 2018</span></a></li>
                                    <li><a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i><span>Jul 2018</span></a></li>
                                    <li><a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i><span>Dec 2018</span></a></li>
                                    <li><a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i><span>Aug 2018</span></a></li>
                                    <li><a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i><span>Jan 2018</span></a></li>
                                    <li><a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i><span>Sep 2018</span></a></li>
                                    <li><a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i><span>Feb 2018</span></a></li>
                                    <li><a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i><span>Oct 2018</span></a></li>
                                    <li><a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i><span>Mar 2018</span></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="blog-right-bx">
                            <h2>Recent Blog Posts</h2>
                            <div class="recent-blog">
                                <ul>
                                    <li>
                                        <h3><a href="#">Holiday Hair and Makeup: Tips From the Stylist</a></h3>
                                        <p>2 days ago</p>
                                    </li>
                                    <li>
                                        <h3><a href="#">Following This Advice Will Help Your Skin</a></h3>
                                        <p>2 days ago</p>
                                    </li>
                                    <li>
                                        <h3><a href="#">Pairing Your Makeup With Your Garments</a></h3>
                                        <p>2 days ago</p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="blog-right-bx categories-right-bx">
                            <h2>Categories</h2>
                            <div class="categories-bx">
                                <ul>
                                    <li><a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i><span>Hair</span></a></li>
                                    <li><a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i><span>Makeup</span></a></li>
                                    <li><a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i><span>Nail</span></a></li>
                                    <li><a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i><span>Massage</span></a></li>
                                    <li><a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i><span>Skin Care</span></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.newhome', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/lscouponslogicsp/public_html/resources/views/homes/blog.blade.php ENDPATH**/ ?>