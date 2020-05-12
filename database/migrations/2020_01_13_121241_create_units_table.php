<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('units', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('residence_address')->nullable();
            $table->string('phone_no')->nullable();
            $table->string('adhaar_no')->nullable();
            $table->string('email')->nullable();
            $table->string('serial_no_controller')->nullable();
            $table->string('location_controller')->nullable();
            $table->string('manufacturer_vfd')->nullable();
            $table->string('serial_no_vfd')->nullable();
            $table->string('motor_type')->nullable();
            $table->string('motor_category')->nullable();
            $table->string('motor_serial_no')->nullable();
            $table->string('pump_serial_no')->nullable();
            $table->string('motor_hp')->nullable();
            $table->string('motor_head_size')->nullable();
            $table->string('imei_number', 100)->unique();
            $table->string('rms_phone_no')->nullable();
            $table->string('device_id')->nullable();
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
        Schema::dropIfExists('units');
    }
}
