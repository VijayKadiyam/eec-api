<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Role;
use App\Permission;

class AssignPermissionsController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth:api');
  }

   /*
     * Assign permission to role
     *
     *@
     */
    public function store(Request $request)
    {
      $request->validate([
          'permission_id'         =>  'required',
          'role_id' =>  'required'
      ]);

      $role           = Role::find($request->role_id);
      $permission     = Permission::find($request->permission_id);
      $permissionId   = $permission->id;
      $role->assignpermission($permissionId);
      $rolePermissions = Role::where('id', '=', $request->role_id)
        ->with('permissions')
        ->get();

      return response()->json([
          'data'  =>  $rolePermissions
      ], 201); 
    }
}
