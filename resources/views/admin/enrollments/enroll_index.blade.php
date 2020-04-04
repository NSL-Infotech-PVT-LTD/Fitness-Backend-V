@extends('layouts.backend')

@section('content')
<div class="container">
    <div class="row">
        @include('admin.sidebar')

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Enrollment</div>
                <div class="card-body">
                    <!--                        <a href="{{ url('/admin/enrollments/create') }}" class="btn btn-success btn-sm" title="Add New Enrollment">
                                                <i class="fa fa-plus" aria-hidden="true"></i> Add New
                                            </a>
                    
                                            {!! Form::open(['method' => 'GET', 'url' => '/admin/tournament', 'class' => 'form-inline my-2 my-lg-0 float-right', 'role' => 'search'])  !!}-->
                    <br/>
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <thead>
                                <tr>
                                    <th>Id</th><th>Type</th><th>Price</th><th>Size</th><th>Images</th><th>Payment Id</th><th>customer Name</th><th>Winning Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($enrollment as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->type }}</td>
                                    <td>${{ $item->price }}</td>
                                    <td>{{ $item->size }}</td>
                                    <td>
                                    @foreach($item->allImages as $img)
                                    <img width="30" src="{{url('uploads/images/'.$img->images)}}"><br>
                            @endforeach
                            </td>
                            <td>{{ $item->payment_id }}</td>
                            <td>{{ $item->userdetails->name }}</td>
                            @if($item->status == 1)
                            <td><button class="btn btn-success coupan" data-status="winner" data-id="{{$item->id}}">Winner</button></td>
                            @elseif($item->status == 0)
                            <td><button class="btn btn-danger coupan" data-status="make_winner" data-id="{{$item->id}}">Make winner</button></td>
                            @endif
                           
                            </tr>
                            @endforeach
                            </tbody>
                        </table>


                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function () {
            $('.coupan').click(function () {
//            var status = $(this).attr('data-id');

                var form_data = new FormData();
                form_data.append("status", $(this).attr('data-id'));
                form_data.append("value", $(this).attr('data-status'));
                form_data.append("_token", $('meta[name="csrf-token"]').attr('content'));
                $.ajax({
                    url: "{{route('enrollment.winnerstatus')}}",
                    method: "POST",
                    data: form_data,
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: "json",
//                beforeSend: function () {
//                        Swal.showLoading();
//                },
                    success: function (data)
                    {
                        if (data.success == true) {
                            Swal.hideLoading();
                            location.reload();
                        }
                    }
                });

            });
        });
    </script>




    @endsection
