<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Unit;

class UnitsController extends Controller
{
  public function __construct()
  {
    $this->middleware(['auth:api', 'company']);
  }

  public function index(Request $request)
  {
    $units = [];
    if($request->search) {
      $search = $request->search;
      $units = request()->company->units()
        ->where('imei_number', 'LIKE', '%' . $search . '%')
        ->get();
    }
    else
      $units = request()->company->units;

    foreach($units as $unit) {
      $data = $unit->datas()->latest()->first();
      $unit['data'] = $data;
    }

    return response()->json([
      'data'     =>  $units
    ], 200);
  }

  public function store(Request $request)
  {
    $request->validate([
      'imei_number' =>  'required'
    ]);

    $unit = new Unit($request->all());
    $request->company->units()->save($unit);

    return response()->json([
      'data'    =>  $unit
    ], 201); 
  }

  public function show(Unit $unit)
  {
    return response()->json([
      'data'   =>  $unit
    ], 200);   
  }

  public function update(Request $request, Unit $unit)
  {
    $unit->update($request->all());
      
    return response()->json([
      'data'  =>  $unit
    ], 200);
  }

  public function destroy($id)
  {
    $user = Unit::find($id);
    $user->delete();
  }
}
