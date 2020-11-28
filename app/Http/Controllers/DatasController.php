<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Unit;
use App\Data;

class DatasController extends Controller
{
  public function __construct()
  {
    $this->middleware(['auth:api', 'company'])
      ->except(['index', 'storeByDevice']);
  }

  public function index(Request $request, Unit $unit)
  {
    if($request->forOutput) {
      $datas = $unit->unitDatas()->paginate(5000)->toArray();
      $datas = $datas['data'];
    }
    else {
      $datas = $unit->datas()->paginate(5000)->toArray();
      $datas = $datas['data'];
    }
    

    return response()->json([
      'data'     =>  $datas
    ], 200);
  }

  public function storeByDevice(Request $request)
  {
    $request->validate([
      'data'  =>  'required'
    ]);
    $str_arr = explode (",", $request->data); 
    $data['data'] = $request->data;
    $data['imei_number'] = $str_arr[0];
    $data['date'] = substr($str_arr[1], 0, 2) . '-' . substr($str_arr[1], 2, 2) . '-' . substr($str_arr[1], 4, 2);
    $data['time'] = substr($str_arr[2], 0, 2) . ':' . substr($str_arr[2], 2, 2) . ':' . substr($str_arr[2], 4, 2);;
    $data['pump_status'] = $str_arr[3];
    $data['voltage'] = substr($str_arr[4], 0, 3);
    // $data['current'] = substr($str_arr[4], 3, 2) . '.' . substr($str_arr[4], 5);
    $data['current'] = substr($str_arr[4], 3);
    $data['frequency'] = $str_arr[5];
    $data['temperature'] = $str_arr[6];
    $data['phase_current_r'] = substr($str_arr[7], 0, 2);
    $data['phase_current_y'] = substr($str_arr[7], 2, 2);
    $data['phase_current_b'] = substr($str_arr[7], 4, 2);
    $data['dummy'] = $str_arr[8];
    $data['reserved'] = $str_arr[9];

    $unit = Unit::where('imei_number', $data['imei_number'])
      ->first();

    if($unit)
      $data['unit_id']  = $unit->id;

    $data = new Data($data);
    $data->save();

    return 'SUCCESS';
    
    // return response()->json([
    //   'data'    =>  $data
    // ], 201); 
  }

  public function store(Request $request, Unit $unit)
  {
    $request->validate([
      'data'  =>  'required'
    ]);

    $data = new Data($request->all());
    $unit->datas()->save($data);

    return response()->json([
      'data'    =>  $data
    ], 201); 
  }

  public function show(Unit $unit, Data $data)
  {
    return response()->json([
      'data'   =>  $data
    ], 200);   
  }

  public function update(Request $request, Unit $unit, Data $data)
  {
    $data->update($request->all());
      
    return response()->json([
      'data'  =>  $data
    ], 200);
  }
}
