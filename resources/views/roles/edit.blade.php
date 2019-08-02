@extends('layouts.app')

@section('title')
    Role Edit Page
@endsection

@section('content')
    <section class="content">
        <!-- Horizontal Layout -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            <strong>Edit Role</strong>
                        </h2>
                    </div>
                    <div class="body">
                        <form class="form-horizontal" method="post" action="/role/{{ $role->id }}">
                            {{ csrf_field() }}
                            {{ method_field('PUT') }}
                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach($errors->all() as $error)
                                            {{ $error }}<br>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <div class="row clearfix">
                                <div class="col-lg-1 col-md-1 col-sm-1 col-xs-5 form-control-label">
                                    <label for="name">Role Name</label>
                                </div>
                                <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" name="roleName" id="name" class="form-control" value="{{ $role->name }}" required autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-lg-1 col-md-1 col-sm-4 col-xs-5 form-control-label">
                                    <label for="description">Description</label>
                                </div>
                                <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" name="roleDescription" id="description" value="{{ $role->description }}" required class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="header">
                                    <h2>
                                        <strong>Edit Assigned Permission</strong>
                                    </h2>
                                </div>
                                @php
                                    $permission = $role->permission->pluck('name')->toArray();
                                @endphp
                                <div class="col-md-6">
                                    <div class="body table-responsive">
                                        <table class="table table-borderless" style="border: hidden">
                                            <tr>
                                                <th></th>
                                                <th>Create</th>
                                                <th>Edit</th>
                                                <th>View</th>
                                                <th>Delete</th>
                                            </tr>
                                            <tr style="border: hidden">
                                                <th>Post</th>
                                                <td>
                                                    <input type="checkbox" id="11" name="permission[]" class="filled-in chk-col-light-blue" value="create post" @if(in_array("create post",$permission)) checked @endif>
                                                    <label for="11"></label>
                                                </td>
                                                <td>
                                                    <input type="checkbox" id="12" name="permission[]" class="filled-in chk-col-light-blue" value="edit post" @if(in_array("edit post", $permission)) checked @endif>
                                                    <label for="12"></label>
                                                </td>
                                                <td>
                                                    <input type="checkbox" id="13"name="permission[]" class="filled-in chk-col-light-blue" value="view post" @if(in_array("view post", $permission)) checked @endif>
                                                    <label for="13"></label>
                                                </td>
                                                <td>
                                                    <input type="checkbox" id="14"name="permission[]" class="filled-in chk-col-light-blue" value="delete post" @if(in_array("delete post", $permission)) checked @endif>
                                                    <label for="14"></label>
                                                </td>
                                            </tr>
                                            <tr style="border: hidden">
                                                <th>Comment</th>
                                                <td>
                                                    <input type="checkbox" id="21"name="permission[]" class="filled-in chk-col-light-blue" value="create comment" @if(in_array("create comment", $permission)) checked @endif>
                                                    <label for="21"></label>
                                                </td>
                                                <td>
                                                    <input type="checkbox" id="22"name="permission[]" class="filled-in chk-col-light-blue" value="edit comment" @if(in_array("edit comment", $permission)) checked @endif>
                                                    <label for="22"></label>
                                                </td>
                                                <td>
                                                    <input type="checkbox" id="23"name="permission[]" class="filled-in chk-col-light-blue" value="view comment" @if(in_array("view comment", $permission)) checked @endif>
                                                    <label for="23"></label>
                                                </td>
                                                <td>
                                                    <input type="checkbox" id="24"name="permission[]" class="filled-in chk-col-light-blue" value="delete comment" @if(in_array("delete comment", $permission)) checked @endif>
                                                    <label for="24"></label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Tag</th>
                                                <td>
                                                    <input type="checkbox" id="31"name="permission[]" class="filled-in chk-col-light-blue" value="create tag" @if(in_array("create tag", $permission)) checked @endif>
                                                    <label for="31"></label>
                                                </td>
                                                <td>
                                                    <input type="checkbox" id="32"name="permission[]" class="filled-in chk-col-light-blue" value="edit tag" @if(in_array("edit tag", $permission)) checked @endif>
                                                    <label for="32"></label>
                                                </td>
                                                <td>
                                                    <input type="checkbox" id="33"name="permission[]" class="filled-in chk-col-light-blue" value="view tag" @if(in_array("view tag", $permission)) checked @endif>
                                                    <label for="33"></label>
                                                </td>
                                                <td>
                                                    <input type="checkbox" id="34"name="permission[]" class="filled-in chk-col-light-blue" value="delete tag" @if(in_array("delete tag", $permission)) checked @endif>
                                                    <label for="34"></label>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <button type="submit" class="btn btn-lg btn-primary m-t-15 waves-effect pull-right m-r-30">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- #END# Horizontal Layout -->
    </section>
@endsection

@section('script')

@endsection
