@extends('layouts.app')

@section('title')
    Home Page
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6"><h3>Roles</h3><br></div>
                                <div class="col-lg-6">
                                    <button type="button" class="btn btn-lg bg-blue-grey waves-effect pull-right m-r-15 m-b-10" onclick="window.location.href = '/role/create';">Create</button>
                                </div>
                            </div>
                                <table id="data" class="table table-hover table-bordered table-striped datatable">
                                    <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Name</th>
                                        <th>Permissions Count</th>
                                        <th>Description</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js" defer></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script>

        $(function () {
            $('#data').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('datatable/getrole') }}',
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'count', name: 'count'},
                    {data: 'description', name: 'description'},
                    {data: 'action', name: 'action'},
                ]
            });
        });
        function redirect(id) {
            window.location.href = "/role/"+id+"/edit";
        }

    // Delete
    function deleteRole(id) {
        swal({
            title: "Are you sure to delete this role ?",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel",
            closeOnConfirm: false,
            closeOnCancel: true
        },
            function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: '/role/' + id,
                        method: 'delete',
                        data: {
                            '_token': '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            swal({
                                title: "Success!",
                                text: response.message,
                                icon: "success",
                                confirmButtonClass: "btn-success",
                                confirmButtonText: "Ok!",
                                closeOnConfirm: false,
                            }, function () {
                                window.location = "/role";
                            });
                        },
                        error: function (response) {
                            swal({
                                title: "Warning!",
                                text: response.responseJSON.message,
                                icon: "warning",
                            });
                        }
                    });
                }
            });
    }

    </script>
@endsection
