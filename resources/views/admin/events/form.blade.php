<div class="form-group{{ $errors->has('name') ? 'has-error' : ''}}">
    {!! Form::label('name', 'Name', ['class' => 'control-label']) !!}
    {!! Form::text('name', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
</div>

<?php
if (isset($events->image))
    echo "<img width='100' src=" . url('uploads/events/' . $events->image) . ">";
?>
<div class="form-group{{ $errors->has('image') ? 'has-error' : ''}}">
    {!! Form::label('image', 'Image', ['class' => 'control-label']) !!}
    {!! Form::file('image', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('image', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('description') ? 'has-error' : ''}}">
    {!! Form::label('description', 'Description', ['class' => 'control-label']) !!}
    {!! Form::textarea('description', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('start_date') ? ' has-error' : ''}}">
    {!! Form::label('start_date', 'Start Date: ', ['class' => 'control-label']) !!}
    {!! Form::date('start_date', null, ['class' => 'form-control', 'required' => 'required']) !!}
    {!! $errors->first('start_date', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('end_date') ? ' has-error' : ''}}">
    {!! Form::label('end_date', 'End Date: ', ['class' => 'control-label']) !!}
    {!! Form::date('end_date', null, ['class' => 'form-control', 'required' => 'required']) !!}
    {!! $errors->first('end_date', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group{{ $errors->has('location_id') ? 'has-error' : ''}}">
    {!! Form::label('location_id', 'Location ', ['class' => 'control-label']) !!}
    {!! Form::select('location_id', \App\Location::where('status','1')->get()->pluck('name','id'), isset($event->location_id) ? $event->location_id : '', ['class' => 'form-control', 'multiple' => false]) !!}
    {!! $errors->first('location_id', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group">
    {!! Form::submit($formMode === 'edit' ? 'Update' : 'Create', ['class' => 'btn btn-primary']) !!}
</div>
