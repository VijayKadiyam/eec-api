<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
  protected $fillable = [
    'name', 'email', 'phone', 'address', 'logo_path', 'contact_person', 'time_zone', 'is_active'
  ];

  /*
   * A company belongs to many users
   *
   *@
   */
  public function users()
  {
    return $this->belongsToMany(User::class)
      ->with('roles', 'companies', 'company_designation', 'company_state_branch', 'supervisors');
  }

  /*
   * A company has many states
   *
   *@
   */
  public function company_states()
  {
    return $this->hasMany(CompanyState::class)
      ->with('company_state_branches');
  }

  /*
   * A company has many company designations
   *
   *@
   */
  public function company_designations()
  {
    return $this->hasMany(CompanyDesignation::class);
  }

  /*
   * Save Default companydesignations
   *
   *@
   */
  public function saveDefaultDesignations()
  {
    $designations[] = new CompanyDesignation(['name' => 'Admin']);
    $this->company_designations()->saveMany($designations);
  }

  /*
   * A company has many listings
   *
   *@
   */
  public function listings()
  {
    return $this->hasMany(Listing::class)
      ->with('products');
  }

  /*
   * A company has many products
   *
   *@
   */
  public function products()
  {
    return $this->hasMany(Product::class);
  }

  public function units()
  {
    return $this->hasMany(Unit::class);
  }
}
