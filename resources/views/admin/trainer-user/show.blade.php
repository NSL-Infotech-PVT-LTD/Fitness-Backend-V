@extends('layouts.backend')
@section('content')
<?php // dd($traineruser);?>
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
                            <tbody>
                                <tr>
                                    <th>ID</th><td>{{ $traineruser->id }}</td>
                                </tr>
                                <tr><th> First Name </th><td> {{ $traineruser->first_name }} </td></tr>
                                <tr><th> Middle Name </th><td> {{ $traineruser->middle_name }} </td></tr>
                                <tr><th> Last Name </th><td> {{ $traineruser->last_name }} </td></tr>
                                <tr><th> email </th><td> {{ $traineruser->email }} </td></tr>
				<?php if (!empty($traineruser->expirence)) { ?>
    				<tr><th> Experience	  </th><td> {{ $traineruser->expirence	 }} </td></tr>
				<?php } else { ?>
    				<tr><th> Experience	  </th><td> NA </td></tr>
				<?php } ?>
				<?php if (!empty($traineruser->certifications)) { ?>
    				<tr><th> Certifications	  </th><td> {{ $traineruser->certifications	 }} </td></tr>
				<?php } else { ?>
    				<tr><th> Certifications	  </th><td> NA </td></tr>
				<?php } ?>
				<?php if (!empty($traineruser->specialities)) { ?>
    				<tr><th> Specialty	  </th><td> {{ $traineruser->specialities	 }} </td></tr>
				<?php } else { ?>
    				<tr><th> Specialty	  </th><td> NA </td></tr>
				<?php } ?>
                                <tr><th> Birth Date	  </th><td> {{ $traineruser->birth_date	 }} </td></tr>
				<?php if (!empty($traineruser->about)) { ?>
    				<tr><th> About	  </th><td> {{ $traineruser->about	 }} </td></tr>
				<?php } else { ?>
    				<tr><th> About	  </th><td> NA </td></tr>
				<?php } ?>
				<?php if (!empty($traineruser->address_house)) { ?>

    				<tr><th> Address	  </th><td> {{ $traineruser->address_house	 }} </td></tr>
				<?php } else { ?>
    				<tr><th> Address	  </th><td> NA </td></tr>
				<?php } ?>

                                <tr><th> Emergency Contact No </th><td> {{ $traineruser->emergency_contact_no }} </td></tr>
				<?php if ($traineruser->image == ''): ?>
    				<tr><th>Image</th><td>NAN</td></tr>
				<?php else: ?>
    				<tr><th>Image</th><td><img width="150" src="{{url('uploads/trainer-user/'.$traineruser->image)}}"> </td></tr>
				<?php endif; ?>
				<tr><th>Emirates Image1</th> 
				    <td>
					<?php if (!empty($traineruser->emirate_image1)) { ?>
    					<img src=" {{ $traineruser->emirate_image1 }}" width="100"; ></td>
				    <?php } else { ?>
    				    <td>NA</td>
				    <?php } ?>
				</tr>
				<tr><th>Emirates Image2</th> 
				    <td>
					<?php if (!empty($traineruser->emirate_image2)) { ?>
    					<img src=" {{ $traineruser->emirate_image2 }}" width="100"; ></td>
				    <?php } else { ?>
    				    <td>NA</td>
				    <?php } ?>
				</tr>

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