<style>
    /* Style the tab */
    .tab {
        overflow: hidden;
        border: 1px solid #ccc;
        background-color: #f1f1f1;
        margin-bottom: 20px;
    }

    /* Style the buttons inside the tab */
    a.tablinks{

        cursor: pointer;
        padding: 11px 20px;
        transition: 0.3s;
        font-size: 14px;
        background: #020202;
        color: white;
        font-weight: bold;
        display: inline-block;
        letter-spacing: 1px;
        text-transform: uppercase
    }
    .tab  a.active{
        cursor: pointer;
        padding: 11px 20px;
        transition: 0.3s;
        font-size: 14px;
        background: #ffc107;
        color: black;
        font-weight: bold;
        display: inline-block;
        letter-spacing: 1px;
        text-transform: uppercase
    }

    /* Change background color of buttons on hover */
    .tab button:hover {
        background-color: #ddd;
    }

    /* Create an active/current tablink class */
    .tab button.active {
        background-color: #ccc;
    }

    /* Style the tab content */
    .tabcontent {
        display: none;
        padding: 10px 12px;
        -webkit-animation: fadeEffect 1s;
        animation: fadeEffect 1s;
        border: 1px solid #a99f9f;
        margin-bottom: 13px;
        border-bottom: 1px solid #000;
    }

    /* Fade in tabs */
    @-webkit-keyframes fadeEffect {
        from {opacity: 0;}
        to {opacity: 1;}
    }

    @keyframes fadeEffect {
        from {opacity: 0;}
        to {opacity: 1;}
    }
    .tabcontent h3 {
        font-size: 15px;
        margin-bottom: 9px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .tabcontent input {
        height: 40px;
        text-indent: 10px;
        width: 29%;
        border: 1px solid #b1aaaa;
        box-shadow: 1px 0px 6px #9e9898;
        font-size: 14px;
        font-weight: bold
    }
</style>
<?php // dd($role->plans);?>
<div class="form-group{{ $errors->has('name') ? ' has-error' : ''}}">
    {!! Form::label('name', 'Name: ', ['class' => 'control-label']) !!}
    {!! Form::select('name', ['' => 'Select Role', 'Gym Members' => 'Gym Members', 'Pool and Beach members' => 'Pool and Beach members', 'Local Guest' => 'Local Guest', 'Fairmont Hotel Guest' => 'Fairmont Hotel Guest'], null, ['class' => 'form-control']) !!}
    {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('label') ? ' has-error' : ''}}">
    {!! Form::label('label', 'Label: ', ['class' => 'control-label']) !!}
    {!! Form::text('label', null, ['class' => 'form-control']) !!}
    {!! $errors->first('label', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('member') ? ' has-error' : ''}}">
    {!! Form::label('member', 'Member: ', ['class' => 'control-label']) !!}
    {!! Form::text('member', null, ['class' => 'form-control']) !!}
    {!! $errors->first('member', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('type') ? 'has-error' : ''}}">
    {!! Form::label('type', 'Type', ['class' => 'control-label']) !!}
    {!! Form::select('type', ['user'=>'Users','user_with_child'=>'Users With Child', 'guest'=>'Guest'], isset($role->type) ? $role->type : [], ['class' => 'form-control', 'multiple' => false]) !!}
    {!! $errors->first('type', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('category') ? 'has-error' : ''}}">
    {!! Form::label('category', 'Category', ['class' => 'control-label']) !!}
    {!! Form::select('category', ['single'=>'Single', 'couple'=>'Couple', 'family_with_1'=>'Family With One Child', 'family_with_2'=>'Family With Two Child', 'family_with_3'=>'Family With three Child', 'family_with_4'=>'Family With four Child', 'family_with_5'=>'Family With five Child', 'family_with_6'=>'Family With six Child', 'family_with_7'=>'Family With seven Child', 'family_with_8'=>'Family With eight Child', 'family_with_9'=>'Family With nine Child', 'family_with_10'=>'Family With ten Child'], isset($role->category) ? $role->category : [], ['class' => 'form-control', 'multiple' => false]) !!}
    {!! $errors->first('category', '<p class="help-block">:message</p>') !!}
</div>
<?php
if (isset($role->image))
    echo "<img width='100' src=" . url('uploads/roles/' . $role->image) . ">";
?>
<div class="form-group{{ $errors->has('image') ? 'has-error' : ''}}">
    {!! Form::label('image', 'Image (360 X 450)', ['class' => 'control-label','style'=>"width: 100%"]) !!}
    {!! Form::file('image', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('image', '<p class="help-block">:message</p>') !!}
</div>

<?php
$monthly = isset($role->plans['monthly']['fee']) ? $role->plans['monthly']['fee'] : '';
$quarterly = isset($role->plans['quarterly']['fee']) ? $role->plans['quarterly']['fee'] : '';
$half_yearly = isset($role->plans['half_yearly']['fee']) ? $role->plans['half_yearly']['fee'] : '';
$yearly = isset($role->plans['yearly']['fee']) ? $role->plans['yearly']['fee'] : '';
?>
<div class="prices">
    <div class="tab">
        <a class="tablinks <?= $monthly != '' ? "active" : '' ?>" onclick="price(event, 'Monthly')">Monthly </a>
        <a class="tablinks <?= $quarterly != '' ? "active" : '' ?>" onclick="price(event, 'Quarterly')">Quarterly</a>
        <a class="tablinks <?= $half_yearly != '' ? "active" : '' ?>" onclick="price(event, 'Half')">Half Yearly</a>
        <a class="tablinks <?= $yearly != '' ? "active" : '' ?>" onclick="price(event, 'Yearly')">Yearly</a>
    </div>
    <div id="Monthly" class="tabcontent " <?= $monthly != '' ? "style='display:block'" : '' ?> >
        <h3>Monthly </h3>
        <input type="text" id="Monthly" placeholder="Enter Your Amount" name="monthly"  value="<?= $monthly != '' ? $monthly : '' ?>">

    </div>
    <div id="Quarterly" class="tabcontent"<?= $quarterly != '' ? "style='display:block'" : '' ?> >
        <h3>Quarterly</h3>
        <input type="text" id="quarterly" placeholder="Enter Your Amount" name="quarterly" value="<?= $quarterly != '' ? $quarterly : '' ?>">        
    </div>

    <div id="Half" class="tabcontent second" <?= $half_yearly != '' ? "style='display:block'" : '' ?> >
        <h3>Half Yearly</h3>
        <input type="text" id="haff" placeholder="Enter Your Amount" name="half_yearly"  value="<?= $half_yearly != '' ? $half_yearly : '' ?>"> 

    </div>

    <div id="Yearly" class="tabcontent" <?= $yearly != '' ? "style='display:block'" : '' ?> >
        <h3>Yearly</h3>
        <input type="text" id="yearly" placeholder="Enter Your Amount" name="yearly"  value="<?= $yearly != '' ? $yearly : '' ?>">

    </div>
</div>

<div class="form-group{{ $errors->has('label') ? ' has-error' : ''}}">
    {!! Form::label('label', 'Permissions: ', ['class' => 'control-label']) !!}
    {!! Form::select('permissions[]', $permissions, isset($role) ? $role->permissions->pluck('name') : [], ['class' => 'form-control', 'multiple' => true]) !!}
    {!! $errors->first('label', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group">
    {!! Form::submit($formMode === 'edit' ? 'Update' : 'Create', ['class' => 'btn btn-primary']) !!}
</div>
