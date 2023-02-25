@extends('layouts.admin')
@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
	$( function() {
		$("#fromdatesearch").datepicker({
			defaultDate: "+1w",
			changeMonth: true,
			dateFormat: 'yy-mm-dd',
			numberOfMonths: 1,
            //minDate: 'mm-dd-yyyy',
            //maxDate:'mm-dd-yyyy',
            changeYear: true,
            onClose: function(selectedDate) {
            	if(selectedDate){$("#fromdatesearch").datepicker("option", "", selectedDate);}
            }
        });
		$("#todatesearch").datepicker({
			defaultDate: "+1w",
			changeMonth: true,
			dateFormat: 'yy-mm-dd',
			numberOfMonths: 1,
            //minDate: 'mm-dd-yyyy',
            //maxDate:'mm-dd-yyyy',
            changeYear: true,
            onClose: function(selectedDate) {
            	if(selectedDate){$("#todatesearch").datepicker("option", "", selectedDate);}
            }
        });
	} );
</script>

<style>
	b, strong {
		font-weight: 700;
		font-size: 16px;
		color: #ffffff !important;
	}
	/*.admin_no_record {
		background-color: #ffffff !important;
		}*/
	</style>
	<script>
		$(function(){
			$("#appointmentbtn").click(function(e) {
				var todate = $('#todatesearch').val();
				var fromdate = $('#fromdatesearch').val();
				var staff_id =$('#staff_id').val();
       
        var service_ids = $('#service_ids').val();
        var todayy = "<?php echo date('Y-m-d') ?>"
        var yesterdayy = "<?php echo date('Y-m-d',strtotime('-1 day'))?>";
        var tomorroww = "<?php echo date('Y-m-d',strtotime('+1 day')) ?>";

        if(todate == todayy && fromdate == todayy){
        	$(".btnactive").removeClass("active");
        	$("#todays").addClass("active"); 
        	$(Today).show();
        	$(days).show();
        	$(custom).hide();  
        	$(Yesterday).hide();
        	$(Tomorrow).hide();
        	$(Anather).hide();


        }
        if(todate == yesterdayy && fromdate == yesterdayy){ 
        	$(".btnactive").removeClass("active");
        	$("#yesterday").addClass("active");
        	$(Today).hide();
        	$(days).show();
        	$(custom).hide();
        	$(Yesterday).show();
        	$(Tomorrow).hide();
        	$(Anather).hide();}
        	if(todate == tomorroww && fromdate == tomorroww){ 
        		$(".btnactive").removeClass("active");
        		$("#tomorrow").addClass("active");
        		$(Today).hide();
        		$(days).show();
        		$(custom).hide();
        		$(Yesterday).hide();
        		$(Tomorrow).show();
        		$(Anather).hide();}
        		else{
        			$.ajaxSetup({
        				headers: {
        					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        				}
        			});
        			$.ajax({
        				url: "<?php echo HTTP_PATH; ?>/admin/admins/custom",
        				cache: false,
        				type: "POST",
        				data: {todate:todate, fromdate:fromdate,staff_id:staff_id,service_ids:service_ids},
        				success: function(result){
        					var results = JSON.parse(result);
        					var staffdata=results['staff'];
        					var servicedata=results['service'];


            
            $(Ranges).show();
            $(Today).hide();
            $(Yesterday).hide();
            $(Tomorrow).hide();

            if(($("#services").hasClass("btn-primary"))){
            	$(Services_range).show();
            	$(Staff_range).hide();
            }
            if(($("#staff").hasClass("btn-primary"))){
            	$(Staff_range).show();
            	$(Services_range).hide();
            }

            $("#services").click(function(e) {
            	$(Services_range).show();
            	$(Staff_range).hide();
            });
            $("#staff").click(function(e) {
            	$(Staff_range).show();
            	$(Services_range).hide();
            });
            $(todays).click(function () {
            	$(Today).show();
            	$(Yesterday).hide();
            	$(Tomorrow).hide();
            	$(Anather).hide();
            	$(Ranges).hide();
            });
            $(yesterday).click(function () {
            	$(Today).hide();
            	$(Yesterday).show();
            	$(Tomorrow).hide();
            	$(Anather).hide();
            	$(Ranges).hide();
            });
            $(tomorrow).click(function () {
            	$(Today).hide();
            	$(Yesterday).hide();
            	$(Tomorrow).show();
            	$(Anather).hide();
            	$(Ranges).hide();
            });
            var admin_id = "<?php echo Session::get('adminid');?>"
            		console.log(admin_id);
            var content1="";
            var colors =["#0084ff","#FF4500","#BA55D3","#228B22","#DC143C","#8D99D3","#33BB75","#FF5733","#B042D9","#DAA939","#FDD0E4","#FF80ED","#C9BCDB","#994554","#BADA55","#A6545E","#336666","#33BB75","#0084ff","#BA55D3","#33BB75","#FF80ED","#B042D9","#DAA939","#33BB75","#FF5733","#B042D9","#DAA939","#FDD0E4","#FF80ED","#C9BCDB","#994554","#BADA55","#A6545E"];  
            if(fromdate == ''){
            	fromdate = 0;}
            	if(todate == ''){
            		todate = 0;}
            		
            		if(staffdata.length!=0){
            		for (i = 0; i < staffdata.length; i++) {
            			content1=content1+'<div class="col-lg-3 col-md-3"><div class="small-box  corner" style="background-color:'+ colors[i]+'!important;"><div class="inner" id="anotherinner"><i class=""><img src="<?php  echo PROFILE_SMALL_DISPLAY_PATH ?>'+staffdata[i].profile_image+'" class="dshimage"></i></div><div class="icon"><h4 style="padding-top:5px;">'+staffdata[i].staff_name+' </h4><h5 class="todaybooking">Number of Bookings</h5><h4><b class="todaycount">'+staffdata[i].total+' </b><a href="{{HTTP_PATH}}/admin/requests/staff-'+staffdata[i].staff_id+'/'+fromdate+'/'+todate+'" class="fa fa-arrow-circle-right todaycount" style="padding-left: 90px;"></a></h4></div></div></div>'

            		}}
            		else{ 
            		$('#nostaff').show();
            		content1='<div class="admin_no_record">No Booking request found.</div>';

            	}

            	var content2 ="";
            	if(servicedata.length!=0){
            	for (i = 0; i < servicedata.length; i++) {
                // staffdata[i].staff_name;service_ids
                content2=content2+'<div class="col-lg-3 col-md-3"><div class="small-box  corner" style="background-color: '+ colors[i]+'!important;"><div class="inner" id="anotherinner"><i class=""><img src="<?php  echo SERVICE_SMALL_DISPLAY_PATH ?>'+servicedata[i].service_image+'" class="dshimage"></i></div><div class="icon"><h4 style="padding-top:5px;">'+servicedata[i].name+' </h4><h5 class="todaybooking">Number of Bookings</h5><h5><b class="todaycount">'+servicedata[i].total+' </b><a href="{{HTTP_PATH}}/admin/requests/service-'+servicedata[i].service_ids+'/'+fromdate+'/'+todate+'" class="fa fa-arrow-circle-right todaycount" style="padding-left: 90px;"></a></h5></div></div></div>'
            }}
           else{
           	
            	$('#noservice').show();
            content2='<div class="admin_no_record">No Booking  request found.</div>';

        }

        $('#Staff_range').html(content1);
        $('#Services_range').html(content2);

    }
});
}
});
});
</script>
<script type="text/javascript">
	$(document).ready(function () {
		$(Today).show();
		$(Yesterday).hide();
		$(Tomorrow).hide();
		$(Anather).hide();
		$(services).click(function () {
			
			$(services).addClass('btn-primary');
			$(services).removeClass('btn-default');
			$(staff).addClass('btn-default');
			$(staff).removeClass('btn-primary');
			$(Services).show();
			$(Staff).hide();
			$('#staff_id').val('service');

		});
		$(staff).click(function () {
			
			$(staff).addClass('btn-primary');
			$(staff).removeClass('btn-default');
			$(services).addClass('btn-default');
			$(services).removeClass('btn-primary');
			$(Staff).show();
			$(Services).hide();
			$('#staff_id').val('staff');

		});

		$(todays).click(function () {
			$(Today).show();
			$(Yesterday).hide();
			$(Tomorrow).hide();
			$(Anather).hide();
		});
		$(yesterday).click(function () {
			$(Today).hide();
			$(Yesterday).show();
			$(Tomorrow).hide();
			$(Anather).hide();
		});
		$(tomorrow).click(function () {
			$(Today).hide();
			$(Yesterday).hide();
			$(Tomorrow).show();
			$(Anather).hide();
		});

	});
</script>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script type="text/javascript">
	$(document).ready(function () {
		$(".btnwidth").click(function () {
			$(".btnwidth").removeClass("active");
			$(this).addClass("active");
		});

		$(".btnactive").click(function () {
			$(".btnactive").removeClass("active");
			$(this).addClass("active");
		});

		$('#todate').click(function(){
			$(document).ready(function(){
				$("#my-datepicker").datepicker().focus();
			});
		});
	});
</script>
<script>
	$(document).ready(function() {
		$('#calendar1').click(function(event) {

		});
	});
</script>

<?php

use App\Models\Appointment;
?>
<div class="content-wrapper">
	



	<section class="content-header content-header-dashboard">
		<div class="ersu_message">@include('elements.admin.errorSuccessMessage')</div>
		<div class="col-md-2">
			<h2>Dashboard</h2>
		</div>
		<div class="col-md-6">
		</div>
		<div id ="my_styles">
			<div class="col-md-4">
				<div class="ser-butt">
					<button type="submit" class="btn btn-default btnwidth" id="services"><i class ="fa fa-list-ul"></i><span>Services</span></button>
					<submit class="btn btn-primary btnwidth" id="staff"><i class="fa fa-group"></i><span>Staff</span></submit>
				</div>
			</div>
		</div>
		<div class="col-md-12">
			<div class="day-box-custom" id ="days">
				<ul>
					<li>
						<a class="btnactive" id="yesterday">
							<span class="day-name" >Yesterday</span>
							<span class="day-date"><?php $date = date('Y-m-d',strtotime("-1 day")); echo $date;?>
						</span>
					</a>
				</li>
				<li>
					<a class="btnactive active" id="todays">
						<span class="day-name" >Today</span>
						<span class="day-date"><?php echo $date = date('Y-m-d');?></span>
					</a>
				</li>
				<li>
					<a class="btnactive" id="tomorrow">
						<span class="day-name tomorrow" >Tomorrow</span>
						<span class="day-date"><?php $datetime = new DateTime('tomorrow');
						echo $datetime->format('Y-m-d');?></span>
					</a>
				</li>
				<li>
					<a href="#">
						<span class="day-name" id="custombtn">Custom</span>
						<span class="day-date"></span>
					</a>
				</li>
			</ul>
		</div>
		<div class="day-custom" id ="custom">
			<!--  {{ Form::open() }}  -->
			<div class="form-group" id="calendar1">
				<a href="javascript:void(0)" ><i class="fa fa-calendar fromcalendar" aria-hidden="true" id ="fromcalendar"></i></a>
				<input type="text" id="fromdatesearch" name = "fromdatesearch" placeholder="From" class="form-control fromdatesearch">
			</div>
			<div class="form-group">
				<a><i class="fa fa-calendar" aria-hidden="true" id="tocalendar"></i></a>
				<input type="text" id ="todatesearch" name ="todatesearch" placeholder="To" class="form-control">
			</div>
			<input type="text" id ="staff_id" value="staff" name ="staff_id" placeholder="To" class="form-control" hidden>
			<input type="text" id ="service_ids" name ="service_ids" placeholder="To" value="service" class="form-control" hidden>
			<div class="form-group">
				<button class="btn btn-primary" id="appointmentbtn" name= "appointmentbtn" onclick="javascript:void(0)">Go</button>
				<!-- {{Form::submit('Go', ['class' => 'btn btn-primary','id' => 'appointmentbtn'])}} -->
			</div>
			<!-- {{Form::close()}}  -->
		</div>
	</div>
</section>

@if(!$dadhboardData->isEmpty()) 
<section class="content">
	<div class="" id ="Today">

		<div class="" id="Services" hidden>
			<?php
			global $colours;
			$color = $colours;
			?>
			<div class="row">
				<?php $i=0;?>
				@foreach($dadhboardData as $data)
				<?php
				$startdate = date('Y-m-d 00:00:00');
				$endtdate = date('Y-m-d 23:59:59');
				$range = [$startdate, $endtdate];
				$admin = Session::get('adminid');
				if($admin!=1){
				$appointment = Appointment::select(DB::raw("CONCAT(',',service_ids,',') AS service,id"))->having('service', 'like', '%,' . $data->id . ',%')
				->whereBetween('booking_date_time', $range)
				->where('staff_id',$admin)
				->get()->count();
			}else{$appointment = Appointment::select(DB::raw("CONCAT(',',service_ids,',') AS service,id"))->having('service', 'like', '%,' . $data->id . ',%')
				->whereBetween('booking_date_time', $range)
				->get()->count();}
				if($appointment >= 1){	$i++;	
					?>
					<div class="col-lg-3 col-md-3">
						<?php
						shuffle($color);
						?>

						<div class="small-box bg-default corner" style="background-color: <?php echo $color[0]; ?>!important;" >

							<div class="inner" id="anotherinner">

								<i class="">  
									@if($data->service_image != '')
									{{HTML::image(SERVICE_SMALL_DISPLAY_PATH.$data->service_image, SITE_TITLE,[ "border-radius" => '25px 0px 0px 10px;','class'=>"dshimage"])}}
									@else
									{{HTML::image('public/img/noimage.png','our-clients',["border-radius" => "25px 0px 0px 10px;",'class'=>"dshimage"])}}
									@endif 
								</i>
							</div>
							<div class="icon">
								<h4 style="padding-top:5px;">{{$data->name}} </h4>
								<h5 class="todaybooking">Today's Booking</h5>
								<h6><b class="todaycount"> {{$appointment}} </b><a href="{{url('/admin/requests/service-'.$data->id)}}" class="fa fa-arrow-circle-right todaycount" style="padding-left: 90px;"></a></h6>
							</div>
						</div>
					</div>
					<?php
				}
				?>
				@endforeach
				<?php if($i==0){
					echo  '<div id="listingJS" style="display: none;" class="alert alert-success alert-block fade in"></div>
					<div class="admin_no_record">No booking request found for Today.</div>';
				}
				?>
			</div>
			<div class="row">

			</div>
		</div>

		<div class =""id="Staff">
			<?php
			global $colours;
			$color = $colours;
			?>
			<div class="row">
				<?php $i=0;?>
				@foreach($staffData as $data)
				<?php
				$startdate = date('Y-m-d 00:00:00');
				$endtdate = date('Y-m-d 23:59:59');
				$range = [$startdate, $endtdate];
				$appointment = DB::table('appointments')
				->where('staff_id', $data->id)
				->whereBetween('booking_date_time', $range)
				->get()->count();
				if($appointment >= 1){ $i++;
					?>
					<div class="col-lg-3 col-md-3">
						<?php
						shuffle($color);
						?>
						<div class="small-box bg-default corner" style="background-color: <?php echo $color[0]; ?>!important;">
							<div class="inner">
								<i class="">
									@if($data->profile_image != '')
									{{HTML::image(PROFILE_SMALL_DISPLAY_PATH.$data->profile_image, SITE_TITLE,["border-radius" => '25px 0px 0px 10px;','class'=>"dshimage"])}}
									@else
									{{HTML::image('public/img/noimage.png','our-clients',[ "border-radius" => '25px 0px 0px 10px;','class'=>"dshimage"])}}
									@endif 
								</i>
							</div>
							<div class="icon">
								<h4 style="padding-top:5px;">{{$data->first_name}}</h4>
								<h5 class="todaybooking">Today's Booking</h5>
								<h6><b class="todaycount" style="margin:0"> {{$appointment}} </b><a href="{{url('/admin/requests/staff-'.$data->id)}}" class="fa fa-arrow-circle-right todaycount" style="padding-left: 90px;"></a></h6>
							</div>
						</div>
					</div>
					<?php
				}
				?>
				@endforeach
				<?php if($i==0){
					echo  '<div id="listingJS" style="display: none;" class="alert alert-success alert-block fade in"></div>
					<div class="admin_no_record">No booking request found for Today.</div>';
				}
				?>
			</div>

		</div>
	</div>

	<div id="Ranges">
		<?php global $colours;
		$color = $colours;
		shuffle($color);
		?>
		<div id="Services_range" hidden>
			<div class="row" id="servicess">
				
			</div>
		</div>
		<div id="Staff_range">
			<div class="row" id="staffff">
				
			</div>
		</div>
	</div>

	<div class ="" id ="Yesterday">
		<div class =""id="Services" hidden>
			<?php
			global $colours;
			$color = $colours;
			?>
			<div class="row">
				<?php $i=0;?>
				@foreach($dadhboardData as $data)
				<?php
				
				$startdate = date('Y-m-d 00:00:00',strtotime("-1 day"));
				$endtdate = date('Y-m-d 23:59:59',strtotime("-1 day"));
				$start = date('Y-m-d',strtotime("-1 day"));
				$end = 0;
				$range = [$startdate, $endtdate];
				$admin = Session::get('adminid');
				if($admin!=1){
				$appointment = Appointment::select(DB::raw("CONCAT(',',service_ids,',') AS service,id"))->having('service', 'like', '%,' . $data->id . ',%')
				->whereBetween('booking_date_time', $range)
				->where('staff_id',$admin)
				->get()->count();
				}else{
					$appointment = Appointment::select(DB::raw("CONCAT(',',service_ids,',') AS service,id"))->having('service', 'like', '%,' . $data->id . ',%')
				->whereBetween('booking_date_time', $range)
				->get()->count();
				}
				//print_r($appointment);
				if($appointment >= 1){ $i++;
					?>
					<div class="col-lg-3 col-md-3">
						<?php
						shuffle($color);
						?>
						<div class="small-box bg-default corner" style="background-color: <?php echo $color[0]; ?>!important;">
							<div class="inner">

								<i class="">  
									@if($data->service_image != '')
									{{HTML::image(SERVICE_SMALL_DISPLAY_PATH.$data->service_image, SITE_TITLE,[ "border-radius" => '25px 0px 0px 10px;','class'=>"dshimage"])}}
									@else
									{{HTML::image('public/img/noimage.png','our-clients',["border-radius" => "25px 0px 0px 10px;",'class'=>"dshimage"])}}
									@endif 
								</i>
							</div>
							<div class="icon">
								<h4 style="padding-top:5px;">{{$data->name}} </h4>
								<h5 class="todaybooking">Yesterday's Bookings</h5>
								<h6><b class="todaycount"> {{$appointment}} </b><a href="{{url('/admin/requests/service-'.$data->id.'/'.$start.'/'.$start)}}" class="fa fa-arrow-circle-right todaycount" style="padding-left: 90px;"></a></h6>
							</div>
						</div>
					</div>
				<?php }
				else{

				} 
				?>
				@endforeach 
				<?php if($i==0){
					echo '<div id="listingJS" style="display: none;" class="alert alert-success alert-block fade in"></div>
					<div class="admin_no_record">No booking request found for Yesterday.</div>';
				} ?>
			</div>
		</div>

		<div class =""id="Staff">
			<?php
			global $colours;
			$color = $colours;
			?>
			<div class="row">
				<?php $i=0;?>
				@foreach($staffData as $data)
				<?php
				$startdate = date('Y-m-d 00:00:00',strtotime("-1 day"));
				$start = date('Y-m-d',strtotime("-1 day"));
				$end = 0;
				$endtdate = date('Y-m-d 23:59:59',strtotime("-1 day"));
				$range = [$startdate, $endtdate];

				$appointment = DB::table('appointments')
				->where('staff_id', $data->id)
				->whereBetween('booking_date_time', $range)
				->get()->count();
                            //print_r($appointment);
				if($appointment >= 1){ $i++;
					?>
					<div class="col-lg-3 col-md-3">
						<?php
						shuffle($color);
						?>
						<div class="small-box bg-default corner" style="background-color: <?php echo $color[0]; ?>!important;">
							<div class="inner">
								<i class="">
									@if($data->profile_image != '')
									{{HTML::image(PROFILE_SMALL_DISPLAY_PATH.$data->profile_image, SITE_TITLE,["border-radius" => '25px 0px 0px 10px;','class'=>"dshimage"])}}
									@else
									{{HTML::image('public/img/noimage.png','our-clients',[ "border-radius" => '25px 0px 0px 10px;','class'=>"dshimage"])}}
									@endif 
								</i>
							</div>
							
							<div class="icon">
								<h4 style="padding-top:5px;">{{$data->first_name}}</h4>
								<h5 class="todaybooking">Yesterday's Bookings</h5>
								<h6><b class="todaycount" style="margin:0"> {{$appointment}} </b><a href="{{url('/admin/requests/staff-'.$data->id.'/'.$start.'/'.$start)}}" class="fa fa-arrow-circle-right todaycount" style="padding-left: 90px;"></a></h6>
							</div>
						</div>
					</div>
				<?php } ?>
				@endforeach
				<?php if($i==0){
					echo '<div id="listingJS" style="display: none;" class="alert alert-success alert-block fade in"></div>
					<div class="admin_no_record">No booking request found for Yesterday.</div>';
				} ?>
			</div>
		</div>
	</div>

	<div class ="" id ="Tomorrow">
		<div class =""id="Services" hidden>
			<?php
			global $colours;
			$color = $colours;
			?>
			<div class="row">
				<?php $i=0;?>
				@foreach($dadhboardData as $data)
				<?php
				$datetime = new DateTime('tomorrow');
				$startdate = $datetime->format('Y-m-d 00:00:00');
				$start = $datetime->format('Y-m-d');
				$end = 0;
				$endtdate = $datetime->format('Y-m-d 23:59:59');
				$range = [$startdate, $endtdate];
				$admin = Session::get('adminid');
				if($admin!=1){
				$appointment = Appointment::select(DB::raw("CONCAT(',',service_ids,',') AS service,id"))->having('service', 'like', '%,' . $data->id . ',%')
				->whereBetween('booking_date_time', $range)
				->where('staff_id',$admin)
				->get()->count();
			}else{
				$appointment = Appointment::select(DB::raw("CONCAT(',',service_ids,',') AS service,id"))->having('service', 'like', '%,' . $data->id . ',%')
				->whereBetween('booking_date_time', $range)
				->get()->count();
			}
				if($appointment >= 1){ $i++;
					?>
					<div class="col-lg-3 col-md-3">
						<?php
						shuffle($color);
						?>
						<div class="small-box bg-default corner" style="background-color: <?php echo $color[0]; ?>!important;">
							<div class="inner">

								<i class="">  
									@if($data->service_image != '')
									{{HTML::image(SERVICE_SMALL_DISPLAY_PATH.$data->service_image, SITE_TITLE,[ "border-radius" => '25px 0px 0px 10px;','class'=>"dshimage"])}}
									@else
									{{HTML::image('public/img/noimage.png','our-clients',["border-radius" => "25px 0px 0px 10px;",'class'=>"dshimage"])}}
									@endif 
								</i>
							</div>

							
							<div class="icon">
								<h4 style="padding-top:5px;">{{$data->name}} </h4>
								<h5 class="todaybooking">Tomorrow's Bookings</h5>
								<h6><b class="todaycount"> {{$appointment}} </b><a href="{{url('/admin/requests/service-'.$data->id.'/'.$start.'/'.$start)}}" class="fa fa-arrow-circle-right todaycount" style="padding-left: 90px;"></a></h6>
							</div>
						</div>
					</div>
				<?php } ?>
				@endforeach
				<?php if($i==0){
					echo  '<div id="listingJS" style="display: none;" class="alert alert-success alert-block fade in"></div>
					<div class="admin_no_record">No booking request found for Tomorrow.</div>';
				}
				?>
			</div>
		</div>

		<div class =""id="Staff">
			<?php
			global $colours;
			$color = $colours;
			?>
			<div class="row">
				<?php $i=0;?>
				@foreach($staffData as $data)
				<?php
				$datetime = new DateTime('tomorrow');
				$startdate = $datetime->format('Y-m-d 00:00:00');
				$endtdate = $datetime->format('Y-m-d 23:59:59');
				$range = [$startdate, $endtdate];
				$start = $datetime->format('Y-m-d');
				$end = 0;
				$appointment = DB::table('appointments')
				->where('staff_id', $data->id)
				->whereBetween('booking_date_time', $range)
				->get()->count();
                            //print_r($appointment);
				if($appointment >= 1){ $i++;
					?>
					<div class="col-lg-3 col-md-3">
						<?php
						shuffle($color);
						?>
						<div class="small-box bg-default corner" style="background-color: <?php echo $color[0]; ?>!important;">
							<div class="inner">
								<i class="">
									@if($data->profile_image != '')
									{{HTML::image(PROFILE_SMALL_DISPLAY_PATH.$data->profile_image, SITE_TITLE,["border-radius" => '25px 0px 0px 10px;','class'=>"dshimage"])}}
									@else
									{{HTML::image('public/img/noimage.png','our-clients',[ "border-radius" => '25px 0px 0px 10px;','class'=>"dshimage"])}}
									@endif 
								</i>
							</div>
							
							<div class="icon">
								<h4 style="padding-top:5px;">{{$data->first_name}}</h4>
								<h5 class="todaybooking">Tomorrow's Bookings</h5>
								<h6><b class="todaycount" style="margin:0"> {{$appointment}} </b><a href="{{url('/admin/requests/staff-'.$data->id.'/'.$start.'/'.$start)}}" class="fa fa-arrow-circle-right todaycount" style="padding-left: 90px;"></a></h6>
							</div>
						</div>
					</div>
				<?php } ?>
				@endforeach
				<?php if($i==0){
					echo  '<div id="listingJS" style="display: none;" class="alert alert-success alert-block fade in"></div>
					<div class="admin_no_record">No booking request found for Tomorrow.</div>';
				}
				?>
			</div>
		</div>
	</div>

	<div class ="" id ="Anather">
		<div class =""id="Services" hidden>
			<?php
			global $colours;
			$color = $colours;
			?>
			<div class="row">
				@foreach($dadhboardData as $data)
				<div class="col-lg-3 col-md-3">
					<?php
					shuffle($color);
					?>
					<div class="small-box bg-default corner" style="background-color: <?php echo $color[0]; ?>!important;">
						<div class="inner">

							<i class="">  
								@if($data->service_image != '')
								{{HTML::image(SERVICE_SMALL_DISPLAY_PATH.$data->service_image, SITE_TITLE,[ "border-radius" => '25px 0px 0px 10px;','class'=>"dshimage"])}}
								@else
								{{HTML::image('public/img/noimage.png','our-clients',["border-radius" => "25px 0px 0px 10px;",'class'=>"dshimage"])}}
								@endif 
							</i>
						</div>

						<?php
						?>
						<div class="icon">
							<h4 style="padding-top:5px;">{{$data->name}} </h4>
							<h5 class="todaybooking">Today's Booking</h5>
							<h6><b class="todaycount"> {{$appointment}} </b><a href="{{url('/admin/requests/service-'.$data->id)}}" class="fa fa-arrow-circle-right todaycount" style="padding-left: 90px;"></a></h6>
						</div>
					</div>
				</div>
				@endforeach 
			</div>
		</div>

		<div class =""id="Staff">
			<?php
			global $colours;
			$color = $colours;
			?>
			<div class="row">
				@foreach($staffData as $data)
				<div class="col-lg-3 col-md-3">
					<?php
					shuffle($color);
					?>
					<div class="small-box bg-default corner" style="background-color: <?php echo $color[0]; ?>!important;">
						<div class="inner">
							<i class="">
								@if($data->profile_image != '')
								{{HTML::image(PROFILE_SMALL_DISPLAY_PATH.$data->profile_image, SITE_TITLE,["border-radius" => '25px 0px 0px 10px;','class'=>"dshimage"])}}
								@else
								{{HTML::image('public/img/noimage.png','our-clients',[ "border-radius" => '25px 0px 0px 10px;','class'=>"dshimage"])}}
								@endif 
							</i>
						</div>
						<?php
						$startdate = date('Y-m-d 00:00:00',strtotime("-2 day"));
						$endtdate = date('Y-m-d 23:59:59',strtotime("-2 day"));
						$range = [$startdate, $endtdate];
                        //print_r($range);exit;
						$appointment = DB::table('appointments')
						->where('staff_id', $data->id)
						->whereBetween('booking_date_time', $range)
						->get()->count();
                            //print_r($appointment);
						?>
						<div class="icon">
							<h4 style="padding-top:5px;">{{$data->first_name}}</h4>
							<h5 class="todaybooking">Today's Booking</h5>
							<h6><b class="todaycount" style="margin:0"> {{$appointment}} </b><a href="{{url('/admin/requests/staff-'.$data->id)}}" class="fa fa-arrow-circle-right todaycount" style="padding-left: 90px;"></a></h6>
						</div>
					</div>
				</div>
				@endforeach
			</div>
		</div>
	</div>

	<div id= "nostaff" style="display:none">
	</div>
	<div id= "noservice" style="display:none">
	</div>

	<div class="">
		<h4 class="admin_st">Appointment Statistics</h4>
		<div class="relative_box_esjad">
			<div class="company_tab">
				<span class="cpc" id="cchart0" onclick="updateUser(0)">Today</span>
				<span class="cpc" id="cchart5" onclick="updateUser(5)">Tomorrow</span>
				<span class="cpc" id="cchart1"  onclick="updateUser(1)">Yesterday</span>
				<span class="cpc active" id="cchart4"  onclick="updateUser(4)">Last 7 days</span>
				<span class="cpc active" id="cchart2"  onclick="updateUser(2)">Last 30 days</span>                            
				<span  class="cpc" id="cchart3" onclick="updateUser(3)">Last 12 months</span>
			</div>
			<div class="chart_loader" id="user_chart_loader">{{HTML::image("public/img/website_load.svg", SITE_TITLE)}}</div>
			<div class="admin_chart" id="user_chart"></div>
		</div>
	</div>
</section>
@else 
<div id="listingJS" style="display: none;" class="alert alert-success alert-block fade in"></div>
<div class="admin_no_record">No booking request found.</div>
@endif
</div>
<script>
	$(function () {
		updateUser(0);
	});
	function updateUser(daycnt) {
		$('.cpc').removeClass('active');
		$('#cchart' + daycnt).addClass('active');
		$.ajax({
			type: 'get',
			url: '{{HTTP_PATH}}/admin/admins/userchart/' + daycnt,
			beforeSend: function () {
				$("#user_chart_loader").show();
			},
			success: function (result) {
				$("#user_chart").html(result);
			}
		});
	}

</script>
<script>
	$(document).ready(function(){
		$("#custombtn").click(function(){
			$(".day-box-custom").addClass("intro");
			$(".day-custom").addClass("day-custom-show");
			$("#custom").css('display','block');
			$("#fromdatesearch").val('');
			$("#todatesearch").val('');
			$('.day-box-custom').css('display','none');
		});
		$('#staff_id').val('staff');
	});

</script>
@endsection