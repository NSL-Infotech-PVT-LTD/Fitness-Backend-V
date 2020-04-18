@extends('layouts.backend')



<style>
    #gallery-lightbox-container {
        display: flex;
        position: fixed;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        align-items: center;
        justify-content: center;
        background-repeat: no-repeat;
        background-color: rgba(20, 10, 30, 0.88);
        overflow: auto;
    }

    #gallery-lightbox {
        position: relative;
        display: flex;
        justify-content: center;
        vertical-align: middle;
        align-items: center;
        flex-direction: column;
        /* margin: 120px auto 0 auto; */
        width: 80%;
        height: 700px;
    }

    #lightbox-image-container {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: center;
        margin: 100px 0 0 0;
    }
    /* less top margin on small devices */
    @media screen and (max-width: 600px) {
        #lightbox-image-container {
            margin: 40px 0 0 0;
        }
    }

    #lightbox-image {
        width: 90vw;
        max-width: 700px;
        height: auto;
        margin: 15px;
        max-height: 500px;
        border-radius: 10px;
        box-shadow: 0 6px 20px 0 rgba(00, 00, 00, 0.6);
    }

    #lightbox-text {
        font-size: 1.2em;
        text-align: center;
        margin: 15px;
        width: 90vw;
        max-width: 400px;
        height: auto;
        color: #efd5d5;
    }

    #exit-lightbox {
        font-size: 2em;
        text-align: center;
        margin: 0 auto;
        line-height: 1;
        border: 1px solid #efd5d5;
        border-radius: 8px;
        padding: 8px 16px;
        width: 50px;
        color: #efd5d5;
        cursor: pointer;
    }
</style>
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

                                        <div id="gallery-lightbox-container">
                                            <div id="gallery-lightbox">
                                                <div id="lightbox-image-container">
                                                    <img  id="lightbox-image"  onclick= "openLightbox();" width="10" src="{{url('uploads/images/'.$img->images)}}"  style="margin-top:10px;">
                                                    <div>
                                                        <p id="lightbox-text">**image description will be placed here by jQuery function**</p>
                                                        <p id="exit-lightbox">&#x2716;</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
<!--                                        <img  id="lightbox-image"  onclick= "openLightbox();" width="10" src="{{url('uploads/images/'.$img->images)}}"  style="margin-top:10px;"><br>-->
                                       
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
        $('#gallery-lightbox-container').hide();

        function openLightbox() {
            $('#lightbox-text').html('<p>Description of Item</p>');
            $('#lightbox-image').attr('src', 'image.jpg');
            $('#gallery-lightbox-container').slideDown(200);
        }
    });
</script>




@endsection
