<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;

class UsersController extends Controller
{
  public function __construct()
  {
    $this->middleware(['auth:api', 'company']);
  }

  /*
   * To get all the users
   *
   *@
   */
  public function index(Request $request)
  {
    $role = 3;
    $users = [];
    if($request->search == 'all')
      $users = $request->company->users()->with('roles')
        ->latest()->get();
    else if($request->search) {
      $search = $request->search;
      $users = $request->company->users()->with('roles')
        ->where('name', 'LIKE', '%' . $search . '%')
        ->orWhere('email', 'LIKE', '%' . $search . '%')
        ->orWhere('phone', 'LIKE', '%' . $search . '%')
        ->get();
    }
    else 
      if($request->role_id) {
        $role = Role::find($request->role_id);
        $users = $request->company->users()
          ->whereHas('roles', function($q) use($role) { 
            $q->where('name', '=', $role->name);
          })->latest()->get();
      }

    return response()->json([
          'data'  =>  $users
      ], 200);
  }

  /*
   * To store a new company user
   *
   *@
   */
  public function store(Request $request)
  {
    $request->validate([
      'name'                    => ['required', 'string', 'max:255'],
      'email'                   => ['required', 'string', 'email', 'max:255', 'unique:users'],
      'phone'                   => ['required', 'unique:users']
    ]);

    $user  = $request->all();
    $user['password'] = bcrypt('123456');

    $user = new User($user);

    $user->save();

    return response()->json([
      'data'     =>  $user
    ], 201); 
  }

  /*
   * To show particular user
   *
   *@
   */
  public function show($id)
  {
    $user = User::where('id' , '=', $id)
      ->with('roles', 'companies', 'company_designation', 'company_state_branch', 'supervisors')->first();

    return response()->json([
      'data'  =>  $user,
      'success' =>  true
    ], 200); 
  }

  /*
   * To update user details
   *
   *@
   */
  public function update(Request $request, User $user)
  {
    $request->validate([
      'name'                    => ['required', 'string', 'max:255'],
      'email'                   => ['required', 'string', 'email', 'max:255'],
      'phone'                   =>  'required'
    ]);

    $user->update($request->all());
    $user->roles = $user->roles;
    $user->companies = $user->companies;
    
    return response()->json([
      'data'  =>  $user,
      'success' =>  true
    ], 200);
  }

  public function destroy($id)
  {
    $user = User::find($id);
    $user->delete();
  }
}