<?php

use Illuminate\Database\Seeder;
use App\Company;

class CompanySeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    Company::create([
      'name'    => 'RMS', 
      'email'   =>  'email@rms.com', 
      'address' =>  'Thane',
      'phone'   =>  '9820704909'
    ]);
  }
}
