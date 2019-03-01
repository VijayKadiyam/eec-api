<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->integer('active')->default(0);
            $table->string('password');
            $table->integer('phone')->unique();
            $table->string('api_token', 60)->unique()->nullable();
            $table->string('doj')->nullable();
            $table->string('dob')->nullable();
            $table->string('company_designation_id')->nullable();
            $table->string('company_state_branch_id')->nullable();
            $table->string('pf_no')->nullable();
            $table->string('uan_no')->nullable();
            $table->string('esi_no')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
