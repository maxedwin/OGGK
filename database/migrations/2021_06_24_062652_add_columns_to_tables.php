<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cajah', function ($table) {
            $table->string('pdf_file')->nullable();
        });

        Schema::table('nota_creditoh', function ($table) {
            $table->string('pdf_file')->nullable();
        });

        Schema::table('guia_remisionh', function ($table) {
            $table->string('pdf_file')->nullable();
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
