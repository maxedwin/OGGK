<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToOrdenVenta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orden_ventah', function ($table) {
            $table->string('direccion_entrega')->nullable();
            $table->boolean('is_igv')->nullable()->default(false);
            $table->string('telefono_entrega', 64)->nullable();
            $table->string('email_entrega', 128)->nullable();
        });

        /*
        
        ///// ADD COLUMNS
        
        ALTER TABLE orden_ventah ADD COLUMN direccion_entrega varchar(250) NULL;
        ALTER TABLE orden_ventah ADD COLUMN is_igv BOOLEAN DEFAULT FALSE NULL;

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
