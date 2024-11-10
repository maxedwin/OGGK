<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriaUso extends Model
{
    protected $table = 'categorias_uso';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function parent(){
    	return $this->hasOne('App\Models\Categoria','idempresa');
    }

    public function user(){
    	return $this->belongsTo('App\User','idempresa','idempresa');
    }
}
