@extends('layouts.backend')

@section('content')
<?php // dd($user)?>
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
                            </tbody>
                                <tr>
			    <td>
				<?php if ($user->image != null) { ?>
				<img  class="imageEnlarge" id="imageresource" src="{{ $user->image}}"  width="50px;" title="Click to Enlarge">
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
			    <?php if (!empty($user->emergency_contact_no)) { ?>
    			    <tr><th> Emergency Contact No	  </th><td> {{ $user->emergency_contact_no	 }} </td></tr>
			    <?php } else { ?>
    			    <tr><th> Emergency Contact No	  </th><td> NA </td></tr>
			    <?php } ?>
			    <!--<tr><th>Emergency Contact No</th><td> {{ $user->emergency_contact_no }} </td></tr>-->
			    <tr><th>Email</th><td> {{ $user->email }} </td></tr>
			    <?php if (!empty($user->birth_date)) { ?>
    			    <tr><th> Birth Date	  </th><td> {{ $user->birth_date	 }} </td></tr>
			    <?php } else { ?>
    			    <tr><th> Birth Date	  </th><td> NA </td></tr>
			    <?php } ?>
			    <!--<tr><th>Birth Date</th><td> {{ $user->birth_date }} </td></tr>-->
			    <?php if (!empty($user->city)) { ?>
    			    <tr><th> City </th><td> {{ $user->city	 }} </td></tr>
			    <?php } else { ?>
    			    <tr><th> City </th><td> NA </td></tr>
			    <?php } ?>
			    <!--<tr><th>City</th><td> {{ $user->city }} </td></tr>-->
			    <?php if (!empty($user->marital_status)) { ?>
    			    <tr><th> Marital Status	  </th><td> {{ $user->marital_status	 }} </td></tr>
			    <?php } else { ?>
    			    <tr><th> Marital Status	  </th><td> NA </td></tr>
			    <?php } ?>
			    <!--<tr><th>Marital Status</th><td> {{ $user->marital_status }} </td></tr>-->
			    <?php if (!empty($user->designation)) { ?>
    			    <tr><th> Designation	  </th><td> {{ $user->designation	 }} </td></tr>
			    <?php } else { ?>
    			    <tr><th> Designation  </th><td> NA </td></tr>
			    <?php } ?>
			    <!--<tr><th>Designation</th><td> {{ $user->designation }} </td></tr>-->
			    <tr><th>Emirates Id</th><td> {{ $user->emirates_id }} </td></tr>
			    <tr><th>Emirates Image1</th> 
				<td>
				    <?php if (!empty($user->emirate_image1)) { ?>
    				    <img class="imageEnlarge"  src=" {{ $user->emirate_image1 }}" width="100"; ></td>
				<?php } else { ?>
    				<td>NA</td>
				<?php } ?>
			    </tr>
			    <tr><th>Emirates Image2</th> 
				<td>
				    <?php if (!empty($user->emirate_image2)) { ?>
    				    <img class="imageEnlarge" src=" {{ $user->emirate_image2 }}" width="100"; ></td>
				<?php } else { ?>
    				<td>NA</td>
				<?php } ?>
			    </tr>
			    <?php if (!empty($user->address)) { ?>
    			    <tr><th>Address  </th><td> {{ $user->address	 }} </td></tr>
			    <?php } else { ?>
    			    <tr><th> Address  </th><td> NA </td></tr>
			    <?php } ?>
			    <!--<tr><th>Address</th><td> {{ $user->address }} </td></tr>-->
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
			    <?php if (!empty($user->city)) { ?>
    			    <tr><th> City </th><td> {{ $user->city	 }} </td></tr>
			    <?php } else { ?>
    			    <tr><th> City </th><td> NA </td></tr>
			    <?php } ?>
<!--                                <tr><th>Nationality</th><td> {{ $user->nationality }} </td></tr>
			    <tr><th>Work Place</th><td> {{ $user->workplace }} </td></tr>
			    <tr><th>How did you hear about us</th><td> {{ $user->about_us }} </td></tr>-->
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

<!-- Creates the bootstrap modal where the image will appear -->
<div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="background-color:rgba(255,255,255,.8)";>
      <div class="modal-header" style="background-color:#337ab7";>
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <!--<h4 class="modal-title" id="myModalLabel">Image preview</h4>-->
      </div>
      <div class="modal-body">
        <img src="" id="imagepreview" style="width: 460px; height: 300px;" >
      </div>
<!--      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>-->
    </div>
  </div>
</div>

<script>
    $(function(){
    $(".imageEnlarge").on("click", function() {
   $('#imagepreview').attr('src', $(this).attr('src')); // here asign the image to the modal when the user click the enlarge link
   $('#imagemodal').modal('show'); // imagemodal is the id attribute assigned to the bootstrap modal, then i use the show function
});
});  
</script> 
@endsection
