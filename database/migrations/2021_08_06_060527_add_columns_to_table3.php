<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToTable3 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('guia_remisionh', function ($table) {
            $table->integer('correlativo_inside')->default(0)->nullable();
            $table->boolean('is_ncp')->default(false)->nullable();
        });
        Schema::table('cajah', function ($table) {
            $table->integer('correlativo_inside')->default(0)->nullable();
            $table->boolean('is_ncp')->default(false)->nullable();
        });
        Schema::table('orden_ventah', function ($table) {
            $table->boolean('is_ncp')->default(false)->nullable();
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
