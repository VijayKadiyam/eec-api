<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Unit;

class UnitsTest extends TestCase
{
  use DatabaseTransactions;
  
  public function setUp()
  {
    parent::setUp();

    $this->company = factory(\App\Company::class)->create([
      'name' => 'test'
    ]);
    $this->user->assignCompany($this->company->id);
    $this->headers['company-id'] = $this->company->id;

    factory(Unit::class)->create([
      'company_id'  =>  $this->company->id 
    ]);

    $this->payload = [ 
      'first_name'  =>  'First 2',
      'imei_number'     =>  'no'
    ];
  }

  /** @test */
  function user_must_be_logged_in_before_accessing_the_controller()
  {
    $this->json('post', '/api/units')
      ->assertStatus(401); 
  }

  /** @test */
  function it_requires_following_details()
  {
    $this->json('post', '/api/units', [], $this->headers)
         ->assertStatus(422)
         ->assertExactJson([
            "errors"  =>  [
              "imei_number"                    =>  ["The imei number field is required."],
            ],
            "message" =>  "The given data was invalid."
        ]);
  }

  /** @test */
  function add_new_service()
  {
    $this->disableEH();
    $this->json('post', '/api/units', $this->payload, $this->headers)
      ->assertStatus(201)
      ->assertJson([
          'data'   =>[
            'first_name'  =>  'First 2'
          ]
        ])
      ->assertJsonStructureExact([
          'data'   => [
            'first_name',
            'imei_number',
            'company_id',
            'updated_at',
            'created_at',
            'id'
          ]
        ]);
  }

  /** @test */
  function list_of_services()
  {
    $this->json('GET', '/api/units',[], $this->headers)
      ->assertStatus(200)
      ->assertJsonStructure([
          'data' => [
            0=>[
              'first_name'
            ] 
          ]
        ]);
      $this->assertCount(1, Unit::all());
  }

  /** @test */
  function show_single_service()
  {
    $this->json('get', "/api/units/1", [], $this->headers)
      ->assertStatus(200)
      ->assertJson([
          'data'  => [
            'first_name'  =>  'First 1'
          ]
        ]);
  }

  /** @test */
  function update_single_service()
  {
    $payload = [ 
      'first_name'  =>  'First 1 Updated'
    ];

    $this->json('patch', '/api/units/1', $payload, $this->headers)
      ->assertStatus(200)
      ->assertJson([
          'data'    => [
            'first_name'  =>  'First 1 Updated',
          ]
       ])
      ->assertJsonStructureExact([
          'data'  => [
            'id',
            'company_id',
            'first_name',
            'middle_name',
            'last_name',
            'residence_address', 
            'phone_no', 
            'adhaar_no', 
            'email', 
            'serial_no_controller', 
            'location_controller', 
            'manufacturer_vfd', 
            'serial_no_vfd',
            'motor_type', 
            'motor_category', 
            'motor_serial_no', 
            'pump_serial_no',
            'motor_hp', 
            'motor_head_size', 
            'imei_number', 
            'rms_phone_no', 
            'device_id', 
            'created_at',
            'updated_at',
            'send_email',
            'latitude',
            'longitude'
          ]
      ]);
  }


}
