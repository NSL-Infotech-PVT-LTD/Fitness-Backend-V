<div class="row">
    <div class="col-md-7">

        <div class="form-group{{ $errors->has('class_type') ? 'has-error' : ''}}">
            {!! Form::radio('class_type', 'recurring', ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
            {!! Form::label('class_type', 'Recurring', ['class' => 'control-label']) !!}
            {!! Form::radio('class_type', 'one-time', ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
            {!! Form::label('class_type', 'One Time', ['class' => 'control-label']) !!}
            {!! $errors->first('class_type', '<p class="help-block">:message</p>') !!}

        </div>
        <div class="row">
            <div class="col-md-6 col-sm-6">
                <div class="form-group{{ $errors->has('start_date') ? 'has-error' : ''}}">
                    {!! Form::label('start_date', 'Start Date', ['class' => 'control-label']) !!}
                    {!! Form::date('start_date', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
                    {!! $errors->first('start_date', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="col-md-6 col-sm-6">
                <div class="form-group{{ $errors->has('end_date') ? 'has-error' : ''}}">
                    {!! Form::label('end_date', 'End Date', ['class' => 'control-label']) !!}
                    {!! Form::date('end_date', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('end_date', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
        </div>
        <div class="col-md-12 col-sm-12">
            <div class="form-group" style="overflow: hidden;">
                <div id="checkboxes">
                    <?php foreach (['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $day): ?>
                        <div class="checkbox">
                            <input type="checkbox" id="{{$day}}}" name="repeat_on[]" value="{{$day}}" <?= isset($classschedule->repeat_on)?(in_array($day, $classschedule->repeat_on) ? 'checked=""' : ''):'' ?>>
                            <label for="{{$day}}"></label>
                            <span>{{$day}}</span>
                        </div>
                    <?php endforeach; ?>

                </div>
            </div>
        </div>

        <div class="row" style="width:100%;">
            <div class="col-md-6 col-sm-6">
                <div class="form-group{{ $errors->has('start_time') ? 'has-error' : ''}}">
                    {!! Form::label('start_time', 'Start Time', ['class' => 'control-label']) !!}
                    {!! Form::input('time', 'start_time', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
                    {!! $errors->first('start_time', '<p class="help-block">:message</p>') !!}
                </div>

            </div>

            <div class="col-md-6 col-sm-6">
                <div class="form-group{{ $errors->has('duration') ? 'has-error' : ''}}">
                    {!! Form::label('duration', 'Duration', ['class' => 'control-label']) !!}
                    {!! Form::text('duration', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
                    {!! $errors->first('duration', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
        </div>
    </div>


    <div class="col-md-5 col-sm-5">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group{{ $errors->has('class_id') ? 'has-error' : ''}}">
                    {!! Form::label('class_id', 'Class Id', ['class' => 'control-label']) !!}
                    {!! Form::select('class_id', \App\Classes::where('status','1')->get()->pluck('name','id'), isset($classschedule->class_id) ? $classschedule->class_id : '', ['class' => 'form-control', 'multiple' => false]) !!}
                    {!! $errors->first('class_id', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group{{ $errors->has('trainer_id') ? 'has-error' : ''}}">
                    {!! Form::label('trainer_id', 'Trainer Id', ['class' => 'control-label']) !!}
                    {!! Form::select('trainer_id', \App\User::whereIn('id',\DB::table('role_user')->where('role_id', '1')->pluck('user_id'))->get()->pluck('first_name','id'), isset($classschedule->trainer_id) ? $classschedule->trainer_id : '', ['class' => 'form-control', 'multiple' => false]) !!}
                    {!! $errors->first('trainer_id', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group{{ $errors->has('cp_spots') ? 'has-error' : ''}}">
                    {!! Form::label('cp_spots', 'Cp Spots', ['class' => 'control-label']) !!}
                    {!! Form::text('cp_spots', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('cp_spots', '<p class="help-block">:message</p>') !!}

                </div>

            </div>
            <div class="col-md-6">
                <div class="form-group{{ $errors->has('capacity') ? 'has-error' : ''}}">
                    {!! Form::label('capacity', 'Capacity', ['class' => 'control-label']) !!}
                    {!! Form::text('capacity', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('capacity', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::submit($formMode === 'edit' ? 'Update' : 'Create', ['class' => 'btn btn-primary']) !!}
            </div>
        </div>
    </div>

</div>




