<?php 
global $withdrawal_status; 
?>
@if($status == 0 || $status == 1)
	{{Form::select('status',$withdrawal_status, $status, ['class'=>'form-control required ', 'placeholder'=>'Select Status', 'autocomplete' => 'off', 'id' => 'WithdrawalStatus'.$id, 'onchange' => "changestatus(this.value,'" . $id . "','" . $status . "')"])}}
@else
	{{$withdrawal_status[$status]}}
@endif