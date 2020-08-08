<div class="form-group{{ $errors->has('model_type') ? 'has-error' : ''}}">
    {!! Form::label('model_type', 'Model Type', ['class' => 'control-label']) !!}
    {!! Form::text('model_type', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('model_type', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('model_id') ? 'has-error' : ''}}">
    {!! Form::label('model_id', 'Model Id', ['class' => 'control-label']) !!}
    {!! Form::text('model_id', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('model_id', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('payment_status') ? 'has-error' : ''}}">
    {!! Form::label('payment_status', 'Payment Status', ['class' => 'control-label']) !!}
    {!! Form::text('payment_status', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('payment_status', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('payment_params') ? 'has-error' : ''}}">
    {!! Form::label('payment_params', 'Payment Params', ['class' => 'control-label']) !!}
    {!! Form::textarea('payment_params', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('payment_params', '<p class="help-block">:message</p>') !!}
</div>


<div class="form-group">
    {!! Form::submit($formMode === 'edit' ? 'Update' : 'Create', ['class' => 'btn btn-primary']) !!}
</div>
