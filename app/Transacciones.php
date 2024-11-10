<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Transacciones extends Model {
    protected $table = 'transacciones';
    protected $primaryKey = 'idtransaccion';


    public function products(){
    	return $this->belongsTo('App\Models\Producto','idproducto');
    }

    public function user(){
    	return $this->belongsTo('App\User','idempresa');
    }
}