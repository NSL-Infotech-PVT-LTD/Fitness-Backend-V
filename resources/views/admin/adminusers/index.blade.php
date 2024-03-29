@extends('layouts.backend')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Admin Users</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/home')}}">Home</a></li>
                        <li class="breadcrumb-item active">Users</li>
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

                            <div class="card-body">
                                <a href="{{ url('/admin/adminusers/create') }}" class="btn btn-success btn-sm" title="Add New User">
                                    <i class="fa fa-plus" aria-hidden="true"></i> Add New
                                </a>




                            </div>

                        </div>
                        <!-- /.card-header -->
                        <div class="content">
                            <table class="table table-bordered table-hover data-table ">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <?php foreach ($rules as $rule): ?>
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
                ajax: "{{ route('adminusers.index') }}",
                columns: [
                {data: 'id', name: 'id'},
<?php foreach ($rules as $rule): ?>
                    {data: "{{$rule}}", name: "{{$rule}}"},
<?php endforeach; ?>
                {data: 'action', name: 'action', orderable: false, searchable: false
                }
                ,
                ]
        }
        );
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
                                swal.fire("Deleted!", "User has been deleted", "success");
                                table.ajax.reload(null, false);
                            }
                        }
                    });
                }
            });
        });
        $('.data-table').on('click', '.changeStatus', function (e) {
//       alert('a');
            e.preventDefault();
            var id = $(this).attr('data-id');
            var status = $(this).attr('data-status');
            Swal.fire({
                title: 'Are you sure you wanted to change status?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, ' + status + ' it!'
            }).then((result) => {
                Swal.showLoading();
                if (result.value) {
                    var form_data = new FormData();
                    form_data.append("id", id);
                    form_data.append("status", status);
                    form_data.append("_token", $('meta[name="csrf-token"]').attr('content'));
                    $.ajax({
                        url: "{{route('user.changeStatus')}}",
                        method: "POST",
                        data: form_data,
                        contentType: false,
                        cache: false,
                        processData: false,
                        beforeSend: function () {
//                        Swal.showLoading();
                        },
                        success: function (data)
                        {
                            Swal.fire(
                                    status + ' !',
                                    'User has been ' + status + ' .',
                                    'success'
                                    ).then(() => {
                                table.ajax.reload(null, false);
                            });
                        }
                    });
                }
            });
        });









    }
    );

</script>
@endsection
