@extends('layouts.backend')

@section('content')
<style>
    .main-overview {
        height: 99px;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(115px, 1fr)); /* Where the magic happens */
        grid-auto-rows: 24px;
        grid-gap: 10px;
        margin: 10px;
    }

    .overviewcard {
        height: 99px;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px;
        background-color: #b52e31;
        text-decoration: none;
    }
    .overviewcard__icon {
        font-size: 20px;
        text-decoration: none;
    }
    .overviewcard__info {
        font-size: 35px;
        text-decoration: none;
    }
    .overviewcard:hover {
        background-color: #2b303a;
    }
    hr.new4 {
  border: 1px solid red;
}

</style>

<div class="container">
    <h3>Users</h3>
    <div class="row">
        
        <?php foreach ($users as $name => $user): ?>
            <div class="col-sm-4">
                <a href="{{url('admin/users/role/'.$user['role_id'])}}">
                    <div class="main-overview active">
                        <div class="overviewcard">
                            <div class="overviewcard__icon">Total {{ucfirst($name)}}&nbsp;&nbsp;</div>
                            <div class="overviewcard__info">
                                {{$user['count']}}
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div> 
<hr class="new4">
<div class="container">
    <h3>Tournament</h3>
    <div class="row">
        <div class="col-sm-4">
            <a href="{{url('admin/tournaments')}}">
                <div class="main-overview active">
                    <div class="overviewcard">
                        <div class="overviewcard__icon">Total Tournaments&nbsp;&nbsp;</div>
                        <div class="overviewcard__info">
                            {{$tournament['count']}}
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-4">
            <a href="{{url('admin/products')}}">
                <div class="main-overview active">
                    <div class="overviewcard">
                        <div class="overviewcard__icon">Total Approved Products&nbsp;&nbsp</div>
                        <div class="overviewcard__info">
                            {{$products->where('id')->where('created_by',$dealer)->where('state','1')->count()}}
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-4">
            <a href="{{url('admin/products')}}">
                <div class="main-overview active">
                    <div class="overviewcard">
                        <div class="overviewcard__icon">Total Pending Products&nbsp;&nbsp</div>
                        <div class="overviewcard__info">
                            {{$products->where('id')->where('created_by',$dealer)->where('state','0')->count()}}
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<hr class="new4">
<div class="container">
    <h3>Warehouse's Products</h3>
    <div class="row">
        <div class="col-sm-4">
            <a href="{{url('admin/products')}}">
                <div class="main-overview active">
                    <div class="overviewcard">
                        <div class="overviewcard__icon">Total Subadmin's Products&nbsp;&nbsp;</div>
                        <div class="overviewcard__info">
                            {{$products->where('id')->where('created_by',$subadmin)->count()}}
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-4">
            <a href="{{url('admin/products')}}">
                <div class="main-overview active">
                    <div class="overviewcard">
                        <div class="overviewcard__icon">Total Approved Products&nbsp;&nbsp</div>
                        <div class="overviewcard__info">
                            {{$products->where('id')->where('created_by',$subadmin)->where('state','1')->count()}}
                        </div>
                    </div>
                </div>
            </a>
        </div>
         <div class="col-sm-4">
            <a href="{{url('admin/products')}}">
                <div class="main-overview active">
                    <div class="overviewcard">
                        <div class="overviewcard__icon">Total Pending Products&nbsp;&nbsp</div>
                        <div class="overviewcard__info">
                            {{$products->where('id')->where('created_by',$subadmin)->where('created_by','0')->count()}}
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>


@endsection
