<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        $roles = Role::all();
        return view('roles.create_user_role',compact('users','roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $role = Role::findById($request->role_id);
            $user = User::find($request->user_id);
            $user->assignRole($role);
            return response()->json(['action' => 'success', 'message' => 'Role assigned succesfully']);
        } catch (\Throwable $exception) {
            return response()->json(['action' => 'error', 'message' => 'Unable to assign role'], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        try {
            $user = User::find($id);
            $old_role = $user->roles()->value('name');
            $user->removeRole($old_role);
            $user->assignRole($request->roleName);
            return response()->json(['action' => 'success', 'message' => 'Role assigned succesfully']);
        } catch (\Throwable $exception) {
            return response()->json(['action' => 'error', 'message' => 'Unable to assign role'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $user = User::find($id);
            $role = $user->roles()->value('name');
            $user->removeRole($role);
            return response()->json(['action' => 'success', 'message' => 'Role removed succesfully']);
        } catch (\Throwable $exception) {
            return response()->json(['action' => 'error', 'message' => 'Unable to remove the role'], 500);
        }
    }

    public function getUsers()
    {
        $user = User::whereHas('roles')->get();
        return DataTables::of($user)->editColumn('name', function ($user) {
                return $user->name;
            })
            ->addColumn('role_name', function ($user) {
                return $user->roles()->value('name');
            })
            ->addColumn('action', function ($user) {
            if ($user->roles()->value('name') != 'admin'){
                return '<button type="button" id="view" data-id="' . $user->id . '"
                                                class="btn btn-default btn-circle waves-effect waves-circle waves-float m-r-10" onclick="deleteRole(' . $user->id . ', \'' . $user->name . '\')">
                                            <i class="material-icons">delete</i></button>
                                            <button type="button" id="view" data-id="' . $user->id . '"
                                                data-toggle="modal" data-target="#smallModal" class="btn btn-default btn-circle waves-effect waves-circle waves-float" onclick="redirect(' . $user->id . ', \'' . $user->name . '\', \'' . $user->roles()->value('name') . '\')">
                                            <i class="material-icons">edit</i></button>';
            } else{
                return '<i class="material-icons m-l-30">remove</i>';
            }
        })->make(true);
    }

}
