<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('datas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('unit_id')->nullable();
            $table->string('data')->nullable();
            $table->string('imei_number')->nullable();
            $table->string('date')->nullable();
            $table->string('time')->nullable();
            $table->string('pump_status')->nullable();
            $table->string('voltage')->nullable();
            $table->string('current')->nullable();
            $table->string('frequency')->nullable();
            $table->string('temperature')->nullable();
            $table->string('phase_current_r')->nullable();
            $table->string('phase_current_y')->nullable();
            $table->string('phase_current_b')->nullable();
            $table->string('dummy')->nullable();
            $table->string('reserved')->nullable();
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
        Schema::dropIfExists('datas');
    }
}
