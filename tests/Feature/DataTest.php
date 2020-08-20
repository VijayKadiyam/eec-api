<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Unit;
use App\Data;

class DataTest extends TestCase
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

    $this->unit = factory(Unit::class)->create([
      'company_id'  =>  $this->company->id,
      'imei_number' =>  '869270049552002'
    ]);

    factory(Data::class)->create([
      'unit_id'     =>  $this->unit->id,
    ]);

    $this->payload = [ 
      'data'  =>  '869270049552002,130120,180934,01,630079,153,54,545454,00000,RRRR'
    ];
  }

  /** @test */
  function user_must_be_logged_in_before_accessing_the_controller()
  {
    $this->json('post', '/api/units/' . $this->unit->id . '/datas')
      ->assertStatus(401); 
  }

  /** @test */
  function login_requires_email_and_password()
  {
    $this->json('POST', '/api/units/' . $this->unit->id . '/datas', [], $this->headers)
      ->assertStatus(422)
      ->assertExactJson([
          "errors"  =>  [
            "data"     =>  ["The data field is required."],
          ],
          "message" =>  "The given data was invalid."
        ]);
  }

  /** @test */
  function add_new_data()
  {
    $this->disableEH();
    $this->json('post', '/api/units/' . $this->unit->id . '/datas', $this->payload, $this->headers)
      ->assertStatus(201)
      ->assertJson([
          'data'   =>[
            'data'  =>  '869270049552002,130120,180934,01,630079,153,54,545454,00000,RRRR'
          ]
        ])
      ->assertJsonStructureExact([
          'data'   => [
            'data',
            'unit_id',
            'updated_at',
            'created_at',
            'id'
          ]
        ]);
  }

  /** @test */
  function add_new_data_from_device()
  {
    $this->disableEH();
    $this->json('get', '/api/data', $this->payload, $this->headers)
      ->assertStatus(201)
      ->assertJson([
          'data'   =>[
            'data'  =>  '869270049552002,130120,180934,01,630079,153,54,545454,00000,RRRR'
          ]
        ])
      ->assertJsonStructureExact([
          'data'   => [
            'data',
            'imei_number', 
            'date', 
            'time', 
            'pump_status', 
            'voltage', 
            'current', 
            'frequency', 
            'temperature', 
            'phase_current_r', 
            'phase_current_y', 
            'phase_current_b', 
            'dummy', 
            'reserved',
            'unit_id',
            'updated_at',
            'created_at',
            'id'
          ]
        ]);
  }

  /** @test */
  function list_of_datas()
  {
    $this->json('GET', '/api/units/' . $this->unit->id . '/datas',[], $this->headers)
      ->assertStatus(200)
      ->assertJsonStructure([
          'data' => [
            0=>[
              'data'
            ] 
          ]
        ]);
      $this->assertCount(1, Data::all());
  }

  /** @test */
  function show_single_data()
  {
    $this->json('get', '/api/units/' . $this->unit->id . "/datas/1", [], $this->headers)
      ->assertStatus(200)
      ->assertJson([
          'data'  => [
            'data'  =>  'Data 1'
          ]
        ]);
  }

  /** @test */
  function update_single_data()
  {
    $payload = [ 
      'data'  =>  'Data 1 Updated'
    ];

    $this->json('patch', '/api/units/' . $this->unit->id . "/datas/1", $payload, $this->headers)
      ->assertStatus(200)
      ->assertJson([
          'data'    => [
            'data'  =>  'Data 1 Updated',
          ]
       ])
      ->assertJsonStructureExact([
          'data'  => [
            'id',
            'unit_id',
            'data',
            'imei_number', 
            'date', 
            'time', 
            'pump_status', 
            'voltage', 
            'current', 
            'frequency', 
            'temperature', 
            'phase_current_r', 
            'phase_current_y', 
            'phase_current_b', 
            'dummy', 
            'reserved',
            'created_at',
            'updated_at',
          ]
      ]);
  }
}
