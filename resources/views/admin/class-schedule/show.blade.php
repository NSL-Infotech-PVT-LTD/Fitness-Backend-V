@extends('layouts.backend')

@section('content')
<?php // dd($user->role->name,$user->role->current_plan->toArray(),$user->role->current_plan->fee_type,$user->role->action_date)?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>View Details</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/home')}}">Home</a></li>
                        <li class="breadcrumb-item active">Details</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                    <div class="card-header">ClassSchedule {{ $classschedule->id }}</div>
                    <div class="card-body">

                        <a href="{{ url('/admin/class-schedule') }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                        <a href="{{ url('/admin/class-schedule/' . $classschedule->id . '/edit') }}" title="Edit ClassSchedule"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>
                        {!! Form::open([
                            'method'=>'DELETE',
                            'url' => ['admin/classschedule', $classschedule->id],
                            'style' => 'display:inline'
                        ]) !!}
                            {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> Delete', array(
                                    'type' => 'submit',
                                    'class' => 'btn btn-danger btn-sm',
                                    'title' => 'Delete ClassSchedule',
                                    'onclick'=>'return confirm("Confirm delete?")'
                            ))!!}
                        {!! Form::close() !!}
                        <br/>
                        <br/>

                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th>ID</th><td>{{ $classschedule->id }}</td>
                                    </tr>
                                    <tr><th> Class Type </th><td> {{ $classschedule->class_type }} </td></tr><tr><th> Start Date </th><td> {{ $classschedule->start_date }} </td></tr><tr><th> End Date </th><td> {{ $classschedule->end_date }} </td></tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

@endsection

