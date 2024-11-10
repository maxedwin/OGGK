<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsNotaCreditoh extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cajah', function ($table) {
            $table->integer('correlativoG')->default(0);
        });

        Schema::table('nota_creditoh', function ($table) {
            $table->string('xml_file')->nullable();
            $table->string('cdr_file')->nullable();
            $table->integer('correlativoG')->default(0);
            $table->integer('tipo_doc')->nullable();
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
