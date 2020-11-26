<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use App\CrudeData;

HeadingRowFormatter::default('none');

class DataImport implements ToModel, WithHeadingRow
{
  public function model(array $row)
  {
    $UNIX_DATE = ($row['Date'] - 25569) * 86400;
    $date = gmdate("d/m/Y", $UNIX_DATE);
    $UNIX_TIME = ($row['Time'] - 25569) * 86400;
    $time = gmdate("H:i:s", $UNIX_TIME);

    $data = [
      // 'company_id'          =>  request()->company->id,
      'imei_number'         =>  $row['IMEI Number'],
      'date'                =>  $date,
      'time'                =>  $time,
      'pump_status'         =>  $row['Pump Status'],
      'voltage'             =>  $row['Voltage'],
      'current'             =>  $row['Current'],
      'frequency'           =>  $row['Frequency'],
      'temperature'         =>  $row['Temperature'],
      'phase_current_r'     =>  $row['Phase Current R'],
      'phase_current_y'     =>  $row['Phase Current Y'],
      'phase_current_b'     =>  $row['Phase Current B'],
      'dummy'               =>  $row['Dummy'],
      'reserved'            =>  $row['Reserved'],
    ];

    
    return new CrudeData($data);
  }

  public function headingRow(): int
  {
    return 1;
  }

  public function batchSize(): int
  {
    return 1000;
  }
}
