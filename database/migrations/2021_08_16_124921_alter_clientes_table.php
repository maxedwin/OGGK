<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
          Schema::table('clientes', function ($table) {
            $table->integer('ruta')->nullable();
            $table->integer('secuencia')->nullable();
            $table->string('codigo')->nullable();
            $table->string('contacto_telefono2')->nullable();
            $table->string('contacto_telefono3')->nullable();
            $table->string('contacto_telefono4')->nullable();
            $table->string('contacto_telefono5')->nullable();
            $table->string('direccion_ent') ->nullable();
            $table->integer('estado_cliente')->nullable();
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
