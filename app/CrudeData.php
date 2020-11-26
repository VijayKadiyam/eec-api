<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CrudeData extends Model
{
  protected $table = "crude_datas";

  protected $fillable = [
    'imei_number', 'date', 'time', 'pump_status', 'voltage', 'current', 'frequency', 'temperature', 'phase_current_r', 'phase_current_y', 'phase_current_b', 'dummy', 'reserved', 'water_supply_hrs', 'water_supply_qty'
  ];
}
