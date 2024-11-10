<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePotencialClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('potencial_clientes', function ($table) {
            $table->integer('idusuario') ->nullable();
            $table->integer('idempresa')->default(1);
            $table->integer('idvendedor') ->nullable();
            $table->increments('idpotencial');
            $table->string('nombre_comercial');
            $table->string('direccion') ->nullable();
            $table->string('distrito') ->nullable();
            $table->string('contacto_nombre')->nullable();
            $table->string('contacto_telefono')->nullable();
            $table->string('contacto_telefono2')->nullable();
            $table->string('contacto_telefono3')->nullable();
            $table->string('contacto_telefono4')->nullable();
            $table->string('contacto_telefono5')->nullable();
            $table->string('contacto_email')->nullable();            
            $table->string('provincia') ->nullable();
            $table->string('departamento') ->nullable();
            $table->string('tipo_emp') ->nullable();
            $table->integer('ruta')->nullable();
            $table->integer('secuencia')->nullable();
            $table->string('codigo')->nullable();
            $table->dateTime('created_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
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
