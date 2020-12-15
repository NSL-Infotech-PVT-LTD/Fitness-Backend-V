<div class="form-group{{ $errors->has('about_us') ? 'has-error' : ''}}">
    {!! Form::label('about_us', 'About Us', ['class' => 'control-label']) !!}
    {!! Form::textarea('about_us', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('about_us', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('terms_and_conditions') ? 'has-error' : ''}}">
    {!! Form::label('terms_and_conditions', 'Terms And Conditions', ['class' => 'control-label']) !!}
    {!! Form::textarea('terms_and_conditions', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('terms_and_conditions', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('privacy_policy') ? 'has-error' : ''}}">
    {!! Form::label('privacy_policy', 'Private Policy', ['class' => 'control-label']) !!}
    {!! Form::textarea('privacy_policy', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('privacy_policy', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('admin_email') ? 'has-error' : ''}}">
    {!! Form::label('admin_email', 'Email', ['class' => 'control-label']) !!}
    {!! Form::email('admin_email', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('admin_email', '<p class="help-block">:message</p>') !!}
</div>


<div class="form-group">
    {!! Form::submit($formMode === 'edit' ? 'Update' : 'Create', ['class' => 'btn btn-primary']) !!}
</div>
