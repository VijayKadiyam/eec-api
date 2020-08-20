<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Data extends Model
{
  protected $table = "datas";

  protected $fillable = [
    'unit_id', 'data', 'imei_number', 'date', 'time', 'pump_status', 'voltage', 'current', 'frequency', 'temperature', 'phase_current_r', 'phase_current_y', 'phase_current_b', 'dummy', 'reserved'
  ];

  public function unit()
  {
    return $this->belongsTo(Unit::class);
  }
}
