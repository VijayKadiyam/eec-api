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
    $data['current'] = substr($str_arr[4], 3, 2) . '.' . substr($str_arr[4], 5);
    // $data['current'] = substr($str_arr[4], 3);
    $data['frequency'] = $str_arr[5];
    $data['temperature'] = $str_arr[6];
    $data['phase_current_r'] = substr($str_arr[7], 0, 2) . '.' . substr($str_arr[7], 2, 1);
    $data['phase_current_y'] = substr($str_arr[7], 3, 2) . '.' . substr($str_arr[7], 5, 1);
    $data['phase_current_b'] = substr($str_arr[7], 6, 2) . '.' . substr($str_arr[7], 8, 1);
    // $data['phase_current_r'] = substr($str_arr[7], 0, 2);
    // $data['phase_current_y'] = substr($str_arr[7], 2, 2);
    // $data['phase_current_b'] = substr($str_arr[7], 4, 2);
    $data['dummy'] = $str_arr[8];
    $data['reserved'] = $str_arr[9];

    $unit = Unit::where('imei_number', $data['imei_number'])
      ->first();

    if($unit)
      $data['unit_id']  = $unit->id;

    $data = new Data($data);
    $data->save();

    // For Jharkhand API
    // if($unit->location == 'Jharkhand') {
      $queryStringsArray = [];
      $queryStringsArray[] = ['key'   =>  'VendorId', 'value' =>  'STO04'];
      $queryStringsArray[] = ['key'   =>  'DeviceType', 'value' =>  'PUMP'];
      $queryStringsArray[] = ['key'   =>  'DeviceName', 'value' =>  'RMS' . $unit->id];
      $queryStringsArray[] = ['key'   =>  'DeviceId', 'value' =>  $unit->imei_number];
      $queryStringsArray[] = ['key'   =>  'Date', 'value' =>  Carbon::parse($data->date)->format('Y-m-d')];
      $queryStringsArray[] = ['key'   =>  'Time', 'value' =>  $data->time];
      $queryStringsArray[] = ['key'   =>  'TimeZone', 'value' =>  'Asia/Kolkata'];
      $queryStringsArray[] = ['key'   =>  'Latitude', 'value' =>  $unit->latitude];
      $queryStringsArray[] = ['key'   =>  'Longitude', 'value' =>  $unit->longitude];
      $queryStringsArray[] = ['key'   =>  'SoftwareVer', 'value' =>  '1.0.0'];
      $queryStringsArray[] = ['key'   =>  'SingleStrength', 'value' =>  '20'];
      $queryStringsArray[] = ['key'   =>  'MotorSpeed', 'value' =>  'NA'];
      $queryStringsArray[] = ['key'   =>  'DV', 'value' =>  'NA'];
      $queryStringsArray[] = ['key'   =>  'DC', 'value' =>  'NA'];
      $queryStringsArray[] = ['key'   =>  'MotorVoltage', 'value' =>  $data->voltage];
      $queryStringsArray[] = ['key'   =>  'MotorCurrent', 'value' =>  $data->current];
      $queryStringsArray[] = ['key'   =>  'CurrentFlow', 'value' =>  ''];
      $queryStringsArray[] = ['key'   =>  'InstPower', 'value' =>  ''];
      $queryStringsArray[] = ['key'   =>  'TotalEnrgy', 'value' =>  ''];
      $queryStringsArray[] = ['key'   =>  'TotalOnTime', 'value' =>  ''];
      $queryStringsArray[] = ['key'   =>  'Status', 'value' =>  $data->pump_status];
      $queryStringsArray[] = ['key'   =>  'FaultStatus', 'value' =>  ''];
      $queryStringsArray[] = ['key'   =>  'CurrentSensor', 'value' =>  ''];
      $queryStringsArray[] = ['key'   =>  'FaultCode', 'value' =>  ''];
      $queryStringsArray[] = ['key'   =>  'TotalFlow', 'value' =>  ''];
      $queryStringsArray[] = ['key'   =>  'TotalOffTime', 'value' =>  ''];
      $queryStringsArray[] = ['key'   =>  'Temp', 'value' =>  $data->temperature];
      $queryStringsArray[] = ['key'   =>  'SpeedStatus', 'value' =>  ''];
      $queryStringsArray[] = ['key'   =>  'OutputPower', 'value' =>  $data->voltage & $data->current];

      $qString = '';

      $count = 0;
      foreach($queryStringsArray as $queryString) {
        if($count != 0) {
          $qString = $qString . '&';
        }
        $qString = $qString . $queryString['key'] . '=' . $queryString['value'];
        $count++;
      }

      $endpoint = "https://gis.jharkhand.gov.in/ejalportal/RMS_DATA.asmx/Get_RMS_DATA?" . $qString;
      $client = new \GuzzleHttp\Client();
      $response = $client->request('GET', $endpoint);

      // var_dump($response);
      // $statusCode = $response->getStatusCode();
      // $content = json_decode($response->getBody(), true);
    // }

    // return 'SUCCESS';
    
    return response()->json([
      'response'=>  'SUCCESS',
      'data'    =>  $data
    ], 201); 
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
