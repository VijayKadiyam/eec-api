<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;

class UserTest extends TestCase
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

    $this->payload = [ 
      'name'                 =>'sangeetha',
      'phone'                => 9844778380,
      'email'                =>'sangeetha@gmail.com',
      'can_send_email'        =>  0
    ];
  }

  /** @test */
  function user_must_be_logged_in()
  {
    $this->json('post', '/api/users')
         ->assertStatus(401);
  }

  /** @test */
  function it_requires_following_details()
  {
    $this->json('post', '/api/users', [], $this->headers)
         ->assertStatus(422)
         ->assertExactJson([
            "errors"  =>  [
              "name"                    =>  ["The name field is required."],
              "email"                   =>  ["The email field is required."],
              "phone"                   =>  ["The phone field is required."],
            ],
            "message" =>  "The given data was invalid."
        ]);
  }

  /** @test */
  function add_new_user()
  {
    $this->disableEH();
    $this->json('post', '/api/users', $this->payload, $this->headers)
     ->assertStatus(201)
     ->assertJson([
        'data'  =>  [
          'name'                 =>'sangeetha',
          'phone'                => 9844778380,
          'email'                =>'sangeetha@gmail.com'
        ]
      ])
      ->assertJsonStructure([
          'data'  =>  [
            'name',
            'phone',
            'email',
          ]
        ])
      ->assertJsonStructureExact([
          'data'  =>  [
            'name',
            'phone',
            'email',
            'updated_at',
            'created_at',
            'id',
          ]
        ]);
  }

  /** @test */
  public function list_of_users()
  {
    $this->disableEH();
    $user = factory(\App\User::class)->create();
    $user->assignRole(3);
    $user->assignCompany($this->company->id);

    $this->json('get', '/api/users?role_id=3', [], $this->headers)
      ->assertStatus(200)
      ->assertJsonStructure([
          'data' => []
        ]);
    $this->assertCount(1, User::whereHas('roles',  function($q) {
                                $q->where('name', '!=', 'Admin');
                                $q->where('name', '!=', 'Super Admin');
                              })->get());
  }

  /** @test */
  function show_single_user_details()
  {
    $this->disableEH();
    $this->json('get', "/api/users/1", [], $this->headers)
      ->assertStatus(200)
      ->assertJsonStructure([
          'data'  =>  [
            'name',
            'phone',
            'email' 
          ]
        ]);
  }

  /** @test */
  function update_single_user_details()
  {
    $this->disableEH();
    $payload  = [ 
      'name'  =>  'sangeetha',
      'phone' =>  9088597123,
      'email' =>  'preethi@gmail.com',
      'can_send_email'  =>  0
    ];
    $this->json('patch', '/api/users/1', $payload, $this->headers)
      ->assertStatus(200)
      ->assertJson([
          'data'    =>  [
            'phone' =>  9088597123,
            'email' =>  'preethi@gmail.com'
          ]
        ])
      ->assertJsonStructureExact([
          'data'  => [
            'id',
            'name',
            'email',
            'email_verified_at',
            'active',
            'phone',
            'api_token',
            'created_at',
            'updated_at',
            'attachment',
            'roles',
            'companies',
          ],
          'success'
        ]);
  }

  /** @test */
  function update_user_can_send_email()
  {
    $this->disableEH();
    $payload  = [ 
      'name'  =>  'sangeetha',
      'phone' =>  9088597123,
      'email' =>  'preethi@gmail.com',
      'can_send_email'  =>  1
    ];
    $this->json('patch', '/api/users/1', $payload, $this->headers)
      ->assertStatus(200)
      ->assertJson([
          'data'    =>  [
            'phone' =>  9088597123,
            'email' =>  'preethi@gmail.com'
          ]
        ])
      ->assertJsonStructureExact([
          'data'  => [
            'id',
            'name',
            'email',
            'email_verified_at',
            'active',
            'phone',
            'api_token',
            'created_at',
            'updated_at',
            'attachment',
            'roles',
            'companies',
          ],
          'success'
        ]);
  }
}
