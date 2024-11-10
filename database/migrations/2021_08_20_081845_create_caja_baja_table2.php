<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCajaBajaTable2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('caja_baja', function ($table) {
            $table->increments('id');
            $table->integer('idcajah');
            $table->string('ticket');
            $table->string('documento');
            $table->string('motivo')->nullable();
            $table->string('xml_file')->nullable();
            $table->string('cdr_file')->nullable();
            $table->string('pdf_file')->nullable();
            $table->integer('correlativoG')->default(0);
            $table->string('codeG')->nullable();
            $table->text('descriptionG')->nullable();
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
