@if($status=='1')
    <a href="{{ URL::to($action)}}" title="Deactivate" class="deactivate"><button class="btn btn-primary btn-xs"><i class="fa fa-check"></i></button></a>
@else
    <a href="{{ URL::to($action)}}" title="Activate" class="activate"><button class="btn btn-primary btn-xs"><i class="fa fa-ban"></i></button></a>
@endif