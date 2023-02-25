{{ HTML::script('public/js/facebox.js')}}
{{ HTML::style('public/css/facebox.css')}}
<script type="text/javascript">
    $(document).ready(function ($) {
        $('.close_image').hide();
        $('a[rel*=facebox]').facebox({
            closeImage: '{!! HTTP_PATH !!}/public/img/close.png'
        });
    });
</script>

<div class="page_loader" id="loaderID">{{HTML::image("public/img/website_load.svg", SITE_TITLE)}}
</div>
@if(!$experts->isEmpty())
<div class="marginzero-expertlist">
    <div class="ersu_message">@include('elements.admin.errorSuccessMessage')</div>
    {{ Form::open(array('method' => 'post', 'id' => 'actionFrom')) }}
    <section id="no-more-tables" class="lstng-section">
        <div class="topn-expert">
            <div class="topn_rightd ddpagingshorting" id="pagingLinks" align="right">
                <div class="panel-heading" style="align-items:center;">
                    {{$experts->appends(Input::except('_token'))->render()}}
                </div>
            </div>                
        </div>
    
                <div class="top-moduls-expert">
                    <div class="row">
                        <!-- <div> -->
                            <?php foreach($experts as $expert){
                            ?>
                            <div class="col-xs-12 col-md-3 col-lg-3 col-xl-3 ">
                                <div class="card expert-thumnail">
                                   
                                    <div class="letest-img experts-img">
                                         <a href="{{url('/expertdetail/'.$expert->slug)}}">
                                        <?php $eximg=($expert->profile_image!=null && $expert->profile_image!='') ? PROFILE_FULL_DISPLAY_PATH.$expert->profile_image:PROFILE_FULL_DISPLAY_PATH.'no_user_img.png'?>
                                        {{HTML::image($eximg,'img')}}
                                        </a>
                                    </div>
                                        
                                    <div class="card-body">
                                    <div class="card-boxes">
                                        <h3><a href="{{url('/expertdetail/'.$expert->slug)}}">{{ucfirst($expert->first_name)." ".ucfirst($expert->last_name )}}</a></h3>
                                        <p><?php echo (strlen($expert->service_names)<20)?$expert->service_names:substr($expert->service_names,0,20).'...';?></p>
                                    </div>
                                        <div class="book-exprtt"><a href="{{url('/expertdetail/'.$expert->slug)}}"><span>Book Now</span><i>{{HTML::image('public/img/front/book-arrow.png','our-clients')}}</i></a></div>
                                    </div>
                                    
                                </div>
                            </div>
                            
                            <?php }?>

                        <!-- </div> -->
                    </div>
                    <!-- <div>&nbsp;</div>
                    <div class="view-all"><a href="#">View All</a></div> -->
                </div>
        
    </section>
    {{ Form::close()}}
</div>         
<!-- </div>  -->
@else 
<div id="listingJS" style="display: none;" class="alert alert-success alert-block fade in"></div>
<div class="admin_no_record">No record found.</div>
@endif

