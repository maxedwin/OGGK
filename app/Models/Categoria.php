<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Categoria extends Model {
    protected $table = 'categorias';
    protected $primaryKey = 'idcategoria';
    protected $fillable = ['descripcion'];
    public $timestamps = false;

    public function parent(){
    	return $this->hasOne('App\Models\Categoria','idempresa');
    }

    public function user(){
    	return $this->belongsTo('App\User','idempresa','idempresa');
    }
}