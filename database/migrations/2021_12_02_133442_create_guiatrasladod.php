<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGuiatrasladod extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('guia_trasladod', function ($table) {
            $table->increments('id_guia_trasladod');
            $table->foreign('id_guia_traladoh')->references('id_guia_trasladoh')->on('guia_trasladoh');
            $table->foreign('idproducto')->references('idproducto')->on('producto');
            $table->integer('cantidad');
            $table->float('peso_unit')->nullable();
            $table->float('peso_total')->nullable();
            $table->string('peso_und');
            $table->integer('idempresa');
            $table->dateTime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('cantidad_ent')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('guia_trasladod');
    }
}
