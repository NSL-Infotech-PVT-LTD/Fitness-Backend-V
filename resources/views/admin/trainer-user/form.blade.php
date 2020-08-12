<div class="form-group{{ $errors->has('first_name') ? ' has-error' : ''}}">
    {!! Form::label('first_name', 'First Name: ', ['class' => 'control-label']) !!}
    {!! Form::text('first_name', null, ['class' => 'form-control', 'required' => 'required']) !!}
    {!! $errors->first('first_name', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('middle_name') ? ' has-error' : ''}}">
    {!! Form::label('middle_name', 'Middle Name: ', ['class' => 'control-label']) !!}
    {!! Form::text('middle_name', null, ['class' => 'form-control']) !!}
    {!! $errors->first('middle_name', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('last_name') ? ' has-error' : ''}}">
    {!! Form::label('last_name', 'Last Name: ', ['class' => 'control-label']) !!}
    {!! Form::text('last_name', null, ['class' => 'form-control', 'required' => 'required']) !!}
    {!! $errors->first('last_name', '<p class="help-block">:message</p>') !!}
</div>
<div class="col-md-12 form-group{{ $errors->has('mobile') ? ' has-error' : ''}}">
    {!! Form::label('mobile', 'Mobile: ', ['class' => 'control-label']) !!}
    {!! Form::text('mobile_prefix', null, ['class' => 'form-control col-md-2', 'required' => 'required','placeholder'=>'Prefix']) !!}
    {!! Form::text('mobile', null, ['class' => 'form-control col-md-10', 'required' => 'required']) !!}
    {!! $errors->first('mobile', '<p class="help-block">:message</p>') !!}
</div>
<div class="col-md-12 form-group{{ $errors->has('emergency_contact_no') ? ' has-error' : ''}}">
    {!! Form::label('emergency_contact_no', 'Emergency contact no: ', ['class' => 'control-label']) !!}
    {!! Form::text('emergency_contact_no_prefix', null, ['class' => 'form-control col-md-2', 'required' => 'required','placeholder'=>'Prefix']) !!}
    {!! Form::text('emergency_contact_no', null, ['class' => 'form-control col-md-10', 'required' => 'required']) !!}
    {!! $errors->first('emergency_contact_no', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('email') ? ' has-error' : ''}}">
    {!! Form::label('email', 'Email: ', ['class' => 'control-label']) !!}
    {!! Form::email('email', null, ['class' => 'form-control', 'required' => 'required']) !!}
    {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('expirence') ? ' has-error' : ''}}">
    {!! Form::label('expirence', 'Expirence: ', ['class' => 'control-label']) !!}
    {!! Form::text('expirence', null, ['class' => 'form-control', 'required' => 'required']) !!}
    {!! $errors->first('expirence', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('password') ? ' has-error' : ''}}">
    {!! Form::label('password', 'Password:', ['class' => 'control-label']) !!}
    {!! Form::label('password', 'Password between 6 and 20 characters; must contain at least one lowercase letter, one uppercase letter, one numeric digit, and one special character, but cannot contain whitespace.', ['class' => 'control-label']) !!}
    @php
    $passwordOptions = ['class' => 'form-control'];
    if ($formMode === 'create') {
    $passwordOptions = array_merge($passwordOptions, ['required' => 'required']);
    }
    @endphp
    {!! Form::password('password', $passwordOptions) !!}
    {!! $errors->first('password', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('birth_date') ? ' has-error' : ''}}">
    {!! Form::label('birth_date', 'Birth Date: ', ['class' => 'control-label']) !!}
    {!! Form::date('birth_date', null, ['class' => 'form-control', 'required' => 'required']) !!}
    {!! $errors->first('birth_date', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('emirates_id') ? ' has-error' : ''}}">
    {!! Form::label('emirates_id', 'Emirates Id: ', ['class' => 'control-label']) !!}
    {!! Form::text('emirates_id', null, ['class' => 'form-control', 'required' => 'required']) !!}
    {!! $errors->first('emirates_id', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('address_house') ? ' has-error' : ''}}">
    {!! Form::label('address_house', 'Building/ House No.  : ', ['class' => 'control-label']) !!}
    {!! Form::text('address_house', null, ['class' => 'form-control', 'required' => 'required']) !!}
    {!! $errors->first('address_house', '<p class="help-block">:message</p>') !!}
</div>
<div class="col-md-12">
    <div class="col-md-3 form-group{{ $errors->has('address_street') ? ' has-error' : ''}}">
        {!! Form::label('address_street', 'Street: ', ['class' => 'control-label']) !!}
        {!! Form::text('address_street', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('address_street', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="col-md-3 form-group{{ $errors->has('address_city') ? ' has-error' : ''}}">
        {!! Form::label('address_city', 'City: ', ['class' => 'control-label']) !!}
        {!! Form::text('address_city', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('address_city', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="col-md-3 form-group{{ $errors->has('address_country') ? ' has-error' : ''}}">
        {!! Form::label('address_country', 'Country: ', ['class' => 'control-label']) !!}
        {!! Form::text('address_country', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('address_country', '<p class="help-block">:message</p>') !!}
    </div>
    <!--    <div class="col-md-3 form-group{{ $errors->has('address_postcode') ? ' has-error' : ''}}">
            {!! Form::label('address_postcode', 'Postcode: ', ['class' => 'control-label']) !!}
            {!! Form::text('address_postcode', null, ['class' => 'form-control', 'required' => 'required']) !!}
            {!! $errors->first('address_postcode', '<p class="help-block">:message</p>') !!}
        </div>-->
</div>
<?php
if (isset($traineruser->image))
    echo "<img width='100' src=" . url('uploads/trainer-user/' . $traineruser->image) . ">";
?>
<div class="form-group{{ $errors->has('image') ? 'has-error' : ''}}">
    {!! Form::label('image', 'Profile Image (360 X 450)', ['class' => 'control-label']) !!}
    {!! Form::file('image', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('image', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('about') ? ' has-error' : ''}}">
    {!! Form::label('about', 'About: ', ['class' => 'control-label']) !!}
    {!! Form::textarea('about', null, ['class' => 'form-control', 'required' => 'required']) !!}
    {!! $errors->first('about', '<p class="help-block">:message</p>') !!}
</div>
<div class="row form-group{{ $errors->has('services') ? ' has-error' : ''}}">
    {!! Form::label('services', 'Offered Services: ', ['class' => 'control-label']) !!}
    <?php if (\App\Service::where('status', '1')->get()->isEmpty() == true): ?>
        {{'There is no offered Services Yet Kindly Check Services Section!'}}
    <?php
    else:
        $servies = isset($traineruser->services) ? $traineruser->services : [];
        ?>
        <?php foreach (\App\Service::where('status', '1')->get()->pluck('name', 'id') as $id => $service): ?>
            <div class="col-md-6 float-right">
                {!! Form::label($id, $service, ['class' => 'control-label float-left col-md-4 text-capitalize']) !!}
                {{ Form::checkbox('services[]',$id,(in_array($id, $servies)?true:false), ['class' => 'form-controls','id'=>$id]) }}
            </div>
        <?php endforeach; ?>
    <?php endif; ?>      
</div>
<div class="form-group">
    {!! Form::submit($formMode === 'edit' ? 'Update' : 'Create', ['class' => 'btn btn-primary']) !!}
</div>
<style type="text/css">
    .col-md-3.form-group {
        float: left;
        padding-left: 0px;
    }

    input.form-control.col-md-2 {
        float: left;
        padding-left: 0px;
    }

    label.control-label {
        float: left;
        width: 100%;
    }

    .col-md-12 {padding-left: 0px;}
</style>