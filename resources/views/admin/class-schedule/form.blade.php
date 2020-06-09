<div class="form-group{{ $errors->has('class_type') ? 'has-error' : ''}}">
    {!! Form::label('class_type', 'Class Type', ['class' => 'control-label']) !!}
    {!! Form::text('class_type', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('class_type', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('start_date') ? 'has-error' : ''}}">
    {!! Form::label('start_date', 'Start Date', ['class' => 'control-label']) !!}
    {!! Form::date('start_date', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('start_date', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('end_date') ? 'has-error' : ''}}">
    {!! Form::label('end_date', 'End Date', ['class' => 'control-label']) !!}
    {!! Form::date('end_date', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('end_date', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('repeat_on') ? 'has-error' : ''}}">
    {!! Form::label('repeat_on', 'Repeat On', ['class' => 'control-label']) !!}
    {!! Form::textarea('repeat_on', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('repeat_on', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('start_time') ? 'has-error' : ''}}">
    {!! Form::label('start_time', 'Start Time', ['class' => 'control-label']) !!}
    {!! Form::input('time', 'start_time', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('start_time', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('duration') ? 'has-error' : ''}}">
    {!! Form::label('duration', 'Duration', ['class' => 'control-label']) !!}
    {!! Form::text('duration', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('duration', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('class_id') ? 'has-error' : ''}}">
    {!! Form::label('class_id', 'Class Id', ['class' => 'control-label']) !!}
    {!! Form::text('class_id', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('class_id', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('trainer_id') ? 'has-error' : ''}}">
    {!! Form::label('trainer_id', 'Trainer Id', ['class' => 'control-label']) !!}
    {!! Form::text('trainer_id', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('trainer_id', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('cp_spots') ? 'has-error' : ''}}">
    {!! Form::label('cp_spots', 'Cp Spots', ['class' => 'control-label']) !!}
    {!! Form::text('cp_spots', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('cp_spots', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('capacity') ? 'has-error' : ''}}">
    {!! Form::label('capacity', 'Capacity', ['class' => 'control-label']) !!}
    {!! Form::text('capacity', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('capacity', '<p class="help-block">:message</p>') !!}
</div>


<div class="form-group">
    {!! Form::submit($formMode === 'edit' ? 'Update' : 'Create', ['class' => 'btn btn-primary']) !!}
</div>
