                                  <?php
                                    $location = $product->location_id;
                                    ?>
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 fl-column">
                                        <a class="card-main card-main--equal-height cursor-pointer" same-height="" href="">
                                            <div class="card-main__content card-main__content--lg">

                                                <div class="fl-row fl-row--gutter">
                                                    <div class="fl-column">
                                                        <h2 class="card-main__heading">
                                                            <?php echo $product->name; ?>
                                                            <?php $location = DB::table('locations')->where(['id' => $product->location_id, 'status' => 1])->first(); ?>
                                                            <span class="card-main__value m_locality line-height-xs display-inline-block font-weight-regular display-block margin-top-xs"><?php echo $location->location_name; ?></span>

                                                        </h2>

                                                    </div>
                                                    <?php
                                                    $is_set_image = 0;
                                                    $image = explode(',', $product->images);
                                                    foreach ($image as $key => $images) {
                                                        ?>
                                                        <div class="fl-column">
                                                            {{HTML::image(PRODUCT_SMALL_DISPLAY_PATH.$images, SITE_TITLE,['style'=>"max-width: 90px; max-height:54px;",'class'=>"card-main__img"])}}
                                                        </div>
                                                        <?php
                                                        $is_set_image = 1;
                                                        break;
                                                    }
                                                    ?>
                                                </div>


                                                <?php
                                                $deal = \DB::table("deals")
                                                        ->select("deals.*")
                                                        ->where(['status' => 1])
                                                        ->whereRaw("find_in_set($product->id, product_id)")
                                                        ->first();

                                                if (!empty($deal)){
                                                    ?>
                                                    <div class="card-list margin-top-s">
                                                        <div class="card-main">

                                                            <span class="tag tag--delight tag--small txt-uppercase bg-delight-1">Deals</span>
                                                            <span class="card-main__value txt-primary ellipsis line-height-primary font-weight-semibold ellipsis--sm"><?php echo $deal->deal_name; ?></span>
                                                            <span class="card-main__value txt-primary line-height-primary font-weight-semibold"> from
                                                                <i class="fa fa-inr"></i> <?php echo $deal->final_price; ?>
                                                            </span>


                                                        </div>
                                                    </div>
                                                <?php } else { ?>
                                                    <div class="card-list margin-top-s">
                                                        <div class="card-main">

                                                            <span class="tag tag--delight tag--small txt-uppercase bg-delight-1">No Deal</span>
                                                            <span class="card-main__value txt-primary ellipsis line-height-primary font-weight-semibold ellipsis--sm">Deals Not available for this product</span>
                                                            <span class="card-main__value txt-primary line-height-primary font-weight-semibold"> from
                                                                <i class="fa fa-inr"></i><?php echo $product->price; ?>
                                                            </span>


                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                            <div class="card-main__footer">

                                                <div class="section content-footer">
                                                    <div class="">

                                                        <span class="card-main__field line-height-xs font-weight-semibold">Services - </span>
                                                        <span class="card-main__value line-height-xs font-weight-semibold ellipsis">
                                                            Hair Color, Hair Spa, Hair Wash, Hair Styling, Keratin, Rebonding, Smoothening, Bleach, Cleanup, De-Tan, Facial, Scrub, Threading, Shave, Beard Trim, Pedicure, Polish, Manicure, Waxing, Bridal Package, Makeup, Dress Draping, Skin Treatment
                                                        </span>

                                                    </div>
                                                </div>
                                                <div class="section bg-primary content-footer border-radius--bottom">
                                                    <div class="fl-row fl-row--middle">
                                                        <div class="fl-column">
                                                            <p class="card-main__field line-height-xs font-weight-semibold txt-secondary bought-count">415 Bought</p>
                                                        </div>
                                                        <div class="fl-column">

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                              