<div class="front row" >
    
<div class="form-group col-md-4{{ $errors->has('first_name') ? ' has-error' : ''}}">
    {!! Form::label('first_name', 'First Name: ', ['class' => 'control-label']) !!}
    {!! Form::text('first_name', null, ['class' => 'form-control', 'required' => 'required']) !!}
    {!! $errors->first('first_name', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group col-md-4{{ $errors->has('middle_name') ? ' has-error' : ''}}">
    {!! Form::label('middle_name', 'Middle Name: ', ['class' => 'control-label']) !!}
    {!! Form::text('middle_name', null, ['class' => 'form-control']) !!}
    {!! $errors->first('middle_name', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group col-md-4{{ $errors->has('last_name') ? ' has-error' : ''}}">
    {!! Form::label('last_name', 'Last Name: ', ['class' => 'control-label']) !!}
    {!! Form::text('last_name', null, ['class' => 'form-control', 'required' => 'required']) !!}
    {!! $errors->first('last_name', '<p class="help-block">:message</p>') !!}
</div>
<!--<div class="form-group{{ $errors->has('child') ? ' has-error' : ''}}">
    {!! Form::label('child', 'Child: ', ['class' => 'control-label']) !!}
    {!! Form::radio('child','1_child' ,['class' => 'form-control', 'required' => 'required']) !!} One Child
    {!! Form::radio('child','2_child' ,['class' => 'form-control', 'required' => 'required']) !!} Two Children
    {!! Form::radio('child','none' ,['class' => 'form-control', 'required' => 'required','checked' => false]) !!} None
    {!! $errors->first('child', '<p class="help-block">:message</p>') !!}
</div>-->
<div class="form-group col-md-4{{ $errors->has('mobile') ? ' has-error' : ''}}">
    {!! Form::label('mobile', 'Mobile: ', ['class' => 'control-label']) !!}
    {!! Form::text('mobile', null, ['class' => 'form-control', 'required' => 'required']) !!}
    {!! $errors->first('mobile', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group col-md-4{{ $errors->has('gender') ? ' has-error' : ''}}">
    {!! Form::label('gender', 'Gender: ', ['class' => 'control-label']) !!}
    {!! Form::radio('gender','male' ,['class' => 'form-control', 'required' => 'required']) !!} Male
    {!! Form::radio('gender','female' ,['class' => 'form-control', 'required' => 'required']) !!} Female
    {!! $errors->first('marital_status', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group col-md-4{{ $errors->has('emirates_id') ? ' has-error' : ''}}">
    {!! Form::label('emirates_id', 'Emirates Id: ', ['class' => 'control-label']) !!}
    {!! Form::text('emirates_id', null, ['class' => 'form-control', 'required' => 'required']) !!}
    {!! $errors->first('emirates_id', '<p class="help-block">:message</p>') !!}
</div>


<div class="form-group col-md-4{{ $errors->has('emirate_image1') ? 'has-error' : ''}}">
    {!! Form::label('emirate_image1', 'Emirate Copy1', ['class' => 'control-label']) !!}
    {!! Form::file('emirate_image1', null,  ['class' => 'form-control', 'required' => 'required']) !!}
    {!! $errors->first('emirate_image1', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group col-md-4{{ $errors->has('emirate_image2') ? ' has-error' : ''}}">
    {!! Form::label('emirate_image2', 'Emirate Copy2: ', ['class' => 'control-label']) !!}
    {!! Form::file('emirate_image2', null, ['class' => 'form-control', 'required' => 'required']) !!}
    {!! $errors->first('emirate_image2', '<p class="help-block">:message</p>') !!}
</div>
<?php
//dd($formMode);
if ($formMode == 'create'):
    ?>
    <div class="form-group col-md-4{{ $errors->has('email') ? ' has-error' : ''}}">
        {!! Form::label('email', 'Email: ', ['class' => 'control-label']) !!}
        {!! Form::email('email', '', ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="form-group col-md-4{{ $errors->has('password') ? ' has-error' : ''}}">
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
<?php endif; ?>
</div>

    {!! Form::submit($formMode === 'edit' ? 'Update' : 'See More...', ['class'=>'btn btn-primary pull-right', 'id' => 'type_select']) !!}
    <div class="row" id="other">
<div class="form-group col-md-4{{ $errors->has('birth_date') ? ' has-error' : ''}}">
    {!! Form::label('birth_date', 'Birth Date: ', ['class' => 'control-label']) !!}
    {!! Form::date('birth_date', null, ['class' => 'form-control']) !!}
    {!! $errors->first('birth_date', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group col-md-4{{ $errors->has('emergency_contact_no') ? ' has-error' : ''}}">
    {!! Form::label('emergency_contact_no', 'Emergency contact no: ', ['class' => 'control-label']) !!}
    {!! Form::text('emergency_contact_no', null, ['class' => 'form-control']) !!}
    {!! $errors->first('emergency_contact_no', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group col-md-4{{ $errors->has('designation') ? ' has-error' : ''}}">
    {!! Form::label('designation', 'Designation: ', ['class' => 'control-label']) !!}
    {!! Form::text('designation', null, ['class' => 'form-control']) !!}
    {!! $errors->first('designation', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group col-md-4{{ $errors->has('address') ? ' has-error' : ''}}">
    {!! Form::label('address', 'Address: ', ['class' => 'control-label']) !!}
    {!! Form::text('address', null, ['class' => 'form-control']) !!}
    {!! $errors->first('address', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group col-md-4{{ $errors->has('city') ? ' has-error' : ''}}">
    {!! Form::label('city', 'City', ['class' => 'control-label']) !!}
    {!! Form::select('city', ['Abu Dhabi','Dubai','Ajman','Fujairah','Ras al Khaimah','Sharjah','Umm al Quwain'], null, ['class' => 'form-control', 'multiple' => false]) !!}
</div>
<?php if ($formMode == 'create'): ?>
    <div class="form-group col-md-4{{ $errors->has('role_plan') ? ' has-error' : ''}}">
        {!! Form::label('role_plan', 'Role Plan', ['class' => 'control-label']) !!}
        {!! Form::select('role_plan', \App\RolePlans::where('role_id',$role_id)->get()->pluck('role_plan','id')->toArray(), isset($user_roles) ? $user_roles : [], ['class' => 'form-control', 'multiple' => false]) !!}
    </div>
<?php endif; ?>
<div class="form-group col-md-4">
    {!! Form::hidden('role_id', $role_id,['class' => 'form-control']) !!}
</div>
    </div>

    <div class="form-group col-md-4">
    {!! Form::submit($formMode === 'edit' ? 'Update' : 'Create', ['class' => 'btn btn-success']) !!}
</div>
<script>
    $("#other").hide();

    $('#type_select').on('click', function() {
        $('#other').toggle();
    });

</script>