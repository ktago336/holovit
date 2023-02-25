@extends('layouts.merchant_inner')
@section('content')

  
  

 
 
<section class="listing_deal">
    <div class="container">
	<div class="row">
		 <div class="col-md-3">
                    @include('elements.merchant_left_menu')
                </div>
				 <div class="col-md-9">
				 <div class="merchant-deshboard">
				 <div class="row">
				 <div class="col-md-6">
				 <div class="merchant-deals">
				 <a href="#"><span><strong>6</strong> Deals</span><i class="fa fa-handshake-o" aria-hidden="true"></i></a>
	</div>
	</div>
	</div>
	</div>
	</div>
	</div>
	</div>
</section>
   <script>
            $(window).scroll(function () {
                if ($(this).scrollTop() > 5) {
                    $(".menu_header").addClass("fixed-me");
                } else {
                    $(".menu_header").removeClass("fixed-me");
                }
            });
        </script>          
  
    


@endsection