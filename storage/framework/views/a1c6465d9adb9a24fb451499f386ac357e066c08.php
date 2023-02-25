       <div class="tab-content">
                    <div id="home" class="tab-pane active" >

                        <div class="row" id="listing" > 
   <?php if (!empty($merchants)) { 
   //print_r($products); exit;
   ?>
                            <?php $__empty_1 = true; $__currentLoopData = $merchants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $merchant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
   <?php echo $__env->make('elements.deals.individual_product', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
   
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
<div class="no_record" style="padding-top: 102px;padding-left: 113px;"><h2><?php echo e(('No Merchants available for this Category.')); ?></h1></div>
<?php endif; ?>
<?php } ?>
                        </div>





                    </div>
             

                    </div>
                 
<?php /**PATH /home/holovitr/domains/holovit.ru/public_html/resources/views/elements/deals/search.blade.php ENDPATH**/ ?>