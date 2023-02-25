       <div class="tab-content">
                    <div id="home" class="tab-pane active" >

                        <div class="row" id="listing" > 
   <?php if (!empty($products)) { 
   //print_r($products); exit;
   ?>
                            @forelse($products as $product)
   @include('elements.products.individual_product')
   
@empty
<div class="no_record">{{ __('message.No more records found.') }}</div>
@endforelse
<?php } ?>
                        </div>





                    </div>
             

                    </div>
                 
