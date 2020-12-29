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
                <div class="card-body">
                    <a href="{{ url(url()->previous()) }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                    <br/>
                    <br/>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <?php if ($user->image != null) { ?>
                                            <img width="100" src="{{ url('uploads/image/'.$user->image)}}">
                                        <?php } else { ?>
                                            <div class='country-img'>NA</div>
                                        <?php } ?>

                                    </td>
                                </tr>
                                <tr><th>Membership ID</th><td>VFM{{ $user->id }}</td></tr>
                                <tr><th>First Name</th><td> {{ $user->first_name }} </td></tr>
                                <tr><th>Middle Name</th><td> {{ $user->middle_name }} </td></tr>
                                <tr><th>Last Name</th><td> {{ $user->last_name }} </td></tr>
                                <!--<tr><th>Child</th><td> {{ $user->child }} </td></tr>-->
                                <tr><th>Mobile</th><td> {{ $user->mobile }} </td></tr>
                                <tr><th>Emergency Contact No</th><td> {{ $user->emergency_contact_no }} </td></tr>
                                <tr><th>Email</th><td> {{ $user->email }} </td></tr>
                                <tr><th>Birth Date</th><td> {{ $user->birth_date }} </td></tr>
                                <tr><th>City</th><td> {{ $user->city }} </td></tr>
                                <tr><th>Marital Status</th><td> {{ $user->marital_status }} </td></tr>
                                <tr><th>Designation</th><td> {{ $user->designation }} </td></tr>
                                <tr><th>Emirates Id</th><td> {{ $user->emirates_id }} </td></tr>
                                <tr><th>Address</th><td> {{ $user->address }} </td></tr>
                                <tr><th>Approved Action Date</th><td>{{$user->roles['0']['created_at'] }} </td></tr>
                                <?php
//                                dd($user->role->current_plan->fee_type);
                                $subscription_endDate = new Carbon\Carbon($user->roles['0']['action_date']);
                                switch ($user->roles['0']['current_plan']['fee_type']):
                                    case'Monthly':
                                        $subscription_endDate = $subscription_endDate->addMonth();
//                                        dd('ss');
                                        break;
                                    case'Quarterly':
                                        $subscription_endDate = $subscription_endDate->addMonths(3);
                                        break;
                                    case'Half yearly':
                                        $subscription_endDate = $subscription_endDate->addMonths(6);
                                        break;
                                    case'Yearly':
                                        $subscription_endDate = $subscription_endDate->addMonths(12);
                                        break;
                                endswitch;
//                                dd($subscription_endDate);
                                $subscription_end = new Carbon\Carbon($subscription_endDate);

//                                $left = $subscription_end->subDays(Carbon\Carbon::now());
//                                echo $subscription_end->diffForHumans();
                                ?>
                                <tr><th>Subscription End Date</th><td> {{ $subscription_end->diffForHumans() }} </td></tr>
                                <tr><th>Subscription</th><td> {{ $user->roles['0']['name'].' | '.$user->roles['0']['current_plan']['role_plan'] }} </td></tr>
                                <tr>
                                    <th>Status</th>
                                    <?php if (!empty($user->status == '1')) { ?>
                                        <td> Active </td>
                                    <?php } else { ?>
                                        <td> Inactive</td>
                                    <?php } ?>
                                </tr>
                                <tr><th>Role Plan</th><td> {{ $user->roles['0']->name }}  </td></tr>
                                <tr><th>Created At</th><td> {{ $user->created_at }} </td></tr>
                                <tr><th>Nationality</th><td> {{ $user->nationality }} </td></tr>
                                <tr><th>Work Place</th><td> {{ $user->workplace }} </td></tr>
                                <tr><th>How did you hear about us</th><td> {{ $user->about_us }} </td></tr>
                                <tr><th></th><td> </td></tr>
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
