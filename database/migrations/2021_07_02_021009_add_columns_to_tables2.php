<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToTables2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('visit', function ($table) {
            $table->decimal('latitud', 10, 8)->default(0)->nullable();
            $table->decimal('longitud', 10, 8)->default(0)->nullable();
            $table->boolean('is_app')->default(false)->nullable();
        });

        Schema::table('orden_ventah', function ($table) {
            $table->integer('status_doc')->default(-1)->nullable();
            $table->integer('status_ent')->default(-1)->nullable();
            $table->integer('status_cob')->default(-1)->nullable();
        });

        Schema::table('guia_remisionh', function ($table) {
            $table->integer('status_ent')->default(-1)->nullable();
        });

        Schema::table('cajah', function ($table) {
            $table->integer('status_cob')->default(-1)->nullable();
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
