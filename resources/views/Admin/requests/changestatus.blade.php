@extends('layouts.admin')
@section('content')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $("#adminForm").validate();
    });
    function showhidetime(value) {
        
        $.each($("input[name='" + value + "']:checked"), function () {
            $("#"+value+"_time_from").show();
            $("#"+value+"_time_to").show();
        });
        $.each($("input[name='" + value + "']:unchecked"), function () {
            $("#"+value+"_time_from").val('');
            $("#"+value+"_time_to").val('');
            $("#"+value+"_time_from").hide();
            $("#"+value+"_time_to").hide();
        });
    }
    $(function() {
     $("#next_appointment_date").datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            dateFormat: 'yy-mm-dd',
            numberOfMonths: 1,
            minDate: 'mm-dd-yyyy',
            //maxDate:'mm-dd-yyyy',
            changeYear: true,
            onClose: function(selectedDate) {
              if(selectedDate){$("#next_appointment_date").datepicker("option", "", selectedDate);}
            }
        });
  });
</script>


<style type="text/css">
  
   td{
    padding:2px;
    width:20%;
  }
  /*table{
    width:80%;
  }*/
  table {
   max-width:100%; 
   width:100%; 
  }
  .total-tr{border-top:1px solid black;border-bottom:1px solid black;}
  .print-table td{width:20%;}.print-table {max-width:100%;width:80%;}
  .showatr {
  width: 100%;
  }
  .sizes-drop {
  background: #ffffff;
  border: 1px solid #e9e8e8;
  position: absolute;
  width: 96.5%;
  z-index: 1;
}
.crooss span {
  display: inline-block;
  padding: 2px;
  line-height: 10px;
  color: #fff;
  cursor: pointer;
  position: absolute;
  right: -8px;
  top: -7px;
  background: #f00;
  border-radius: 50%;
  width: 20px;
  height: 20px;
  text-align: center;
  line-height: 17px;
  font-weight: bold;
}
.sizemorescroll {
  background: #f6f6f6;
  max-height: 200px;
  overflow-x: hidden;
  overflow-y: auto;
  padding: 0;
  width: 100%;
  z-index: 1;
}
.cloth_size {
  float: left;
  width: 100%;
}
.des_box_cont.test-size {
  border-bottom: 1px solid #ddd;
  margin-bottom: 0;
  padding: 4px 10px;
}
.des_box_cont.test-size input[type="checkbox"] {
  vertical-align: middle;
  margin: -3px 5px 0 0;
}
.des_box_cont.test-size label {
  margin: 0;
  font-weight: normal;
  vertical-align: top; color: #797979
}
.newstyle
{
  display: block!important;
  width:100%!important;
}
</style>
<div class="content-wrapper">
    
    <section class="content-header">
        <h1>Update Booking Request</h1>
        <ol class="breadcrumb">
            <li><a href="{{URL::to('admin/admins/dashboard')}}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
            <li ><a href="{{URL::to('admin/requests')}}">Booking Requests</a></li>
            <li class="active"> Update Booking Request</li>
        </ol>
    </section>

    <section class="content">
        <div class="box box-info">
            <div class="ersu_message">@include('elements.admin.errorSuccessMessage')</div>
            
            
            {{ Form::open(array('url'=>'admin/changestatus/'.$appointment->slug , 'method' => 'post', 'id' => 'adminForm','onsubmit'=>'return checkFormData()')) }}
                
              <div class="form-horizontal">
                <div class="box-body">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Appointment Status</label>
                        <div class="col-sm-10">
                            {{Form::select('changedstatus', ['Pending'=>'Pending','Canceled'=>'Canceled','Completed'=>'Completed','No show'=>'No show','Confirmed'=>'Confirmed','Visited'=>'Visited'],$appointment->status, ['class' => 'small form-control required', 'id' => 'changedstatus'])}}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Next Appointment Date</label>
                        <div class="col-sm-10">
                            <input type="text" id="next_appointment_date" name = "next_appointment_date" placeholder="Select Next Appointment Date" class="form-control form_date" value="{{$appointment->next_appointment_date}}"> 
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Select Services <span class="require"></span></label>
                        <div class="col-sm-10" >
                            <?php
                                $vall = '';
                                $oldAssets = array();
                                $convert_to_array=array();
                                $cnt=0;
                                // print_r($appointment->service_ids);
                                if(isset($appointment->service_ids) && $appointment->service_ids!='' && $appointment->service_ids!=0 ){
                                  
                                    $convert_to_array = explode(",", $appointment->service_ids);
                                    $cnt=count( $convert_to_array);
                                    $vall = count( $convert_to_array).' services selected';
                                    $oldAssets = $appointment->service_ids;
                                }
                            ?>
                            <div id="size-filter" class="showatr" style="float:left;">
                                <input type="hidden" id="isSelect" value="0">   
                                <input type="text" id="ProductTotalSize" placeholder="Select Services" readonly="readonly" autocomplete="off" onclick="$('#sizes-dropdown').toggle();" class="form-control" name="Testpapers[sselectedassets]" value="<?php echo $vall;?>" data-count="<?php echo $cnt; ?>">               
                                <div class="sizes-drop" style="display: none;" id="sizes-dropdown">
                                    <div class="crooss">
                                        <span class="astclose" onclick="$('#sizes-dropdown').toggle();" >X</span>                                        
                                    </div>
                                    <div class="sizemorescroll">
                                        <div class="cloth_size"> 
                                            <?php if($allservices){
                                                $assetArray = array();
                                                foreach($allservices as $service){
                                                    $aid = $service->id;
                                                    $aname = $service->name;
                                                    $assetArray[$aid] = $aname;
                                                    $aname = str_replace('"', '', $aname);
                                                    $checked = '';
                                                    if(in_array($aid, $convert_to_array)){
                                                        $checked = 'checked';
                                                    }
                                                    $maxq = 40;//$catquestion[$key];
                                                    ?>
                                                    <div class="des_box_cont test-size connect-cat newstyle" style=""><input onclick="changeCount(<?php echo $aid;?>,'<?php echo $aname;?>')" <?php echo $checked;?> type="checkbox" id="StrategyAsset<?php echo $aid;?>" value="<?php echo $aid;?>" name="service_ids[]"><label for="StrategyAsset<?php echo $aid;?>" class=""><?php echo $assetArray[$aid];?></label></div>
                                                <?php }
                                            } ?>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="box-footer">
                        <label class="col-sm-2 control-label" for="inputPassword3">&nbsp;</label>
                        {{Form::submit('Submit', ['class' => 'btn btn-info'])}}
                        <a href="{{ URL::to( 'admin/requests')}}" title="Cancel" class="btn btn-default canlcel_le">Cancel</a>
                    </div>
                </div>
              </div>
            {{ Form::close()}}
            
      </div>  
  </section>
  
</div>

<script>

  function changeCount(aid, aname){
    // console.log("id: "+aid);
    if($("#StrategyAsset"+aid).is(':checked')){
        var count=$("#ProductTotalSize").data("count");
        count++;
        $("#ProductTotalSize").data("count",count);
        $("#ProductTotalSize").val(count+' services selected');
    }else{
      
        var count=$("#ProductTotalSize").data("count");
        count--;
        $("#ProductTotalSize").data("count",count);
        $("#ProductTotalSize").val(count+' services selected');
    }
}   
</script>
@endsection