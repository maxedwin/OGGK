<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsistenciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asistencias', function ($table) {
            $table->increments('id');
            $table->integer('idusuario')->default(0)->nullable();
            $table->date('register_date');
            $table->string('check_in');
            $table->string('check_out')->default(0)->nullable();
            $table->string('lunch_time')->default(0)->nullable();
            $table->float('hours')->default(0)->nullable();
            $table->text('descripcion')->nullable();
            $table->dateTime('created_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::table('producto', function ($table) {
            $table->integer('contador_uso')->default(0)->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
