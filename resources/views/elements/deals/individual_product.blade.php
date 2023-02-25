                                 
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 fl-column">
                                        <a class="card-main card-main--equal-height cursor-pointer" same-height="" href="<?php echo HTTP_PATH.'/deals/detail/'.$merchant->slug; ?>">
                                            <div class="card-main__content card-main__content--lg">
<?php //echo '<pre>'; print_r($merchant); exit; ?>
                                                <div class="fl-row fl-row--gutter">
                                                    <div class="fl-column">
                                                        <h2 class="card-main__heading">
                                                            <?php echo $merchant->busineess_name; ?>
                                                            
                                                            <span class="card-main__value m_locality line-height-xs display-inline-block font-weight-regular display-block margin-top-xs"><?php echo isset($merchant->City->name)?$merchant->City->name:''; ?></span>

                                                        </h2>

                                                    </div>
                                                    <?php
                                                    $is_set_image = 0;
                                                    $image = explode(',', $merchant->profile_image);
													
													if($image != ''){
                                                    foreach ($image as $key => $images) {
                                                        ?>
                                                        <div class="fl-column">
                                                           {{HTML::image(MERCHANT_SMALL_DISPLAY_PATH.$images, SITE_TITLE,['style'=>"max-width: 90px; max-height:54px;",'class'=>"card-main__img"])}}
                                                        </div>
                                                        <?php
                                                        $is_set_image = 1;
                                                        break;
                                                    }}else{ ?>
														<img height="50" src="<?php echo HTTP_PATH; ?>/public/img/noimage.png ?>" alt="Image"/>
													<?php 
													}
                                                    ?>
                                                </div>


                                                <?php
                                               // $deal = \DB::table("deals")
                                                    //    ->select("deals.*")
                                                      //  ->where(['status' => 1,'merchant_id'=>$merchant->id])
														//->max('price')
                                                        //->whereRaw("find_in_set($product->id, product_id)")
                                                       // ->first();

                                               // if (!empty($deal)){
                                                    ?>
                                                    <div class="card-list margin-top-s">
                                                        <div class="card-main">
														<?php if(!empty( $merchant->currentDeal)){ ?>
                                                            <span class="tag tag--delight tag--small txt-uppercase bg-delight-1">Deals</span>
                                                            <span class="card-main__value txt-primary ellipsis line-height-primary font-weight-semibold ellipsis--sm">{{$merchant->currentDeal->deal_name}}</span>
                                                            <span class="card-main__value txt-primary line-height-primary font-weight-semibold"> from
                                                                {{CURR.$merchant->currentDeal->final_price}}
                                                            </span>
														<?php }else{ ?>
															<span class="tag tag--delight tag--small txt-uppercase bg-delight-1">No Deal</span>
                                                            <span class="card-main__value txt-primary ellipsis line-height-primary font-weight-semibold ellipsis--sm">Deals Not available for this product</span>
                                                            <!--<span class="card-main__value txt-primary line-height-primary font-weight-semibold"> from
                                                                <i class="fa fa-inr"></i>100rs
                                                            </span>-->
														<?php } ?>

                                                        </div>
                                                    </div>
                                                <?php //} else { ?>
                                                    
                                                <?php //} ?>
                                            </div>
                                            <div class="card-main__footer">

                                                <div class="section content-footer">
                                                    <div class="">

                                                        <span class="card-main__field line-height-xs font-weight-semibold">Services - </span>
                                                        <span class="card-main__value line-height-xs font-weight-semibold ellipsis">
														<?php 
														if($merchant->service_ids){
															$sids=explode(',',$merchant->service_ids);
															//print_r($merchant->service_ids);
															$serviceobj = DB::table('categories')->where(['status' => 1])->whereIn('id', $sids)->orderBy('category_name', 'ASC')->pluck('category_name', 'id'); 
														    echo implode(', ',json_decode(json_encode($serviceobj),true));
														}else{
															echo "No service available!";
														}
														 ?>
                                                            <!--Hair Color, Hair Spa, Hair Wash, Hair Styling, Keratin, Rebonding, Smoothening, Bleach, Cleanup, De-Tan, Facial, Scrub, Threading, Shave, Beard Trim, Pedicure, Polish, Manicure, Waxing, Bridal Package, Makeup, Dress Draping, Skin Treatment-->
                                                        </span>

                                                    </div>
                                                </div>
                                                <div class="section bg-primary content-footer border-radius--bottom">
                                                    <div class="fl-row fl-row--middle">
                                                        <div class="fl-column">
                                                            <p class="card-main__field line-height-xs font-weight-semibold txt-secondary bought-count"><?php echo count($merchant->allOrder);?> Bought</p>
                                                        </div>
                                                        <div class="fl-column">

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                              