<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Marca extends Model {
    protected $table = 'marcas';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function productos(){
    	return $this->hasMany('App\Models\Producto', 'idmarca');
    }

}