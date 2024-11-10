<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarcasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marcas', function ($table) {
            $table->increments('id');
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->dateTime('created_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::table('producto', function ($table) {
            $table->integer('idmarca')->default(0)->nullable()->unsigned();
            $table->float('costo_sin_igv')->default(0)->nullable();
            $table->float('precio_rango_0')->default(0)->nullable();
            $table->float('precio_rango_1')->default(0)->nullable();
            $table->float('precio_rango_2')->default(0)->nullable();
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
