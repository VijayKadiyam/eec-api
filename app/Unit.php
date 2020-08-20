<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
  protected $fillable = [
    'first_name', 'middle_name', 'last_name', 'residence_address', 'phone_no', 'adhaar_no', 'email', 'serial_no_controller', 'location_controller', 'manufacturer_vfd', 'serial_no_vfd', 'motor_type', 'motor_category', 'motor_serial_no', 'pump_serial_no', 'motor_hp', 'motor_head_size', 'imei_number', 'rms_phone_no', 'device_id', 'send_email'
  ];

  public function company()
  {
    return $this->belongsTo(Company::class);
  }

  public function datas()
  {
    return $this->hasMany(Data::class)
      ->latest();
  }

  public function unitDatas()
  {
    return $this->hasMany(Data::class);
  }
}
