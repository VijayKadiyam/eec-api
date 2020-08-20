<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
  public function __construct()
  {
    $this->middleware(['auth:api', 'company']);
  }

  public function count(Request $request)
  {
    $users = $request->company->users()
          ->whereHas('roles', function($q){ 
            $q->where('name', '=', 'Employee');
          });
    // Employee Count
    $count['employees'] = $users->count();
    // Active Employee Count
    $count['activeEmployees'] = $users->where('active', '=', 1)->count();
    
    return response()->json([
      'count' =>  $count
    ]);
  }
}
