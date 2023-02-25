<script>
$(document).ready(function(){
	$('#search_location').keyup(function(){

        var searchText = $(this).val();

        $('ul > li').each(function(){

            var currentLiText = $(this).text(),
                showCurrentLi = currentLiText.indexOf(searchText) !== -1;

            $(this).toggle(showCurrentLi);

        });     
    });
})
</script>




<div class="col-md-4 col-sm-4 col-lg-3">

                      <div class="card-main1">
                       <p class="cat12">Categories</p>
                       <?php $count = 0; ?>
  

						@if($category_merchants)
							@foreach($category_merchants as $ct_id=>$ct_count)
							<div class="question_button_sec" id="question_button_sec">
                                <!--<a href="<?php echo HTTP_PATH; ?>deals/search/{{$categories[$ct_id]['slug']}}" class="righ_0 collapsed" for="address" data-toggle="collapse" data-target="#collapsetwo<?php echo $count; ?>" aria-controls="collapseOne" aria-expanded="false">-->
                                <a href="<?php echo HTTP_PATH; ?>/deals/search/{{$categories[$ct_id]['slug']}}" class="righ_0 collapsed" for="address" aria-expanded="false">
								{{$categories[$ct_id]['category_name']}}
									<span>{{$ct_count}}</span>
                                </a>
								<!--<input type="hidden" value="" id="" name="parent_id">-->
                            </div> 
							@endforeach
						@endif
							
 

                            <!--<div id="collapsetwo" class="one collapse " data-parent="#accordion" style="">
                                
                                <div class="question_form_sec" id="question_form_sec">
                              
                                    <label class="subcat_check full-width deltimesub" style="">

										<input id="testsub" type="radio" name="subcategory_id" value="" class="deltimesub"><span class="txt-primary padding-left-xs" id="show_sub_cat_products">subcate name</span>
										  
										 <input type="hidden" value="" id="" name="subsubcat_id"> 
										
										<span class="count flt-right">20</span>
									  </span>
									</label>
                                 </div>  
                                
                                  
                            </div>-->


  
 
                             <p class="cat12">Location</p>
                             <div class="serch">
							 {{Form::search('search_location', null, ['class'=>'form-group form-control ng-untouched ng-pristine ng-valid', 'placeholder'=>'Search for a location', 'id'=>'search_location'])}}
                                                          <!--<input class="form-group form-control ng-untouched ng-pristine ng-valid" placeholder="Search for a location" type="search">-->
                           </div>	
						
                          
                           <div class="question_button_sec result" id="question_button_sec">

                                <a class="righ_0 collapsed" for="address" data-toggle="collapse" data-target="#collapsethree" aria-controls="collapseOne" aria-expanded="false"> {{$selected_city->name}}
                                <!--<span>({{array_sum($locality_merchants)}})</span>-->
                                </a>

                            </div>
                            <div id="collapsethree" class="one" data-parent="#accordion" style="">
                                <div class="question_form_sec" id="question_form_sec">
								<?php  
								//$location_ids = $subcategories = DB::table('products')->where(['category_id' => $cat_id, 'status' => 1])->select('location_id')->get();
								//echo '<pre>';print_r(array_unique($location_ids));
								//echo '<pre>';print_r($location_ids); exit;
								//foreach($location_ids as $key => $location_id){
									//print_r($location_id); exit;
								//$crt_loc_array = explode(',',$location_id['location_id']);
								//echo '<pre>';print_r($location_id); exit;
								
								?>
                             <ul class="list-block">
							 @if($locality_merchants)
							 @foreach($locality_merchants as $lc_id=>$lc_count)
							 <li>
								<label class="nb-checkbox full-width">
								  <input value="{{$lc_id}}" name="locality_ids[]" type="checkbox" class="locality_ids ng-pristine ng-valid ng-touched">
								  <div class="nb-checkbox__bg">
									<div class="nb-checkbox__icon"></div>
								  </div>
								  <span class="txt-primary padding-left-xs">{{$localities[$lc_id]}}
									<span class="count flt-right">({{$lc_count}})</span>
								  </span>
								</label>
							  </li>
							 @endforeach
							 @endif
          
        </ul>
                              <?php // } ?>  
                                 </div>  
                                 <!--<a class="view-more font-xs txt-brand-primary">+ View More</a>-->

                            </div>
                      </div>  
                    </div>
					<input type="hidden" id="order_type" value="{{$order_type}}" name="order_type">
					<input type="hidden" id="keyword" value="{{$search_keyword}}" name="keyword">
<script type="text/javascript">
    $(document).ready(function () {  
		$(".locality_ids").click(function (event) {
			//alert();
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
			$.ajax({ 
				type: 'POST',  
				url: '<?php echo HTTP_PATH; ?>/deals/search<?php echo $slug?"/".$slug:"";?>',
				data: $("#searchform").serialize(),
				success: function (data) {
					 //alert(data);
					//NProgress.done();
					$('.page-list-content').html(data);
					//$("#loaderID").hide();


				},
				error: function (data) {
					console.log("error");
					console.log(data);
				}
			});
			});
//         $(".deltimesub").click('change', function (event) {
//            updateresult ();
//        });
//        $('#show_sub_cat_products').click(function() { 
//          //alert('sdfds');
//       var parent_id = "<?php //echo $allrecord->id; ?>"; 
//       var subcat = "<?php //echo $subcategorie->id; ?>";
//       var slug = "<?php //echo $subsubcat; ?>";
//           $.ajaxSetup({
//        				headers: {
//        					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//        				}
//        			});
//        $.ajax({ 
//            type: 'POST',  
//            url: '<?php //echo HTTP_PATH; ?>/products/searchsubcat',
//            data: {'slug': slug,'parent_id':parent_id,'subcat':subcat},
//            success: function (data) {
//                 //alert(data);
//                //NProgress.done();
//                $(".search").html(data);
//                //$("#loaderID").hide();
//
//
//            },
//            error: function (data) {
//                console.log("error");
//                console.log(data);
//            }
//        });
//        return false;
//    });
    });
 </script>