<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsGuiaRemisionh extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cajah', function ($table) {
            $table->string('codeG')->nullable();
            $table->text('descriptionG')->nullable();
        });

        Schema::table('nota_creditoh', function ($table) {
            $table->string('codeG')->nullable();
            $table->text('descriptionG')->nullable();
        });
        
        Schema::table('guia_remisionh', function ($table) {
            $table->string('xml_file')->nullable();
            $table->string('cdr_file')->nullable();
            $table->integer('correlativoG')->default(0);
            $table->string('codeG')->nullable();
            $table->text('descriptionG')->nullable();
            $table->boolean('hoja_ruta')->nullable()->default(false);
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
