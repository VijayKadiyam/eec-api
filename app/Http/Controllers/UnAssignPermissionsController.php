<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Role;
use App\Permission;

class UnAssignPermissionsController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth:api');
  }

  /*
   * UnAssign permission og rolw
   *
   *@
   */
  public function store(Request $request)
  {
    $request->validate([
      'role_id'  =>  'required',
      'permission_id'           =>  'required'
    ]);

    $role           = Role::find($request->role_id);
    $permission     = Permission::find($request->permission_id);
    $permissionId   = $permission->id;
    $role->unassignpermission($permissionId);
    $rolePermissions = Role::where('id', '=', $request->role_id)
      ->with('permissions')
      ->get();

    return response()->json([
    'data'    =>  $rolePermissions
    ], 201); 
  }
}
