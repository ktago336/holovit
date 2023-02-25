<?php echo e(HTML::script('public/js/facebox.js')); ?>

<?php echo e(HTML::style('public/css/facebox.css')); ?>

<script type="text/javascript">
    $(document).ready(function ($) {
        $('.close_image').hide();
        $('a[rel*=facebox]').facebox({
            closeImage: '<?php echo HTTP_PATH; ?>/public/img/close.png'
        });
    });
</script>
<?php
$parent_id = Session::get('parent_id');
$adminLId = Session::get('adminid');
$adminRols = App\Http\Controllers\Admin\AdminsController::getAdminRoles(Session::get('adminid'));
$checkSubRols = App\Http\Controllers\Admin\AdminsController::getAdminRolesSub(Session::get('adminid'));
//print_r($adminRols);
?>
<div class="admin_loader" id="loaderID"><?php echo e(HTML::image("public/img/website_load.svg", SITE_TITLE)); ?></div>
<?php if(!$allrecords->isEmpty()): ?>
    <div class="panel-body marginzero">
        <div class="ersu_message"><?php echo $__env->make('elements.admin.errorSuccessMessage', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?></div>
        <?php echo e(Form::open(array('method' => 'post', 'id' => 'actionFrom'))); ?>

            <section id="no-more-tables" class="lstng-section">
                <div class="topn">
                    <div class="topn_left">Pages List</div>
                    <div class="topn_rightd ddpagingshorting" id="pagingLinks" align="right">
                        <div class="panel-heading" style="align-items:center;">
                            <?php echo e($allrecords->appends(Input::except('_token'))->render()); ?>

                        </div>
                    </div>                
                </div>
                <div class="tbl-resp-listing">
                <table class="table table-bordered table-striped table-condensed cf">
                    <thead class="cf ddpagingshorting">
                        <tr>
                            <th class="sorting_paging"><?php echo \Kyslik\ColumnSortable\SortableLink::render(array ('title', 'Page Title'));?></th>
                           <th class="sorting_paging"><?php echo \Kyslik\ColumnSortable\SortableLink::render(array ('updated_at', 'Last Updated'));?></th>
                           <th class="action_dvv"> Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $allrecords; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $allrecord): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td data-title="Full Name"><?php echo e($allrecord->title); ?></td>
                                <td data-title="Last Updated"><?php echo e($allrecord->updated_at->format('M d, Y')); ?></td>
                                <td data-title="Action">
                                    <?php $role = 2; if(isset($checkSubRols[4])){
                                    if ($adminLId == 1 || in_array($role, $checkSubRols[4])) { ?>
                                    <a href="<?php echo e(URL::to('admin/pages/edit/'.$allrecord->slug)); ?>" title="Edit" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>
                                <?php } } else{?><a href="<?php echo e(URL::to('admin/pages/edit/'.$allrecord->slug)); ?>" title="Edit" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>
                                <?php } ?>
                                <?php $role = 4; if(isset($checkSubRols[4])){
                                if ($adminLId == 1 || in_array($role, $checkSubRols[4])) { ?>
                                    <a href="#info<?php echo $allrecord->id; ?>" title="View" class="btn btn-primary btn-xs" rel='facebox'><i class="fa fa-eye"></i></a>
                                <?php } } else{?><a href="#info<?php echo $allrecord->id; ?>" title="View" class="btn btn-primary btn-xs" rel='facebox'><i class="fa fa-eye"></i></a>
                                <?php } ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </section>
        <?php echo e(Form::close()); ?>

        </div>         
    </div>
<?php else: ?> 
    <div id="listingJS" style="display: none;" class="alert alert-success alert-block fade in"></div>
    <div class="admin_no_record">No record found.</div>
<?php endif; ?>


<?php if(!$allrecords->isEmpty()): ?>
    <?php $__currentLoopData = $allrecords; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $allrecord): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div id="info<?php echo $allrecord->id; ?>" style="display: none;">
            <div class="nzwh-wrapper">
                <fieldset class="nzwh">
                     <legend class="head_pop"><?php echo $allrecord->title; ?></legend>
                    <div class="drt">
                        <div class="admin_pop"><?php echo $allrecord->description; ?></div>  
                    </div>
                </fieldset>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?><?php /**PATH /home/lscouponslogicsp/public_html/resources/views/elements/admin/pages/index.blade.php ENDPATH**/ ?>