<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RoleUserTest extends TestCase
{
  use DatabaseTransactions;

  /** @test */
  function user_requires_role_and_user()
  {
    $this->json('post', '/api/role_user', [], $this->headers)
      ->assertStatus(422)
      ->assertExactJson([
          "errors"     =>  [
            "role_id"  =>  ["The role id field is required."],
            "user_id"  =>  ["The user id field is required."]
          ],
          "message"    =>  "The given data was invalid."
        ]);
  }

  /** @test */
  function assign_role()
  {
    $userTwo  = factory(\App\User::class)->create();
    $userTwo->assignRole(2);
    $check    = $userTwo->hasRole(2);
    $this->assertTrue($check);
  }

  /** @test */
  function assign_role_to_user()
  {
    $this->disableEH();
    $userTwo       = factory(\App\User::class)->create();
    $this->payload = [ 
      'user_id'    => $userTwo->id,
      'role_id'    => 2
    ];
    $this->json('post', '/api/role_user', $this->payload)
      ->assertStatus(201)
      ->assertJson([
          'data'  =>  [
            'name'                    =>  $userTwo->name,
            'phone'                   =>  $userTwo->phone,
            'email'                   =>  $userTwo->email,
            'roles'                   =>  [
              0 =>  [
                'name'  =>  'Admin'
              ]
            ]
          ]
        ])
      ->assertJsonStructureExact([
        'data'  =>  [
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
        ],
        'success'
      ]);
  }
}
