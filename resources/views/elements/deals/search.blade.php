       <div class="tab-content">
                    <div id="home" class="tab-pane active" >

                        <div class="row" id="listing" > 
   <?php if (!empty($merchants)) { 
   //print_r($products); exit;
   ?>
                            @forelse($merchants as $merchant)
   @include('elements.deals.individual_product')
   
@empty
<div class="no_record" style="padding-top: 102px;padding-left: 113px;"><h2>{{('No Merchants available for this Category.') }}</h1></div>
@endforelse
<?php } ?>
                        </div>





                    </div>
             

                    </div>
                 
