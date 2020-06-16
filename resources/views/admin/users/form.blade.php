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
<div class="form-group{{ $errors->has('child') ? ' has-error' : ''}}">
    {!! Form::label('child', 'Child: ', ['class' => 'control-label']) !!}
    {!! Form::radio('child','1_child' ,['class' => 'form-control', 'required' => 'required']) !!} One Child
    {!! Form::radio('child','2_child' ,['class' => 'form-control', 'required' => 'required']) !!} Two Children
    {!! Form::radio('child','none' ,['class' => 'form-control', 'required' => 'required','checked' => false]) !!} None
    {!! $errors->first('child', '<p class="help-block">:message</p>') !!}
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

<?php
//dd($formMode);
if ($formMode == 'create'):
    ?>
    <div class="form-group{{ $errors->has('email') ? ' has-error' : ''}}">
        {!! Form::label('email', 'Email: ', ['class' => 'control-label']) !!}
        {!! Form::email('email', '', ['class' => 'form-control', 'required' => 'required']) !!}
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
<?php endif; ?>
<div class="form-group{{ $errors->has('birth_date') ? ' has-error' : ''}}">
    {!! Form::label('birth_date', 'Birth Date: ', ['class' => 'control-label']) !!}
    {!! Form::date('birth_date', null, ['class' => 'form-control', 'required' => 'required']) !!}
    {!! $errors->first('birth_date', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('marital_status') ? ' has-error' : ''}}">
    {!! Form::label('marital_status', 'Marital Status: ', ['class' => 'control-label']) !!}
    {!! Form::radio('marital_status','single' ,['class' => 'form-control', 'required' => 'required']) !!} Single
    {!! Form::radio('marital_status','married' ,['class' => 'form-control', 'required' => 'required']) !!} Married
    {!! $errors->first('marital_status', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('designation') ? ' has-error' : ''}}">
    {!! Form::label('designation', 'Designation: ', ['class' => 'control-label']) !!}
    {!! Form::text('designation', null, ['class' => 'form-control', 'required' => 'required']) !!}
    {!! $errors->first('designation', '<p class="help-block">:message</p>') !!}
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
<?php if ($formMode == 'create'): ?>
    <div class="form-group{{ $errors->has('role_plan') ? ' has-error' : ''}}">
        {!! Form::label('role_plan', :', ['class' => 'control-label']) !!}
        {!! Form::select('role_plan', \App\RolePlans::where('role_id',$role_id)->get()->pluck('role_plan','id')->toArray(), isset($user_roles) ? $user_roles : [], ['class' => 'form-control', 'multiple' => false]) !!}
    </div>
<?php endif; ?>
<div class="form-group">
    {!! Form::hidden('role_id', $role_id,['class' => 'form-control']) !!}
</div>
<div class="form-group">
    {!! Form::submit($formMode === 'edit' ? 'Update' : 'Create', ['class' => 'btn btn-primary']) !!}
</div>
