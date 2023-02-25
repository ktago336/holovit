{{ HTML::script('public/js/facebox.js')}}
{{ HTML::style('public/css/facebox.css')}}
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">

<script type="text/javascript">
    $(document).ready(function ($) {
        $('.close_image').hide();
        $('a[rel*=facebox]').facebox({
            closeImage: '{!! HTTP_PATH !!}/public/img/close.png'
        });

        // $("[name='next_appointment_date']").datepicker({
        //     defaultDate: "+1w",
        //     changeMonth: true,
        //     dateFormat: 'yy-mm-dd',
        //     numberOfMonths: 1,
        //     //minDate: 'mm-dd-yyyy',
        //     //maxDate:'mm-dd-yyyy',
        //     changeYear: true,
        //     onClose: function(selectedDate) {
        //         if(selectedDate){$("[name='next_appointment_date']").datepicker("option", "", selectedDate);}
        //     }
        // });
        
    });
</script>

<?php
use App\Models\Admin;
?>
<script type="text/javascript">

    $(function(){
        $('select[name="staff_id"]').on('change', function(){
            var staff_id = $(this).val();
        });
        $('.staff_id').change(function(){
            var staff_id = $(this).val();
            $(this).closest('tr').find('td:first').each(function() {
                var textval = $(this).text(); 
       //alert(textval);
       
       $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
       $.ajax({
        url: "<?php echo HTTP_PATH; ?>/admin/assignstaff/"+staff_id,
        cache: false,
        type: "POST",
        data: {textval, textval},
        success: function(data){
         $('#staff_id').append(data);
     }
 });
   });
        });
    });

</script>
<?php 
$parent_id = Session::get('parent_id');
$adminLId = Session::get('adminid');
$adminRols = App\Http\Controllers\Admin\AdminsController::getAdminRoles(Session::get('adminid'));
$checkSubRols = App\Http\Controllers\Admin\AdminsController::getAdminRolesSub(Session::get('adminid'));
?>
<div class="admin_loader" id="loaderID">{{HTML::image("public/img/website_load.svg", SITE_TITLE)}}</div>
@if(!$appointments->isEmpty())
<div class="panel-body marginzero">
    <div class="ersu_message">@include('elements.admin.errorSuccessMessage')</div>
    <!-- <input type="hidden" id="iscancel" value="<?php //echo isset($iscancel)?$iscancel:0; ?>">
        <input type="hidden" id="requestslug" value="<?php //echo isset($request)?$request:0; ?>"> -->
        {{ Form::open(array('method' => 'post', 'id' => 'actionFrom')) }}

        <section id="no-more-tables" class="lstng-section">
            <div class="topn">
                <div class="topn_left">Request List</div>
                <div class="topn_rightd ddpagingshorting" id="pagingLinks" align="right">
                 
                    <div class="panel-heading" style="align-items:center;">
                        {{$appointments->appends(Input::except('_token'))->render()}}
                    </div>
                </div>                
            </div>

            <div class="tbl-resp-listing">
                <table class="table table-bordered table-striped table-condensed cf">
                    <thead class="cf ddpagingshorting">
                        <tr>
                            <!-- <th style="width:5%">#</th> -->
                            <th class="sorting_paging" hidden>@sortablelink('id', 'id')</th>
                            <th class="sorting_paging">@sortablelink('appointment_number', 'Appointment Number')</th>
                            
                            <th class="sorting_paging">@sortablelink('service_ids', 'Service')</th>
                            <th class="sorting_paging">@sortablelink('user_id', 'Customer Name')</th>
                            <th class="sorting_paging">
                                @sortablelink('staff_id', 'Staff Name')
                            </th> 
                        <!-- <th class="sorting_paging">
                            @sortablelink('staff_id', 'Assign Staff')
                        </th> -->
                        
                        <th class="sorting_paging">@sortablelink('booking_date_time', 'Appointment Date Time')</th>
                        <!-- <th class="sorting_paging">@sortablelink('description', 'Description')</th> -->
                        <th class="sorting_paging">@sortablelink('status', 'Status')</th>
                        <th class="sorting_paging">@sortablelink('total_price', 'Price')</th>
                        <!-- <th class="sorting_paging">@sortablelink('created_at', 'Date')</th> -->
                        <th class="action_dvv"> Action</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach($appointments as $appointment)
                    <tr>
                        <!-- <th style="width:5%"><input type="checkbox" onclick="javascript:isAllSelect(this.form);" name="chkRecordId[]" value="{{$appointment->id}}" /></th> -->
                        <td data-title="id" hidden>
                            {{$appointment->id}}
                        </td>
                        <td data-title="Appointment Number">{{$appointment->appointment_number}}</td>
                        
                        <?php 
                        if($appointment->service_ids!='' && $appointment->service_ids!=0){
                         $appointment->service_ids = explode(",",$appointment->service_ids);
                         $a = '';
                         foreach($appointment->service_ids as $appointment->service_ids){
                             $services = DB::table('services')->where('id',$appointment->service_ids)->first();
                                   //if()
                             $a.= $services->name.", ";

                         }
                     }else{
                      $a='N/A';
                  }
                  ?> 

                  <td data-title="Servicename">

                    {{rtrim($a, ", ")}}
                </td>
                <td data-title="Username">@if($appointment->user_id != '0')
                    {{$appointment->User['first_name']}}
                    @else
                    {{'N/A'}}
                @endif</td>
                        <!-- <td data-title="Staffname">@if($appointment->staff_id!='1')
                            {{$appointment->Admin->first_name}}
                            @else
                            {{'N/A'}}
                        @endif</td>  -->
                        <td data-title="Assign Staff">
                            <div class="appointment-input">

                               <?php
                               if($appointment->staff_id!='1'){
                                  echo $appointment->Admin->first_name;
                              }
                              else{
                               $appointment->service_ids = explode(",",$appointment->service_ids);
                               foreach($appointment->service_ids as $appointment->service_ids){
                                   $staff = Admin::select(DB::raw("CONCAT(',',service_ids,',') AS service,id"))->having('service','like','%,'.$appointment->service_ids.',%')->get()->toArray();
                                   $sids = array_column($staff, 'id');
                                   $staff_id=Admin::whereIn('id',$sids)->get()->pluck('first_name','id');
                                   

                                   $staffselected = DB::table('appointments')->join('admins', 'appointments.staff_id', '=', 'admins.id')->where('appointments.id',$appointment->id)->pluck('admins.first_name','admins.id');
                                   
                               }
                               
                               ?>
                               
                               <?php
                               
                               if($staff_id)
                               {
                                   ?> 
                                   {!!Form::select('staff_id',$staff_id, null, ['class' => 'form-control staff_id', 'placeholder' => 'Assign Staff', 'id' => 'staff_id', 'name' => 'staff_id'])!!}

                                   <?php

                               }
                             //print_r($staffselected);exit;
                           }
                           ?>
                       </div>  
                   </td>
                   
                   <td data-title="Appointment Date Time">{{$appointment->booking_date_time}}</td>
                   <!-- <td data-title="Description">{{$appointment->description}}</td> -->
                   <?php global $change_status; ?>
                   <td data-title="Description" class="{{ $change_status[$appointment->status]}}">{{$appointment->status}}</td>
                   <td data-title="Price">{{CURR }}{{$appointment->total_price}}</td>
                   <!-- <td data-title="Date">{{$appointment->created_at->format('M d, Y')}}</td> -->
                   <td data-title="Action">
                     <?php $role = 2; if ($adminLId == 1 || $appointment->staff_id == $adminLId || (isset($checkSubRols[6])) && in_array($role, $checkSubRols[6])) { ?>
                    @if($appointment->status == 'Pending')
                    <a href="{{ URL::to( 'admin/services/cancle/'.$appointment->slug)}}" title="Cancel" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure want to decline this request ?')"><i class="fa fa-times-circle-o" data-slug="{{$appointment->slug}}"></i></a>
                    
                    @endif
                  <?php } ?>
                    <?php //$role = 4; if($adminLId == 1 || (isset($checkSubRols[6]) && in_array($role, $checkSubRols[6]))) { ?>
                    <a href="#info{!! $appointment->id !!}" title="View" class="btn btn-primary btn-xs" rel='facebox'><i class="fa fa-eye"></i></a>
                  <?php //} ?>
                  <?php $role = 2; if ($adminLId == 1 || $appointment->staff_id == $adminLId || (isset($checkSubRols[6])) && in_array($role, $checkSubRols[6])) { 
                      if($appointment->status != 'Visited' && $appointment->status != 'Completed'){
                    ?>
                    <a href="{{url('admin/reschedule/'.$appointment->slug)}}" title="Reschedule" class="btn btn-primary btn-xs"><i class="fa fa-calendar-plus-o"></i></a>
                  <?php 
                      }
                    } ?>
                   <?php $role = 2; if ($adminLId == 1 || $appointment->staff_id == $adminLId || $appointment->id == $adminLId || (isset($checkSubRols[6])) && in_array($role, $checkSubRols[6])) { ?>
                    <a href="{{url('admin/updatestatus/'.$appointment->slug)}}" title="Edit" class="btn btn-warning btn-xs" ><i class="fa fa-pencil-square-o" data-slug="{{$appointment->slug}}"></i></a>
                  <?php } ?>
                     <?php $role = 4; if ($adminLId == 1 || $appointment->staff_id == $adminLId || (isset($checkSubRols[6])) && in_array($role, $checkSubRols[6])) { ?>
                    @if($appointment->status == 'Visited' || $appointment->status == 'Completed')
                    <a href="{{ URL::to('admin/invoice/'.$appointment->slug)}}" title="invoice" class="btn btn-success btn-xs"><i class="fa fa-list-alt"></i></a>
                    @endif
                  <?php } ?>

                </td>  
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<?php
$admin_id = Session::get('adminid');
if($admin_id == 1){
    ?>
    
    <?php
}
?>   

</section>
{{ Form::close()}}
</div>         
</div> 
@else 
<div id="listingJS" style="display: none;" class="alert alert-success alert-block fade in"></div>
<div class="admin_no_record">No record found.</div>
@endif



@if(!$appointments->isEmpty())
@foreach($appointments as $record)
<div id="info{!! $record->id !!}" style="display: none;">
    <div class="nzwh-wrapper">
        <fieldset class="nzwh">
            <legend class="head_pop">Booking Details</legend>
            <div class="drt">
              <!-- `id`, `service_ids`, `user_id`, `staff_id`, `guest_name`, `guest_email`, `guest_contact`, `booking_date_time`, `next_appointment_date`, `status`, `slug`, `description`, `total_price`, `payment_status`, `created_at`, `updated_at` -->
              <div class="admin_pop"><span>Appointment Number : </span>  <label>{!! $record->appointment_number !!}</label></div>
              <?php if($record->user_id!=0){ ?>
                <div class="admin_pop"><span>Customer Name : </span>  <label>{!! ($record->user_id!=0 && $record->User && $record->User->first_name!='')?$record->User->first_name:'N/A' !!}</label></div>
            <?php } ?>
            <div class="admin_pop"><span>Services : </span>  
              <label>

                <?php
                $originalserviceids=$record->getOriginal('service_ids');
                $a = '';
                if($originalserviceids!='' && $originalserviceids!=0){
                 $service_ids = explode(",",$originalserviceids);
                 foreach($service_ids as $s){
                     $services = DB::table('services')->where('id',$s)->first();
                     $a= $a.''.$services->name.", ";
                 }
             }else{
              $a='N/A';
          }
          ?>
          {{$a}} 
      </label>
  </div>
  <div class="admin_pop"><span>Staff Name : </span>  <label>{!! ($record->staff_id!=0 && $record->Admin && $record->Admin->first_name!='')? $record->Admin->first_name." ".$record->Admin->last_name :'N/A' !!}</label></div>
  <?php
  if($record->user_id==0 || $record->user_id==''){
    ?>
    <div class="admin_pop"><span>Customer Name : </span>  <label>{!! ($record->guest_name!='')?$record->guest_name:'N/A' !!}</label></div>
    <div class="admin_pop"><span>Customer Email : </span>  <label>{!! ($record->guest_email!='')?$record->guest_email:'N/A' !!}</label></div>
    <div class="admin_pop"><span>Customer Contact : </span>  <label>{!! ($record->guest_contact!='')?$record->guest_contact:'N/A' !!}</label></div>
    <?php
}
?>
<div class="admin_pop"><span>Appointment Date Time : </span>  <label><?php echo date("d-m-Y H:i",strtotime($record->booking_date_time)) ?></label></div>
<div class="admin_pop"><span>Next Appointment Date : </span>  <label><?php echo ($record->next_appointment_date!=null && $record->next_appointment_date!='')? date("d-m-Y",strtotime($record->next_appointment_date)):'N/A' ?></label></div>
<div class="admin_pop"><span>Status : </span>  <label>{!! $record->status !!}</label></div>
<div class="admin_pop"><span>Description : </span>  <label>{!! $record->description !!}</label></div>
<div class="admin_pop"><span>Total Price : </span>  <label>{!! $record->total_price !!}</label></div>
<div class="admin_pop"><span>Payment Status : </span>  <label>{!! $record->payment_status !!}</label></div>
<div class="admin_pop"><span>Created On : </span>  <label><?php echo date("d-m-Y H:i",strtotime($record->created_at)) ?></label></div>




</fieldset>
</div>
</div>
@endforeach
@endif

<style>
    .drt {
     /* height: 300px; */
     overflow: auto;
 }
 fieldset {
   padding: .0em .0em .0em !important;
}
</style>
<script type="text/javascript">
   $('#slottimeblock').css('display','none');
   $('#noslotblock').css('display','none');


   function getdate(){
    var booking_date_time = $('#booking_date_time').val();
    alert(booking_date_time);
    var isFixedSlot=$('#fixedslot').val();
    var today = new Date();
    var dd = today.getDate();

    var mm = today.getMonth()+1; 
    var yyyy = today.getFullYear();
    if(dd<10) 
    {
        dd='0'+dd;
    } 

    if(mm<10) 
    {
        mm='0'+mm;
    } 
    today = yyyy+'-'+mm+'-'+dd;
    var mydate = new Date(bookeddate);
    var staffid=$('#staff_id').val();
    if(staffid==''){
        staffid=0;
    }
    console.log("selected date :"+mydate);
    console.log("today date :"+today);
    if(bookeddate<today)
    {
        
        $('#booking_date_time').val(null);
        alert("Please select current or future date")
        return;
    }
    console.log("date is not old");

    var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    var mlist = [ "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" ];
    var dateformatstr=days[mydate.getDay()]+", "+mydate.getDate()+" "+mlist[mydate.getMonth()]+", "+mydate.getFullYear();
    $("#click-date").html(dateformatstr);
    $("#available-date").html(dateformatstr);
    console.log(dateformatstr);
    console.log("fixed : "+isFixedSlot);
    console.log("staffid : "+staffid);

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

    $.ajax({ 
      type: 'POST',
      url: "<?php echo HTTP_PATH; ?>/getslotstartdata",
      cache: false,
      data:{'date':booking_date_time,'isFixedSlot':isFixedSlot,'slug':staffid,'dayname':days[mydate.getDay()]},
      success: function (result)
      {

        var results = JSON.parse(result);
        console.log(results);
        var content="<option value=''>Select Time</option>";
        if(results.length>0){
          var i;
          
          for (i = 0; i < results.length; ++i) {
           content=content+"<option value='"+results[i]+"'>"+results[i]+"</option>";

       }
       $('#noslotblock').css('display','none');
       $("#slottimeid").html(content);
   }else{
    $('#noslotblock').css('display','block');
    $("#slottimeid").html(content);
}
}
});
    
    $('#slottimeblock').css('display','block');
}
function changeStaff(){
    $('#bookeddate').val("");
    $('#noslotblock').css('display','none');
    $('#slottimeblock').css('display','none');
}

function showappointmentdate(e){
  console.log(e.dataset.appoinment);
  console.log("element : "+$("#next_appointment_date_"+e.dataset.appoinment).attr('style'));
  console.log($("#next_appointment_date_"+e.dataset.appoinment));
  
      // console.log();
      if($("#next_appointment_date_"+e.dataset.appoinment).css('display') == 'none'){
         // $("#next_div_appoinment"+e.dataset.id).show();
         //$("#next_appointment_date_"+e.dataset.appoinment).css('display','block');
     } else{
          // $("#next_div_appoinment"+e.dataset.id).hide();
         // $("#next_appointment_date_"+e.dataset.appoinment).css('display','none');
     }
      // modal=$("changestatus24");
      // modal.location.reload(true);
      // $("#changestatus"+e.dataset.appoinment).dialog("option","modal",true)
      //               .dialog("close")
      //               .dialog("open");
  }
  
</script>



