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
    });
</script>
<?php 
$parent_id = Session::get('parent_id');
$adminLId = Session::get('adminid');
$adminRols = App\Http\Controllers\Admin\AdminsController::getAdminRoles(Session::get('adminid'));
$checkSubRols = App\Http\Controllers\Admin\AdminsController::getAdminRolesSub(Session::get('adminid'));
?>
<?php
use App\Models\Admin;
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
            <div class="topn_left">Reports List</div>
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
                       <!--  <th style="width:5%">#</th> -->
                        <th class="sorting_paging">@sortablelink('appointment_number', 'Appointment Number')</th>
                        <th class="sorting_paging" hidden>@sortablelink('id', 'id')</th>
                        <th class="sorting_paging">@sortablelink('service_ids', 'Service')</th>
                        <th class="sorting_paging">@sortablelink('user_id', 'Customer Name')</th>
                        <th class="sorting_paging">
                            @sortablelink('staff_id', 'Staff Name')
                        </th> 
                        <th class="sorting_paging">@sortablelink('booking_date_time', 'Appointment Date Time')</th>
                        <th class="sorting_paging">@sortablelink('status', 'Status')</th>
                        <th class="sorting_paging">@sortablelink('total_price', 'Price')</th>
                        <th class="sorting_paging">@sortablelink('created_at', 'Date')</th>
                        </tr>
                </thead>
                <tbody>

                    @foreach($appointments as $appointment)
                    <tr>
                        <!-- <th style="width:5%"><input type="checkbox" onclick="javascript:isAllSelect(this.form);" name="chkRecordId[]" value="{{$appointment->id}}" /></th> -->
                        <td data-title="Appointment Number">{{$appointment->appointment_number}}</td>
                        <td data-title="id" hidden>
                            {{$appointment->id}}
                        </td>
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
                            {{$a}}
                        </td>
                        <td data-title="Username">@if($appointment->user_id != '0')
                            {{$appointment->User['first_name']}}
                        @else
                            {{'N/A'}}
                        @endif</td>
                        <td data-title="Staffname">@if($appointment->staff_id!='1')
                            {{$appointment->Admin->first_name}}
                            @else
                            {{'N/A'}}
                        @endif</td> 
                        <td data-title="Appointment Date Time">{{$appointment->booking_date_time}}</td>
                    <?php global $change_status; ?>
                    <td data-title="Description" class="{{ $change_status[$appointment->status]}}">{{$appointment->status}}</td>
                    <td data-title="Price">{{CURR }}{{$appointment->total_price}}</td>
                    <td data-title="Date">{{$appointment->created_at->format('M d, Y')}}</td> 
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>
        
    
</section>
{{ Form::close()}}
</div>         
</div> 
@else 
<div id="listingJS" style="display: none;" class="alert alert-success alert-block fade in"></div>
<div class="admin_no_record">No record found.</div>
@endif




@if(!$appointments->isEmpty())
@foreach($appointments as $appointment)
<div id="info{!! $appointment->id !!}" style="display: none;">
    <div class="nzwh-wrapper">
        <fieldset class="nzwh">
            <legend class="head_pop">{!! $appointment->User['first_name']!!} 
              <?php if($appointment->user_id == '') echo "N/A";?>
            </legend>
            <div class="drt">
                {{ Form::open(array('url'=>'admin/services/reschedule/'.$appointment->slug,'method' => 'post', 'id' => 'serviceForm', 'enctype' => "multipart/form-data")) }}
                <br>

                <div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Appointment Date <span class="require">*</span></label>
                    <div class="col-sm-9">
                        <input type="text" id ="booking_date_time" name ="booking_date_time"  placeholder="Booking Date" onfocus="(this.type='date')" class="form-control required" onchange="getdate()">
                        
                    </div>
                </div><br>
                    <span class="text-danger" id="noslotblock">No Slot Available Please Check For Another Date</span>
                </div>
                <div id ="slottimeblock">
                    <div class="form-group">
                    <label class="col-sm-3 control-label">Time Slot<span class="require">*</span></label> 
                    <div class="col-sm-9">
                            <select name="slottime" class="form-control required" placeholder="Select Time" id="slottimeid">
                            <option value="">Select time</option>
                            <?php
                            global $default_time;
                            foreach ($default_time as $d) {
                            ?>
                            <option value="<?php echo $d; ?>"><?php echo $d; ?></option>
                            <?php   
                            }
                            ?>
                            </select> 
                              
                    </div>
                    </div>
                </div>

                <br><br><br>
                <div class="box-footer">
                    <label class="col-sm-4 control-label" for="inputPassword3">&nbsp;</label>
                    {{Form::submit('Submit', ['class' => 'btn btn-info'])}}
                    <a href="{{ URL::to( 'admin/requests')}}" title="Cancel" class="btn btn-default canlcel_le">Cancel</a>

                </div>

                {{Form::close()}}
        </fieldset>
    </div>
</div>
@if(!$appointments->isEmpty())
    @foreach($appointments as $appointment)
        <div id="changestatus{!! $appointment->id !!}" style="display: none;">
            <div class="nzwh-wrapper">
                <fieldset class="nzwh">
                     <legend class="head_pop">Appoinment Request : {!! $appointment->id !!}</legend>
                    <div class="drt">
                        <div class="admin_pop">
                          {{ Form::open(array('url'=>'admin/changestatus/'.$appointment->slug,'method' => 'post', 'id' => 'serviceForm', 'enctype' => "multipart/form-data")) }}
                          <div class="row">
                            <!-- 'Pending','Canceled','Completed','No show','Confirmed' -->
                            <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12">
                              {{Form::select('changedstatus', ['Pending'=>'Pending','Canceled'=>'Canceled','Completed'=>'Completed','No show'=>'No show','Confirmed'=>'Confirmed'],$appointment->status, ['class' => 'small form-control required', 'id' => 'changedstatus'])}}
                              <!-- {!! $appointment->status !!} -->
                            </div>
                            <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12">
                              {{Form::submit('Change Status', ['class' => 'btn btn-info'])}}
                              <!-- <button class="btn btn-primary"></button> -->
                            </div>
                        </div>  
                        {{Form::close()}}
                    </div>
                </fieldset>
            </div>
        </div>
    @endforeach
@endif
<style>
.drt {
    height: 300px; 
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
    // function cancelConfirm(e){
    //   if(){
    //     alert("cancel");
    //   }else{
    //     alert("want to re");
    //   }
    // }
</script>
@endforeach
@endif


