<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSlidesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slides', function ($table) {
            $table->increments('id');
            $table->string('image')->nullable();
            $table->string('alias')->nullable();
            $table->boolean('active')->nullable()->default(true);
            $table->dateTime('created_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::create('categorias_uso', function ($table) {
            $table->increments('id');
            $table->string('name')->nullable();
        });

        Schema::table('producto', function ($table) {
            $table->integer('idcategoria_uso')->nullable();
            $table->string('adjunto')->nullable();
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
