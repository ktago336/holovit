<?php $__env->startSection('content'); ?>
<script type="text/javascript">
    $(document).ready(function () {
        $("#adminForm").validate();
    });
  
    
</script>
<div class="content-wrapper">
    <section class="content-header">
        <h1> Site Settings</h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo e(URL::to('admin/admins/dashboard')); ?>"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
            <li><a href="javascript:void(0);"><i class="fa fa-cogs"></i> Configuration</a></li>
            <li class="active"> Site Settings</li>
        </ol>
    </section>

    <section class="content">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Site Settings</h3>
            </div>
            <div class="ersu_message"><?php echo $__env->make('elements.admin.errorSuccessMessage', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?></div>
            <?php echo e(Form::model($recordInfo, ['method' => 'post', 'id' => 'adminForm', 'enctype' => "multipart/form-data"])); ?>  
            <div class="form-horizontal">
                <div class="box-body">
                                  
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Site Title <span class="require">*</span></label>
                        <div class="col-sm-10">
                            <?php echo e(Form::text('site_title', null, ['class'=>'form-control required', 'placeholder'=>'Site title', 'autocomplete' => 'off'])); ?>

                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Company Name <span class="require">*</span></label>
                        <div class="col-sm-10">
                            <?php echo e(Form::text('company_name', null, ['class'=>'form-control required', 'placeholder'=>'Company name', 'autocomplete' => 'off'])); ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Contact Number <span class="require">*</span></label>
                        <div class="col-sm-10">
                            <?php echo e(Form::text('contact_number', null, ['class'=>'form-control required', 'placeholder'=>'Contact number', 'autocomplete' => 'off', 'minlength' => 8, 'maxlength' => 16])); ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Contact Email <span class="require">*</span></label>
                        <div class="col-sm-10">
                            <?php echo e(Form::text('contact_email', null, ['class'=>'form-control required email', 'placeholder'=>'Contact email', 'autocomplete' => 'off'])); ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Company Address <span class="require">*</span></label>
                        <div class="col-sm-10">
                            <?php echo e(Form::textarea('address', null, ['class'=>'form-control required', 'placeholder'=>'Enter your company address', 'autocomplete' => 'off', 'rows'=>4])); ?>

                        </div>
                    </div>
<div class="form-group">
                        <label class="col-sm-2 control-label">Home Logo <span class="require"></span></label>
                        <div class="col-sm-10">
                            <?php echo e(Form::file('home_logo', ['class'=>'form-control', 'accept'=>'image/png'])); ?>

                            <span class="help-text"> Supported File Types: png (Max. 2MB) (Best view:183 x 46px)</span>
                            <?php if($recordInfo->home_logo != ''): ?>
                               <div><?php echo e(HTML::image(LOGO_IMAGE_DISPLAY_PATH.$recordInfo->home_logo, SITE_TITLE,['style'=>"max-width: 200px"])); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Logo <span class="require"></span></label>
                        <div class="col-sm-10">
                            <?php echo e(Form::file('logo', ['class'=>'form-control', 'accept'=>'image/png'])); ?>

                            <span class="help-text"> Supported File Types: png (Max. 2MB) (Best view:183 x 46px)</span>
                            <?php if($recordInfo->logo != ''): ?>
                               <div><?php echo e(HTML::image(LOGO_IMAGE_DISPLAY_PATH.$recordInfo->logo, SITE_TITLE,['style'=>"max-width: 200px"])); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Favicon Icon <span class="require"></span></label>
                        <div class="col-sm-10">
                            <?php echo e(Form::file('favicon', ['class'=>'form-control', 'accept'=>'image/png'])); ?>

                            <span class="help-text"> Supported File Types: png (Max. 50KB)</span>
                            <?php if($recordInfo->favicon != ''): ?>
                               <div><?php echo e(HTML::image(LOGO_IMAGE_DISPLAY_PATH.$recordInfo->favicon, SITE_TITLE)); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="box-header with-border"><h3 class="box-title">Social Links</h3></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Facebook Link <span class="require"></span></label>
                        <div class="col-sm-10">
                            <?php echo e(Form::text('facebook_link', null, ['class'=>'form-control url', 'placeholder'=>'Facebook link', 'autocomplete' => 'off'])); ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Twitter Link <span class="require"></span></label>
                        <div class="col-sm-10">
                            <?php echo e(Form::text('twitter_link', null, ['class'=>'form-control url', 'placeholder'=>'Twitter link', 'autocomplete' => 'off'])); ?>

                        </div>
                    </div>
                   
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Instagram  Link <span class="require"></span></label>
                        <div class="col-sm-10">
                            <?php echo e(Form::text('instagram_link', null, ['class'=>'form-control url', 'placeholder'=>'Instagram link', 'autocomplete' => 'off'])); ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Linkedin  Link <span class="require"></span></label>
                        <div class="col-sm-10">
                            <?php echo e(Form::text('linkedin_link', null, ['class'=>'form-control url', 'placeholder'=>'Linkedin link', 'autocomplete' => 'off'])); ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Pinterest  Link <span class="require"></span></label>
                        <div class="col-sm-10">
                            <?php echo e(Form::text('pinterest_link', null, ['class'=>'form-control url', 'placeholder'=>'Pinterest link', 'autocomplete' => 'off'])); ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Youtube  Link <span class="require"></span></label>
                        <div class="col-sm-10">
                            <?php echo e(Form::text('youtube_link', null, ['class'=>'form-control url', 'placeholder'=>'Youtube link', 'autocomplete' => 'off'])); ?>

                        </div>
                    </div>
                    
                    <?php if(IS_DEMO == 0): ?>
                        <div class="box-footer">
                            <label class="col-sm-2 control-label" for="inputPassword3">&nbsp;</label>
                            <?php echo e(Form::submit('Submit', ['class' => 'btn btn-info'])); ?>

                            <a href="<?php echo e(URL::to('admin/admins/dashboard')); ?>" class="btn btn-default canlcel_le">Cancel</a>
                        </div>
                    <?php else: ?>
                         <blockquote> You are not allowed to update above information, because it's a demo of this product. Once we deliver code to you, you'll be able to update this information. </blockquote>
                    <?php endif; ?>
                </div>
            </div>
            <?php echo e(Form::close()); ?>

        </div>
    </section>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/holovitr/domains/holovit.ru/public_html/resources/views/admin/admins/siteSettings.blade.php ENDPATH**/ ?>