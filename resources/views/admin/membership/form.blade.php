<?php 
//dd($membership);
?>
<div class="form-group{{ $errors->has('user_type') ? 'has-error' : ''}}">
    {!! Form::label('user_type', 'User Type', ['class' => 'control-label']) !!}
    {!! Form::select('user_type', ['gym'=>'Gym','pool'=>'Pool'], isset($membership->detail->user_type) ? $membership->detail->user_type : [], ['class' => 'form-control', 'multiple' => false]) !!}

    {!! $errors->first('user_type', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('category') ? 'has-error' : ''}}">
    {!! Form::label('category', 'Category', ['class' => 'control-label']) !!}
    {!! Form::select('category', ['single'=>'Single', 'couple'=>'Couple', 'family_with_1'=>'Family With One Child', 'family_with_2'=>'Family With Two Child'], isset($membership->detail->category) ? $membership->detail->category : [], ['class' => 'form-control', 'multiple' => false]) !!}
    {!! $errors->first('category', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('name') ? 'has-error' : ''}}">
    {!! Form::label('name', 'Name', ['class' => 'control-label']) !!}
    {!! Form::text('name', isset($membership->detail->name) ? $membership->detail->name : '', ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('description') ? 'has-error' : ''}}">
    {!! Form::label('description', 'Description', ['class' => 'control-label']) !!}
    {!! Form::textarea('description',  isset($membership->detail->description) ? $membership->detail->description : '', ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
</div>

<?php
if (isset($membership->detail->image))
    echo "<img width='100' src=" . url('uploads/membership/' . $membership->detail->image) . ">";
?>
<div class="form-group{{ $errors->has('image') ? 'has-error' : ''}}">
    {!! Form::label('image', 'Image', ['class' => 'control-label']) !!}
    {!! Form::file('image', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('image', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('monthly_fee') ? 'has-error' : ''}}">
    {!! Form::label('monthly_fee', 'Monthly Fee', ['class' => 'control-label']) !!}
    {!! Form::number('monthly_fee', isset($membership->fees['monthly_fee']) ? $membership->fees['monthly_fee'] : '', ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('monthly_fee', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('quaterly_fee') ? 'has-error' : ''}}">
    {!! Form::label('quaterly_fee', 'Quaterly Fee', ['class' => 'control-label']) !!}
    {!! Form::number('quaterly_fee', isset($membership->fees['quaterly_fee']) ? $membership->fees['quaterly_fee'] : '', ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('quaterly_fee', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('half_yearly_fee') ? 'has-error' : ''}}">
    {!! Form::label('half_yearly_fee', 'Half Yearly Fee', ['class' => 'control-label']) !!}
    {!! Form::number('half_yearly_fee', isset($membership->fees['half_yearly_fee']) ? $membership->fees['half_yearly_fee'] : '', ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('half_yearly_fee', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('yearly_fee') ? 'has-error' : ''}}">
    {!! Form::label('yearly_fee', 'Yearly Fee', ['class' => 'control-label']) !!}
    {!! Form::number('yearly_fee', isset($membership->fees['yearly_fee']) ? $membership->fees['yearly_fee'] : '', ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('yearly_fee', '<p class="help-block">:message</p>') !!}
</div>


<div class="form-group">
    {!! Form::submit($formMode === 'edit' ? 'Update' : 'Create', ['class' => 'btn btn-primary']) !!}
</div>
