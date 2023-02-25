                            <ul class="nav nav-tabs">
    <!--<li class="active"><a data-toggle="tab" href="#home">Near me</a></li>-->
	<li data-val="new" class="order_type <?php echo $order_type=='new'?"active":"";?>"><a data-toggle="tab" href="#menu2">Whats New</a></li>
    <li data-val="popular" class="order_type <?php echo $order_type=='popular'?"active":"";?>"><a data-toggle="tab" href="#menu1">Popular</a></li>
     
       <li data-val="htl" class="order_type <?php echo $order_type=='htl'?"active":"";?>"><a data-toggle="tab" href="#menu3">Price(High to Low)</a></li>
       <li data-val="lth" class="order_type <?php echo $order_type=='lth'?"active":"";?>"><a data-toggle="tab" href="#menu4">Price(Low to High)</a></li>
<!--        <li><a data-toggle="tab" href="#menu5">Sale</a></li>
         <li><a data-toggle="tab" href="#menu6">Flowers & More</a></li>
         <li><a data-toggle="tab" href="#menu7">Health & Fitness</a></li>-->
   
  </ul>
  <script type="text/javascript">
    $(document).ready(function () {  
		$(".order_type").click(function (event) {
			//alert($(this).attr("data-val"));
			$("#loaderID").show();
			$("#order_type").val($(this).attr("data-val"));
			$(".order_type").removeClass("active");
			$(this).addClass("active");
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
					$("#loaderID").hide();


				},
				error: function (data) {
					console.log("error");
					console.log(data);
				}
			});
			});
    });
 </script><?php /**PATH /home/lscouponslogicsp/public_html/resources/views/elements/deals/filters.blade.php ENDPATH**/ ?>