@extends('layouts.backend')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">Enrollments {{ $enrollment->id }}</div>
                    <div class="card-body">

                        <a href="{{ url('/admin/enrollments') }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
<!--                        <a href="{{ url('/admin/enrollments/' . $enrollment->id . '/edit') }}" title="Edit Enrollment"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>-->
                        {!! Form::open([
                            'method'=>'DELETE',
                            'url' => ['admin/enrollments', $enrollment->id],
                            'style' => 'display:inline'
                        ]) !!}
                            {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> Delete', array(
                                    'type' => 'submit',
                                    'class' => 'btn btn-danger btn-sm',
                                    'title' => 'Delete Enrollment',
                                    'onclick'=>'return confirm("Confirm delete?")'
                            ))!!}
                        {!! Form::close() !!}
                        <br/>
                        <br/>

                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr><th>ID</th><td>{{ $enrollment->id }}</td></tr>
                                    <tr><th> Type </th><td> {{ $enrollment->type }} </td></tr>
                                    <tr><th> Size </th><td> {{ $enrollment->size }} </td></tr>
                                    <tr><th> Tournament Id </th><td> {{ $enrollment->tournament_id }} </td></tr>
                                    <tr><th> Customer Id </th><td> {{ $enrollment->customer_id }} </td></tr>
                                    <tr><th>Winning Status</th><td> {{ $enrollment->winning_status }} </td></tr>
                                
                                    
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
