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
<div class="form-group{{ $errors->has('mobile') ? ' has-error' : ''}}">
    {!! Form::label('mobile', 'Mobile: ', ['class' => 'control-label']) !!}
    {!! Form::text('mobile', null, ['class' => 'form-control', 'required' => 'required']) !!}
    {!! $errors->first('mobile', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('emergency_contact_no') ? ' has-error' : ''}}">
    {!! Form::label('emergency_contact_no', 'Emergency contact no: ', ['class' => 'control-label']) !!}
    {!! Form::text('emergency_contact_no', null, ['class' => 'form-control', 'required' => 'required']) !!}
    {!! $errors->first('emergency_contact_no', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('email') ? ' has-error' : ''}}">
    {!! Form::label('email', 'Email: ', ['class' => 'control-label']) !!}
    {!! Form::email('email', null, ['class' => 'form-control', 'required' => 'required']) !!}
    {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('password') ? ' has-error' : ''}}">
    {!! Form::label('password', 'Password: ', ['class' => 'control-label']) !!}
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
<div class="form-group{{ $errors->has('address') ? ' has-error' : ''}}">
    {!! Form::label('address', 'Address: ', ['class' => 'control-label']) !!}
    {!! Form::text('address', null, ['class' => 'form-control', 'required' => 'required']) !!}
    {!! $errors->first('address', '<p class="help-block">:message</p>') !!}
</div>
<?php
if (isset($traineruser->image))
    echo "<img width='100' src=" . url('uploads/trainer-user/' . $traineruser->image) . ">";
?>
<div class="form-group{{ $errors->has('image') ? 'has-error' : ''}}">
    {!! Form::label('image', 'Profile Image', ['class' => 'control-label']) !!}
    {!! Form::file('image', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('image', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('about') ? ' has-error' : ''}}">
    {!! Form::label('about', 'About: ', ['class' => 'control-label']) !!}
    {!! Form::textarea('about', null, ['class' => 'form-control', 'required' => 'required']) !!}
    {!! $errors->first('about', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('services') ? ' has-error' : ''}}">
    {!! Form::label('services', 'Offered Services: ', ['class' => 'control-label']) !!}
    {!! Form::select('services[]', \App\Service::where('status','1')->get()->pluck('name','id'), isset($traineruser->services) ? $traineruser->services : [], ['class' => 'form-control', 'multiple' => true]) !!}
</div>
<div class="form-group">
    {!! Form::submit($formMode === 'edit' ? 'Update' : 'Create', ['class' => 'btn btn-primary']) !!}
</div>
