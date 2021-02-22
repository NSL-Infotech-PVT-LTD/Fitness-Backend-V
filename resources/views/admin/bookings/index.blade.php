@extends('layouts.backend')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">

                    <h1>Bookings</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/home')}}">Home</a></li>
                        <li class="breadcrumb-item active">Bookings</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">

                            <!--                <div class="card-body">
                                              
                                                        <a href="{{ url('/admin/bookings/create') }}" class="btn btn-success btn-sm" title="Add New">
                                                        <i class="fa fa-plus" aria-hidden="true"></i> Add New
                                                    </a>
                            
                                            </div>-->

                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-bordered table-hover data-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <?php
                                        foreach ($rules as $rule):
                                            if ($rule == 'model_type')
                                                $rule = 'Module Name';
                                            else if ($rule == 'created_by')
                                                $rule = 'Booked By';
                                            else if ($rule == 'created_at')
                                                $rule = 'Booked On';
                                            else if ($rule == 'sessions')
                                                $rule = 'Session / Hours';
                                            ?>
                                            <th>{{ucfirst($rule)}}</th>
                                        <?php endforeach; ?>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->


                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

<script type="text/javascript">
    $(function () {
    var table = $('.data-table').DataTable({
    language: {
    lengthMenu: "_MENU_",
            search: "_INPUT_",
            searchPlaceholder: "Search..",
            info: "Page <strong>_PAGE_</strong> of <strong>_PAGES_</strong>",
            paginate: {
            first: '<i class="fa fa-angle-double-left"></i>',
                    previous: '<i class="fa fa-angle-left"></i>',
                    next: '<i class="fa fa-angle-right"></i>',
                    last: '<i class="fa fa-angle-double-right"></i>',
            },
    },
            processing: true,
            serverSide: true,
            ajax: "<?= (request()->get('created_by') != '') ? route('bookings.index') . "?created_by=" . request()->get('created_by') : route('bookings.index') ?>",
            columns: [
            {data: 'id', name: 'id'},
<?php foreach ($rules as $rule): ?>
    <?php if ($rule == 'email'): ?>
                    {data: 'email', name: 'email', orderable: false, searchable: false},
    <?php else: ?>
                    {data: "{{$rule}}", name: "{{$rule}}"},
    <?php endif; ?>
<?php endforeach; ?>
            {data: 'action', name: 'action', orderable: false, searchable: false
            }
            ,
            ]
    });
    $('.data-table').on('click', '.changeStatus', function (e) {
    e.preventDefault();
    var url = $(this).attr('traget-href');
    swal.fire({
    title: "Are you sure want to update status of booking?",
            text: "Status change can't be revoked !",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Confirm",
            cancelButtonText: "Cancel",
    }).then((result) => {
    Swal.showLoading();
    if (result.value) {
    $.ajax({
    url: url,
            type: 'POST',
            dataType: 'json',
            data: {method: '_POST', submit: true, _token: '{{csrf_token()}}'},
            success: function (data) {
            if (data.success) {
            swal.fire("Changed!", data.message, "success");
//            table.ajax.reload(null, false);
            location.reload();
            }
            }
    });
    }
    });
    });
//deleting data
    $('.data-table').on('click', '.btnDelete[data-remove]', function (e) {
    e.preventDefault();
    var url = $(this).data('remove');
    swal.fire({
    title: "Are you sure want to remove this item?",
            text: "Data will be Temporary Deleted!",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Confirm",
            cancelButtonText: "Cancel",
    }).then((result) => {
    Swal.showLoading();
    if (result.value) {
    $.ajax({
    url: url,
            type: 'DELETE',
            dataType: 'json',
            data: {method: '_DELETE', submit: true, _token: '{{csrf_token()}}'},
            success: function (data) {
            if (data == 'Success') {
            swal.fire("Deleted!", "Event has been deleted", "success");
            table.ajax.reload(null, false);
            }
            }
    });
    }
    });
    });
    }
    );

</script>
@endsection








