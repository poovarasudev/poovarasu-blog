<?php

namespace App\Http\Controllers;

use App\Http\Requests\Role\StoreRole;
use App\Http\Requests\Role\UpdateRole;
use App\Role;
use Illuminate\Http\Response;
use mysql_xdevapi\Exception;
use Yajra\DataTables\Facades\DataTables;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $roles = Role::all();
        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('roles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return Response
     */
    public function store(StoreRole $request)
    {
        $role = Role::create(['name' => $request->roleName, 'description' => $request->roleDescription, 'guard_name' => "web"]);
        $permissions = $request->permission;
        foreach ($permissions as $permission) {
            $role->givePermissionTo($permission);
        }
        return view('roles.index');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit(Role $role)
    {
        return view('roles.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return Response
     */
    public function update(Role $role, UpdateRole $request)
    {
        try {
            $role->update(['name' => $request->get('roleName'), 'description' => $request->get('roleDescription')]);
            $permissions = $request->permission;
            $old_permissions = $role->permission()->pluck('name')->toArray();
            $new_permissions = array_diff($permissions, $old_permissions);
            $delete_permissions = array_diff($old_permissions, $permissions);
            foreach ($delete_permissions as $delete_permission) {
                $role->revokePermissionTo($delete_permission);
            }
            foreach ($new_permissions as $new_permission) {
                $role->givePermissionTo($new_permission);
            }
            return view('roles.index');
        } catch (\Throwable $exception) {
            return view('errors.500')->with(['url' => route('/')]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy(Role $role)
    {
        try {
            if (!$role->isAdmin()) {
                $role->delete();
            } else {
                throw new Exception();
            }
            return response()->json(['action' => 'success', 'message' => 'Role deleted succesfully']);
        } catch (\Throwable $exception) {
            return response()->json(['action' => 'error', 'message' => 'Unable to delete role'], 500);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     * @throws \Exception
     */
    public function getRoles()
    {
        $role = Role::select('id', 'name', 'description');
        return DataTables::of($role)->addColumn('action', function ($role) {
            if (!$role->isAdmin()) {
                return '<button type="button" id="view" data-id="' .$role->id. '"
        class="btn btn-default btn-circle waves-effect waves-circle waves-float m-r-10"
        onclick="deleteRole(' .$role->id. ')"><i class="material-icons"> delete</i></button>
        <button type="button" id="view" data-id="' .$role->id. '"
        class="btn btn-default btn-circle waves-effect waves-circle waves-float"
        onclick="redirect(' .$role->id. ')"><i class="material-icons"> edit</i></button>';
            } else {
                return '<i class="material-icons m-l-30">remove</i>';
            }

        })
            ->addColumn('count', function ($role) {
                return count($role->permission);
            })
            ->make(true);

    }

}
