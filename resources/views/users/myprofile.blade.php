@extends('layouts.inner')
@section('content')
<section class="profile-section">
    <div class="container">
    	<div class="heading1">  My Stuff</div>
        <div class="row">

<nav class="tab_groupn">
  <div class="nav nav-tabs" id="nav-tab" role="tablist">
    <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-groupons" role="tab" aria-controls="nav-home" aria-selected="true">ACCOUNT MY GROUPONS</a>
    <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-deals" role="tab" aria-controls="nav-profile" aria-selected="false">MY GROUPON+ DEALS</a>
    <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-reservtions" role="tab" aria-controls="nav-contact" aria-selected="false"> MY RESERVATIONS</a>
 <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-account" role="tab" aria-controls="nav-contact" aria-selected="false">ACCOUNT</a>
 <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-contact" aria-selected="false">Profile </a>
  <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-select" role="tab" aria-controls="nav-contact" aria-selected="false">GROUPON SELECT</a>
  </div>
</nav>
<div class="tab-content" id="nav-tabContent">
  <div class="tab-pane fade show active" id="nav-groupons" role="tabpanel" aria-labelledby="nav-home-tab">Consequat occaecat ullamco amet non eiusmod nostrud dolore irure incididunt est duis anim sunt officia. Fugiat velit proident aliquip nisi incididunt nostrud exercitation proident est nisi. Irure magna elit commodo anim ex veniam culpa eiusmod id nostrud sit cupidatat in veniam ad. Eiusmod consequat eu adipisicing minim anim aliquip cupidatat culpa excepteur quis. Occaecat sit eu exercitation irure Lorem incididunt nostrud.</div>
  <div class="tab-pane fade" id="nav-deals" role="tabpanel" aria-labelledby="nav-profile-tab">





  </div>




  <div class="tab-pane fade" id="nav-reservtions" role="tabpanel" aria-labelledby="nav-contact-tab">Consequat occaecat ullamco amet non eiusmod nostrud dolore irure incididunt est duis anim sunt officia. Fugiat velit proident aliquip nisi incididunt nostrud exercitation proident est nisi. Irure magna elit commodo anim ex veniam culpa eiusmod id nostrud sit cupidatat in veniam ad. Eiusmod consequat eu adipisicing minim anim aliquip cupidatat culpa excepteur quis. Occaecat sit eu exercitation irure Lorem incididunt nostrud.</div>
  <div class="tab-pane fade" id="nav-account" role="tabpanel" aria-labelledby="nav-contact-tab">
  	<nav class="nav_groupn">
  <div class="nav nav-tabs" id="nav-tab" role="tablist">
    <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-account1" role="tab" aria-controls="nav-home" aria-selected="true">ACCOUNT</a>
    <a class="nav-item nav-link" id="nav-home-tab" data-toggle="tab" href="#nav-bucks" role="tab" aria-controls="nav-home" aria-selected="true">Groupon Bucks</a>
  
  </div>
</nav>
  	<div class="tab-content from_groupn" id="nav-tabContent">
  		 <div class="tab-pane fade show active" id="nav-account1" role="tabpanel" aria-labelledby="nav-home-tab">
  		 	<form>
  		 	<div class="row">
  		 		<div class="col-md-6">
  		 			<div class="form-group">
    <label for="exampleInputEmail1">First Name</label>
    <input type="email" class="form-control" placeholder="First Name">
   
  </div>
   <div class="form-group">
     <label for="exampleInputPassword1">Last Name </label>
     <input type="password" class="form-control" placeholder="Last Name">
   </div>

<div class="field email_address form-group">
            <div class="emailAddress">Email Address</div>
            <span class="address">
              praveen.kulharee@logicspice.com
                <a href="#" data-bhw="EmailChange" data-bhw-path="EmailChange">Change</a>
            </span>
          </div>

<h3 class="change">Change Your Password</h3>
<div class="form-group">
            
              <label class="current" for="password">Current Password</label>
              <span class="reset">Can't remember your password? <a href="">Reset it now</a></span>
           
            <input type="password" class="form-control" placeholder="Last Name">
          </div>
          <div class="form-group">
             <label for="exampleInputPassword1">New Password</label>
     <input type="password" class="form-control" placeholder="Last Name">
          </div>
         <div class="form-group">
             <label for="exampleInputPassword1">Retype New Password </label>
     <input type="password" class="form-control" placeholder="Last Name">
          </div>
          <div class="submit-row">
            <input type="submit" class="btn-cta" value="Save Changes">
          </div>
  </div>
 
</div>
   </form>

  		 		</div>

          


  		
 <div class="tab-pane fade" id="nav-bucks" role="tabpanel" aria-labelledby="nav-home-tab">Consequat occaecat ullamco amet non eiusmod nostrud dolore irure incididunt est duis anim sunt officia. Fugiat velit proident aliquip nisi incididunt nostrud exercitation proident est nisi. Irure magna elit commodo anim ex veniam culpa eiusmod id nostrud sit cupidatat in veniam ad. Eiusmod consequat eu adipisicing minim anim aliquip cupidatat culpa excepteur quis. Occaecat sit eu exercitation irure Lorem incididunt nostrud.</div>
  	</div>
  </div>
  <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-contact-tab">

<div class="personalize-banner c-bdr-lightgray-md"><h3>Preferences</h3></div>
<div class="row">
  		<div class="col-md-3">
<div class="gender-identity-title"><span class="section-title-header title">Gender Identity</span><span class="optional">optional</span><span class="circle tooltip-trigger" data-tooltip-id="gender-help" data-position="right">?</span></div>
<div class="gender-identity-buttons">
	<div class="button selected" data-id="male">Male</div>
	<div class="button" data-id="female">Female</div>
	<div class="button" data-id="both">Other</div>
	<div class="tooltip" role="tooltip" aria-hidden="true" id="gender-help">
		<div class="indicator">
			
		</div>
		<div class="tooltip-content">
			<span>We will show you deals that we think you will love based on your profile</span></div>
		</div>
	</div>
</div>
<div class="col-md-3">
	<div class="gender-identity-title"><span class="section-title-header title">Birth Date</span><span class="optional">optional</span><span class="circle tooltip-trigger" data-tooltip-id="gender-help" data-position="right">?</span></div>
	<div class="birthdate-input-container"><input aria-labelledby="birthdate-title" class="birthdate-input" pattern="\d\d/\d\d/\d\d\d\d" placeholder="MM/DD/YYYY" type="text" value=""></div>
</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="locations">
			<span id="locations-title" class="section-title-header title">My Locations</span>
			<div class="input-add">
				<div class="gig-field location-input">
					<input aria-labelledby="locations-title" type="text" class="address" name="locationString" value="" placeholder="Add a location">
				</div><div class="button-container">
					<span class="button selected" data-id="home">Home</span>
					<span class="button" data-id="work">Work</span>
					<span class="button" data-id="play">Favorite</span>
				</div><div class="location-add-container">
					<span class="location-add-button">Add</span>
				</div>
				</div><ul class="locations-list"></ul>
			</div>
	</div>
</div>
      <div class="lo2">
                    <h2 class="local-section-heading">
               <a class="category-name cursor-default">Breakfast of Champions 👑</a>
              </h2>
               </div>
            <div class="row">
              
                    <div class="col-sm-6 col-md-6 col-lg-3 col-xs-12">
                        <div class="card">
                            <div class="card__inner">
                                <a href="">
                                    <div class="card__image">
                                       <img src="http://192.168.0.251:85/comp212/groupon_clone/site/public/img/front/cardimg1.jpeg" class="img-responsive" width="261" data-lzled="true" alt="Breakfast Buffets">
                                        <div class="card__favourite">
                                            <label class="nb-checkbox">
                                                <i class="fa fa-heart-o" aria-hidden="true"></i>
                                            </label>
                                        </div>
                                        <div class="card__rating">
                                            <span class="rating-icon nearbuy"></span>
                                            <span class="rating-score" style="color: rgb(63, 126, 0);">New</span>
                                        </div>
                                    </div>
                                    <div class="card__description">
                                        <p class="card__title">County's Kitchen</p>
                                        <div class="card__location">
                                            <p class="card__location"> Big Bazaar Gift Vouchers Worth up to Rs-6000</p>
                                        </div>
                                        <hr class="margin-reset">
                                        <div class="margin-top-s">
                                            <p class="txt-brand-secondary"><i class="fa fa-inr" aria-hidden="true"></i> 1,000</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-3 col-xs-12">
                        <div class="card">
                            <div class="card__inner">
                                <a href="">
                                    <div class="card__image">
{{HTML::image('public/img/front/cardimg1.jpeg','Breakfast Buffets', array('class' => 'img-responsive', 'width'=>'261', 'data-lzled'=>'true'))}}
                                        <div class="card__favourite">
                                            <label class="nb-checkbox">
                                                <i class="fa fa-heart-o" aria-hidden="true"></i>
                                            </label>
                                        </div>
                                        <div class="card__rating">
                                            <span class="rating-icon nearbuy"></span>
                                            <span class="rating-score" style="color: rgb(63, 126, 0);">New</span>
                                        </div>
                                    </div>
                                    <div class="card__description">
                                        <p class="card__title">County's Kitchen</p>
                                        <div class="card__location">
                                            <p class="card__location"> Big Bazaar Gift Vouchers Worth up to Rs-6000</p>
                                        </div>
                                        <hr class="margin-reset">
                                        <div class="margin-top-s">
                                            <p class="txt-brand-secondary"><i class="fa fa-inr" aria-hidden="true"></i> 300</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-3 col-xs-12">
                        <div class="card">
                            <div class="card__inner">
                                <a href="">
                                    <div class="card__image">
                                      {{HTML::image('public/img/front/cardimg1.jpeg','Breakfast Buffets', array('class' => 'img-responsive', 'width'=>'261', 'data-lzled'=>'true'))}}
                                        <div class="card__favourite">
                                            <label class="nb-checkbox">
                                                <i class="fa fa-heart-o" aria-hidden="true"></i>
                                            </label>
                                        </div>
                                        <div class="card__rating">
                                            <span class="rating-icon nearbuy"></span>
                                            <span class="rating-score" style="color: rgb(63, 126, 0);">New</span>
                                        </div>
                                    </div>
                                    <div class="card__description">
                                        <p class="card__title">County's Kitchen</p>
                                        <div class="card__location">
                                            <p class="card__location"> Big Bazaar Gift Vouchers Worth up to Rs-6000</p>
                                        </div>
                                        <hr class="margin-reset">
                                        <div class="margin-top-s">
                                            <p class="txt-brand-secondary"><i class="fa fa-inr" aria-hidden="true"></i> 2,500</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-3 col-xs-12">
                        <div class="card">
                            <div class="card__inner">
                                <a href="">
                                    <div class="card__image">
                                       {{HTML::image('public/img/front/cardimg1.jpeg','Breakfast Buffets', array('class' => 'img-responsive', 'width'=>'261', 'data-lzled'=>'true'))}}
                                        <div class="card__favourite">
                                            <label class="nb-checkbox">
                                                <i class="fa fa-heart-o" aria-hidden="true"></i>
                                            </label>
                                        </div>
                                        <div class="card__rating">
                                            <span class="rating-icon nearbuy"></span>
                                            <span class="rating-score" style="color: rgb(63, 126, 0);">New</span>
                                        </div>
                                    </div>
                                    <div class="card__description">
                                        <p class="card__title">County's Kitchen</p>
                                        <div class="card__location">
                                            <p class="card__location"> Big Bazaar Gift Vouchers Worth up to Rs-6000</p>
                                        </div>
                                        <hr class="margin-reset">
                                        <div class="margin-top-s">
                                            <p class="txt-brand-secondary"><i class="fa fa-inr" aria-hidden="true"></i> 100</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                       <div class="col-sm-6 col-md-6 col-lg-3 col-xs-12">
                        <div class="card">
                            <div class="card__inner">
                                <a href="">
                                    <div class="card__image">
                                      {{HTML::image('public/img/front/cardimg1.jpeg','Breakfast Buffets', array('class' => 'img-responsive', 'width'=>'261', 'data-lzled'=>'true'))}}
                                        <div class="card__favourite">
                                            <label class="nb-checkbox">
                                                <i class="fa fa-heart-o" aria-hidden="true"></i>
                                            </label>
                                        </div>
                                        <div class="card__rating">
                                            <span class="rating-icon nearbuy"></span>
                                            <span class="rating-score" style="color: rgb(63, 126, 0);">New</span>
                                        </div>
                                    </div>
                                    <div class="card__description">
                                        <p class="card__title">County's Kitchen</p>
                                        <div class="card__location">
                                            <p class="card__location"> Big Bazaar Gift Vouchers Worth up to Rs-6000</p>
                                        </div>
                                        <hr class="margin-reset">
                                        <div class="margin-top-s">
                                            <p class="txt-brand-secondary"><i class="fa fa-inr" aria-hidden="true"></i> 100</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                       <div class="col-sm-6 col-md-6 col-lg-3 col-xs-12">
                        <div class="card">
                            <div class="card__inner">
                                <a href="">
                                    <div class="card__image">
                                     {{HTML::image('public/img/front/cardimg1.jpeg','Breakfast Buffets', array('class' => 'img-responsive', 'width'=>'261', 'data-lzled'=>'true'))}}
                                        <div class="card__favourite">
                                            <label class="nb-checkbox">
                                                <i class="fa fa-heart-o" aria-hidden="true"></i>
                                            </label>
                                        </div>
                                        <div class="card__rating">
                                            <span class="rating-icon nearbuy"></span>
                                            <span class="rating-score" style="color: rgb(63, 126, 0);">New</span>
                                        </div>
                                    </div>
                                    <div class="card__description">
                                        <p class="card__title">County's Kitchen</p>
                                        <div class="card__location">
                                            <p class="card__location"> Big Bazaar Gift Vouchers Worth up to Rs-6000</p>
                                        </div>
                                        <hr class="margin-reset">
                                        <div class="margin-top-s">
                                            <p class="txt-brand-secondary"><i class="fa fa-inr" aria-hidden="true"></i> 100</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                       <div class="col-sm-6 col-md-6 col-lg-3 col-xs-12">
                        <div class="card">
                            <div class="card__inner">
                                <a href="">
                                    <div class="card__image">
                                      {{HTML::image('public/img/front/cardimg1.jpeg','Breakfast Buffets', array('class' => 'img-responsive', 'width'=>'261', 'data-lzled'=>'true'))}}
                                        <div class="card__favourite">
                                            <label class="nb-checkbox">
                                                <i class="fa fa-heart-o" aria-hidden="true"></i>
                                            </label>
                                        </div>
                                        <div class="card__rating">
                                            <span class="rating-icon nearbuy"></span>
                                            <span class="rating-score" style="color: rgb(63, 126, 0);">New</span>
                                        </div>
                                    </div>
                                    <div class="card__description">
                                        <p class="card__title">County's Kitchen</p>
                                        <div class="card__location">
                                            <p class="card__location"> Big Bazaar Gift Vouchers Worth up to Rs-6000</p>
                                        </div>
                                        <hr class="margin-reset">
                                        <div class="margin-top-s">
                                            <p class="txt-brand-secondary"><i class="fa fa-inr" aria-hidden="true"></i> 100</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                       <div class="col-sm-6 col-md-6 col-lg-3 col-xs-12">
                        <div class="card">
                            <div class="card__inner">
                                <a href="">
                                    <div class="card__image">
                                       {{HTML::image('public/img/front/cardimg1.jpeg','Breakfast Buffets', array('class' => 'img-responsive', 'width'=>'261', 'data-lzled'=>'true'))}}
                                        <div class="card__favourite">
                                            <label class="nb-checkbox">
                                                <i class="fa fa-heart-o" aria-hidden="true"></i>
                                            </label>
                                        </div>
                                        <div class="card__rating">
                                            <span class="rating-icon nearbuy"></span>
                                            <span class="rating-score" style="color: rgb(63, 126, 0);">New</span>
                                        </div>
                                    </div>
                                    <div class="card__description">
                                        <p class="card__title">County's Kitchen</p>
                                        <div class="card__location">
                                            <p class="card__location"> Big Bazaar Gift Vouchers Worth up to Rs-6000</p>
                                        </div>
                                        <hr class="margin-reset">
                                        <div class="margin-top-s">
                                            <p class="txt-brand-secondary"><i class="fa fa-inr" aria-hidden="true"></i> 100</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
</div>
   <div class="tab-pane fade" id="nav-select" role="tabpanel" aria-labelledby="nav-contact-tab">Consequat occaecat ullamco amet non eiusmod nostrud dolore irure incididunt est duis anim sunt officia. Fugiat velit proident aliquip nisi incididunt nostrud exercitation proident est nisi. Irure magna elit commodo anim ex veniam culpa eiusmod id nostrud sit cupidatat in veniam ad. Eiusmod consequat eu adipisicing minim anim aliquip cupidatat culpa excepteur quis. Occaecat sit eu exercitation irure Lorem incididunt nostrud.</div>
</div>
        </div>
    </div>
</section>
@endsection