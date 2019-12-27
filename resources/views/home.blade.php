@extends('layouts.backend')

@section('content')
<style type="text/css">
    .gig {
        float: left;
        margin-top: 5%;
    }
    .gig .content-top-1 {
        border-bottom: 2px solid red !important;
        /*border-right: 4px solid red;*/
    }
</style>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">

                        {{ session('status') }}
                    </div>
                    @endif

                    <div class="col-md-4 gig">
                        <div class="content-top-1">
                            <div class="col-md-12 top-content">
                           
                                <h5>Customer</h5>
                               
                                <label><?php echo $customer ?></label>
                            </div>
                            <div class="clearfix"> </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 gig">
                        <div class="content-top-1">
                            <div class="col-md-12 top-content">
                                <h5>Service-Provider</h5>
                                <label><?php echo $service_provider ?></label>
                            </div>
                            <div class="clearfix"> </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 gig">
                        <div class="content-top-1">
                            <div class="col-md-12 top-content">
                                <h5>Events</h5>
                                <label>2</label>
                            </div>
                            <div class="clearfix"> </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
