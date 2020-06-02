@extends('layouts.backend')

@section('content')


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
                <div class="card-body">
                    <a href="{{ url(url()->previous()) }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                    <br/>
                    <br/>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>

                            </thead>
                            <tbody>
                                <tr><th>ID.</th><td>{{ $user->id }}</td></tr>
                                <tr><th>First Name</th><td> {{ $user->first_name }} </td></tr>
                                <tr><th>Middle Name</th><td> {{ $user->middle_name }} </td></tr>
                                <tr><th>Last Name</th><td> {{ $user->last_name }} </td></tr>
                                <tr><th>Child</th><td> {{ $user->child }} </td></tr>
                                <tr><th>Mobile</th><td> {{ $user->mobile }} </td></tr>
                                <tr><th>Emergency Contact No</th><td> {{ $user->emergency_contact_no }} </td></tr>
                                <tr><th>Email</th><td> {{ $user->email }} </td></tr>
                                <tr><th>Birth Date</th><td> {{ $user->birth_date }} </td></tr>
                                <tr><th>Marital Status</th><td> {{ $user->marital_status }} </td></tr>
                                <tr><th>Designation</th><td> {{ $user->designation }} </td></tr>
                                <tr><th>Emirates Id</th><td> {{ $user->emirates_id }} </td></tr>
                                <tr><th>Address</th><td> {{ $user->address }} </td></tr>
                                <tr>
                                    <th>Status</th>
                                    <?php if(!empty($user->status== '1')) {?>
                                    <td> Active </td>
                                     <?php } else { ?>
                                    <td> Inactive</td>
                                    <?php } ?>
                                </tr>
                                 
                                <tr><th>Created At</th><td> {{ $user->created_at }} </td></tr>
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
