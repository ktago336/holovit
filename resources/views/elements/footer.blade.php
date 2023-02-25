<footer>
    <div class="js-footer">
        <div class="footer-bottom">
            <div class="wrapper">
                <div class="container">
                    <div class="footer-bottom__categories">
                        <div class="row">
                            <div class="col-md-3">
                                <p class="txt-white margin-bottom-s font-weight-semibold h6 ftdrop1">Company</p>
                                <p class="txt-white margin-bottom-s font-weight-semibold h6 hrlp">Company</p>
                                <ul class="list-block ftblock1">
                                    <li><a href="{{url('/about-us')}}" target="_blank">About Us</a></li>
                                    <li><a href="{{url('/privacy-policy')}}" target="_blank">Privacy Policy</a></li>
									 <li><a href="{{url('/contact-us')}}" target="_blank">Contact Us</a></li>
                                    <!--<li><a href="{{url('/blog')}}" target="_blank">Blog</a></li>
                                    <li><a href="{{url('/sitemap')}}">Sitemap</a></li>-->
                                    <li><a href="{{url('/universal-fine-print')}}" target="_blank">Universal Fine Print</a></li>
                                </ul>
                            </div>
<?php $availableInCities = DB::table('merchants')->join('cities', 'cities.id', '=', 'merchants.city_id')->where(['cities.status' => 1, 'merchants.status' => 1])->whereNotNull('profile_image')->select(['merchants.id','city_id','cities.name','cities.slug'])->orderBy('total_orders', 'DESC')->groupBy('merchants.city_id')->get()->toArray();

//echo "<pre>"; print_r($availableInCities);exit;
?>
                            	@if($availableInCities)
                            <div class="col-md-9">
                                <p class="txt-white margin-bottom-s font-weight-semibold h6 ftdrop2">Available in</p>
                                 <p class="txt-white margin-bottom-s font-weight-semibold h6 hrlp">Available in</p>
								  <div class="available-bx ftblock2">
                                <ul class="list-block help-category ">
                                    @foreach($availableInCities as $availableInCity)
                                    <li><a href="javascript:void(0);" onclick="clicktocity('{{$availableInCity->city_id}}')">{{$availableInCity->name}}</a></li>
                                    @endforeach
                                   
								   <!-- <li><a href="#">Pune</a></li>
								    <li><a href="#">Pune</a></li>
									 <li><a href="#">Jaipur</a></li>
                                   <li><a href="#">Mumbai</a></li>
                                   <li><a href="#">Bangalore</a></li>
                                   <li><a href="#">Chennai</a></li>
                                   <li><a href="#">Hyderabad</a></li>
								    <li><a href="#">Kolkata</a></li>
                                   <li><a href="#">Agra</a></li>
                                   <li><a href="#">Chandigarh</a></li>
                                   <li><a href="#">Goa</a></li>
								    <li><a href="#">Lucknow</a></li>
                                   <li><a href="#">Indore</a></li>
                                   <li><a href="#">Udaipur</a></li>
                                   <li><a href="#">Ahmedabad</a></li>
								    <li><a href="#">Delhi</a></li>
                                   <li><a href="#">Mumbai</a></li>
                                   <li><a href="#">Bangalore</a></li>
                                   <li><a href="#">Chennai</a></li>
                                   <li><a href="#">Hyderabad</a></li>-->
                                </ul>
								 {{ Form::open(array('url'=>'deals/setlocation','method' => 'post', 'id' => 'setlocation1')) }}
                                    {{Form::hidden('city_id', null, ['class'=>'form-control', 'autocomplete' => 'off', 'id' => 'footer_city_id'])}}
								 {{ Form::close() }}
                            </div>
                            </div>
                            @endif
                            
                        </div>
                    </div>
					<div class="bottom-fotter">
					<div class="footer-logo">
					<a href="">{{HTML::image('public/img/front/logo.png','logo')}}</a>
					 </div>
					 <p>Find the best Restaurants, Deals, Discounts & Offers</p>
					 <ul class="list-inline social-links">
					           @if($siteSettings->youtube_link)
					           <li class="youtube"><a href="{!! $siteSettings->youtube_link !!}" target="_blank"><i class="social-media fa fa-youtube-play" aria-hidden="true"></i></a></li>
                               @endif
                               @if($siteSettings->facebook_link)
                                <li class="facebook-icon"><a href="{!! $siteSettings->facebook_link !!}" target="_blank"><i class="social-media fa fa-facebook" aria-hidden="true"></i></a></li>
                                @endif
								 @if($siteSettings->instagram_link)
                                    <li class="instagram-icon"><a href="{!! $siteSettings->instagram_link !!}" target="_blank"><i class="social-media fa fa-instagram" aria-hidden="true"></i></a></li>
                                @endif
                                @if($siteSettings->twitter_link)
                                    <li class="twitter-icon"><a href="{!! $siteSettings->twitter_link !!}" target="_blank"><i class="social-media fa fa-twitter" aria-hidden="true"></i></a></li>
                                @endif
                                
                                @if($siteSettings->linkedin_link)
                                <li class="linkedin-icon"><a href="{!! $siteSettings->linkedin_link !!}" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
                                @endif
                                @if($siteSettings->pinterest_link)
                                <li class="pinterest-icon"><a href="{!! $siteSettings->pinterest_link !!}" target="_blank"><i class="fa fa-pinterest" aria-hidden="true"></i></a></li>
                                @endif
                               
                            </ul>
                                         <div class="copyrights"> © Copyright @ 2023 | <a href="https://holovit.ru/" target="_blank"> Holovit</a>. All Rights Reserved</div>

                </div>
                </div>
            </div>
        </div>
    </div>

</footer>

<script>
function clicktocity(id){
    //alert(id);
    $("#footer_city_id").val(id); 
    //alert($("#city_id").val());
    $("#setlocation1").submit();
}
</script>








<!--<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-md-3 col-lg-4 col-xl-3">
                <a href="{!! HTTP_PATH !!}"> {{HTML::image(LOGO_PATH, SITE_TITLE, array('alt'=>'Logo'))}}</a>
            </div>
            <div class="col-xs-12 col-md-9 col-lg-8 col-xl-9">
                <div class="footer-link">
                    <ul>
                        <li><a href="{{url('/about')}}">About</a></li>
                        <li><a href="{{url('/services')}}">Services</a></li>
                        <li><a href="{{url('/experts')}}">Our Experts</a></li>
                        <li><a href="{{url('/blog')}}">Blog</a></li>
                        <li><a href="{{url('/testimonial')}}">Testimonial</a></li>
                        <li><a href="{{url('/contact')}}">Contacts</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="copyright">
                    © {!! date('Y') !!} All Rights Reserved Privacy Policy
                </div>
            </div>
        </div>
    </div>
</footer>-->