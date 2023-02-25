<?php 
global $withdrawal_status; 
?>
<?php if($status == 0 || $status == 1): ?>
	<?php echo e(Form::select('status',$withdrawal_status, $status, ['class'=>'form-control required ', 'placeholder'=>'Select Status', 'autocomplete' => 'off', 'id' => 'WithdrawalStatus'.$id, 'onchange' => "changestatus(this.value,'" . $id . "','" . $status . "')"])); ?>

<?php else: ?>
	<?php echo e($withdrawal_status[$status]); ?>

<?php endif; ?><?php /**PATH /home/lscouponslogicsp/public_html/resources/views/elements/admin/change_withdrawal_status.blade.php ENDPATH**/ ?>