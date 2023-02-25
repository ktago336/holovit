@extends('layouts.newhome')
@section('content')
<section class="profile-section">
  <div class="container">
    <div class="row">
      <div class="col-xs-12 col-md-4 col-lg-3 col-xl-3 " style="border: 1px solid red">

      </div>
      <div class="col-xs-12 col-md-8 col-lg-9 col-xl-9" style="border: 1px solid red">
        <div class="mx-auto">
          <form action=#>

            <div class="step2 mx-2">
              <h4>Enter Your Information</h4>
              <h6>Your name, email, phone number will be used to send you appointment confirmation and remainders. We'll also be able to call or text you if anything changes.</h6>
            </div>
            <div class="ersu_message">@include('elements.errorSuccessMessage')</div>
            {{ Form::open(array('method' => 'post', 'id' => 'adminForm', 'enctype' => "multipart/form-data")) }}
            <div class="form-horizontal">
              <div class="box-body">
                <div class="form-group">
                  <div class= "col-sm-12">
                    <div class="col-sm-6">
                      {{Form::text('first_name', null, ['class'=>'form-control required alphanumeric', 'placeholder'=>'First Name*', 'autocomplete' => 'off'])}}
                    </div>
                    
                    <div class="col-sm-6">
                      {{Form::text('last_name', null, ['class'=>'form-control required alphanumeric', 'placeholder'=>'Last Name*', 'autocomplete' => 'off'])}}
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-6">
                    {{Form::text('email', null, ['class'=>'form-control required email', 'placeholder'=>'Email*', 'autocomplete' => 'off'])}}
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-6">
                    {{Form::text('contact', null, ['class'=>'form-control required digits', 'placeholder'=>'Phone Number*', 'autocomplete' => 'off', 'minlength' => 8, 'maxlength' => 16])}}
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-12">
                    {{Form::textarea('contact', null, ['class'=>'form-control required digits', 'placeholder'=>'Appointment Notes', 'autocomplete' => 'off','rows' => '5'])}}
                  </div>
                </div>
                <div class="">
                  <label class="col-sm-2 control-label" for="inputPassword3">&nbsp;</label>
                  {{Form::submit('PROCEED TO PAY', ['class' => 'btn btn-appointment'])}}
                </div>
              </div>
            </div>
            {{ Form::close()}}

          </form>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="profile-section">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-md-4 col-lg-3 col-xl-3 " style="border: 1px solid red">

            </div>
            <div class="col-xs-12 col-md-8 col-lg-9 col-xl-9" style="border: 1px solid red">
                <div class="mx-auto" style="border: 1px solid green">
                    <form action=#>

                        <div class="step2 mx-2" style="border: 1px solid green">
                            step 2
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection