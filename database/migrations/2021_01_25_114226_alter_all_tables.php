<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAllTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orden_comprah', function ($table) {
            $table->foreign('idproveedor')->references('idproveedor')->on('proveedores');
            $table->index('numeracion');
        });

        Schema::table('orden_comprad', function ($table) {
            $table->integer('cantidad_fal');
            $table->foreign('id_orden_comprah')->references('id_orden_comprah')->on('orden_comprah');
            $table->foreign('idproducto')->references('idproducto')->on('producto');
        });

        Schema::table('ficha_recepcionh', function ($table) {
            $table->foreign('id_orden_comprah')->references('id_orden_comprah')->on('orden_comprah');
            $table->foreign('idproveedor')->references('idproveedor')->on('proveedores');
            $table->foreign('flete_trans')->references('idproveedor')->on('proveedores');
        });

        Schema::table('ficha_recepciond', function ($table) {
            $table->foreign('id_ficha_recepcionh')->references('id_ficha_recepcionh')->on('ficha_recepcionh');
            $table->foreign('idproducto')->references('idproducto')->on('producto');
            $table->foreign('idlote')->references('idlote')->on('lote');
        });

        Schema::table('lote', function ($table) {
            $table->foreign('idproducto')->references('idproducto')->on('producto');
        });

        Schema::table('cotizacionh', function ($table) {
            $table->foreign('idcliente')->references('idcliente')->on('clientes');
        });

        Schema::table('cotizaciond', function ($table) {
            $table->foreign('idcotizacionh')->references('idcotizacionh')->on('cotizacionh');
            $table->foreign('idproducto')->references('idproducto')->on('producto');
        });

        Schema::table('orden_ventah', function ($table) {
            $table->foreign('idcliente')->references('idcliente')->on('clientes');
        });

        Schema::table('orden_ventad', function ($table) {
            $table->integer('cantidad_fal');
            $table->foreign('id_orden_ventah')->references('id_orden_ventah')->on('orden_ventah');
            $table->foreign('idproducto')->references('idproducto')->on('producto');
        });

        Schema::table('guia_remisionh', function ($table) {
            $table->foreign('idcliente')->references('idcliente')->on('clientes');
            $table->index('id_orden_ventah');
        });

        Schema::table('guia_remisiond', function ($table) {
            $table->foreign('id_guia_remisionh')->references('id_guia_remisionh')->on('guia_remisionh');
            $table->foreign('idproducto')->references('idproducto')->on('producto');
            $table->foreign('idlote')->references('idlote')->on('lote');
        });

        /*
        
        ///// ADD COLUMNS
        
        ALTER TABLE orden_comprad ADD COLUMN cantidad_fal INT NOT NULL;
        ALTER TABLE orden_ventad ADD COLUMN cantidad_fal INT NOT NULL;
        

        ///// ADD FOREIGN KEYS AND INDEXES

        ALTER TABLE orden_comprah ADD CONSTRAINT orden_comprah_idproveedor_foreign FOREIGN KEY (idproveedor) REFERENCES proveedores(idproveedor);
        ALTER TABLE orden_comprah ADD INDEX orden_comprah_numeracion_index (numeracion);

        ALTER TABLE orden_comprad ADD CONSTRAINT orden_comprad_id_orden_comprah_foreign FOREIGN KEY (id_orden_comprah) REFERENCES orden_comprah(id_orden_comprah);
        ALTER TABLE orden_comprad ADD CONSTRAINT orden_comprad_idproducto_foreign FOREIGN KEY (idproducto) REFERENCES producto(idproducto);

        ALTER TABLE ficha_recepcionh ADD CONSTRAINT ficha_recepcionh_id_orden_comprah_foreign FOREIGN KEY (id_orden_comprah) REFERENCES orden_comprah(id_orden_comprah);
        ALTER TABLE ficha_recepcionh ADD CONSTRAINT ficha_recepcionh_idproveedor_foreign FOREIGN KEY (idproveedor) REFERENCES proveedores(idproveedor);
        ALTER TABLE ficha_recepcionh ADD CONSTRAINT ficha_recepcionh_flete_trans_foreign FOREIGN KEY (flete_trans) REFERENCES proveedores(idproveedor);

        ALTER TABLE ficha_recepciond ADD CONSTRAINT ficha_recepciond_id_ficha_recepcionh_foreign FOREIGN KEY (id_ficha_recepcionh) REFERENCES ficha_recepcionh(id_ficha_recepcionh);
        ALTER TABLE ficha_recepciond ADD CONSTRAINT ficha_recepciond_idproducto_foreign FOREIGN KEY (idproducto) REFERENCES producto(idproducto);
        ALTER TABLE ficha_recepciond ADD CONSTRAINT ficha_recepciond_idlote_foreign FOREIGN KEY (idlote) REFERENCES lote(idlote);

        ALTER TABLE lote ADD CONSTRAINT lote_idproducto_foreign FOREIGN KEY (idproducto) REFERENCES producto(idproducto);

        ALTER TABLE cotizacionh ADD CONSTRAINT cotizacionh_idcliente_foreign FOREIGN KEY (idcliente) REFERENCES clientes (idcliente);

        ALTER TABLE cotizaciond ADD CONSTRAINT cotizaciond_idcotizacionh_foreign FOREIGN KEY (idcotizacionh) REFERENCES cotizacionh (idcotizacionh);
        ALTER TABLE cotizaciond ADD CONSTRAINT cotizaciond_idproducto_foreign FOREIGN KEY (idproducto) REFERENCES producto (idproducto);

        ALTER TABLE orden_ventah ADD CONSTRAINT orden_ventah_idcliente_foreign FOREIGN KEY (idcliente) REFERENCES clientes (idcliente);

        ALTER TABLE orden_ventad ADD CONSTRAINT orden_ventad_id_orden_ventah_foreign FOREIGN KEY (id_orden_ventah) REFERENCES orden_ventah (id_orden_ventah);
        ALTER TABLE orden_ventad ADD CONSTRAINT orden_ventad_idproducto_foreign FOREIGN KEY (idproducto) REFERENCES producto (idproducto);

        ALTER TABLE guia_remisionh ADD CONSTRAINT guia_remisionh_idcliente_foreign FOREIGN KEY (idcliente) REFERENCES clientes (idcliente);
        ALTER TABLE guia_remisionh ADD INDEX guia_remisionh_id_orden_ventah_index (id_orden_ventah);

        ALTER TABLE guia_remisiond ADD CONSTRAINT guia_remisiond_id_guia_remisionh_foreign FOREIGN KEY (id_guia_remisionh) REFERENCES guia_remisionh (id_guia_remisionh);
        ALTER TABLE guia_remisiond ADD CONSTRAINT guia_remisiond_idproducto_foreign FOREIGN KEY (idproducto) REFERENCES producto (idproducto);
        ALTER TABLE guia_remisiond ADD CONSTRAINT guia_remisiond_idlote_foreign FOREIGN KEY (idlote) REFERENCES lote (idlote);

        */
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
