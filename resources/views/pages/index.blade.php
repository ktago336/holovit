@extends('layouts.dashboard')
@section('content')
<div class="main_dashboard">
<div class="container">
    <div class="st_pages">
        <div class="st_pages_title">{!! $pageInfo->title !!}</div>
        <div class="st_pages_cnt">{!! $pageInfo->description !!}</div>
    </div>
</div>
</div>
@endsection