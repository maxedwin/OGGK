<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableCategoriasUso extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categorias_uso', function ($table) {
            $table->string('image')->nullable();
            $table->$table->integer('idempresa')->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categorias', function ($table) {
            $table->dropColumn('image');
            $table->dropColumn('idempresa');
        });
    }
}
