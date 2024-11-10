<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarcaCategoria extends Model
{
    protected $table = 'marca_categoria';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function marcas_de_categoria($idcategoriauso){
        $marcas = DB::table('marca_categoria')->where('idcategoriauso','=',$idcategoriauso)->get();
        return $marcas;
    }
}
