@extends('layouts.app')

@section('title')
    Create Users
@endsection

@section('content')
    <section class="content">
        <div class="row clearfix" id="resetAll">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            <strong>Assign Role</strong>
                        </h2>
                    </div>
                    <div class="body">
                        <div class="row clearfix">
                            <div class="col-md-3"></div>
                            <div class="col-md-1 form-control-label">
                                <label for="name">User Name</label>
                            </div>
                            <div class="col-md-3" id="resetDiv">
                                <select class="form-control show-tick" data-live-search="true" id="userId" name="userName">
                                    <option></option>
                                    @foreach($users as $user)
                                        @if(!$user->hasAnyRole($roles))
                                            <option id="option-{{ $user->id }}" value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-md-3"></div>
                            <div class="col-md-1 form-control-label">
                                <label for="name">Role Name</label>
                            </div>
                            <div class="col-md-3">
                                <select class="form-control show-tick" data-live-search="true" id="roleId" name="rollName">
                                    <option></option>
                                    @foreach($roles as $role)
                                        @if(!$role->isAdmin())
                                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-1">
                                <button type="button" id="add-btn" class="btn bg-grey waves-effect">Add</button>
                            </div>
                        </div>
                        <br><br>
                        <div class="row clearfix">
                            <div class="col-md-1"></div>
                            <div class="col-md-10">
                                <table id="data" class="table table-hover table-bordered table-striped datatable">
                                    <thead>
                                    <tr>
                                        <th>User Name</th>
                                        <th>Role</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                            <div class="col-md-1"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('roles.edit_user_role')
    </section>
@endsection

@section('script')
    <script>

        function redirect(userId ,userName, roleName){
            $("#smallModal").find("#editUserId").val(userName);
            $('#editRoleId').val(roleName).trigger('change');
            userIdForRoleEdit = userId;
        }

        function updateUserRole(){
            var userName = $('#editUserId').val();
            var roleName = $('#editRoleId').val();
            var formData = new FormData;
            formData.append('userName', userName);
            formData.append('roleName', roleName);
            formData.append('_method', 'patch');
            formData.append('_token', '{{ csrf_token() }}');
            $.ajax({
                type: "post",
                url: "/user/"+ userIdForRoleEdit,
                dataType: 'json',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    $("#modal-close").click();
                    swal({
                        title: "Success!",
                        text: response.message,
                        icon: "success",
                        confirmButtonClass: "btn-success",
                        confirmButtonText: "Ok!",
                        closeOnConfirm: true,
                    },
                        function (isConfirm) {
                            $('#data').DataTable().ajax.reload(null, false);
                        });
                },
                error: function (response) {
                    alert('error');
                }
            });
        }

        $(document).ready(function(){

            $('#userId').select2({
                placeholder: 'select a User',
                allowClear: true
            });
            $('#roleId').select2({
                placeholder: 'select a Role',
                allowClear: true
            });$('#editRoleId').select2({
                placeholder: 'select a Role',
                allowClear: true,
                width: '100%'
            });
        });

        $('#add-btn').on('click', function () {
            var user_id = $('#userId').val();
            var role_id = $('#roleId').val();
            $.ajax({
                type: "post",
                url: "/user",
                data: {role_id: role_id, user_id: user_id, "_token": "{{ csrf_token() }}"},
                success: function (response) {
                    swal({
                        title: "Success!",
                        text: response.message,
                        icon: "success",
                        confirmButtonClass: "btn-success",
                        confirmButtonText: "Ok!",
                        closeOnConfirm: true,
                    },
                        function (isConfirm) {
                            $('#roleId').val(null).trigger('change');
                            $('#userId').val(null).trigger('change');
                            $('#data').DataTable().ajax.reload(null, false);
                            $("#option-" + user_id).remove();
                        });
                },
                error: function (response) {
                    alert("inside the error");
                }
            });
        });

        var datatable = $(function () {
            $('#data').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('datatable/getuser') }}',
                columns: [
                    {data: 'name', name: 'name'},
                    {data: 'role_name', name: 'role_name'},
                    {data: 'action', name: 'action'},
                ]
            });
        });

        // Delete User-Role
        function deleteRole(id, name) {
            swal({
                    title: "Are you sure to remove this role from the user ?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Yes, remove it!",
                    cancelButtonText: "No, cancel",
                    closeOnConfirm: false,
                    closeOnCancel: true
                },
                function (isConfirm) {
                    if (isConfirm) {
                        $.ajax({
                            url: '/user/' + id,
                            method: 'delete',
                            retrieve: true,
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
                                });
                                $('#data').DataTable().ajax.reload(null, false);
                                $('#userId').append("<option id='option-"+id+"' value='"+id+"'>"+name+"</option>");
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
