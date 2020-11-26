<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DataImport;
use App\CrudeData;
use App\Data;

class CrudeDatasController extends Controller
{
  public function index()
  {
    return response()->json([
      'data'  =>  CrudeData::all()
    ]);
  }

  public function uploadDatas(Request $request)
  {
    if ($request->hasFile('datas')) {
      $file = $request->file('datas');

      Excel::import(new DataImport, $file);
      
      return response()->json([
        'data'    =>  CrudeData::all(),
        'success' =>  true
      ]);
    }
  }

  public function processFile(Request $request)
  {
    $crude_datas = CrudeData::all();

    foreach($crude_datas as $data) {
      $data = [
        'imei_number'         =>  $data->imei_number,
        'date'                =>  $data->date,
        'time'                =>  $data->time,
        'pump_status'         =>  $data->pump_status,
        'voltage'             =>  $data->voltage,
        'current'             =>  $data->current,
        'frequency'           =>  $data->frequency,
        'temperature'         =>  $data->temperature,
        'phase_current_r'     =>  $data->phase_current_r,
        'phase_current_y'     =>  $data->phase_current_y,
        'phase_current_b'     =>  $data->phase_current_b,
        'dummy'               =>  $data->dummy,
        'reserved'            =>  $data->reserved,
      ];
      Data::create($data);
    }
  }

  public function truncate()
  {
    CrudeData::truncate();
  }
}
