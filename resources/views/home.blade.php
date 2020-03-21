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
        background-color: #476B9E;
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
    <h3>Tournaments</h3>
    <div class="row">
        <div class="col-sm-4">
            <a href="{{url('admin/tournament')}}">
                <div class="main-overview active">
                    <div class="overviewcard">
                        <div class="overviewcard__icon">Total Tournaments&nbsp;&nbsp;</div>
                        <div class="overviewcard__info">
                            {{$tournament->count()}}
                        </div>
                    </div>
                </div>
            </a>
        </div>
       
       
    </div>
</div>

<hr class="new4">
<div class="container">
    <h3>Weekly Revenue</h3>
    <div class="row">
       
       
       
    </div>
</div>



@endsection
