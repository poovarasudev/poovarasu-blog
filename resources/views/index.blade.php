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
                                <div class="col-lg-6"><h3>POST</h3></div>
                                <div class="col-lg-6">
                                    <button type="button"
                                            class="btn btn-default btn-circle-lg waves-effect waves-circle waves-float pull-right">
                                        <a href="/post/create"><i class="material-icons">add_to_queue</i></a></button>
                                </div>
                            </div>
                            <br>
                            <table id="data" class="table table-hover table-bordered table-striped datatable">
                                <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Title</th>
                                    <th>Published by</th>
                                    <th>Posted on</th>
                                    <th>View</th>
                                </tr>
                                </thead>
                            </table>
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
    <link href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" rel="stylesheet">
    <script type="text/javascript">
        $(function () {
            $('#data').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('datatable/getdata') }}',
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'title', name: 'title'},
                    {data: 'email', name: 'email'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'action', name: 'action'},
                ]
            });
        });
        function redirect(id) {
            window.location.href = "/post/"+id;
        }
    </script>
@endsection
