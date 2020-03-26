@extends('layouts.backend')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">Tournament {{ $tournament->id }}</div>
                    <div class="card-body">

                        <a href="{{ url('/admin/tournament') }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                        <a href="{{ url('/admin/tournament/' . $tournament->id . '/edit') }}" title="Edit Tournament"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>
                        {!! Form::open([
                            'method'=>'DELETE',
                            'url' => ['admin/tournament', $tournament->id],
                            'style' => 'display:inline'
                        ]) !!}
                            {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> Delete', array(
                                    'type' => 'submit',
                                    'class' => 'btn btn-danger btn-sm',
                                    'title' => 'Delete Tournament',
                                    'onclick'=>'return confirm("Confirm delete?")'
                            ))!!}
                        {!! Form::close() !!}
                        <br/>
                        <br/>

                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr><th>ID</th><td>{{ $tournament->id }}</td></tr>
                                    <tr><th> Name </th><td> {{ $tournament->name }} </td></tr>
                                    <tr><th> Image </th><td><img width="150" src="{{url('uploads/tournament/'.$tournament->image)}}"></td></tr>
                                    <tr><th> Price </th><td> ${{ $tournament->price }} </td></tr>
                                    <tr><th> Description </th><td> {{ $tournament->description }} </td></tr>
                                    <tr><th> Start Date </th><td> {{ $tournament->start_date }} </td></tr>
                                    <tr><th> End Date </th><td> {{ $tournament->end_date }} </td></tr>
                                    <tr><th> Rules </th><td> {{ $tournament->rules }} </td></tr>
                                    <tr><th> Privacy Policy </th><td> {{ $tournament->privacy_policy }} </td></tr>
                                    
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
