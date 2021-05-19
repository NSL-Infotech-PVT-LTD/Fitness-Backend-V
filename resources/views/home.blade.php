@extends('layouts.backend')

@section('content')




<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
          <div class="col-lg-12 col-12 dashboard_img">


<h2>Dashboard</h2>
<p>Welcome  Administrator</p>
</div>
</div>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
   
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row dashboard_box">
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box one_">
                        <div class="inner">
                            <h3><?= App\TrainerUser::where('status','1')->count() ?></h3>
                            <p>Approved Personal Trainer</p>
                        </div>
                        <div class="inner">
                            <h3><?= App\TrainerUser::count() ?></h3>
                            <p>Total Personal Trainer</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="{{ url('admin/trainer-user') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
<!--                <div class="col-lg-3 col-6">
                     small box 
                    <div class="small-box two_">
                        <div class="inner">
                            <h3>{{$customer = DB::table('role_user')->where('role_id', 3)->count()}}</h3>

                            <p>Customer Registrations</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="{{ url('admin/users/role/3') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>-->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box three_">
                        <div class="inner">
                            <h3>{{DB::table('users')->where('status','1')->count()}}</h3>

                            <p>Approved Members</p>
                        </div>
                        <div class="inner">
                            <h3>{{DB::table('users')->count()}}</h3>

                            <p>Total Members</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a href="{{ url('admin/users/role/1') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
               
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box four_">
                        <div class="inner">
                            <h3>{{DB::table('classes')->where('status','1')->count()}}</h3>
                            <p>Approved Classes</p>
                        </div>
                        <div class="inner">
                            <h3>{{$class = DB::table('classes')->count()}}</h3>

                            <p>Total Classes</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ url('admin/class') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                

                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box five_">
                        <div class="inner">
                            <h3>{{DB::table('events')->where('status','1')->count()}}</h3>
                            <p>Approved Events</p>
                        </div>
                        <div class="inner">
                            <h3>{{$events = DB::table('events')->count()}}</h3>
                            <p>Total Events</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ url('admin/events') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
            </div>

        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>


@endsection
