<?php $__env->startSection('content'); ?>
<section class="profile-section">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-md-12 col-lg-12 col-xl-12">
                <div class="jumbotron text-center h-100 justify-content-center align-items-center">
                    <h1 class="display-3">Thank You!</h1>
                    <p class="lead"><strong>Your order(#<?php echo $slug;?>) has been submitted sucessfully! </p>
                    <hr>
                    <!-- <p>
                      Having trouble? <a href="">Contact us</a>
                    </p> -->
                    <p class="lead">
                        <a class="btn btn-primary btn-sm" href="<?php echo e(url('/deals/search')); ?>" role="button">Continue</a>
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.inner', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/lscouponslogicsp/public_html/resources/views/homes/thank.blade.php ENDPATH**/ ?>