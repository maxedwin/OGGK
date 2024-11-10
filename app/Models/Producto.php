<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Producto extends Model {
    protected $table = 'producto';
    protected $primaryKey = 'idproducto';
    public $timestamps = false;

    public function category(){
    	return $this->belongsTo('App\Models\Categoria','idcategoria');
    }

    public function user(){
    	return $this->belongsTo('App\User','idempresa');
    }

    public function transacciones(){
        return $this->hasMany('App\Models\Transacciones','idproducto');
    }

}