<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGuiatrasladoh extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('guia_trasladoh', function ($table) {
            $table->increments('id_guia_trasladoh');
            $table->integer('idempresa');
            $table->integer('idscucursal');
            $table->integer('idusuario');
            $table->integer('idvendedor');
            $table->foreign('idcliente')->references('idcliente')->on('clientes');
            $table->integer('idtransporte');
            $table->integer('iddespachador');
            $table->integer('idalmacen');
            $table->integer('motivo_traslado');
            $table->string('ubigeo');
            $table->integer('numeracion');
            $table->string('codigoNB');
            $table->dateTime('fechaNB');
            $table->string('peso_total');
            $table->integer('numero_de_bultos');
            $table->float('subtotal');
            $table->float('total');
            $table->float('igv');
            $table->float('descuento');
            $table->text('comentarios');
            $table->dateTime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('updated_at');
            $table->dateTime('f_entrega');
            $table->dateTime('f_cobro');
            $table->dateTime('f_entregado');
            $table->dateTime('f_reprogramar');
            $table->integer('id_usuario_despachador');
            $table->integer('estado_doc');
            $table->float('paga');
            $table->float('vuelto');
            $table->string('xml_file');
            $table->string('cdr_file');
            $table->integer('correlativoG');
            $table->string('codeG');
            $table->text('descriptionG');
            $table->string('pdf_file');
            $table->integer('status_ent');
            $table->integer('correlativo_iniside');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('guia_trasladoh');
    }
}
