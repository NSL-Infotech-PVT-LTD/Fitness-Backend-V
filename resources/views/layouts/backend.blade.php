<!DOCTYPE HTML>
<html>
    <head>
        <title>{{ config('app.name') }}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="keywords" content="{{ config('app.name') }}" />
        <script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
        <!-- Bootstrap Core CSS -->
        <link href="{{ asset('template/css/font-awesome.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('adminLte/plugins/fontawesome-free/css/all.min.css') }}">
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
        <!-- Tempusdominus Bbootstrap 4 -->
        <link rel="stylesheet" href="{{ asset('adminLte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
        <!-- iCheck -->
        <link rel="stylesheet" href="{{ asset('adminLte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
        <!-- JQVMap -->
        <link rel="stylesheet" href="{{ asset('adminLte/plugins/jqvmap/jqvmap.min.css') }}">
        <!-- Theme style -->
        <link rel="stylesheet" href="{{ asset('adminLte/css/adminlte.min.css') }}">
        <!-- overlayScrollbars -->
        <link rel="stylesheet" href="{{ asset('adminLte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
        <!-- Daterange picker -->
        <link rel="stylesheet" href="{{ asset('adminLte/plugins/daterangepicker/daterangepicker.css') }}">
        <!-- summernote -->
        <link rel="stylesheet" href="{{ asset('adminLte/plugins/summernote/summernote-bs4.css') }}">
        <!-- Google Font: Source Sans Pro -->
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">


        <link rel="stylesheet" href="{{ asset('adminLte/plugins/datatables/buttons.bootstrap4.min.css') }}">

        <link rel="stylesheet" href="{{ asset('/template/css/table.dataTable.css') }}">
        <link rel="stylesheet" href="{{ asset('/template/css/new_style.css') }}">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>

        <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;600;700&display=swap" rel="stylesheet">

        <link href='//fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
        <script src="{{asset('js/jquery-1.11.1.min.js')}}"></script>
        <script src="{{ asset('template/js/menu_jquery.js') }}  "></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <style>
            body{ 
                font-family: "Open Sans",-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,"Noto Sans",sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol" !important;
            }
        </style>
    </head>
    <body class="hold-transition sidebar-mini layout-fixed">
        <div class="wrapper">


            <!-- Navbar -->
            <nav class="main-header navbar navbar-expand navbar-white navbar-light">
                <!-- Left navbar links -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link btnmenu" data-widget="pushmenu" href="#" role="button"><i class="fa fa-fw fa-ellipsis-v"></i></a>
                    </li>
                    <!---<li class="nav-item d-none d-sm-inline-block">
                        <a href="{{ url('admin/home')}}" class="nav-link"> <i class="fas fa-house-user"></i></a>
                    </li>-->
                    <li class="nav-item d-none d-sm-inline-block">
                        <!--<a href="#" class="nav-link">Contact</a>-->
                    </li>
                </ul>

                <!-- SEARCH FORM -->
                <!--                <form class="form-inline ml-3">
                                    <div class="input-group input-group-sm">
                                        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
                                        <div class="input-group-append">
                                            <button class="btn btn-navbar" type="submit">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>-->

                <!-- Right navbar links -->
                <ul class="navbar-nav ml-auto">

                    <li class="nav-item dropdown">
                        <a class="nav-link" data-toggle="dropdown" href="#">
                            <i class="far fa-bell"></i>
                            <span class="badge badge-warning navbar-badge">10</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                            <div class="p-2 bg-primary text-center">
                                <h5 class="dropdown-header text-uppercase text-white">Notifications</h5>
                            </div>
                            <ul class="nav-items mb-0">
                                <li>
                                    <a class="text-dark media py-2" href="javascript:void(0)">
                                        <div class="mr-2 ml-3">
                                            <i class="fa fa-fw fa-check-circle text-success"></i>
                                        </div>
                                        <div class="media-body pr-2">
                                            <div class="font-w600">You have a new follower</div>
                                            <small class="text-muted">15 min ago</small>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a class="text-dark media py-2" href="javascript:void(0)">
                                        <div class="mr-2 ml-3">
                                            <i class="fa fa-fw fa-plus-circle text-info"></i>
                                        </div>
                                        <div class="media-body pr-2">
                                            <div class="font-w600">1 new sale, keep it up</div>
                                            <small class="text-muted">22 min ago</small>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a class="text-dark media py-2" href="javascript:void(0)">
                                        <div class="mr-2 ml-3">
                                            <i class="fa fa-fw fa-times-circle text-danger"></i>
                                        </div>
                                        <div class="media-body pr-2">
                                            <div class="font-w600">Update failed, restart server</div>
                                            <small class="text-muted">26 min ago</small>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a class="text-dark media py-2" href="javascript:void(0)">
                                        <div class="mr-2 ml-3">
                                            <i class="fa fa-fw fa-plus-circle text-info"></i>
                                        </div>
                                        <div class="media-body pr-2">
                                            <div class="font-w600">2 new sales, keep it up</div>
                                            <small class="text-muted">33 min ago</small>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a class="text-dark media py-2" href="javascript:void(0)">
                                        <div class="mr-2 ml-3">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div class="media-body pr-2">
                                            <div class="font-w600">You have a new subscriber</div>
                                            <small class="text-muted">41 min ago</small>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a class="text-dark media py-2" href="javascript:void(0)">
                                        <div class="mr-2 ml-3">
                                            <i class="fa fa-fw fa-check-circle text-success"></i>
                                        </div>
                                        <div class="media-body pr-2">
                                            <div class="font-w600">You have a new follower</div>
                                            <small class="text-muted">42 min ago</small>
                                        </div>
                                    </a>
                                </li>
                            </ul>  


                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
                        </div>
                    </li> 

                    <div class="d-flex align-items-center">
                        <div class="dropdown d-inline-block ml-2 show">
                            <button type="button" class="btn btn-sm btn-dual" id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">

                                <span class="d-sm-inline-block">Profile</span>
                                <i class="fa fa-fw fa-angle-down d-none d-sm-inline-block"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right p-0 border-0 font-size-sm" aria-labelledby="page-header-user-dropdown" style="position: absolute; transform: translate3d(-76px, 31px, 0px); top: 0px;left:0px; will-change: transform;" x-placement="bottom-end">
                                <div class="top_left">


                                    <a class="nav-link" style ="Color:Black" href="{{ url('/logout') }}"
                                       onclick="event.preventDefault();
    document.getElementById('logout-form').submit();">
                                        Logout <i class="fas fa-sign-out-alt"></i>
                                    </a>

                                    <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>

                            </div>
                        </div>


                    </div>

                </ul>
            </nav>
            <!-- /.navbar -->
            <!-- Main Sidebar Container -->
            <aside class="main-sidebar sidebar-dark-primary elevation-4">
                <!-- Brand Logo -->
                <a href="http://localhost/volt/public/admin/home" class="brand-link">

                    <span class="brand-text font-weight-light"><img src="/volt/public/logo_black.png"style="max-width:100px"></span>
                </a>

                <!-- Sidebar -->
                <div class="sidebar">
                    <!-- Sidebar user panel (optional) -->
                    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                        <div class="image">
                            <img src="{{ asset('person.jpg') }}">
                        </div>
                        <div class="info">
                            <a href="{{ url('admin/users/role/1') }}" class="d-block">Super Admin</a>
                        </div>
                    </div>

                    <!-- Sidebar Menu -->
                    <nav class="mt-2">
                        <ul id="menu" class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                            <!-- Add icons to the links using the .nav-icon class
                                 with font-awesome or any other icon font library -->

                            <li class="nav-item">
                                <a href="{{ url('admin/home') }}" class="nav-link">
                                    <i class="fas fa-tachometer-alt"></i>
                                    <p>
                                        Dashboard
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item has-treeview">
                                <a href="" class="nav-link">
                                    <i class="fas fa-user"></i>
                                    <p>
                                        Users
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ url('admin/adminusers') }}" class="nav-link">
                                            <i class="fas fa-users-cog"></i>
                                            <p>View All Users</p>
                                        </a>
                                        <a href="{{ url('admin/adminusers/create') }}" class="nav-link">
                                            <i class="fas fa-users-cog"></i>
                                            <p>Add New</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item has-treeview">
                                <a href="" class="nav-link">
                                    <i class="fas fa-user"></i>
                                    <p>
                                        Trainers
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ url('admin/trainer-user') }}" class="nav-link">
                                            <i class="fas fa-users-cog"></i>
                                            <p>View All Trainers</p>
                                        </a>
                                        <a href="{{ url('admin/trainer-user/create') }}" class="nav-link">
                                            <i class="fas fa-users-cog"></i>
                                            <p>Add New</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="nav-item has-treeview">
                                <a href="" class="nav-link">
                                    <i class="fas fa-users"></i>
                                    <p>
                                        Members
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <?php foreach (\App\Role::all() as $role): ?>
                                        <li class="nav-item">
                                            <a href="{{ url('admin/users/role/'.$role->id) }}" class="nav-link">
                                                <i class="far fa-user"></i>
                                                <p><?= ($role->category != '') ? $role->name . ' | ' . (($role->category == 'family_with_2') ? 'Family' : $role->category) : $role->name ?></p>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>

                            <li class="nav-item has-treeview">
                                <a href="" class="nav-link">
                                    <i class="fas fa-clipboard-list"></i>
                                    <p>
                                        Plans
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ url('admin/roles') }}" class="nav-link">
                                            <i class="fas fa-users-cog"></i>
                                            <p>View All Plans</p>
                                        </a>
                                        <a href="{{ url('admin/roles/create') }}" class="nav-link">
                                            <i class="fas fa-users-cog"></i>
                                            <p>Add New</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item has-treeview">
                                <a href="" class="nav-link">
                                    <i class="fas fa-cog"></i>
                                    <p>
                                        Services
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ url('admin/service') }}" class="nav-link">
                                            <i class="fas fa-users-cog"></i>
                                            <p>View All services</p>
                                        </a>
                                        <a href="{{ url('admin/service/create') }}" class="nav-link">
                                            <i class="fas fa-users-cog"></i>
                                            <p>Add New</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item has-treeview">
                                <a href="" class="nav-link">
                                    <i class="fas fa-user"></i>
                                    <p>
                                        Classes
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ url('admin/class') }}" class="nav-link">
                                            <i class="fas fa-users-cog"></i>
                                            <p>View All Class</p>
                                        </a>
                                        <a href="{{ url('admin/class/create') }}" class="nav-link">
                                            <i class="fas fa-users-cog"></i>
                                            <p>Add New</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item has-treeview">
                                <a href="" class="nav-link">
                                    <i class="fas fa-user"></i>
                                    <p>
                                        Class Schedule
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ url('admin/class-schedule') }}" class="nav-link">
                                            <i class="fas fa-users-cog"></i>
                                            <p>View All Class Schedules</p>
                                        </a>
                                        <a href="{{ url('admin/class-schedule/create') }}" class="nav-link">
                                            <i class="fas fa-users-cog"></i>
                                            <p>Add New</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item has-treeview">
                                <a href="" class="nav-link">
                                    <i class="fas fa-calendar-alt"></i>
                                    <p>
                                        Events
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ url('admin/events') }}" class="nav-link">
                                            <i class="fas fa-users-cog"></i>
                                            <p>View All Events</p>
                                        </a>
                                        <a href="{{ url('admin/events/create') }}" class="nav-link">
                                            <i class="fas fa-users-cog"></i>
                                            <p>Add New</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item has-treeview">
                                <a href="" class="nav-link">
                                    <i class="fas fa-location-arrow"></i>
                                    <p>
                                        Venues
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ url('admin/location') }}" class="nav-link">
                                            <i class="fas fa-users-cog"></i>
                                            <p>View All Venues</p>
                                        </a>
                                        <a href="{{ url('admin/location/create') }}" class="nav-link">
                                            <i class="fas fa-users-cog"></i>
                                            <p>Add New</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                        </ul>
                    </nav>
                    <!-- /.sidebar-menu -->
                </div>
                <!-- /.sidebar -->
            </aside>
            @yield('content')
        </div>
        <style type="text/css" class='fa-star-o'>
            table tr th:first-child {width: 50px !important;}
            .menu-open li.nav-item p {
                text-transform: capitalize;
            }
        </style>
        <script>
            var toggle = true;

            $(".sidebar-icon").click(function () {
                if (toggle)
                {
                    $(".page-container").addClass("sidebar-collapsed").removeClass("sidebar-collapsed-back");
                    $("#menu span").css({"position": "absolute"});
                } else
                {
                    $(".page-container").removeClass("sidebar-collapsed").addClass("sidebar-collapsed-back");
                    setTimeout(function () {
                        $("#menu span").css({"position": "relative"});
                    }, 400);
                }

                toggle = !toggle;
            });
            $("#menu li a").each(function () {
                if ((window.location.href == $(this).attr('href'))) {
                    $(this).addClass('active');
                    if ($(this).parent().parent().parent().hasClass('has-treeview')) {
                        $(this).parent().parent().parent().addClass('menu-open');
                        // $(this).parent().parent().parent().find('a.nav-link').addClass('active');
                    }
                }
            });
        </script>

        <!-- jQuery -->
        <script src="{{ asset('adminLte/plugins/jquery/jquery.min.js') }}"></script>
        <!-- jQuery UI 1.11.4 -->
        <script src="{{ asset('adminLte/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
        <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
        <script>
            $.widget.bridge('uibutton', $.ui.button)
        </script>
        <!-- Bootstrap 4 -->
        <script src="{{ asset('adminLte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <!-- ChartJS -->
        <script src="{{ asset('adminLte/plugins/chart.js/Chart.min.js') }}"></script>
        <!-- Sparkline -->
        <script src="{{ asset('adminLte/plugins/sparklines/sparkline.js') }}"></script>
        <!-- JQVMap -->
        <script src="{{ asset('adminLte/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
        <script src="{{ asset('adminLte/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
        <!-- jQuery Knob Chart -->
        <script src="{{ asset('adminLte/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
        <!-- daterangepicker -->
        <script src="{{ asset('adminLte/plugins/moment/moment.min.js') }}"></script>
        <script src="{{ asset('adminLte/plugins/daterangepicker/daterangepicker.js') }}"></script>
        <!-- Tempusdominus Bootstrap 4 -->
        <script src="{{ asset('adminLte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
        <!-- Summernote -->
        <script src="{{ asset('adminLte/plugins/summernote/summernote-bs4.min.js') }}"></script>
        <!-- overlayScrollbars -->
        <script src="{{ asset('adminLte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
        <!-- AdminLTE App -->
        <script src="{{ asset('adminLte/js/adminlte.js') }}"></script>
        <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
        <script src="{{ asset('adminLte/js/pages/dashboard.js') }}"></script>
        <!-- AdminLTE for demo purposes -->
        <script src="{{ asset('adminLte/js/demo.js') }}"></script>
        <!-- jQuery -->
        <!-- Bootstrap 4 -->
        <!-- DataTables -->
        <script src="{{ asset('adminLte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('adminLte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>

         <!--<script src="{{ asset('adminLte/plugins/datatables/be_tables_datatables.min.js') }}"></script>-->
        <script src="{{ asset('adminLte/plugins/datatables/dataTables.buttons.min.js') }}"></script>
        <script src="{{ asset('adminLte/plugins/datatables/buttons.flash.min.js') }}"></script>
        <script src="{{ asset('adminLte/plugins/datatables/buttons.html5.min.js') }}"></script>
        <script src="{{ asset('adminLte/plugins/datatables/buttons.colVis.min.js') }}"></script>
        <script src="{{ asset('adminLte/plugins/datatables/buttons.print.min.js') }}"></script>

        <script src="{{ asset('adminLte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('adminLte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>



        <script>

            function price(evt, allprice) {
                var i, tabcontent, tablinks;
//                evt.currentTarget.className = "active";
//evt.attr('class');
                evt.target.classList.toggle("active");
                if (document.getElementById(allprice).style.display == 'block') {
                    document.getElementById(allprice).style.display = "none";
                } else {
                    document.getElementById(allprice).style.display = "block";
                }


            }
        </script>

    </body>
</html>







