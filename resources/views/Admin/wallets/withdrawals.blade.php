@extends('layouts.admin')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>Withdrawals @if($merchantinfo && $merchantinfo->id > 0){{'('.$merchantinfo->busineess_name.', Balance :'.CURR.$merchantinfo->wallet_balance.')'}}@endif </h1>
        <ol class="breadcrumb">
            <li><a href="{{URL::to('admin/admins/dashboard')}}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
            
            @if($merchantinfo && $merchantinfo->id > 0)
            <li><a href="{{URL::to('admin/admins/merchant')}}"><i class="fa fa-users"></i> <span> Merchants</span></a></li>
            <li class="active">Withdrawals</li>
            @else
            <li class="active">Withdrawals</li>
            @endif
        </ol>
    </section>

    <section class="content">
        <div class="box box-info">
            <div class="ersu_message">@include('elements.admin.errorSuccessMessage')</div>
            <div class="admin_search">
                {{ Form::open(array('method' => 'post', 'id' => 'adminSearch')) }}
                <div class="form-group align_box dtpickr_inputs">
                    <span class="hints">Search by Business Name</span>
                    <span class="hint">{{Form::text('keyword', null, ['class'=>'form-control', 'placeholder'=>'Search by keyword', 'autocomplete' => 'off'])}}</span>
                    <div class="admin_asearch">
                        <div class="ad_s ajshort">{{Form::button('Submit', ['class' => 'btn btn-info admin_ajax_search'])}}</div>
                        <div class="ad_cancel"><a href="{{URL::to('admin/wallets/withdrawals')}}" class="btn btn-default canlcel_le">Clear Search</a></div>
                    </div>
                </div>
                {{ Form::close()}}
				
				@if($merchantinfo && $merchantinfo->id > 0)
					@if($merchantinfo->wallet_balance>=1)
					    <div class="add_new_record"><a href="{{URL::to('admin/wallets/createrequest/'.$merchantinfo->slug)}}" class="btn btn-default"><i class="fa fa-plus"></i> Create Withdraw Request</a></div>
				    @endif
				@else
					<div class="add_new_record"><a href="{{URL::to('admin/wallets/createrequest')}}" class="btn btn-default"><i class="fa fa-plus"></i> Create Withdraw Request</a></div>
				@endif
            </div>            
            <div class="m_content" id="listID">
                @include('elements.admin.wallets.withdrawals')
            </div>
        </div>
    </section>
</div>
@endsection