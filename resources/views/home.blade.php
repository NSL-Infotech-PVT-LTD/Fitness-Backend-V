@extends('layouts.backend')

@section('content')


<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.css" rel="stylesheet">
<script src='https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.min.js'/></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js'/></script>

<!--mycode-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.9.2/jquery.ui.datepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.9.2/themes/sunny/jquery-ui.min.css"></link>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.css"></link>

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
   
    <canvas id="myChart">

    </canvas>

</div>

<script>
let myChart = document.getElementById('myChart').getContext('2d');
//global options
Chart.defaults.global.defaultFontFamily = 'Lato';
Chart.defaults.global.defaultFontSize = 18;
Chart.defaults.global.defaultFontColor = '#777';
let massPopChart = new Chart(myChart, {
type:'bar', //bar, horizontalBar, pie, line, doughnut, radar, polarArea

        
data:{
labels:['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
        datasets:[{
        label:'Enrollments',
                data:[<?php echo $revenueData; ?>],
//                backgroundColor:'green',
                backgroundColor:[
                        'rgba(255,99,132, 0.6)',
                        'rgba(54,162,235,0.6)',
                        'rgba(255,206,86,0.6)',
                        'rgba(75,192,192,0.6)',
                        'rgba(153,102,255,0.6)',
                        'rgba(255,206,86,0.6)',
                        'rgba(75,192,192,0.6)',
                        'rgba(255,99,132, 0.6)',
                        'rgba(54,162,235,0.6)',
                        'rgba(255,206,86,0.6)',
                        'rgba(75,192,192,0.6)',
                        'rgba(153,102,255,0.6)',
                ],
                borderWidth:1,
                borderColor:'#777',
                hoverBorderWidth:3,
                hoverBorderColor:'#000'



        }],
        },
        options:{
        title:{
        display:true,
                text:'Monthly  Revenue',
                fontSize:25
        },
                legend:{
                display:false,
                        position:'right',
                        labels:{
                        fontColor:'#000'
                        }
                },
                layout:{
                padding:{
                left:50,
                        right:0,
                        bottom:0,
                        top:0,
                }
                },
                tooltips:{
                enabled:true

                }
        }


});
</script>










@endsection
