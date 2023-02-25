@extends('layouts.admin')
@section('content')
<?php 
$parent_id = Session::get('parent_id');
$adminLId = Session::get('adminid');
$adminRols = App\Http\Controllers\Admin\AdminsController::getAdminRoles(Session::get('adminid'));
$checkSubRols = App\Http\Controllers\Admin\AdminsController::getAdminRolesSub(Session::get('adminid'));
?>
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
</style>
<div class="content-wrapper">
    
    <section class="content-header">
        <h1>Booking Request Invoice</h1>
        <ol class="breadcrumb">
            <li><a href="{{URL::to('admin/admins/dashboard')}}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
            <li ><a href="{{URL::to('admin/requests')}}">Booking Requests</a></li>
            <li class="active"> Booking Request Invoice</li>
        </ol>
    </section>

    <section class="content">
        <div class="box box-info">
            <div class="ersu_message">@include('elements.admin.errorSuccessMessage')</div>
            <div class="row" class="">
              <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12">
                <ul class="nav nav-pills nav-justified">
                  <li class="active " id="inv-print-btn"><a href="javascript:void(0)"><i class="fa fa-list-alt" aria-hidden="true"></i> Invoice</a></li>
                  <?php if($adminLId != 1) {?>
                  <?php $role = 2; if(isset($checkSubRols[6])){
                  if ($adminLId == 1 || in_array($role, $checkSubRols[6])) { ?>
                  <li class="" id="inv-edit-btn"><a href="javascript:void(0)"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit Invoice</a></li>
                <?php } } } else{?><li class="" id="inv-edit-btn"><a href="javascript:void(0)"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit Invoice</a></li>
                <?php } ?>
                </ul>
              </div>
            </div>
            <div>&nbsp;</div>
            <button class="btn btn-default " id="print-btn"><i class="fa fa-print" aria-hidden="true"></i>Print</button>
            <div id="printinvoice" class="container">
              
              
              <div class="table-responsive">
                
              
              <table  class="print-table" width="100%">
                <tr class="text-center">
                  <td colspan="4" align="center"><h1>Invoice</h1></td>
                </tr>
                <tr>
                  <td colspan="3">&nbsp;</td>
                  <td align="right"><span>Date : </span><span>{{ date('d/m/Y') }}</span></td>
                  <!-- <td >{{ date('d/m/Y') }}</td> -->
                </tr>
                <tr>
                  <td colspan="3">&nbsp;</td>
                  <td align="right"><span>Invoice Number : </span><span>{{$appoinmentdata->id}}</span></td>
                  <!-- <td > {{$appoinmentdata->id}}</td> -->
                </tr>
                <tr>
                  <td colspan="3">&nbsp;</td>
                  <td align="right"><span>Amount : </span><span>{{CURR." ".$appoinmentdata->total_price}}</span></td>
                  <!-- <td >{{CURR." ".$appoinmentdata->total_price}}</td> -->
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td colspan="4" align="">
                    <table width="100%" >
                      <!-- <thead> -->
                        <tr>
                          <td colspan="2" width="" align="center"><div style="border-top:1px solid black;border-bottom:1px solid black;"><h4><strong>INVOICE SUMMARY</strong></h4></div></td>
                        </tr>
                        <tr>
                          <td><strong>Description</strong></td>
                          <td align="right"><strong>Amount</strong></td>
                        </tr>
                        <?php foreach ($services as $s) { ?>
                        <tr>
                          <td>{{$s->name}}</td>
                          <td align="right">{{$s->price}}</td>
                        </tr>
                        <?php } ?>
                        <tr class="total-tr" style="border-top:1px solid black;border-bottom:1px solid black;">
                          <td><h4><strong>TOTAL AMOUNT DUE</strong></h4></td>
                          <td align="right"><h4><strong>{{CURR." ".$appoinmentdata->total_price}}</strong></h4></td>
                        </tr>
                      <!-- </thead> -->
                    </table>
                  </td>
                </tr>
              </table>
              </div>
            </div>
            <div id="editinvoice" style="display:none">   
            {{ Form::open(array('url'=>'admin/saveinvoice/'.$appoinmentdata->slug , 'method' => 'post', 'id' => 'adminForm','onsubmit'=>'return checkFormData()')) }}
              
             <div id="wrapper">
              <div>&nbsp;</div>
              <table align='center' cellspacing=2 cellpadding=5 id="data_table" class="table">

                <tr>
                  <th>Service Name</th>
                  <th>Price</th>
                  <th>Action</th>
                </tr>
                <?php
                $i=1;
                  foreach ($services as $s) {
                     // print_r($s->name);
                ?>     
                
                  <tr id="row{{$i}}">
                    <td id="name_row{{$i}}" data-value="{{$s->id}}">{{$s->name}}</td>
                    <td id="country_row{{$i}}">{{$s->price}}</td>
                    <td>
                      <!-- <input type="button" id="edit_button{{$i}}" value="Edit" class="edit" onclick="edit_row('{{$i}}')"> -->
                      <input type="button" id="save_button{{$i}}" value="Save" class="save" onclick="save_row('{{$i}}')" style="display:none">
                      <button type="button"  class="delete btn btn-danger" onclick="delete_row('{{$i}}')" title="Delete Service"><i class="fa fa-times"></i></button>
                    </td>
                  </tr>
                <?php
                $i++;     
                   }
                ?>
                <tr>
                  <td>
                    <select onchange="fetch_select(this.value);" id="new_name" class="form-control">
                      <option value="">Select Service</option>
                      <?php
                        foreach($allservices as $as)
                        {
                          if(in_array($as['name'], $oldservices)){
                              echo "<option value='".$as['id']."'  style='display:none;'>".$as['name']."</option>";
                          }else{
                              echo "<option value='".$as['id']."'>".$as['name']."</option>";   
                          }

                         
                        }
                      ?>
                     </select>

                    <!-- <input type="text" id="new_name"> -->
                  </td>

                  <td><input type="text" id="new_country" value='0' readonly="readonly" class="form-control"></td>
                  <td><button type="button" class="add btn btn-primary" onclick="add_row();" title="Add Service"><i class='fa fa-plus'></i></button></td>
                </tr>

              </table>  
              <label>Total :</label> <input type="text" name="total_price" id="total_price" value="{{$appoinmentdata->total_price}}" readonly="readonly">
              <input type="hidden" name="service_ids" id="service_ids" value="{{$appoinmentdata->service_ids}}" class="required"><input type="submit" name="submit" value="Save" class="btn btn-success" >
            </div>
            <div class="form-group">&nbsp;</div>
           <!-- </div> -->
            {{ Form::close()}}
            </div>
      </div>  
  </section>
  
</div>

<script>
function printDiv() 
{

  var divToPrint=document.getElementById('DivIdToPrint');

  var newWin=window.open('','Print-Window');

  newWin.document.open();

  newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');

  newWin.document.close();

  setTimeout(function(){newWin.close();},10);

}
//   function edit_row(no)
// {
//  document.getElementById("edit_button"+no).style.display="none";
//  document.getElementById("save_button"+no).style.display="block";
  
//  var name=document.getElementById("name_row"+no);
//  var country=document.getElementById("country_row"+no);
//  // var age=document.getElementById("age_row"+no);
  
//  var name_data=name.innerHTML;
//  var country_data=country.innerHTML;
//  // var age_data=age.innerHTML;
  
//  name.innerHTML="<input type='text' id='name_text"+no+"' value='"+name_data+"'>";
//  country.innerHTML="<input type='text' id='country_text"+no+"' value='"+country_data+"'>";
//  // age.innerHTML="<input type='text' id='age_text"+no+"' value='"+age_data+"'>";
// }

// function save_row(no)
// {
//  var name_val=document.getElementById("name_text"+no).value;
//  var country_val=document.getElementById("country_text"+no).value;
//  // var age_val=document.getElementById("age_text"+no).value;

//  document.getElementById("name_row"+no).innerHTML=name_val;
//  document.getElementById("country_row"+no).innerHTML=country_val;
//  // document.getElementById("age_row"+no).innerHTML=age_val;

//  document.getElementById("edit_button"+no).style.display="block";
//  document.getElementById("save_button"+no).style.display="none";
// }

function delete_row(no)
{
  
 var price=parseInt($("#country_row"+no).html());
 var total=parseInt($("#total_price").val());
 // console.log('total: '+total)
 //  console.log('price: '+price);
 var deletedservice=$("#name_row"+no).attr('data-value');
 $('option[value="'+deletedservice+'"]').css('display','block')

 // console.log("deletedservice: "+deletedservice);
  $("#total_price").val(total-price);
  document.getElementById("row"+no+"").outerHTML="";
  var str='';
 $('[id^="name_row"]').each(function(){
    if(str==''){
      str=(this).dataset.value;
    }else{
      str=str+","+(this).dataset.value;
    }
  });
 $('#service_ids').val(str);
  console.log(str);
}

function add_row()
{
  // $("#name_row").
  if($("#new_name").val()=='null' || $("#new_name").val()=='' || $("#new_name").val()==null){
      alert("Please Select Service Before Add");
      return ;
    }
  var new_name=$("#new_name").find(":selected").text();
  var selectedvalue=$("#new_name").val();
  var new_country=parseInt($("#new_country").val());
  var price=parseInt($("#new_country").val());
  var total=parseInt($("#total_price").val());
  console.log('total: '+total)
  console.log('price: '+price);
  $("#total_price").val(total+price);
  $('option[value="'+selectedvalue+'"]').css('display','none')
 
 // var new_age=document.getElementById("new_age").value;
  
 var table=document.getElementById("data_table");
 var table_len=(table.rows.length)-1;
 console.log(table_len);
 var row = table.insertRow(table_len).outerHTML="<tr id='row"+table_len+"'><td id='name_row"+table_len+"' data-value='"+selectedvalue+"'>"+new_name+"</td><td id='country_row"+table_len+"'>"+new_country+"</td><td> <input type='button' id='save_button"+table_len+"' value='Save' class='save' onclick='save_row("+table_len+")' style='display:none'> <button type='button' value='Delete' class='delete btn btn-danger' onclick='delete_row("+table_len+")'><i class='fa fa-times'></i></button></td></tr>";
 // var row = table.insertRow(table_len).outerHTML="<tr id='row"+table_len+"'><td id='name_row"+table_len+"'>"+new_name+"</td><td id='country_row"+table_len+"'>"+new_country+"</td><td><input type='button' id='edit_button"+table_len+"' value='Edit' class='edit' onclick='edit_row("+table_len+")'> <input type='button' id='save_button"+table_len+"' value='Save' class='save' onclick='save_row("+table_len+")' style='display:none'> <input type='button' value='Delete' class='delete' onclick='delete_row("+table_len+")'></td></tr>";
 var str='';
 $('[id^="name_row"]').each(function(){
    if(str==''){
      str=(this).dataset.value;
    }else{
      str=str+","+(this).dataset.value;
    }
  });
  console.log(str);
  $('#service_ids').val(str);

 document.getElementById("new_name").value="";
 document.getElementById("new_country").value=0;
 
 // document.getElementById("new_age").value="";
}

function fetch_select(e){
  console.log(e);
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  $.ajax({ 
    type: 'POST',
    url: "<?php echo HTTP_PATH; ?>/admin/getprice",
    cache: false,
    data:{'service':e},
    success: function (result)
    {

      // var results = JSON.parse(result);
          console.log('result : '+result);
          $('#new_country').val(result);
          // var content="<option value=''>Select Time</option>";
    }
  });
}
$('#inv-print-btn').click(function(){
  $('#printinvoice').css('display','block');
  $('#print-btn').css('display','block');
  $('#editinvoice').css('display','none');
  $('#inv-edit-btn').removeClass('active');
  $('#inv-print-btn').addClass('active');
})
$('#inv-edit-btn').click(function(){
  $('.ersu_message').html('');
  $('#editinvoice').css('display','block');
  $('#print-btn').css('display','none');
  $('#printinvoice').css('display','none');
  $('#inv-print-btn').removeClass('active');
  $('#inv-edit-btn').addClass('active');
})
$('#print-btn').click(function(){
  var divToPrint=document.getElementById('printinvoice');

  var newWin=window.open('','Print-Window');

  newWin.document.open();

  newWin.document.write('<html><head><style type="text/css">.print-table td{width:20%;}.print-table {max-width:100%;width:100%;}td{padding:4px;}body{font-family:sans-serif;font-size:30px!important;margin:0 auto;} table .total-tr{border-top:1px solid black!important;border-bottom:1px solid black!important;}  @media print {table{ page-break-after: always;}}</style></head><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');

  newWin.document.close();

  setTimeout(function(){newWin.close();},10);
})
function checkFormData(){
  if($('#service_ids').val()!='' && $('#service_ids').val()!='undefined' && $('#service_ids').val()!=null){
    return true;
  }else{
    alert('Please Select atleast One Service');
    return false;
  }
}
</script>
@endsection